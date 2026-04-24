<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs\Brand;

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
            $request->input('name'),
            $request->file('image')
        );
    }
}
