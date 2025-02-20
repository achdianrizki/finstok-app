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
        Schema::create('incoming_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->string('invoice_number');
            $table->date('payment_date'); 
            $table->enum('payment_method', ['tunai', 'transfer']);
            $table->string('bank_account_number')->nullable();
            $table->string('payment_code')->nullable();
            $table->unsignedBigInteger('pay_amount');
            $table->unsignedBigInteger('remaining_payment');
            $table->unsignedBigInteger('total_paid');
            $table->text('information');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_payment');
    }
};
