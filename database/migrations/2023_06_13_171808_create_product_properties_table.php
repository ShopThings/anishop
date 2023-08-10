<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code', 25)->unique();
            $table->string('color_name')->nullable();
            $table->string('color_hex', 12)->nullable();
            $table->string('size')->nullable();
            $table->text('guarantee')->nullable();
            $table->unsignedInteger('weight')
                ->comment('it is in grams');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discounted_price');
            $table->timestamp('discounted_until')->nullable();
            $table->unsignedDecimal('tax_rate')->nullable();
            $table->unsignedInteger('stock-count');
            $table->unsignedInteger('max_cart_count');
            $table->boolean('is_special')->default(true);
            $table->boolean('is_available')->default(true);
            $table->boolean('show_coming_soon')->default(true);
            $table->boolean('show_call_for_more')->default(true);
            $table->boolean('is_published')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_properties');
    }
};
