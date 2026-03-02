<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 20)->primary();
            $table->string('nama_barang', 100);
            $table->decimal('harga', 12, 2);
            $table->string('satuan', 30)->nullable();
            $table->timestamps();
        });

        // Trigger function untuk PostgreSQL
        DB::unprepared('
            CREATE OR REPLACE FUNCTION generate_id_barang()
            RETURNS TRIGGER AS $$
            DECLARE
                last_num INT;
            BEGIN
                SELECT COUNT(*) INTO last_num FROM barang;
                NEW.id_barang := CONCAT(\'BRG\', LPAD((last_num + 1)::TEXT, 5, \'0\'));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER before_insert_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            EXECUTE FUNCTION generate_id_barang();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_barang ON barang');
        DB::unprepared('DROP FUNCTION IF EXISTS generate_id_barang');
        Schema::dropIfExists('barang');
    }
};