<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tenant');
            // persen_komisi = nilai komisi yang sedang aktif (cache), riwayat lengkap ada di tenant_komisi_riwayat
            $table->decimal('persen_komisi', 5, 2)->default(0);
            $table->boolean('status_buka')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
