<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Exceptions;

use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Shared\Domain\DomainException;

class BrandDomainException extends DomainException
{
    public function __construct(BrandError $error)
    {
        parent::__construct(
            domain: 'brand',
            errorCode: $error->name,
            appCode: $error->value,
            message: $error->message()
        );
    }
}
