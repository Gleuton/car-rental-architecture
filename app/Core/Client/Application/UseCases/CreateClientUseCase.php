<?php

declare(strict_types=1);

namespace App\Core\Client\Application\UseCases;

use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;

readonly class CreateClientUseCase
{
    public function __construct(
        private ClientRepositoryInterface $repository,
        private UuidGeneratorInterface $uuidGenerator,
    ) {}

    /**
     * @throws ClientDomainException
     */
    public function execute(CreateClientDTO $dto): Client
    {
        $client = Client::new($this->uuidGenerator->generate(), $dto->name);

        return $this->repository->save($client);
    }
}
