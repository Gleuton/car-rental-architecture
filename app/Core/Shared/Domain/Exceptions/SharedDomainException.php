<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Exceptions;

use App\Core\Shared\Domain\DomainException;
use App\Core\Shared\Domain\Errors\SharedError;

class SharedDomainException extends DomainException
{
    public function __construct(SharedError $error)
    {
        parent::__construct(
            domain: 'shared',
            errorCode: $error->name,
            appCode: $error->value,
            message: $error->message()
        );
    }
}
