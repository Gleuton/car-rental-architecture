<?php

declare(strict_types=1);

namespace App\Core\Brand\Domain\Entity;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\ValueObjects\BrandLogo;
use App\Core\Brand\Domain\ValueObjects\BrandName;

readonly class Brand
{
    private function __construct(
        public ?int $id,
        public BrandName $name,
        public BrandLogo $image
    ) {}

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

    /**
     * @throws BrandDomainException
     */
    public function update(?string $name = null, ?string $image = null): self
    {
        $newName = $name ?? $this->name->value;
        $newImage = $image ?? $this->image->path;

        return new self($this->id, new BrandName($newName), new BrandLogo($newImage));
    }
}
