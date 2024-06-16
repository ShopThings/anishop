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
        Schema::create('send_methods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('image_id')->nullable()
                ->constrained('file_manager')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('determine_price_by_shop_location')->default(true)
                ->comment('method price will calculate only if this flag is false');
            $table->boolean('only_for_shop_location')->default(true)
                ->comment('other places than store location can\'t use this method');
            $table->boolean('apply_number_of_shipments_on_price')->default(true)
                ->comment('if this is true, price of method will determine for every shipment like if you have 2 shipments, price will consider 2 times');
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

            $table->index('title');
            $table->index('price');
            $table->index('is_published');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_methods');
    }
};
