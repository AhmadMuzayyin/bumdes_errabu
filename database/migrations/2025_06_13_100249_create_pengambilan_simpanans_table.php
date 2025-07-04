<?php

use App\Models\Nasabah;
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
        Schema::create('pengambilan_simpanans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Nasabah::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('nominal');
            $table->date('tgl_pengambilan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan_simpanans');
    }
};
