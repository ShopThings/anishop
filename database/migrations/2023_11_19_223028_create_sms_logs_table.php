<?php

use App\Enums\SMS\SMSSenderTypesEnum;
use App\Enums\SMS\SMSTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->text('receiver_numbers');
            $table->string('panel_number', 50)->nullable();
            $table->string('panel_name', 250);
            $table->text('body');
            $table->enum('type', array_map(fn($item) => $item->value, SMSTypesEnum::cases()));
            $table->enum('sender', array_map(fn($item) => $item->value, SMSSenderTypesEnum::cases()));
            $table->timestamp('created_at')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('panel_number');
            $table->index('type');
            $table->index('sender');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
