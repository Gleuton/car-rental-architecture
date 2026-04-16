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
        Schema::table('cars', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable()->unique();
        });

        DB::table('cars')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, static function ($cars): void {
                foreach ($cars as $car) {
                    DB::table('cars')
                        ->where('id', $car->id)
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
        Schema::table('cars', static function (Blueprint $table): void {
            $table->dropUnique('cars_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
};
