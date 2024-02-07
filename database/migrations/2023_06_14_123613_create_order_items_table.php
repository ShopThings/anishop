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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_key_id')
                ->constrained('order_details')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('product_id')
                ->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('product_code', 25);
            $table->string('product_title');
            $table->string('color_name');
            $table->string('color_hex', 12);
            $table->string('size');
            $table->text('guarantee');
            $table->unsignedInteger('weight')
                ->comment('it is in grams');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discounted_price');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedMediumInteger('quantity');
            $table->boolean('has_separate_shipment')->default(false)
                ->comment('this make send price for this product consider as another separate payment');
            $table->boolean('is_returned')->default(false);

            $table->index('product_title');
            $table->index('color_name');
            $table->index('size');
            $table->index('guarantee');
            $table->index('price');
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
