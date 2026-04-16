<?php

declare(strict_types=1);

use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use Illuminate\Support\Str;

it('can create a Client instance', function () {
    $client = Client::new('John Doe');

    expect($client->name)->toBe('John Doe')
        ->and($client->id)->toBeNull()
        ->and(Str::isUuid($client->uuid))->toBeTrue();
});

it('can create a Client instance with ID', function () {
    $client = Client::restore(1, 'John Doe');

    expect($client->id)->toBe(1)
        ->and($client->name)->toBe('John Doe')
        ->and(Str::isUuid($client->uuid))->toBeTrue();
});

it('throws exception when creating a Client instance with empty name', function () {
    Client::new('');
})->throws(ClientDomainException::class, 'Client name cannot be empty');

it('throws exception when name has only whitespace', function () {
    Client::new('   ');
})->throws(ClientDomainException::class, 'Client name cannot be empty');

it('throws exception when creating a Client with name exceeding 255 characters', function () {
    Client::new(str_repeat('a', 256));
})->throws(ClientDomainException::class, 'Client name must not exceed 255 characters');

it('can create a Client with name at max length', function () {
    $longName = str_repeat('a', 255);
    $client = Client::new($longName);

    expect($client->name)->toBe($longName)
        ->and($client->id)->toBeNull()
        ->and(Str::isUuid($client->uuid))->toBeTrue();
});

it('can update client name keeping same id', function () {
    $client = Client::restore(1, 'John Doe');

    $updated = $client->update('John Updated');

    expect($updated->id)->toBe(1)
        ->and($updated->name)->toBe('John Updated')
        ->and($updated->uuid)->toBe($client->uuid);
});

it('keeps current name when update receives null', function () {
    $client = Client::restore(1, 'John Doe');

    $updated = $client->update(null);

    expect($updated->id)->toBe(1)
        ->and($updated->name)->toBe('John Doe')
        ->and($updated->uuid)->toBe($client->uuid);
});
