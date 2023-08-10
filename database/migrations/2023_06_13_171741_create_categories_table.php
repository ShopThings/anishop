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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()
                ->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('escaped_name');
            $table->string('slug');
            $table->text('image')->nullable();
            $table->text('ancestry')->nullable();
            $table->unsignedInteger('level')->default(0);
            $table->integer('priority')->default(0);
            $table->boolean('show_in_menu')->default(true)
                ->comment('show in main categories menu or not');
            $table->boolean('show_in_side_menu')->default(true)
                ->comment('neither show in search side panel or not');
            $table->boolean('show_in_slider')->default(true);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_deletable')->default(true);
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('categories');
    }
};
