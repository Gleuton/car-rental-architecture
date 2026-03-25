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
    public function updateLogo(?string $image): self
    {
        $newImage = $image ? new BrandLogo($image) : $this->image;

        return new self($this->id, $this->name, $newImage);
    }

    /**
     * @throws BrandDomainException
     */
    public function updateName(?string $name): self
    {
        $newName = $name ? new BrandName($name) : $this->name;

        return new self($this->id, $newName, $this->image);
    }
}
