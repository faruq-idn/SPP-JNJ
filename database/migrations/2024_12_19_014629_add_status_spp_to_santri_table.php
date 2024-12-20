<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('santri', function (Blueprint $table) {
            $table->enum('status_spp', ['Lunas', 'Belum Lunas'])->default('Belum Lunas')->after('status');
        });
    }

    public function down()
    {
        Schema::table('santri', function (Blueprint $table) {
            $table->dropColumn('status_spp');
        });
    }
};
