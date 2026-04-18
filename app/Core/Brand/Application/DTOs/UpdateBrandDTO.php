<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\DTOs;

use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\UploadedFile;

readonly class UpdateBrandDTO
{
    private function __construct(
        public string $uuid,
        public ?string $name,
        public ?UploadedFile $imageFile = null
    ) {}

    public static function fromRequestUuid(UpdateBrandRequest $request, string $brandUuid): self
    {
        return new self(
            uuid: $brandUuid,
            name: $request->input('name'),
            imageFile: $request->file('image')
        );
    }
}
