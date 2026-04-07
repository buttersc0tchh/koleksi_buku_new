<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class CustomerController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('menu')->get();

        return view('customer.index', compact('vendors'));
    }

    public function checkout(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return redirect()->route('customer.index')->with('error', 'Pilih menu terlebih dahulu!');
        }

        // Hitung total
        $total     = 0;
        $cartItems = [];
        foreach ($items as $id_menu => $jumlah) {
            $jumlah = (int) $jumlah;
            if ($jumlah <= 0) continue;

            $menu = Menu::find($id_menu);
            if (!$menu) continue;

            $subtotal    = $menu->harga * $jumlah;
            $total      += $subtotal;
            $cartItems[] = [
                'menu'     => $menu,
                'jumlah'   => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($cartItems)) {
            return redirect()->route('customer.index')->with('error', 'Pilih menu terlebih dahulu!');
        }

        return view('customer.checkout', compact('cartItems', 'total'));
    }

    public function generatePayment(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'metode_bayar'   => 'required|in:qris,virtual_account',
            'items'          => 'required|array',
        ]);

        $total     = 0;
        $cartItems = [];
        foreach ($request->items as $id_menu => $jumlah) {
            $jumlah = (int) $jumlah;
            if ($jumlah <= 0) continue;

            $menu = Menu::find($id_menu);
            if (!$menu) continue;

            $subtotal    = $menu->harga * $jumlah;
            $total      += $subtotal;
            $cartItems[] = [
                'menu'     => $menu,
                'jumlah'   => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($cartItems)) {
            return redirect()->route('customer.index')->with('error', 'Tidak ada item valid!');
        }

        $orderId = 'ORDER-' . time() . '-' . rand(1000, 9999);

        // Simpan pesanan dengan status pending
        $pesanan = Pesanan::create([
            'nama'             => $request->customer_name,
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone,
            'timestamp'        => now(),
            'total'            => $total,
            'metode_bayar'     => $request->metode_bayar,
            'status_bayar'     => 0,
            'midtrans_order_id'=> $orderId,
        ]);

        // Simpan detail pesanan
        foreach ($cartItems as $item) {
            DetailPesanan::create([
                'id_menu'    => $item['menu']->id_menu,
                'id_pesanan' => $pesanan->id_pesanan,
                'jumlah'     => $item['jumlah'],
                'harga'      => $item['menu']->harga,
                'subtotal'   => $item['subtotal'],
                'timestamp'  => now(),
                'catatan'    => null,
            ]);
        }

        // Panggil Midtrans API
        $serverKey  = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl    = $isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        $itemDetails = [];
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id'       => (string) $item['menu']->id_menu,
                'price'    => $item['menu']->harga,
                'quantity' => $item['jumlah'],
                'name'     => $item['menu']->nama_menu,
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        if ($request->metode_bayar === 'qris') {
            $payload['payment_type'] = 'qris';
        } else {
            $payload['payment_type'] = 'bank_transfer';
            $payload['bank_transfer'] = ['bank' => 'bca'];
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->post("{$baseUrl}/v2/charge", $payload);

            $result = $response->json();

            // Midtrans returns status_code as string (e.g. "201"), use loose comparison
            $statusCode = (string) ($result['status_code'] ?? '');
            if (in_array($statusCode, ['200', '201'], true)) {
                if ($request->metode_bayar === 'qris') {
                    $qrUrl = null;
                    foreach (($result['actions'] ?? []) as $action) {
                        if ($action['name'] === 'generate-qr-code') {
                            $qrUrl = $action['url'];
                            break;
                        }
                    }
                    $pesanan->update(['qr_url' => $qrUrl]);
                } else {
                    $vaNumbers = $result['va_numbers'] ?? [];
                    $vaNumber  = !empty($vaNumbers) ? $vaNumbers[0]['va_number'] : null;
                    $vaBank    = !empty($vaNumbers) ? ($vaNumbers[0]['bank'] ?? 'bca') : 'bca';
                    $pesanan->update(['va_number' => $vaNumber, 'va_bank' => $vaBank]);
                }
            } else {
                Log::warning('Midtrans charge failed', ['response' => $result, 'order_id' => $orderId]);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans API error: ' . $e->getMessage(), ['order_id' => $orderId]);
        }

        return redirect()->route('payment.show', $pesanan->id_pesanan);
    }

    public function showPayment($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->with('detail.menu')
            ->firstOrFail();

        return view('customer.payment', compact('pesanan'));
    }

    public function paymentStatus($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)->firstOrFail();

        // Cek status ke Midtrans jika masih pending
        if ($pesanan->status_bayar == 0 && $pesanan->midtrans_order_id) {
            $serverKey    = config('services.midtrans.server_key');
            $isProduction = config('services.midtrans.is_production', false);
            $baseUrl      = $isProduction
                ? 'https://api.midtrans.com'
                : 'https://api.sandbox.midtrans.com';

            try {
                $response = Http::withBasicAuth($serverKey, '')
                    ->get("{$baseUrl}/v2/{$pesanan->midtrans_order_id}/status");

                $result = $response->json();

                if (isset($result['transaction_status'])) {
                    if (in_array($result['transaction_status'], ['settlement', 'capture'])) {
                        $pesanan->update(['status_bayar' => 1]);
                    } elseif ($result['transaction_status'] === 'expire') {
                        $pesanan->update(['status_bayar' => 2]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Midtrans status check error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status_bayar' => $pesanan->status_bayar,
            'status_label' => $pesanan->status_bayar == 1 ? 'lunas' : ($pesanan->status_bayar == 2 ? 'expired' : 'pending'),
        ]);
    }

    public function paymentCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');

        // Verifikasi signature Midtrans
        $orderId           = $request->input('order_id');
        $statusCode        = $request->input('status_code');
        $grossAmount       = $request->input('gross_amount');
        $signatureKey      = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Status mapping: 0=pending, 1=lunas/paid, 2=expired, 3=cancelled
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $pesanan->update(['status_bayar' => 1]);
        } elseif ($transactionStatus === 'expire') {
            $pesanan->update(['status_bayar' => 2]);
        } elseif ($transactionStatus === 'cancel') {
            $pesanan->update(['status_bayar' => 3]);
        }

        return response()->json(['message' => 'OK']);
    }

    public function status($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->with('detail.menu')
            ->firstOrFail();

        return view('customer.status', compact('pesanan'));
    }
}
