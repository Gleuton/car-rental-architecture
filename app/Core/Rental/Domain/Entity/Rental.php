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
     * @throws Exception
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
     * @throws Exception
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

    private function validateDateTime(string $startDate): void
    {
        if (! DateTime::createFromFormat('Y-m-d H:i:s', $startDate)) {
            throw new Exception('Invalid date format');
        }
    }

    private function validateInterval(): void
    {
        $startDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate);
        $endDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->endDate);

        if ($startDate > $endDate) {
            throw new Exception('Invalid date');
        }
    }
}
