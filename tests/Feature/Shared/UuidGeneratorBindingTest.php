<?php

declare(strict_types=1);

use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Infra\Identity\LaravelUuidGenerator;

it('resolves the uuid generator port to the Laravel implementation', function () {
    expect(app(UuidGeneratorInterface::class))->toBeInstanceOf(LaravelUuidGenerator::class);
});
