<?php

namespace App\Core\Brand\Application\DTOs;


use App\Http\Requests\Brand\UpdateBrandRequest;

readonly class UpdateBrandDto
{
    private function __construct(
        public int $id,
        public ?string $name,
        public ?string $image
    ) {}
    public static function fromRequestId(UpdateBrandRequest $request, int $brandId): self
    {
        return new self(
            $brandId,
            $request->name,
            $request->image
        );
    }
}