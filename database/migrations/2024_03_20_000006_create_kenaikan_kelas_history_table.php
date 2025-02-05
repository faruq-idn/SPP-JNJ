<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kenaikan_kelas_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('jenjang_awal');
            $table->string('kelas_awal');
            $table->string('status_awal');
            $table->string('jenjang_akhir');
            $table->string('kelas_akhir');
            $table->string('status_akhir');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kenaikan_kelas_history');
    }
};
