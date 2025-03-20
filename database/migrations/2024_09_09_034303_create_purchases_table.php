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
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->decimal('total_discount1', 15, 2)->nullable();
            $table->decimal('total_discount2', 15, 2)->nullable();
            $table->decimal('total_discount3', 15, 2)->nullable();
            $table->date('purchase_date');
            $table->enum('status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->decimal('tax', 15, 2)->nullable();
            $table->string('tax_type')->nullable();
            $table->unsignedBigInteger('ad')->nullable();
            $table->text('information');
            $table->integer('total_qty');
            $table->string('purchase_number');
            $table->integer('due_date_duration');
            $table->date('due_date');
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
