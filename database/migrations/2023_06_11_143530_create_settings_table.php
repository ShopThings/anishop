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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('setting_value')->default('');
            $table->string('group_name');
            $table->text('default_value')->default('');
            $table->bigInteger('min_value')->nullable();
            $table->bigInteger('max_value')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
