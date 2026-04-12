<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

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
            'metode_bayar'   => 'required|in:qris,va',
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

        // Simpan pesanan dengan status pending
        $pesanan = Pesanan::create([
            'nama'              => $request->customer_name,
            'customer_name'     => $request->customer_name,
            'customer_email'    => $request->customer_email,
            'customer_phone'    => $request->customer_phone,
            'timestamp'         => now(),
            'total'             => $total,
            'metode_bayar'      => $request->metode_bayar,
            'status_bayar'      => 0,
            'midtrans_order_id' => null,
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

        $this->generateAndStoreSnapToken($pesanan);

        return redirect()->route('payment.show', [
            'id_pesanan' => $pesanan->id_pesanan,
            'autopay'    => 1,
        ]);
    }

    public function showPayment($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->with('detail.menu')
            ->firstOrFail();

        if ((int) $pesanan->status_bayar === 0 && empty($pesanan->midtrans_token)) {
            $this->generateAndStoreSnapToken($pesanan);
            $pesanan->refresh()->load('detail.menu');
        }

        $clientKey    = config('midtrans.client_key');
        $snapJsUrl    = config('midtrans.snap_url');
        $autoOpenSnap = request()->boolean('autopay', false);

        return view('customer.payment', compact('pesanan', 'clientKey', 'snapJsUrl', 'autoOpenSnap'));
    }

    public function paymentStatus($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)->firstOrFail();

        // Cek status ke Midtrans jika masih pending
        if ($pesanan->status_bayar == 0 && $pesanan->midtrans_order_id) {
            MidtransConfig::$serverKey    = config('midtrans.server_key');
            MidtransConfig::$isProduction = config('midtrans.is_production', false);

            try {
                $status = \Midtrans\Transaction::status($pesanan->midtrans_order_id);

                if (isset($status->transaction_status)) {
                    if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                        $pesanan->update(['status_bayar' => 1]);
                    } elseif ($status->transaction_status === 'expire') {
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
        $serverKey = config('midtrans.server_key');

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

    private function generateAndStoreSnapToken(Pesanan $pesanan): ?string
    {
        $pesanan->loadMissing('detail.menu');
        $orderId = $pesanan->midtrans_order_id ?: 'ORDER-' . $pesanan->id_pesanan;

        $itemDetails = [];
        foreach ($pesanan->detail as $detail) {
            $itemDetails[] = [
                'id'       => (string) $detail->id_menu,
                'price'    => (int) $detail->harga,
                'quantity' => (int) $detail->jumlah,
                'name'     => substr((string) optional($detail->menu)->nama_menu ?: 'Item', 0, 50),
            ];
        }

        if ($pesanan->metode_bayar === 'qris') {
            $enabledPayments = ['qris'];
        } elseif ($pesanan->metode_bayar === 'va') {
            $enabledPayments = ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'];
        } else {
            // Backward compatibility for older "snap" data
            $enabledPayments = ['qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'];
        }

        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $pesanan->total,
                ],
                'item_details'     => $itemDetails,
                'customer_details' => [
                    'first_name' => $pesanan->customer_name ?: $pesanan->nama,
                    'email'      => $pesanan->customer_email,
                    'phone'      => $pesanan->customer_phone,
                ],
                'enabled_payments' => $enabledPayments,
            ]);

            $pesanan->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token'    => $snapToken,
            ]);

            return $snapToken;
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap token error', [
                'id_pesanan' => $pesanan->id_pesanan,
                'order_id'   => $orderId,
                'metode'     => $pesanan->metode_bayar,
                'error'      => $e->getMessage(),
            ]);

            return null;
        }
    }
}
