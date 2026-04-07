<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('customer_name', 255)->nullable()->after('nama');
            $table->string('customer_email', 255)->nullable()->after('customer_name');
            $table->string('customer_phone', 20)->nullable()->after('customer_email');
            $table->string('midtrans_order_id', 100)->nullable()->after('customer_phone');
            $table->string('midtrans_token', 255)->nullable()->after('midtrans_order_id');
            $table->string('qr_url', 500)->nullable()->after('midtrans_token');
            $table->string('va_number', 100)->nullable()->after('qr_url');
            $table->string('va_bank', 50)->nullable()->after('va_number');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_phone',
                'midtrans_order_id',
                'midtrans_token',
                'qr_url',
                'va_number',
                'va_bank',
            ]);
        });
    }
};
