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
            $table->foreignId('buyer_id')->constrained('buyers')->onDelete('cascade');
            $table->foreignId('salesman_id')->nullable()->constrained('salesman')->onDelete('cascade');
            $table->string('sale_number');
            $table->decimal('total_price', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->decimal('discount1_value', 15, 2)->nullable();
            $table->decimal('discount2_value', 15, 2)->nullable();
            $table->decimal('discount3_value', 15, 2)->nullable();
            $table->decimal('total_discount', 15, 2)->nullable();
            $table->date('sale_date');
            $table->enum('status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->decimal('tax', 15, 2);
            $table->text('information')->nullable();
            $table->integer('qty_sold');
            // $table->integer('qty_sold');
            // $table->enum('payment_status', ['lunas', 'belum lunas']);
            // $table->unsignedBigInteger('discount')->nullable();
            // $table->unsignedBigInteger('down_payment')->nullable();
            // $table->unsignedBigInteger('remaining_payment');
            // $table->unsignedBigInteger('total_price');
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
