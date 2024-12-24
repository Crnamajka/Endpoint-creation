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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('CartItemID');
            $table->unsignedBigInteger('CartID'); 
            $table->unsignedBigInteger('VariantID');
            $table->foreign('CartID') 
                ->references('CartID')
                ->on('shopping_carts') 
                ->onDelete('cascade'); 
            $table->foreign('VariantID') 
                ->references('VariantID')
                ->on('product_variants')
                ->onDelete('cascade');
            $table->integer('Quantity')->default(1); 
            $table->decimal('UnitPrice', 10, 2); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
