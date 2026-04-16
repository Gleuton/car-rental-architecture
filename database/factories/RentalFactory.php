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
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', '+30 days');
        $endDate = (clone $startDate)->modify('+1 day');
        $initialKm = $this->faker->numberBetween(0, 200000);
        $car = Car::factory()->create();
        $client = Client::factory()->create();
        $dayPriceCents = $this->faker->numberBetween(1000, 10000);
        $finalKm = $initialKm + $this->faker->numberBetween(0, 1000);

        return [
            'uuid' => $this->faker->uuid(),
            'car_id' => $car->id,
            'client_id' => $client->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'day_price_cents' => $dayPriceCents,
            'initial_km' => $initialKm,
            'final_km' => $finalKm,
        ];
    }
}
