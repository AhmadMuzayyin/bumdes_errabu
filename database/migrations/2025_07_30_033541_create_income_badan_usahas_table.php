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
        Schema::create('income_badan_usahas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BadanUsaha::class)->constrained()->cascadeOnDelete();
            $table->enum('jenis_pemasukan', ['Modal Usaha', 'Pemasukan Lainnya', 'Pendapatan Usaha']);
            $table->bigInteger('nominal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_badan_usahas');
    }
};
