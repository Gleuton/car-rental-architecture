<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Car;
use App\Models\Client;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', '+30 days');
        $endDate = (clone $startDate)->modify('+1 day');
        $initialKm = $this->faker->numberBetween(0, 200000);

        return [
            'car_id' => Car::factory(),
            'client_id' => Client::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'day_price_cents' => $this->faker->numberBetween(1000, 10000),
            'initial_km' => $initialKm,
            'final_km' => $initialKm + $this->faker->numberBetween(0, 1000),
        ];
    }
}
