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
        Schema::create('return_order_request_items', function (Blueprint $table) {
            $table->id();
            $table->string('return_code', 25);
            $table->foreign('return_code')->references('code')->on('return_order_requests')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_item_id')
                ->constrained('order_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedMediumInteger('quantity');
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('accepted_by')->nullable()
                ->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('updated_at')->nullable();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_order_request_items');
    }
};
