<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\DTOs;

use App\Http\Requests\CarModel\UpdateCarModelRequest;
use Illuminate\Http\UploadedFile;

class UpdateCarModelDTO
{
    private function __construct(
        public int $id,
        public ?int $brandId,
        public ?string $name,
        public ?UploadedFile $image,
        public ?int $doorsNumber,
        public ?int $seatsNumber,
        public ?bool $airbags,
        public ?bool $abs,
    ) {}

    public static function fromRequest(UpdateCarModelRequest $request, int $id): self
    {
        return new self(
            $id,
            $request->input('brand_id'),
            $request->input('name'),
            $request->file('image'),
            $request->input('doors_number'),
            $request->input('seats_number'),
            $request->input('airbags'),
            $request->input('abs')
        );
    }
}
