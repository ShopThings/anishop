<?php

use App\Repositories\Contracts\FileRepositoryInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_manager', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extension', 10);
            $table->text('path');
            $table->text('disk')->default(FileRepositoryInterface::STORAGE_DISK_PUBLIC);
            $table->boolean('is_deletable')->default(true);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            // for MySQL only (I guess)
            $table->text('full_path')
                ->virtualAs('CONCAT(file_manager.path, \'/\', file_manager.name, \'.\', file_manager.extension)');

            $table->index('name');
            $table->index('extension');
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_manager');
    }
};
