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
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('cascade');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('just_paid');
            $table->enum('status', ['paid', 'unpaid']);
            $table->string('supplier_name');
            $table->integer('stock');
            $table->integer('discount');
            $table->string('invoice number');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
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
