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
            $table->string('name');
            $table->enum('payment_method', ['cash', 'tempo']);
            $table->string('buyer');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('distributor_id')->nullable()->constrained()->onDelete('cascade'); // Nullable jika pembeli bukan distributor
            $table->decimal('diskon');
            $table->unsignedBigInteger('amount');
            $table->decimal('total_price', 15, 2); // Harga total setelah diskon
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
