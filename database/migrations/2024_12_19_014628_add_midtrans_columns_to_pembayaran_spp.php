<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran_spp', 'snap_token')) {
                $table->string('snap_token')->nullable();
            }
            if (!Schema::hasColumn('pembayaran_spp', 'order_id')) {
                $table->string('order_id')->nullable();
            }
            if (!Schema::hasColumn('pembayaran_spp', 'payment_details')) {
                $table->json('payment_details')->nullable();
            }
            if (!Schema::hasColumn('pembayaran_spp', 'payment_type')) {
                $table->string('payment_type')->nullable();
            }
            if (!Schema::hasColumn('pembayaran_spp', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'order_id']);
        });
    }
};
