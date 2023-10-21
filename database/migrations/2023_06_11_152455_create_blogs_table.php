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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('blog_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title');
            $table->string('escaped_title');
            $table->string('slug');
            $table->foreignId('image_id')->nullable()
                ->constrained('file_manager')->nullOnDelete()->cascadeOnUpdate();
            $table->text('description');
            $table->text('keywords');
            $table->boolean('is_commenting_allowed')->default(true);
            $table->boolean('is_published')->default(true);
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
        Schema::dropIfExists('blogs');
    }
};
