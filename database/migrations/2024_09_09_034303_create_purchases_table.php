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
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('sub_total');
            $table->decimal('total_discount')->nullable();
            $table->date('purchase_date');
            $table->enum('status', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->string('tax');
            $table->text('information');
            $table->integer('total_qty');
            $table->string('purchase_number');
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
