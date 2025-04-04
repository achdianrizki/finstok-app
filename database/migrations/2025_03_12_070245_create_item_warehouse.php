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
        Schema::create('item_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->integer('stock');
            $table->integer('original_stock');
            $table->decimal('price_per_item', 15, 2);
            $table->bigInteger('physical')->nullable();
            $table->bigInteger('profit')->nullable()->default(0);
            $table->decimal('difference', 15, 2)->default(0);


            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_warehouse');
    }
};
