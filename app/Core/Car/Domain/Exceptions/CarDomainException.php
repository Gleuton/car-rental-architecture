<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Exceptions;

use App\Core\Car\Domain\Errors\CarError;
use App\Core\Shared\Domain\DomainException;

class CarDomainException extends DomainException
{
    public function __construct(CarError $error)
    {
        parent::__construct(
            domain: 'car',
            errorCode: $error->name,
            appCode: $error->value,
            message: $error->message()
        );
    }
}
