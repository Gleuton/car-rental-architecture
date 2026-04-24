<?php

declare(strict_types=1);

namespace App\Core\Car\Application\DTOs\CarModel;

use App\Http\Requests\CarModel\UpdateCarModelRequest;
use Illuminate\Http\UploadedFile;

readonly class UpdateCarModelDTO
{
    private function __construct(
        public string $uuid,
        public ?string $brandUuid,
        public ?string $name,
        public ?UploadedFile $image,
        public ?int $doorsNumber,
        public ?int $seatsNumber,
        public ?bool $airbags,
        public ?bool $abs,
    ) {}

    public static function fromRequest(UpdateCarModelRequest $request, string $uuid): self
    {
        return new self(
            $uuid,
            $request->input('brand_uuid'),
            $request->input('name'),
            $request->file('image'),
            $request->input('doors_number'),
            $request->input('seats_number'),
            $request->input('airbags'),
            $request->input('abs')
        );
    }
}
