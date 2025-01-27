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
            $table->string('bulan');
            $table->year('tahun');
            $table->decimal('nominal', 10, 0);
            $table->timestamp('tanggal_bayar')->nullable();
            $table->string('keterangan')->nullable();

            // Kolom untuk Midtrans
            $table->string('snap_token')->nullable();
            $table->char('order_id', 36)->nullable()->unique();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('status')->default('unpaid');

            $table->timestamps();

            // Index untuk performa
            $table->index(['santri_id', 'bulan', 'tahun']);
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
