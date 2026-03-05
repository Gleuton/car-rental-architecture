<?php

declare(strict_types=1);

namespace App\Core\Client\Application\DTOs;

use App\Http\Requests\Client\UpdateClientRequest;

readonly class UpdateClientDTO
{
    private function __construct(
        public int $id,
        public ?string $name,
    ) {}

    public static function fromRequest(UpdateClientRequest $request, int $clientId): self
    {
        return new self(
            id: $clientId,
            name: $request->input('name'),
        );
    }
}
