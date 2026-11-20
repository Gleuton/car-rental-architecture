<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Entity;

use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Core\Shared\Domain\ValueObjects\Uuid;

readonly class Client
{
    /**
     * @throws ClientDomainException
     */
    private function __construct(
        public string $uuid,
        public string $name,
    ) {
        $this->validate($name);
    }

    /**
     * @throws ClientDomainException
     */
    public static function new(Uuid $uuid, string $name): self
    {
        return new self($uuid->value, $name);
    }

    /**
     * @throws ClientDomainException
     */
    public static function restore(string $name, string $uuid): self
    {
        return new self($uuid, $name);
    }

    /**
     * @throws ClientDomainException
     */
    private function validate(string $name): void
    {
        if (trim($name) === '') {
            throw new ClientDomainException('Client name cannot be empty');
        }

        if (mb_strlen($name) > 255) {
            throw new ClientDomainException('Client name must not exceed 255 characters');
        }
    }

    /**
     * @throws ClientDomainException
     */
    public function update(?string $name = null): self
    {
        $newName = $name ?? $this->name;

        return new self($this->uuid, $newName);
    }
}
