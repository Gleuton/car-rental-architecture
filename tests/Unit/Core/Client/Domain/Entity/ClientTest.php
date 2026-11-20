<?php

declare(strict_types=1);

use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Core\Shared\Domain\ValueObjects\Uuid;

const TEST_CLIENT_UUID = '11111111-1111-4111-8111-111111111111';

it('can create a Client instance with the given uuid', function () {
    $client = Client::new(Uuid::fromString(TEST_CLIENT_UUID), 'John Doe');

    expect($client->name)->toBe('John Doe')
        ->and($client->uuid)->toBe(TEST_CLIENT_UUID);
});

it('can restore a Client instance with uuid', function () {
    $client = Client::restore('John Doe', TEST_CLIENT_UUID);

    expect($client->name)->toBe('John Doe')
        ->and($client->uuid)->toBe(TEST_CLIENT_UUID);
});

it('throws exception when creating a Client instance with empty name', function () {
    Client::new(Uuid::fromString(TEST_CLIENT_UUID), '');
})->throws(ClientDomainException::class, 'Client name cannot be empty');

it('throws exception when name has only whitespace', function () {
    Client::new(Uuid::fromString(TEST_CLIENT_UUID), '   ');
})->throws(ClientDomainException::class, 'Client name cannot be empty');

it('throws exception when creating a Client with name exceeding 255 characters', function () {
    Client::new(Uuid::fromString(TEST_CLIENT_UUID), str_repeat('a', 256));
})->throws(ClientDomainException::class, 'Client name must not exceed 255 characters');

it('can create a Client with name at max length', function () {
    $longName = str_repeat('a', 255);
    $client = Client::new(Uuid::fromString(TEST_CLIENT_UUID), $longName);

    expect($client->name)->toBe($longName)
        ->and($client->uuid)->toBe(TEST_CLIENT_UUID);
});

it('can update client name keeping same uuid', function () {
    $client = Client::restore('John Doe', TEST_CLIENT_UUID);

    $updated = $client->update('John Updated');

    expect($updated->name)->toBe('John Updated')
        ->and($updated->uuid)->toBe(TEST_CLIENT_UUID);
});

it('keeps current name when update receives null', function () {
    $client = Client::restore('John Doe', TEST_CLIENT_UUID);

    $updated = $client->update(null);

    expect($updated->name)->toBe('John Doe')
        ->and($updated->uuid)->toBe(TEST_CLIENT_UUID);
});
