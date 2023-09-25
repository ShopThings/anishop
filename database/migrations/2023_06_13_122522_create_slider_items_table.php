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
        Schema::create('slider_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')
                ->constrained('sliders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('priority')->default(0);
            $table->jsonb('options')->default('[]');
            $table->boolean('is_published')->default(true);
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_items');
    }
};
