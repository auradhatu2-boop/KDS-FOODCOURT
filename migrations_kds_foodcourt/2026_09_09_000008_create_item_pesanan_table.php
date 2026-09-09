<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_pesanan_id')->constrained('sub_pesanan')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained('menu_items')->restrictOnDelete();
            $table->unsignedSmallInteger('qty')->default(1);
            // snapshot harga saat dipesan, supaya perubahan harga menu di kemudian hari
            // tidak mengubah nilai transaksi historis
            $table->decimal('harga_satuan', 10, 2);
            $table->text('catatan_kustomisasi')->nullable();
            $table->timestamps();

            $table->index('sub_pesanan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_pesanan');
    }
};
