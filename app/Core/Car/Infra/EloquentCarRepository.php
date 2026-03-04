<?php

declare(strict_types=1);

namespace App\Core\Car\Infra;

use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Models\Car as EloquentCar;

class EloquentCarRepository implements CarRepositoryInterface
{
    public function save(Car $car): Car
    {
        $eloquentCar = EloquentCar::create([
            'car_model_id' => $car->carModelId,
            'license_plate' => $car->licensePlate,
            'color' => $car->color,
            'is_available' => $car->isAvailable,
            'km' => $car->km,
        ]);

        return $this->toDomainCar($eloquentCar);
    }

    public function existsByLicensePlate(string $licensePlate): bool
    {
        return EloquentCar::where('license_plate', $licensePlate)->exists();
    }

    public function findById(int $id): Car
    {
        $eloquentCar = EloquentCar::findOrFail($id);

        return $this->toDomainCar($eloquentCar);
    }

    private function toDomainCar(EloquentCar $eloquentCar): Car
    {
        return Car::restore(
            $eloquentCar->id,
            $eloquentCar->car_model_id,
            $eloquentCar->license_plate,
            $eloquentCar->color,
            (bool) $eloquentCar->is_available,
            $eloquentCar->km,
        );
    }
}
