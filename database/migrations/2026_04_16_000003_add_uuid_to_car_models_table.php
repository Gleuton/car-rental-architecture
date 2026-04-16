<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_models', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable()->unique();
        });

        DB::table('car_models')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, static function ($carModels): void {
                foreach ($carModels as $carModel) {
                    DB::table('car_models')
                        ->where('id', $carModel->id)
                        ->update([
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_models', static function (Blueprint $table): void {
            $table->dropUnique('car_models_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
};
