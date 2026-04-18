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
        $this->backfillCarModelBrandUuid();
        $this->backfillCarCarModelUuid();
        $this->backfillRentalUuids();

        if (Schema::hasColumn('car_models', 'brand_uuid')) {
            Schema::table('car_models', static function (Blueprint $table): void {
                $table->uuid('brand_uuid')->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('cars', 'car_model_uuid')) {
            Schema::table('cars', static function (Blueprint $table): void {
                $table->uuid('car_model_uuid')->nullable(false)->change();
            });
        }

        if (Schema::hasColumn('rentals', 'car_uuid') || Schema::hasColumn('rentals', 'client_uuid')) {
            Schema::table('rentals', static function (Blueprint $table): void {
                if (Schema::hasColumn('rentals', 'car_uuid')) {
                    $table->uuid('car_uuid')->nullable(false)->change();
                }

                if (Schema::hasColumn('rentals', 'client_uuid')) {
                    $table->uuid('client_uuid')->nullable(false)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('car_models', 'brand_uuid')) {
            Schema::table('car_models', static function (Blueprint $table): void {
                $table->uuid('brand_uuid')->nullable()->change();
            });
        }

        if (Schema::hasColumn('cars', 'car_model_uuid')) {
            Schema::table('cars', static function (Blueprint $table): void {
                $table->uuid('car_model_uuid')->nullable()->change();
            });
        }

        if (Schema::hasColumn('rentals', 'car_uuid') || Schema::hasColumn('rentals', 'client_uuid')) {
            Schema::table('rentals', static function (Blueprint $table): void {
                if (Schema::hasColumn('rentals', 'car_uuid')) {
                    $table->uuid('car_uuid')->nullable()->change();
                }

                if (Schema::hasColumn('rentals', 'client_uuid')) {
                    $table->uuid('client_uuid')->nullable()->change();
                }
            });
        }
    }

    private function backfillCarModelBrandUuid(): void
    {
        if (! Schema::hasColumn('car_models', 'brand_id') || ! Schema::hasColumn('car_models', 'brand_uuid')) {
            return;
        }

        DB::table('car_models')
            ->select('id', 'brand_id')
            ->whereNull('brand_uuid')
            ->orderBy('id')
            ->chunkById(100, static function ($carModels): void {
                foreach ($carModels as $carModel) {
                    $brandUuid = DB::table('brands')
                        ->where('id', $carModel->brand_id)
                        ->value('uuid');

                    if ($brandUuid === null) {
                        continue;
                    }

                    DB::table('car_models')
                        ->where('id', $carModel->id)
                        ->update([
                            'brand_uuid' => $brandUuid,
                        ]);
                }
            });
    }

    private function backfillCarCarModelUuid(): void
    {
        if (! Schema::hasColumn('cars', 'car_model_id') || ! Schema::hasColumn('cars', 'car_model_uuid')) {
            return;
        }

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
    }

    private function backfillRentalUuids(): void
    {
        if (! Schema::hasColumn('rentals', 'car_id') || ! Schema::hasColumn('rentals', 'client_id') || ! Schema::hasColumn('rentals', 'car_uuid') || ! Schema::hasColumn('rentals', 'client_uuid')) {
            return;
        }

        DB::table('rentals')
            ->select('id', 'car_id', 'client_id')
            ->where(function ($query): void {
                $query->whereNull('car_uuid')
                    ->orWhereNull('client_uuid');
            })
            ->orderBy('id')
            ->chunkById(100, static function ($rentals): void {
                foreach ($rentals as $rental) {
                    $carUuid = DB::table('cars')
                        ->where('id', $rental->car_id)
                        ->value('uuid');

                    $clientUuid = DB::table('clients')
                        ->where('id', $rental->client_id)
                        ->value('uuid');

                    $updates = [];

                    if ($carUuid !== null) {
                        $updates['car_uuid'] = $carUuid;
                    }

                    if ($clientUuid !== null) {
                        $updates['client_uuid'] = $clientUuid;
                    }

                    if ($updates === []) {
                        continue;
                    }

                    DB::table('rentals')
                        ->where('id', $rental->id)
                        ->update($updates);
                }
            });
    }
};
