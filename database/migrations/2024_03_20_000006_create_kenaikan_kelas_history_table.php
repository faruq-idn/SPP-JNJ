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
            $table->string('kelas_sebelum');  // Format: "7A", "8B", etc
            $table->string('kelas_sesudah')->nullable();  // Format: "8A", "9B", null untuk lulus
            $table->enum('status', ['aktif', 'lulus'])->default('aktif');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kenaikan_kelas_history');
    }
};
