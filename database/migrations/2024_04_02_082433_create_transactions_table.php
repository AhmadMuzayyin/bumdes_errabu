<?php

use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->string('nama_pembeli');
            $table->string('telepon');
            $table->string('nama_barang');
            $table->decimal('total_bayaran', 10, 2)->default(0);
            $table->decimal('total_kembalian', 10, 2)->default(0);
            $table->dateTime('tanggal_transaksi');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
