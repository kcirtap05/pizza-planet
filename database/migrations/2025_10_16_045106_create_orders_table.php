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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pizza_id')->nullable()->constrained()->nullOnDelete();
            $table->json('custom_toppings')->nullable();
            $table->decimal('total_price', 6, 2);
            $table->enum('payment_method', ['card', 'paypal']); 
            $table->timestamps();

            $table->index('pizza_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
