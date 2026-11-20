<?php

declare(strict_types=1);

use App\Core\Shared\Domain\ValueObjects\Uuid;
use App\Core\Shared\Infra\Identity\LaravelUuidGenerator;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid as RamseyUuid;

afterEach(function () {
    Str::createUuidsNormally();
});

it('generates a valid Uuid', function () {
    $uuid = (new LaravelUuidGenerator())->generate();

    expect($uuid)->toBeInstanceOf(Uuid::class)
        ->and(Str::isUuid($uuid->value))->toBeTrue();
});

it('generates a different Uuid on each call', function () {
    $generator = new LaravelUuidGenerator();

    expect($generator->generate()->equals($generator->generate()))->toBeFalse();
});

it('delegates generation to the Laravel Str uuid factory', function () {
    $fixedUuid = '11111111-1111-4111-8111-111111111111';
    Str::createUuidsUsing(static fn () => RamseyUuid::fromString($fixedUuid));

    $uuid = (new LaravelUuidGenerator())->generate();

    expect($uuid->value)->toBe($fixedUuid);
});
