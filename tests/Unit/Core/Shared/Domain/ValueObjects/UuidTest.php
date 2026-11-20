<?php

declare(strict_types=1);

use App\Core\Shared\Domain\Errors\SharedError;
use App\Core\Shared\Domain\Exceptions\SharedDomainException;
use App\Core\Shared\Domain\ValueObjects\Uuid;

it('creates a Uuid from a valid string', function () {
    $uuid = Uuid::fromString('11111111-1111-4111-8111-111111111111');

    expect($uuid->value)->toBe('11111111-1111-4111-8111-111111111111');
});

it('normalizes the value to lowercase', function () {
    $uuid = Uuid::fromString('ABCDEFAB-CDEF-4ABC-8DEF-ABCDEFABCDEF');

    expect($uuid->value)->toBe('abcdefab-cdef-4abc-8def-abcdefabcdef');
});

it('throws a shared domain exception for invalid uuids', function (string $invalidUuid) {
    Uuid::fromString($invalidUuid);
})->with([
    'empty string' => '',
    'random text' => 'not-a-uuid',
    'missing hyphens' => '11111111111141118111111111111111',
    'too short' => '11111111-1111-4111-8111-11111111111',
    'too long' => '11111111-1111-4111-8111-1111111111111',
    'non hex characters' => 'gggggggg-1111-4111-8111-111111111111',
    'surrounding spaces' => ' 11111111-1111-4111-8111-111111111111 ',
    'trailing newline' => "11111111-1111-4111-8111-111111111111\n",
])->throws(SharedDomainException::class, SharedError::INVALID_UUID->message());

it('exposes error metadata on invalid uuid', function () {
    try {
        Uuid::fromString('invalid');
    } catch (SharedDomainException $exception) {
        expect($exception->domain)->toBe('shared')
            ->and($exception->errorCode)->toBe('INVALID_UUID')
            ->and($exception->appCode)->toBe(SharedError::INVALID_UUID->value);

        return;
    }

    $this->fail('Expected SharedDomainException was not thrown.');
});

it('considers uuids with the same value equal regardless of case', function () {
    $lowercase = Uuid::fromString('abcdefab-cdef-4abc-8def-abcdefabcdef');
    $uppercase = Uuid::fromString('ABCDEFAB-CDEF-4ABC-8DEF-ABCDEFABCDEF');

    expect($lowercase->equals($uppercase))->toBeTrue();
});

it('considers uuids with different values not equal', function () {
    $first = Uuid::fromString('11111111-1111-4111-8111-111111111111');
    $second = Uuid::fromString('22222222-2222-4222-8222-222222222222');

    expect($first->equals($second))->toBeFalse();
});

it('can be cast to string', function () {
    $uuid = Uuid::fromString('11111111-1111-4111-8111-111111111111');

    expect((string) $uuid)->toBe('11111111-1111-4111-8111-111111111111');
});
