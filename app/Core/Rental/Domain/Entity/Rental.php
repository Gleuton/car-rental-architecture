<?php

declare(strict_types=1);

namespace App\Core\Rental\Domain\Entity;

use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use DateTimeImmutable;
use Illuminate\Support\Str;

class Rental
{
    private DateTimeImmutable $startAt;

    private DateTimeImmutable $endAt;

    public private(set) float $totalPrice;

    /**
     * @throws RentalDomainException
     */
    private function __construct(
        public readonly string $uuid,
        public readonly string $carUuid,
        public readonly string $clientUuid,
        public readonly int $dayPriceCents,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly int $initialKm,
        public readonly int $finalKm,
    ) {
        $this->startAt = $this->parseDateTime($this->startDate);
        $this->endAt = $this->parseDateTime($this->endDate);

        $this->validatePrice();
        $this->validateMileage();
        $this->validateInterval();
        $this->calculateTotalPrice();
    }

    /**
     * @throws RentalDomainException
     */
    public static function new(
        string $carUuid,
        string $clientUuid,
        int $dayPriceCents,
        string $startDate,
        string $endDate,
        int $initialKm,
        int $finalKm,
    ): self {
        return new self(
            (string) Str::uuid(),
            $carUuid,
            $clientUuid,
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
        string $carUuid,
        string $clientUuid,
        int $dayPriceCents,
        string $startDate,
        string $endDate,
        int $initialKm,
        int $finalKm, ?string $uuid = null,
    ): self {
        return new self(
            $uuid ?? (string) Str::uuid(),
            $carUuid,
            $clientUuid,
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
    private function parseDateTime(string $dateString): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateString);
        $errors = DateTimeImmutable::getLastErrors();

        $hasErrors = $errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0);

        if ($date === false || $hasErrors || $date->format('Y-m-d H:i:s') !== $dateString) {
            throw new RentalDomainException(RentalError::INVALID_DATE_FORMAT);
        }

        return $date;
    }

    /**
     * @throws RentalDomainException
     */
    private function validatePrice(): void
    {
        if ($this->dayPriceCents < 0) {
            throw new RentalDomainException(RentalError::INVALID_DAY_PRICE);
        }
    }

    /**
     * @throws RentalDomainException
     */
    private function validateMileage(): void
    {
        if ($this->initialKm < 0) {
            throw new RentalDomainException(RentalError::INVALID_INITIAL_KM);
        }

        if ($this->finalKm < 0) {
            throw new RentalDomainException(RentalError::INVALID_FINAL_KM);
        }

        if ($this->finalKm < $this->initialKm) {
            throw new RentalDomainException(RentalError::FINAL_KM_LESS_THAN_INITIAL);
        }
    }

    /**
     * @throws RentalDomainException
     */
    private function validateInterval(): void
    {
        if ($this->startAt > $this->endAt) {
            throw new RentalDomainException(RentalError::INVALID_DATE_INTERVAL);
        }
    }

    private function calculateTotalPrice(): void
    {
        $this->totalPrice = ($this->dayPriceCents * $this->endAt->diff($this->startAt)->days) / 100;
    }
}
