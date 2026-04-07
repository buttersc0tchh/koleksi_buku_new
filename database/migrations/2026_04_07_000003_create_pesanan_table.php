<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('id_pesanan');
            $table->string('nama', 255);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->string('metode_bayar', 255)->nullable();
            $table->smallInteger('status_bayar')->default(0); // 0=belum, 1=lunas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
