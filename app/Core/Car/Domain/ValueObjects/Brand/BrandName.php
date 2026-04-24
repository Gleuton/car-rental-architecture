<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\ValueObjects\Brand;

use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;

readonly class BrandName
{
    /**
     * @throws BrandDomainException
     */
    public function __construct(public string $value)
    {
        $this->validate($value);
    }

    /**
     * @throws BrandDomainException
     */
    private function validate(string $name): void
    {
        if (trim($name) === '') {
            throw new BrandDomainException(BrandError::INVALID_NAME);
        }

        if (mb_strlen($name) < 3) {
            throw new BrandDomainException(BrandError::NAME_TOO_SHORT);
        }

        if (mb_strlen($name) > 120) {
            throw new BrandDomainException(BrandError::NAME_TOO_LONG);
        }
    }
}
