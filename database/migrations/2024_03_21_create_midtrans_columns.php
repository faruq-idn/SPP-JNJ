<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->string('snap_token')->nullable();
            $table->string('order_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'order_id',
                'payment_details',
                'payment_type',
                'transaction_id'
            ]);
        });
    }
};