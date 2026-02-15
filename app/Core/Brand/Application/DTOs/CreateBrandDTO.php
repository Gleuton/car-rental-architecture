<?php

namespace App\Core\Brand\Application\DTOs;

use App\Http\Requests\Brand\StoreBrandRequest;
use Illuminate\Http\UploadedFile;

readonly class CreateBrandDTO
{
    private function __construct(
        public string $name,
        public UploadedFile $image
    ) {}

    public static function fromRequest(StoreBrandRequest $request): self
    {
        return new self(
            $request->name,
            $request->file('image')
        );
    }
}
