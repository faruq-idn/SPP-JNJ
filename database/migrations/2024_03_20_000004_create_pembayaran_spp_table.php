<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->date('tanggal_bayar')->nullable();
            $table->string('bulan');
            $table->year('tahun');
            $table->decimal('nominal', 10, 2);
            $table->foreignId('metode_pembayaran_id')->nullable()
                ->constrained('metode_pembayaran')
                ->nullOnDelete();
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
