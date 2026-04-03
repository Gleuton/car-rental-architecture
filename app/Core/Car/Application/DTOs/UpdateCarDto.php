<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs;

use App\Http\Requests\Car\UpdateCarRequest;

readonly class UpdateCarDto
{
    private function __construct(
        public int $id,
        public ?string $licensePlate,
        public ?string $color,
        public ?bool $isAvailable,
    ) {}

    public static function fromRequest(UpdateCarRequest $request, int $carId): self
    {
        return new self(
            $carId,
            $request->input('license_plate'),
            $request->input('color'),
            $request->input('is_available'),
        );
    }
}
