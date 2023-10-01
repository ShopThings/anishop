<?php

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('badge_id')
                ->constrained('blog_comment_badges')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('comment_id')->nullable()
                ->constrained('blog_comments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('answer_to')->nullable()
                ->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('condition', array_map(fn($item) => $item->name, CommentConditionsEnum::cases()))
                ->comment('show comment condition like if it is accepted or not');
            $table->enum('status', array_map(fn($item) => $item->name, CommentStatusesEnum::cases()))
                ->comment('show comment status like if it is read or not');
            $table->text('description');
            $table->unsignedBigInteger('flag_count')->default(0);
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
        Schema::dropIfExists('blog_comments');
    }
};
