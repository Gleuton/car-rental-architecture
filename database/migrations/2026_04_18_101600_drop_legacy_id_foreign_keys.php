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
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('car_models', 'brand_id')) {
            Schema::table('car_models', static function (Blueprint $table) use ($driver): void {
                if ($driver !== 'sqlite') {
                    $table->dropForeign(['brand_id']);
                }

                $table->dropColumn('brand_id');
            });
        }

        if (Schema::hasColumn('cars', 'car_model_id')) {
            Schema::table('cars', static function (Blueprint $table) use ($driver): void {
                $table->dropIndex(['car_model_id']);

                if ($driver !== 'sqlite') {
                    $table->dropForeign(['car_model_id']);
                }

                $table->dropColumn('car_model_id');
            });
        }

        if (Schema::hasColumn('rentals', 'car_id') || Schema::hasColumn('rentals', 'client_id')) {
            Schema::table('rentals', static function (Blueprint $table) use ($driver): void {
                $table->dropIndex(['car_id']);
                $table->dropIndex(['client_id']);

                if ($driver !== 'sqlite') {
                    $table->dropForeign(['car_id']);
                    $table->dropForeign(['client_id']);
                }

                $table->dropColumn(['car_id', 'client_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('car_models', static function (Blueprint $table): void {
            if (! Schema::hasColumn('car_models', 'brand_id')) {
                $table->unsignedBigInteger('brand_id')->nullable()->after('uuid');
            }
        });

        DB::table('car_models')
            ->select('id', 'brand_uuid')
            ->whereNull('brand_id')
            ->orderBy('id')
            ->chunkById(100, static function ($carModels): void {
                foreach ($carModels as $carModel) {
                    $brandId = DB::table('brands')
                        ->where('uuid', $carModel->brand_uuid)
                        ->value('id');

                    if ($brandId === null) {
                        continue;
                    }

                    DB::table('car_models')
                        ->where('id', $carModel->id)
                        ->update(['brand_id' => $brandId]);
                }
            });

        Schema::table('cars', static function (Blueprint $table): void {
            if (! Schema::hasColumn('cars', 'car_model_id')) {
                $table->unsignedBigInteger('car_model_id')->nullable()->after('uuid');
            }
        });

        DB::table('cars')
            ->select('id', 'car_model_uuid')
            ->whereNull('car_model_id')
            ->orderBy('id')
            ->chunkById(100, static function ($cars): void {
                foreach ($cars as $car) {
                    $carModelId = DB::table('car_models')
                        ->where('uuid', $car->car_model_uuid)
                        ->value('id');

                    if ($carModelId === null) {
                        continue;
                    }

                    DB::table('cars')
                        ->where('id', $car->id)
                        ->update(['car_model_id' => $carModelId]);
                }
            });

        Schema::table('rentals', static function (Blueprint $table): void {
            if (! Schema::hasColumn('rentals', 'car_id')) {
                $table->unsignedBigInteger('car_id')->nullable()->after('uuid');
            }

            if (! Schema::hasColumn('rentals', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('car_id');
            }
        });

        DB::table('rentals')
            ->select('id', 'car_uuid', 'client_uuid')
            ->where(function ($query): void {
                $query->whereNull('car_id')
                    ->orWhereNull('client_id');
            })
            ->orderBy('id')
            ->chunkById(100, static function ($rentals): void {
                foreach ($rentals as $rental) {
                    $carId = DB::table('cars')
                        ->where('uuid', $rental->car_uuid)
                        ->value('id');

                    $clientId = DB::table('clients')
                        ->where('uuid', $rental->client_uuid)
                        ->value('id');

                    $updates = [];

                    if ($carId !== null) {
                        $updates['car_id'] = $carId;
                    }

                    if ($clientId !== null) {
                        $updates['client_id'] = $clientId;
                    }

                    if ($updates === []) {
                        continue;
                    }

                    DB::table('rentals')
                        ->where('id', $rental->id)
                        ->update($updates);
                }
            });

        Schema::table('car_models', static function (Blueprint $table): void {
            $table->foreign('brand_id')->references('id')->on('brands')->restrictOnDelete();
        });

        Schema::table('cars', static function (Blueprint $table): void {
            $table->foreign('car_model_id')->references('id')->on('car_models')->restrictOnDelete();
            $table->index('car_model_id');
        });

        Schema::table('rentals', static function (Blueprint $table): void {
            $table->foreign('car_id')->references('id')->on('cars')->restrictOnDelete();
            $table->foreign('client_id')->references('id')->on('clients')->restrictOnDelete();
            $table->index('car_id');
            $table->index('client_id');
        });
    }
};
