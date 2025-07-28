<?php

use App\Models\BadanUsaha;
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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->enum('sumber_dana', ['APB DESA', 'BANK', 'PEMERINTAH PROVINSI', 'PEMERINTAH KOTA', 'PIHAK KETIGA', 'LAIN-LAIN', 'Penghasilan Foto Copy', 'Penghasilan BRI Link', 'Penghasilan Simpan Pinjam']);
            $table->bigInteger('nominal');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
