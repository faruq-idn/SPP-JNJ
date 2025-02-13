o<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->unsignedBigInteger('wali_id')->nullable();
            $table->foreign('wali_id')->references('id')->on('users')->nullOnDelete();
            $table->string('nama_wali')->nullable();
            $table->date('tanggal_masuk');
            $table->enum('jenjang', ['SMP', 'SMA']);
            $table->string('kelas');
            $table->foreignId('kategori_id')->constrained('kategori_santri');
            $table->enum('status', ['aktif', 'non-aktif', 'lulus'])->default('aktif');
            $table->enum('status_spp', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            $table->timestamps();

            // Index
            $table->index('wali_id');
            $table->index('kategori_id');
            $table->index('status');
            $table->index('status_spp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('santri');
    }
};
