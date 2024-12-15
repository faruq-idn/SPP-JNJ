<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri');
            $table->date('tanggal_bayar');
            $table->string('bulan');
            $table->integer('tahun');
            $table->decimal('nominal', 10, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'midtrans']);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('keterangan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
