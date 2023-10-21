<?php

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
                ->constrained('order_details')->cascadeOnDelete()->cascadeOnUpdate()
                ->comment('for make more than one order relate to another');
            $table->string('code', 25)->unique();
            $table->string('payment_method_code', 25);
            $table->string('payment_method_title');
            $table->enum('payment_method_type', array_map(fn($item) => $item->name, PaymentTypesEnum::cases()));
            $table->enum('payment_status', array_map(fn($item) => $item->name, PaymentStatusesEnum::cases()));
            $table->timestamp('payment_status_changed_at')->nullable();
            $table->foreignId('payment_status_changed_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('payed_at')->nullable();
            $table->boolean('must_delete_later')->default(false);
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
        Schema::dropIfExists('orders');
    }
};
