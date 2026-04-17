<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Core\Client\Domain\Entity\Client;

class ClientResource
{
    public static function toArray(Client $client): array
    {
        return [
            'uuid' => $client->uuid,
            'name' => $client->name,
        ];
    }
}
