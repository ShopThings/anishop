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
        Schema::create('weight_post_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('min_weight');
            $table->unsignedInteger('max_weight');
            $table->unsignedBigInteger('post_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_post_prices');
    }
};
