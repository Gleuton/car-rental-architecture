<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\ValueObjects\Brand;

use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;

readonly class BrandLogo
{
    /**
     * @throws BrandDomainException
     */
    public function __construct(public string $path)
    {
        $this->validate();
    }

    /**
     * @throws BrandDomainException
     */
    private function validate(): void
    {
        if (trim($this->path) === '') {
            throw new BrandDomainException(
                BrandError::LOGO_PATH_EMPTY
            );
        }

        if (mb_strlen($this->path) > 255) {
            throw new BrandDomainException(
                BrandError::LOGO_PATH_TOO_LONG
            );
        }
    }
}
