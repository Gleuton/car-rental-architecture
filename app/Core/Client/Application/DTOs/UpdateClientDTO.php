<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

use App\Http\Requests\Client\UpdateClientRequest;

readonly class UpdateClientDTO
{
    private function __construct(
        public string $uuid,
        public ?string $name,
    ) {}

    public static function fromRequest(UpdateClientRequest $request, string $client): self
    {
        return new self(
            uuid: $client,
            name: $request->input('name'),
        );
    }
}
