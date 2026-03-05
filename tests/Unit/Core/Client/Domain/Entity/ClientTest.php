<?php

declare(strict_types=1);

use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;

it('can create a Client instance', function () {
    $client = Client::new('John Doe');

    expect($client->name)->toBe('John Doe')
        ->and($client->id)->toBeNull();
});

it('can create a Client instance with ID', function () {
    $client = Client::restore(1, 'John Doe');

    expect($client->id)->toBe(1)
        ->and($client->name)->toBe('John Doe');
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
        ->and($client->id)->toBeNull();
});
