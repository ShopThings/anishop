<?php

use App\Enums\Payments\GatewaysEnum;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('image_id')->nullable()
                ->constrained('file_manager')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('type', array_map(fn($item) => $item->name, PaymentTypesEnum::cases()));
            $table->enum('bank_gateway_type', array_map(fn($item) => $item->name, GatewaysEnum::cases()))
                ->nullable();
            $table->text('options')->nullable()
                ->comment('for more options about the selected type for example credentials of bank gateway type');
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
        Schema::dropIfExists('payment_methods');
    }
};
