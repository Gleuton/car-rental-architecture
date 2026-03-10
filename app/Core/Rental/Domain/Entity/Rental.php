<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Entity;

use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use DateTime;

readonly class Rental
{
    /**
     * @throws RentalDomainException
     */
    private function __construct(
        public ?int $id,
        public int $carId,
        public int $clientId,
        public int $dayPriceCents,
        public string $startDate,
        public string $endDate,
        public int $initialKm,
        public int $finalKm,
    ) {
        $this->validateDateTime($startDate);
        $this->validateDateTime($endDate);

        $this->validateInterval();
    }

    /**
     * @throws RentalDomainException
     */
    public static function new(
        int $carId,
        int $clientId,
        int $dayPriceCents,
        string $startDate,
        string $endDate,
        int $initialKm,
        int $finalKm,
    ): self {
        return new self(
            null,
            $carId,
            $clientId,
            $dayPriceCents,
            $startDate,
            $endDate,
            $initialKm,
            $finalKm,
        );
    }

    /**
     * @throws RentalDomainException
     */
    public static function restore(
        int $id,
        int $carId,
        int $clientId,
        int $dayPriceCents,
        string $startDate,
        string $endDate,
        int $initialKm,
        int $finalKm
    ): self {
        return new self(
            $id,
            $carId,
            $clientId,
            $dayPriceCents,
            $startDate,
            $endDate,
            $initialKm,
            $finalKm
        );
    }

    /**
     * @throws RentalDomainException
     */
    private function validateDateTime(string $dateString): void
    {
        if (! DateTime::createFromFormat('Y-m-d H:i:s', $dateString)) {
            throw new RentalDomainException(RentalError::INVALID_DATE_FORMAT);
        }
    }

    /**
     * @throws RentalDomainException
     */
    private function validateInterval(): void
    {
        $startDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate);
        $endDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->endDate);

        if ($startDate > $endDate) {
            throw new RentalDomainException(RentalError::INVALID_DATE_INTERVAL);
        }
    }
}
