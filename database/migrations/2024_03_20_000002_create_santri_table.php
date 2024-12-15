<?php

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
            $table->foreignId('wali_id')->constrained('users');
            $table->date('tanggal_masuk');
            $table->enum('jenjang', ['SMP', 'SMA']);
            $table->string('kelas');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->foreignId('kategori_id')->constrained('kategori_santri');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('santri');
    }
};
