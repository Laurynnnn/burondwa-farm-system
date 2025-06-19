<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->enum('movement_type', ['in', 'out']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('package_unit')->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('reference_type')->nullable(); // e.g., 'sale', 'manual'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
}; 