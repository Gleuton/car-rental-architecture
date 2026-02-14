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
        Schema::create('cars', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_model_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('license_plate');
            $table->string('color');
            $table->boolean('is_available')->default(true);
            $table->integer('km')->default(0);

            $table->timestamps();

            $table->index('car_model_id');
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
