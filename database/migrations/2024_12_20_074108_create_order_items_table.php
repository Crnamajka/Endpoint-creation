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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemID');
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('VariantID');
            $table->foreign('OrderID')
                ->references('OrderID')
                ->on('orders')
                ->onDelete('cascade');
            $table->foreign('VariantID')
                ->references('VariantID')
                ->on('product_variants')
                ->onDelete('cascade');
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
