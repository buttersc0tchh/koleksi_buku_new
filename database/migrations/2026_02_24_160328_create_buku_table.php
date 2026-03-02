<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Berdasarkan ER Diagram Modul 1 [cite: 27, 32]
        Schema::create('buku', function (Blueprint $table) {
            $table->id('idbuku'); // Sesuai Modul: idbuku INT [cite: 34]
            $table->string('kode', 20); // Sesuai Modul: kode VARCHAR(20) [cite: 35]
            $table->string('judul', 500); // Sesuai Modul: judul VARCHAR(500) [cite: 36]
            $table->string('pengarang', 200); // Sesuai Modul: pengarang VARCHAR(200) [cite: 37]
            
            // Kolom Foreign Key [cite: 38]
            $table->unsignedBigInteger('idkategori'); 
            
            // Relasi ke tabel 'kategoris' pada kolom 'id' (karena di pgAdmin kamu namanya 'id')
            $table->foreign('idkategori')
                  ->references('id')
                  ->on('kategoris')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};