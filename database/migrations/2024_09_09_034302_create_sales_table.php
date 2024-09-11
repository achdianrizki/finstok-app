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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('qty_sold');
            $table->enum('payment_method', ['cash', 'credit']);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->unsignedBigInteger('discount');
            $table->unsignedBigInteger('down_payment');
            $table->unsignedBigInteger('remaining_payment');
            $table->unsignedBigInteger('total_price');
            $table->foreignId('distributor_id')->nullable()->constrained('distributors')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
