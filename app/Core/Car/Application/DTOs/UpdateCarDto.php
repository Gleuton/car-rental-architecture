<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs;

use App\Http\Requests\Car\UpdateCarRequest;

readonly class UpdateCarDto
{
    private function __construct(
        public string $uuid,
        public ?string $licensePlate,
        public ?string $color,
        public ?bool $isAvailable,
    ) {}

    public static function fromRequest(UpdateCarRequest $request, string $carUuid): self
    {
        return new self(
            $carUuid,
            $request->input('license_plate'),
            $request->input('color'),
            $request->input('is_available'),
        );
    }
}
