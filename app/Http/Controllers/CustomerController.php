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

        $pesanan = Pesanan::create([
            'nama'              => $request->customer_name,
            'customer_name'     => $request->customer_name,
            'customer_email'    => $request->customer_email,
            'customer_phone'    => $request->customer_phone,
            'timestamp'         => now(),
            'total'             => $total,
            'metode_bayar'      => $request->metode_bayar == 'qris' ? 'QRIS' : 'Virtual Account',
            'status_bayar'      => 0,
            'midtrans_order_id' => $orderId,
        ]);

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

        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;

        $itemDetails = [];
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id'       => (string) $item['menu']->id_menu,
                'price'    => (int) $item['menu']->harga,
                'quantity' => (int) $item['jumlah'],
                'name'     => substr($item['menu']->nama_menu, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $total,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
            'enabled_payments' => [
                'other_qris',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
            ],
        ];

        $snapToken = null;
        try {
            $snapToken = Snap::getSnapToken($params);
            $pesanan->update(['midtrans_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap token error: ' . $e->getMessage(), ['order_id' => $orderId]);
        }

        return redirect()->route('payment.show', $pesanan->id_pesanan);
    }

    public function showPayment($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->with('detail.menu')
            ->firstOrFail();

        $clientKey = config('midtrans.client_key');
        $snapJsUrl = config('midtrans.snap_url');

        return view('customer.payment', compact('pesanan', 'clientKey', 'snapJsUrl'));
    }

    public function paymentStatus($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)->firstOrFail();

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
        $serverKey         = config('midtrans.server_key');
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

        // Generate QR Code
        $qrCode = null;
        if ($pesanan->status_bayar == 1) {
            $writer    = new \Endroid\QrCode\Writer\PngWriter();
            $qrCodeObj = new \Endroid\QrCode\QrCode('Pesanan #' . $pesanan->id_pesanan);
            $result = $writer->write($qrCodeObj);
            $qrCode = base64_encode($result->getString());
        }

        return view('customer.status', compact('pesanan', 'qrCode'));
    }
}