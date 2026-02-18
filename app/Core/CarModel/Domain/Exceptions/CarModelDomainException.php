<?php

declare(strict_types=1);

namespace App\Core\CarModel\Domain\Exceptions;

use App\Core\CarModel\Domain\Errors\CarModelError;
use App\Core\Shared\Domain\DomainException;

class CarModelDomainException extends DomainException
{
    public function __construct(CarModelError $error)
    {
        parent::__construct(
            domain: 'car_model',
            errorCode: $error->name,
            appCode: $error->value,
            message: $error->message()
        );
    }
}
