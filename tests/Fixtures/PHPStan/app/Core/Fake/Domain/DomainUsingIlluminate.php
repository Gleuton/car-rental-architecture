<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Domain;

use Illuminate\Support\Str;

final class DomainUsingIlluminate
{
    public function identifier(): string
    {
        return (string) Str::uuid();
    }
}
