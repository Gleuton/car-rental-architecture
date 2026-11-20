<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Identity;

use App\Core\Shared\Domain\ValueObjects\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): Uuid;
}
