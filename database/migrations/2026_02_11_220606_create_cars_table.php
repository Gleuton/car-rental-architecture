<?php

declare(strict_types=1);

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
        Schema::create('cars', static function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('car_model_uuid')
                ->constrained('car_models', 'uuid')
                ->restrictOnDelete();

            $table->string('license_plate');
            $table->string('color');
            $table->boolean('is_available')->default(true);
            $table->integer('km')->default(0);

            $table->timestamps();

            $table->index('car_model_uuid');
            $table->index('license_plate');
            $table->index('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
