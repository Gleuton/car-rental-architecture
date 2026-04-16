<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition(): array
    {
        $brandId = Brand::factory()->create()->id;
        $name = $this->faker->name();
        $image = $this->faker->word();
        $doors = $this->faker->numberBetween(2, 4);
        $seats = $this->faker->numberBetween(2, 7);
        $airbags = $this->faker->boolean();
        $abs = $this->faker->boolean();

        return [
            'uuid' => $this->faker->uuid(),
            'brand_id' => $brandId,
            'name' => $name,
            'image' => $image,
            'doors' => $doors,
            'seats' => $seats,
            'airbags' => $airbags,
            'abs' => $abs,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
