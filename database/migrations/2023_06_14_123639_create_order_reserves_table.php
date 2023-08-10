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
        Schema::create('order_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_code')
                ->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_reserves');
    }
};
