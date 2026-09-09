<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_induk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('meja')->restrictOnDelete();
            $table->decimal('total_bayar', 12, 2);
            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'batal',
            ])->default('menunggu_pembayaran');
            $table->timestamps();

            $table->index(['meja_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_induk');
    }
};
