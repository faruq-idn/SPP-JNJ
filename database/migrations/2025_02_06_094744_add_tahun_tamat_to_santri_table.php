<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ubah tipe enum status santri
        DB::statement("ALTER TABLE santri MODIFY COLUMN status ENUM('aktif', 'non-aktif', 'lulus', 'keluar') DEFAULT 'aktif'");
        
        // Tambah kolom tahun_tamat
        Schema::table('santri', function (Blueprint $table) {
            $table->year('tahun_tamat')->nullable()->after('status');
        });
    }

    public function down()
    {
        // Hapus kolom tahun_tamat
        Schema::table('santri', function (Blueprint $table) {
            $table->dropColumn('tahun_tamat');
        });

        // Kembalikan tipe enum status santri
        DB::statement("ALTER TABLE santri MODIFY COLUMN status ENUM('aktif', 'non-aktif') DEFAULT 'aktif'");
    }
};
