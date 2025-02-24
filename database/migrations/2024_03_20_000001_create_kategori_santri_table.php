<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->decimal('biaya_makan', 10, 2)->default(0);
            $table->decimal('biaya_asrama', 10, 2)->default(0);
            $table->decimal('biaya_listrik', 10, 2)->default(0);
            $table->decimal('biaya_kesehatan', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_santri');
    }
};
