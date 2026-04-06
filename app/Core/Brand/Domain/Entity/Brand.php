<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Entity;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\ValueObjects\BrandLogo;
use App\Core\Brand\Domain\ValueObjects\BrandName;

class Brand
{
    private function __construct(
        private readonly ?int $id,
        private BrandName $name,
        private BrandLogo $image
    ) {}

    public function id(): ?int
    {
        return $this->id;
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
    public static function new(string $name, string $image): self
    {
        return new self(
            null,
            new BrandName($name),
            new BrandLogo($image)
        );
    }

    /**
     * @throws BrandDomainException
     */
    public static function restore(int $id, string $name, string $image): self
    {
        return new self(
            $id,
            new BrandName($name),
            new BrandLogo($image)
        );
    }
}
