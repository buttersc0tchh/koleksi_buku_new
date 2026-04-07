<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('id_detail_pesanan');
            $table->unsignedInteger('id_menu');
            $table->unsignedInteger('id_pesanan');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('catatan', 255)->nullable();

            $table->foreign('id_menu')->references('id_menu')->on('menu')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
