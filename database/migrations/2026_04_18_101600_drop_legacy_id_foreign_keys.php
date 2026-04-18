<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

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
        // Intentionally left blank: sequential legacy IDs are deprecated.
    }
};
