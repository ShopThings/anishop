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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->string('mobile', 11);
            $table->text('message')->comment('message of user');
            $table->text('answer')->nullable()
                ->comment('respond to user');
            $table->boolean('is_seen')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->foreignId('answered_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('changed_status_at')->nullable();
            $table->foreignId('changed_status_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $table->index('title');
            $table->index('answered_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
