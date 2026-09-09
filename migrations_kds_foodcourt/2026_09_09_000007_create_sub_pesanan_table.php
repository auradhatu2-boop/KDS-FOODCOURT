<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_induk_id')->constrained('pesanan_induk')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();

            $table->enum('status_dapur', [
                'diterima',
                'dimasak',
                'siap',
                'diambil',
            ])->default('diterima');

            // hasil split payment (FR-PAY-01), snapshot komisi dipotong pada saat split
            $table->decimal('nilai_bruto', 12, 2);
            $table->decimal('persen_komisi_dipotong', 5, 2);
            $table->decimal('bagian_dana_tenant', 12, 2);

            // timestamp per tahap, dipakai untuk FR-KDS-03 (kalibrasi estimasi waktu masak)
            $table->timestamp('waktu_diterima')->nullable();
            $table->timestamp('waktu_dimasak')->nullable();
            $table->timestamp('waktu_siap')->nullable();
            $table->timestamp('waktu_diambil')->nullable();

            $table->timestamps();

            // NFR-DAT-01: isolasi & pelaporan per tenant
            $table->index(['tenant_id', 'status_dapur']);
            $table->index('pesanan_induk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_pesanan');
    }
};
