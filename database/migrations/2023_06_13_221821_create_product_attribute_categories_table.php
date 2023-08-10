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
        Schema::create('product_attribute_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_attribute_id')
                ->constrained('product_attributes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')
                ->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('priority')->default(0);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute_categories');
    }
};
