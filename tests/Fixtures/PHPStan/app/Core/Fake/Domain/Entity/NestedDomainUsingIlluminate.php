<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Domain\Entity;

use Illuminate\Support\Str;

final class NestedDomainUsingIlluminate
{
    public function identifier(): string
    {
        return (string) Str::uuid();
    }
}
