<?php

use App\Enums\Orders\ReturnOrderStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_order_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_detail_id')
                ->constrained('order_details')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')
                ->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('code', 25)->unique();
            $table->text('description')->nullable();
            $table->text('not_accepted_description')->nullable();
            $table->enum('status', array_map(fn($item) => $item->value, ReturnOrderStatusesEnum::cases()))
                ->default(ReturnOrderStatusesEnum::CHECKING->value);
            $table->boolean('seen_status')->default(false);
            $table->timestamp('status_changed_at')->nullable();
            $table->foreignId('status_changed_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('requested_at')->useCurrent()->nullable();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('status');
            $table->index('requested_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_order_requests');
    }
};
