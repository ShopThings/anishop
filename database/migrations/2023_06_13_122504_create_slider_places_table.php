<?php

use App\Enums\Sliders\SliderPlacesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slider_places', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('place_in', array_map(fn($item) => $item->name, SliderPlacesEnum::cases()))
                ->comment('is it an enum to place a slider in predefined places');
            $table->boolean('is_published')->default(true);

            $table->index('place_in');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_places');
    }
};
