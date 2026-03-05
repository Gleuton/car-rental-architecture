<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

use App\Http\Requests\Client\StoreClientRequest;

readonly class CreateClientDTO
{
    private function __construct(
        public string $name
    ) {}

    public static function fromRequest(StoreClientRequest $request): self
    {
        return new self(
            $request->input('name')
        );
    }
}
