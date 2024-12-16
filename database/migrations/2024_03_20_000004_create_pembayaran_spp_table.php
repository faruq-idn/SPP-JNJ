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
            $table->foreignId('petugas_id')->nullable()->constrained('users');
            $table->date('tanggal_bayar');
            $table->string('bulan', 2);
            $table->year('tahun');
            $table->decimal('nominal', 10, 0);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'midtrans']);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
