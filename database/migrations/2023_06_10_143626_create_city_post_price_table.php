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
        Schema::create('city_post_price', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')
                ->constrained('cities')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('post_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_post_price');
    }
};
