<?php

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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama barang atau aset yang dibeli
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('cascade'); // Nullable jika pembelian adalah aset
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('amount');
            $table->enum('purchase_type', ['stock', 'asset']); // Jenis pembelian: barang dagangan atau aset
            $table->string('supplier_name'); // Pemasok barang/aset
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
