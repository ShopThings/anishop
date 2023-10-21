<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->text('description');
            $table->boolean('is_seen')->default(true);
            $table->timestamp('changed_status_at')->nullable();
            $table->foreignId('changed_status_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->nullable();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
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
