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
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id('CartID');
            $table->unsignedBigInteger('UserID');
            $table->foreign('UserID')
                ->references('UserID')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamp('CreatedDate')->useCurrent(); 
            $table->enum('Status', ['open', 'closed', 'abandoned'])->default('open'); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};
