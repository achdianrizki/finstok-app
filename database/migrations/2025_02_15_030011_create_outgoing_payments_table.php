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
        Schema::create('outgoing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('cascade');
            $table->string('receipt_number');
            $table->date('payment_date');
            $table->text('note')->nullable();
            $table->enum('payment_method', ['tunai', 'transfer']);
            $table->string('bank_account_number')->nullable();
            $table->string('payment_code')->nullable();
            $table->decimal('total_unpaid', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_payments');
    }
};
