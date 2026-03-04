<?php

declare(strict_types=1);

namespace Database\Factories;

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

        return [
            'car_model_id' => $carModel->id,
            'license_plate' => $this->faker->regexify('[A-Z]{3}-[0-9]{4}'),
            'color' => $this->faker->regexify('[A-Za-z]{3,10}'),
            'is_available' => $this->faker->boolean(),
            'km' => $this->faker->numberBetween(1, 100),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
