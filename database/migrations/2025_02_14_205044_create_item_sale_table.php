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
        Schema::create('item_sale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->integer('qty_sold');
            $table->integer('discount1')->nullable();
            $table->integer('discount2')->nullable();
            $table->integer('discount3')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->integer('ad');

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_sale');
    }
};
