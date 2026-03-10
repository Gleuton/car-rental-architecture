<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Exceptions;

use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Shared\Domain\DomainException;

class RentalDomainException extends DomainException
{
    public function __construct(RentalError $error)
    {
        parent::__construct(
            domain: 'rental',
            errorCode: $error->name,
            appCode: $error->value,
            message: $error->message()
        );
    }
}
