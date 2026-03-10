<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Entity;

use DateTime;
use Exception;

readonly class Rental
{
    /**
     * @throws Exception
     */
    private function __construct(
        public ?int $id,
        public int $carId,
        public int $clientId,
        public int $dayPriceCents,
        public DateTime $startDate,
        public DateTime $endDate,
        public int $initialKm,
        public int $finalKm,
    ) {
        $this->validateStartEnd();
    }

    /**
     * @throws Exception
     */
    public static function new(
        int $carId,
        int $clientId,
        int $dayPriceCents,
        DateTime $startDate,
        DateTime $endDate,
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
     * @throws Exception
     */
    public static function restore(
        int $id,
        int $carId,
        int $clientId,
        int $dayPriceCents,
        DateTime $startDate,
        DateTime $endDate,
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

    private function validateStartEnd(): void
    {
        if ($this->startDate > $this->endDate) {
            throw new Exception('Invalid date');
        }
    }
}
