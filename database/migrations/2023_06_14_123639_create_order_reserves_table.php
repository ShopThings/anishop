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
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_key_id')
                ->constrained('order_details')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();

            $table->index('order_key_id');
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
