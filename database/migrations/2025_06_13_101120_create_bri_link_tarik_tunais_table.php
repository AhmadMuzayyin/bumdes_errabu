<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bri_link_tarik_tunais', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama');
            $table->string('norek')->nullable();
            $table->string('norek_tujuan')->nullable();
            $table->bigInteger('nominal')->default(0);
            $table->date('tgl_tarik_tunai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bri_link_tarik_tunais');
    }
};
