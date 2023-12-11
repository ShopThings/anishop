<?php

use App\Enums\AccountTypesEnum;
use App\Enums\Notification\AccountNotificationTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_main_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('account', 255)
                ->comment('it is either user\'s number or email');
            $table->enum('account_type', array_map(fn($item) => $item->name, AccountTypesEnum::cases()));
            $table->enum('notification_type', array_map(fn($item) => $item->name, AccountNotificationTypesEnum::cases()))
                ->comment('what kind of notification should be announced to the account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_main_numbers');
    }
};
