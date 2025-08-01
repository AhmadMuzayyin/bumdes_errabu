<?php

use App\Models\HargaFotoCopy;
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
        Schema::create('pembayaran_foto_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HargaFotoCopy::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('jumlah');
            $table->bigInteger('total_pembayaran');
            $table->date('tgl_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_foto_copies');
    }
};
