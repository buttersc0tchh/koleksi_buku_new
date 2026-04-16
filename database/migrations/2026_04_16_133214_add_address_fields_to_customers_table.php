<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('email');
            $table->string('provinsi', 100)->nullable()->after('alamat');
            $table->string('kota', 100)->nullable()->after('provinsi');
            $table->string('kecamatan', 100)->nullable()->after('kota');
            $table->string('kodepos_kelurahan', 100)->nullable()->after('kecamatan');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'provinsi', 'kota', 'kecamatan', 'kodepos_kelurahan']);
        });
    }
};