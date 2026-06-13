<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs\CarModel;

use App\Http\Requests\CarModel\StoreCarModelRequest;
use Illuminate\Http\UploadedFile;

readonly class CreateCarModelDTO
{
    private function __construct(
        public string $brandUuid,
        public string $name,
        public UploadedFile $image,
        public int $doorsNumber,
        public int $seatsNumber,
        public bool $airbags,
        public bool $abs,
    ) {}

    public static function fromRequest(StoreCarModelRequest $request): self
    {
        return new self(
            $request->input('brand_uuid'),
            $request->input('name'),
            $request->file('image'),
            (int) $request->input('doors_number'),
            (int) $request->input('seats_number'),
            (bool) $request->input('airbags'),
            (bool) $request->input('abs')
        );
    }
}
