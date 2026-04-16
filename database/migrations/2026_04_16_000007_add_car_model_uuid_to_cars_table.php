<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', static function (Blueprint $table): void {
            $table->uuid('car_model_uuid')->nullable()->after('car_model_id');
            $table->index('car_model_uuid');
        });

        DB::table('cars')
            ->select('id', 'car_model_id')
            ->whereNull('car_model_uuid')
            ->orderBy('id')
            ->chunkById(100, static function ($cars): void {
                foreach ($cars as $car) {
                    $carModelUuid = DB::table('car_models')
                        ->where('id', $car->car_model_id)
                        ->value('uuid');

                    if ($carModelUuid === null) {
                        continue;
                    }

                    DB::table('cars')
                        ->where('id', $car->id)
                        ->update([
                            'car_model_uuid' => $carModelUuid,
                        ]);
                }
            });

        Schema::table('cars', static function (Blueprint $table): void {
            $table->foreign('car_model_uuid')
                ->references('uuid')
                ->on('car_models')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', static function (Blueprint $table): void {
            $table->dropForeign(['car_model_uuid']);
            $table->dropIndex(['car_model_uuid']);
            $table->dropColumn('car_model_uuid');
        });
    }
};
