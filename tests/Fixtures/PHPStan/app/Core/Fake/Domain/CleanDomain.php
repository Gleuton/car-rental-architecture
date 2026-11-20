<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Domain;

use DateTimeImmutable;

final class CleanDomain
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
