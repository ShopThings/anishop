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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('code', 25)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('address');
            $table->string('postal_code', 15);
            $table->string('receiver_name');
            $table->string('receiver_mobile');
            $table->text('description')
                ->comment('for additional information that can be update anytime with admin users.');
            $table->string('coupon_code', 25);
            $table->unsignedBigInteger('coupon_price');
            $table->unsignedBigInteger('shipping_price')->default(0);
            $table->unsignedBigInteger('discount_price');
            $table->unsignedBigInteger('final_price');
            $table->unsignedBigInteger('total_price');
            $table->string('send_status_title');
            $table->string('send_status_color_hex', 12);
            $table->timestamp('send_status_changed_at')->nullable();
            $table->foreignId('send_status_changed_by')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->boolean('is_needed_factor')->default(false);
            $table->boolean('is_in_place_delivery')->default(false);
            $table->boolean('is_product_returned_to_stock')->default(false);
            $table->timestamp('ordered_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
