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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->constrained('menus')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->nullable()
                ->constrained('menu_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->text('link')->default('#');
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('can_have_children')->default(true);
            $table->boolean('is_published')->default(true);
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
