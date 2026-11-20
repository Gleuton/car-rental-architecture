<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Infra;

use Illuminate\Support\Str;

final class InfraUsingIlluminate
{
    public function identifier(): string
    {
        return (string) Str::uuid();
    }
}
