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
        if (Schema::hasColumn('car_models', 'brand_uuid') || ! Schema::hasColumn('car_models', 'brand_id')) {
            return;
        }

        Schema::table('car_models', static function (Blueprint $table): void {
            $table->uuid('brand_uuid')->nullable()->after('brand_id');
            $table->index('brand_uuid');
        });

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

        Schema::table('car_models', static function (Blueprint $table): void {
            $table->foreign('brand_uuid')
                ->references('uuid')
                ->on('brands')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('car_models', 'brand_uuid')) {
            return;
        }

        Schema::table('car_models', static function (Blueprint $table): void {
            $table->dropForeign(['brand_uuid']);
            $table->dropIndex(['brand_uuid']);
            $table->dropColumn('brand_uuid');
        });
    }
};
