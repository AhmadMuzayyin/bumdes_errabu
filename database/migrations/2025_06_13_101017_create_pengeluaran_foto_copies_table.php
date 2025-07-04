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
        Schema::create('pengeluaran_foto_copies', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->bigInteger('jumlah');
            $table->bigInteger('harga')->default(0);
            $table->date('tgl_pengeluaran');
            $table->string('tujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_foto_copies');
    }
};
