<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs;

use App\Http\Requests\Car\StoreCarRequest;

readonly class CreateCarDTO
{
    private function __construct(
        public int $carModelId,
        public string $licensePlate,
        public string $color,
        public bool $isAvailable,
        public int $km,
    ) {}

    public static function fromRequest(StoreCarRequest $request): self
    {
        return new self(
            $request->input('car_model_id'),
            $request->input('license_plate'),
            $request->input('color'),
            $request->input('is_available') ?? true,
            $request->input('km') ?? 0,
        );
    }
}
