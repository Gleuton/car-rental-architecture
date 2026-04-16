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
        Schema::table('rentals', static function (Blueprint $table): void {
            $table->uuid('car_uuid')->nullable()->after('car_id');
            $table->uuid('client_uuid')->nullable()->after('client_id');
            $table->index('car_uuid');
            $table->index('client_uuid');
        });

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

        Schema::table('rentals', static function (Blueprint $table): void {
            $table->foreign('car_uuid')
                ->references('uuid')
                ->on('cars')
                ->restrictOnDelete();

            $table->foreign('client_uuid')
                ->references('uuid')
                ->on('clients')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', static function (Blueprint $table): void {
            $table->dropForeign(['car_uuid']);
            $table->dropForeign(['client_uuid']);
            $table->dropIndex(['car_uuid']);
            $table->dropIndex(['client_uuid']);
            $table->dropColumn(['car_uuid', 'client_uuid']);
        });
    }
};
