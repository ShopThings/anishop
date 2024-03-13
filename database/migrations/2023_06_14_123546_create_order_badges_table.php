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
        Schema::create('order_badges', function (Blueprint $table) {
            $table->id();
            $table->string('code', 25)->unique();
            $table->string('title');
            $table->string('color_hex', 12);
            $table->boolean('is_starting_badge')->default(false)
                ->comment('سفارشات ابتدا در این مرحله قرار می گیرند');
            $table->boolean('is_end_badge')->default(false)
                ->comment('برچسب نهایی برای سفارشات');
            $table->boolean('should_return_order_product')->default(false);
            $table->boolean('can_return_order')->default(false)
                ->comment('آیا سفارش در این مرحله قابل مرجوع می باشد یا خیر');
            $table->boolean('is_title_editable')->default(true);
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

            $table->index('title');
            $table->index('is_published');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_badges');
    }
};
