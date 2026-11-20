<?php

declare(strict_types=1);

namespace Tests\Fixtures\PHPStan\app\Core\Fake\Domain;

use App\Http\Controllers\Controller;

final class DomainUsingAppHttp
{
    public function controller(): ?Controller
    {
        return null;
    }
}
