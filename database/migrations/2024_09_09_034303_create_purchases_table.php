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
            $table->string('name');
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('cascade');
            $table->unsignedBigInteger('price');
            $table->integer('qty');
            $table->unsignedBigInteger('total_price');
            $table->enum('purchase_type', ['stock', 'asset']);
            $table->string('supplier_name');
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
