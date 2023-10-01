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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('condition', array_map(fn($item) => $item->name, CommentConditionsEnum::cases()))
                ->comment('show comment condition like if it is accepted or not');
            $table->enum('status', array_map(fn($item) => $item->name, CommentStatusesEnum::cases()))
                ->comment('show comment status like if it is read or not');
            $table->text('pros')->comment('positive sides');
            $table->text('cons')->comment('negative sides');
            $table->text('description');
            $table->unsignedBigInteger('flag_count')->default(0);
            $table->unsignedBigInteger('up_vote_count')->default(0);
            $table->unsignedBigInteger('down_vote_count')->default(0);
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
        Schema::dropIfExists('comments');
    }
};
