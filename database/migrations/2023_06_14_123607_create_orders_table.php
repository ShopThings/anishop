<?php

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('key_id')
                ->comment('for make more than one order relate to another')
                ->constrained('order_details')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('must_pay_price')
                ->comment('amount of money that a user should pay in this bill');
            $table->foreignId('payment_method_id')->nullable()
                ->constrained('payment_methods')->nullOnDelete()->cascadeOnUpdate();
            $table->string('payment_method_title');
            $table->enum('payment_method_type', array_map(fn($item) => $item->value, PaymentTypesEnum::cases()));
            $table->enum('payment_method_gateway_type', array_map(fn($item) => $item->value, GatewaysEnum::cases()));
            $table->enum('payment_status', array_map(fn($item) => $item->value, PaymentStatusesEnum::cases()));
            $table->timestamp('payment_status_changed_at')->nullable();
            $table->foreignId('payment_status_changed_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('can_pay_again_at')->nullable()
                ->comment('if it has error for any reason, user can pay in after a specific time');
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('paid_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
