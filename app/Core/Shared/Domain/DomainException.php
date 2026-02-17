<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

use Exception;

abstract class DomainException extends Exception
{
    public function __construct(
        public readonly string $domain,
        public readonly string $errorCode,
        public readonly int $appCode,
        string $message
    ) {
        parent::__construct($message, $appCode);
    }
}
