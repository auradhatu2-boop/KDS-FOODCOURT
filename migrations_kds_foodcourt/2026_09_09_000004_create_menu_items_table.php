<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->unsignedSmallInteger('estimasi_masak_menit')->default(10);
            $table->boolean('status_tersedia')->default(true);
            $table->timestamps();

            // NFR-DAT-01: kolom tenant_id wajib + terindeks untuk isolasi data antar tenant
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
