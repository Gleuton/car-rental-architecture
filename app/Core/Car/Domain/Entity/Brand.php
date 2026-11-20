<?php

declare(strict_types=1);

namespace App\Core\Car\Domain\Entity;

use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\ValueObjects\Brand\BrandLogo;
use App\Core\Car\Domain\ValueObjects\Brand\BrandName;
use App\Core\Shared\Domain\Exceptions\SharedDomainException;
use App\Core\Shared\Domain\ValueObjects\Uuid;

class Brand
{
    private function __construct(
        private readonly Uuid $uuid,
        private BrandName $name,
        private BrandLogo $image
    ) {}

    public function uuid(): string
    {
        return $this->uuid->value;
    }

    public function name(): string
    {
        return $this->name->value;
    }

    public function imagePath(): string
    {
        return $this->image->path;
    }

    /**
     * @throws BrandDomainException
     */
    public function changeLogo(?string $image): self
    {
        $this->image = $image ? new BrandLogo($image) : $this->image;

        return $this;
    }

    /**
     * @throws BrandDomainException
     */
    public function rename(?string $name): self
    {
        $this->name = $name ? new BrandName($name) : $this->name;

        return $this;
    }

    /**
     * @throws BrandDomainException
     */
    public static function new(Uuid $uuid, string $name, string $image): self
    {
        return new self(
            $uuid,
            new BrandName($name),
            new BrandLogo($image)
        );
    }

    /**
     * @throws BrandDomainException|SharedDomainException
     */
    public static function restore(string $name, string $image, string $uuid): self
    {
        return new self(
            Uuid::fromString($uuid),
            new BrandName($name),
            new BrandLogo($image)
        );
    }
}
