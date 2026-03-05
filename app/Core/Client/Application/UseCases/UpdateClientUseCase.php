<?php

declare(strict_types=1);

namespace App\Core\Client\Application\UseCases;

use App\Core\Client\Application\DTOs\UpdateClientDTO;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;

readonly class UpdateClientUseCase
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    /**
     * @throws ClientDomainException
     */
    public function execute(UpdateClientDTO $dto): Client
    {
        $client = $this->repository->findById($dto->id);
        $updatedClient = $client->update($dto->name);

        return $this->repository->update($updatedClient);
    }
}
