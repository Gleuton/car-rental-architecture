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
        Schema::create('car_models', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('image');
            $table->tinyInteger('doors')->default(4);
            $table->tinyInteger('seats')->default(5);
            $table->boolean('airbags')->default(false);
            $table->boolean('abs')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
