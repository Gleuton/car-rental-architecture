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
        Schema::create('rentals', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('client_id')
                ->constrained()
                ->restrictOnDelete();

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('day_price_cents')->default(0);
            $table->unsignedInteger('initial_km')->default(0);
            $table->unsignedInteger('final_km')->default(0);

            $table->timestamps();

            $table->index('car_id');
            $table->index('client_id');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
