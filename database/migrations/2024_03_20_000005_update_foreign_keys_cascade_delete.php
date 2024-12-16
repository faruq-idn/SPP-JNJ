<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropForeign(['santri_id']);
            $table->foreign('santri_id')
                  ->references('id')
                  ->on('santri')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropForeign(['santri_id']);
            $table->foreign('santri_id')
                  ->references('id')
                  ->on('santri');
        });
    }
};
