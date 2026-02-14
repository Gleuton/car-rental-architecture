<?php

namespace App\Core\Brand\Application\DTOs;

use App\Http\Requests\Brand\StoreBrandRequest;

readonly class CreateBrandDTO
{
    private function __construct(
        public string $name,
        public string $image
    ) {}

    public static function fromRequest(StoreBrandRequest $request): self
    {
        return new self(
            $request->name,
            $request->image
        );
    }
}
