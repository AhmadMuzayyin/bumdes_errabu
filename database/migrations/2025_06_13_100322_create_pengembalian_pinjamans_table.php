<?php

use App\Models\Pinjamans;
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
        Schema::create('pengembalian_pinjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pinjamans::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('nominal_cicilan');
            $table->date('tgl_pengembalian_sementara');
            $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_pinjamans');
    }
};
