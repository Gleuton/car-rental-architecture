<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Infra\Domain;

use Illuminate\Support\Str;

final class InfraSubfolderNamedDomainUsingIlluminate
{
    public function identifier(): string
    {
        return (string) Str::uuid();
    }
}
