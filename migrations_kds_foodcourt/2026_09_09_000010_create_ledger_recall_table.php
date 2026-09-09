<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Jejak audit setiap kali status sub_pesanan di-recall (FR-KDS-02 / UC-05).
    // Append-only: tidak ada update/delete pada baris yang sudah tercatat.
    public function up(): void
    {
        Schema::create('ledger_recall', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_pesanan_id')->constrained('sub_pesanan')->cascadeOnDelete();
            $table->string('status_sebelum');
            $table->string('status_sesudah');
            $table->string('staf_info')->nullable(); // nama/akun staf yang melakukan recall
            $table->timestamp('direcall_pada')->useCurrent();

            $table->index('sub_pesanan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_recall');
    }
};
