<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Domain;

use App\Models\User;

final class DomainUsingAppModels
{
    public function user(): ?User
    {
        return null;
    }
}
