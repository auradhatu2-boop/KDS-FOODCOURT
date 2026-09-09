<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencairan_dana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->decimal('jumlah_diajukan', 12, 2);
            $table->enum('status_verifikasi', [
                'diajukan',
                'disetujui',
                'ditolak',
                'selesai',
            ])->default('diajukan');
            $table->string('bukti_transfer')->nullable();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status_verifikasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencairan_dana');
    }
};
