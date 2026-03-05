<?php

declare(strict_types=1);

namespace App\Core\Client\Domain\Entity;

use App\Core\Client\Domain\Exceptions\ClientDomainException;

readonly class Client
{
    /**
     * @throws ClientDomainException
     */
    private function __construct(
        public ?int $id,
        public string $name,
    ) {
        $this->validate($name);
    }

    /**
     * @throws ClientDomainException
     */
    public static function new(string $name): self
    {
        return new self(null, $name);
    }

    /**
     * @throws ClientDomainException
     */
    public static function restore(int $id, string $name): self
    {
        return new self($id, $name);
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

        return new self($this->id, $newName);
    }
}
