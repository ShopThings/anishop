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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('first_name', 30);
            $table->string('last_name', 30);
            $table->string('national_code', 10);
            $table->string('shaba_number', 30)->nullable();
            $table->rememberToken();
            $table->boolean('is_admin')->default(false)
                ->comment('for convenience to check if a user has an admin role and consider an admin or not');
            $table->boolean('is_banned')->default(false);
            $table->text('ban_desc')->nullable();
            $table->string('forget_password_code', 6)->nullable();
            $table->timestamp('forget_password_at')->nullable()
                ->comment('time that user forgets password and want to recover it');
            $table->string('verification_code', 6)->nullable();
            $table->timestamp('verified_at')->nullable()
                ->comment('check if user has been activated or not');
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
        Schema::dropIfExists('users');
    }
};
