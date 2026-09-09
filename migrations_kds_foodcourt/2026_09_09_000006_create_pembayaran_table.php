<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            // relasi 1:1 dengan pesanan_induk -> unique constraint
            $table->foreignId('pesanan_induk_id')->unique()->constrained('pesanan_induk')->cascadeOnDelete();
            $table->string('ref_qris')->unique();
            $table->decimal('nominal', 12, 2);
            $table->enum('status_bayar', [
                'pending',
                'sukses',
                'gagal',
                'kedaluwarsa',
            ])->default('pending');
            $table->timestamp('waktu_verifikasi')->nullable();
            // payload mentah webhook disimpan untuk audit/idempotency check (FR-CUST-02a)
            $table->json('payload_webhook')->nullable();
            $table->timestamps();

            $table->index('status_bayar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
