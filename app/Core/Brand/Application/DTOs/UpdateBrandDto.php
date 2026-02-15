<?php

namespace App\Core\Brand\Application\DTOs;

use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\UploadedFile;

readonly class UpdateBrandDto
{
    private function __construct(
        public int $id,
        public ?string $name,
        public ?UploadedFile $imageFile = null
    ) {}

    public static function fromRequestId(UpdateBrandRequest $request, int $brandId): self
    {
        return new self(
            id: $brandId,
            name: $request->name,
            imageFile: $request->file('image')
        );
    }
}
