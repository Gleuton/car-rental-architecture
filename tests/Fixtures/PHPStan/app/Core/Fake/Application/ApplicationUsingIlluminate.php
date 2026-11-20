<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Application;

use Illuminate\Support\Str;

final class ApplicationUsingIlluminate
{
    public function identifier(): string
    {
        return (string) Str::uuid();
    }
}
