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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income'); // Pendapatan
            $table->unsignedBigInteger('expense'); // Pengeluaran
            $table->unsignedBigInteger('profit_loss')->nullable(); // Laba atau Rugi
            $table->foreignId('sale_id')->nullable()->constrained()->onDelete('cascade'); // ID Penjualan
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade'); // ID Pembelian
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
