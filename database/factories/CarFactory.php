<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Car\Domain\Entity\Car as DomainCar;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        /** @var CarModel $carModel */
        $carModel = CarModel::factory()->create();
        $licensePlate = $this->faker->regexify('[A-Z]{3}-[0-9]{4}');
        $color = $this->faker->regexify('[A-Za-z]{3,10}');
        $isAvailable = $this->faker->boolean();
        $km = $this->faker->numberBetween(1, 100);

        return [
            'uuid' => DomainCar::new($carModel->uuid, $licensePlate, $color, $isAvailable, $km)->uuid,
            'car_model_uuid' => $carModel->uuid,
            'license_plate' => $licensePlate,
            'color' => $color,
            'is_available' => $isAvailable,
            'km' => $km,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
