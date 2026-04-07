<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id_menu');
            $table->string('nama_menu', 255);
            $table->integer('harga');
            $table->string('path_gambar', 255)->nullable();
            $table->unsignedInteger('id_vendor');

            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
