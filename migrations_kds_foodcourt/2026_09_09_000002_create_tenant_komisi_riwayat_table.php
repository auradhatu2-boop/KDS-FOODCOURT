<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabel append-only sesuai NFR-DAT-03: koreksi komisi selalu berupa entri baru,
    // tidak ada update/delete terhadap baris yang sudah ada.
    public function up(): void
    {
        Schema::create('tenant_komisi_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->decimal('persen_komisi', 5, 2);
            $table->timestamp('berlaku_sejak');
            $table->string('dicatat_oleh')->nullable(); // nama/akun pengelola yang mengubah
            $table->timestamp('created_at')->useCurrent();
            // sengaja tanpa updated_at: baris tidak pernah diubah setelah dibuat

            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_komisi_riwayat');
    }
};
