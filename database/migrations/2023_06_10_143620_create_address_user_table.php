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
        Schema::create('address_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('full_name', 100);
            $table->string('mobile', 11);
            $table->text('address');
            $table->foreignId('city_id')->constrained('cities')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('province_id')->constrained('provinces')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('postal_code', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_user');
    }
};
