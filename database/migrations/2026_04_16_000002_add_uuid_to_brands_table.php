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
        if (Schema::hasColumn('brands', 'uuid')) {
            return;
        }

        Schema::table('brands', static function (Blueprint $table): void {
            $table->uuid('uuid')->nullable()->unique();
        });

        DB::table('brands')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, static function ($brands): void {
                foreach ($brands as $brand) {
                    DB::table('brands')
                        ->where('id', $brand->id)
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
        if (! Schema::hasColumn('brands', 'uuid')) {
            return;
        }

        Schema::table('brands', static function (Blueprint $table): void {
            $table->dropUnique('brands_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
};
