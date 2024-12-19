<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran_spp', 'bulan')) {
                $table->string('bulan')->after('nominal');
            }
            if (!Schema::hasColumn('pembayaran_spp', 'tahun')) {
                $table->year('tahun')->after('bulan');
            }
            if (!Schema::hasColumn('pembayaran_spp', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun', 'keterangan']);
        });
    }
};
