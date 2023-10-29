<?php

use App\Enums\Payments\GatewaysEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gateway_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete()->cascadeOnUpdate();
            $table->boolean('status')->default(false);
            $table->text('message');
            $table->string('transaction')->nullable()
                ->comment('payment code to track payment');
            $table->string('receipt')->nullable();
            $table->enum('gateway_type', array_map(fn($item) => $item->name, GatewaysEnum::cases()))
                ->comment('indicates which gateway is this');
            $table->string('meta')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_payments');
    }
};
