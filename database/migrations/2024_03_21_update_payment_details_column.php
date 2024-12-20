<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->json('payment_details')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->text('payment_details')->nullable()->change();
        });
    }
};
