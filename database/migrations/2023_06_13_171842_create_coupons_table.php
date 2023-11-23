<?php

use App\Enums\Times\TimesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code', 25)->unique();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('apply_min_price')->nullable();
            $table->unsignedBigInteger('apply_max_price')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedSmallInteger('use_count')->default(1);
            $table->unsignedSmallInteger('reusable_after')->default(TimesEnum::DAYS_30_D->value)
                ->comment('coupon can be use after some days for last user\'s use');
            $table->boolean('is_published')->default(true);
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('code');
            $table->index('apply_min_price');
            $table->index('apply_max_price');
            $table->index('start_at');
            $table->index('end_at');
            $table->index('is_published');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
