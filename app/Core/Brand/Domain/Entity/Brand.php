<?php

namespace App\Core\Brand\Domain\Entity;

use App\Core\Brand\Domain\Errors\BrandError;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;

readonly class Brand
{
    /**
     * @throws BrandDomainException
     */
    private function __construct(
        public ?int $id,
        public string $name,
        public string $image
    ) {
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

    /**
     * @throws BrandDomainException
     */
    public static function new(string $name, string $image): self
    {
        return new self(null, $name, $image);
    }

    /**
     * @throws BrandDomainException
     */
    public static function restore(int $id, string $name, string $image): self
    {
        return new self($id, $name, $image);
    }
}
