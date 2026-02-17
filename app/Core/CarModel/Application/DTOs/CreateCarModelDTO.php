<?php

namespace App\Core\CarModel\Application\DTOs;

use App\Http\Requests\CarModel\StoreCarModelRequest;
use Illuminate\Http\UploadedFile;

class CreateCarModelDTO
{
    private function __construct(
        public int $brandId,
        public string $name,
        public UploadedFile $image,
        public int $doorsNumber,
        public int $seatsNumber,
        public bool $airbags,
        public bool $abs,
    )
    {
    }

    public static function fromRequest(StoreCarModelRequest $request): self
    {
        return new self(
            $request->brand_id,
            $request->name,
            $request->file('image'),
            $request->doors_number,
            $request->seats_number,
            $request->airbags,
            $request->abs
        );
    }
}