<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusSantriTable extends Migration
{
    public function up()
    {
        Schema::table('santri', function (Blueprint $table) {
            // Hapus kolom status yang lama
            $table->dropColumn('status');
        });

        Schema::table('santri', function (Blueprint $table) {
            // Buat ulang kolom status dengan tipe enum yang benar
            $table->enum('status', ['aktif', 'non-aktif', 'lulus'])->default('aktif')->after('kelas');
        });
    }

    public function down()
    {
        Schema::table('santri', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status')->default('aktif')->after('kelas');
        });
    }
}
