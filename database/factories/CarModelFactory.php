<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition(): array
    {
        $brand = Brand::factory()->create();
        $name = $this->faker->name();
        $image = $this->faker->word();
        $doors = $this->faker->numberBetween(2, 4);
        $seats = $this->faker->numberBetween(2, 7);
        $airbags = $this->faker->boolean();
        $abs = $this->faker->boolean();

        return [
            'uuid' => DomainCarModel::new($brand->id, $name, $image, $doors, $seats, $airbags, $abs)->uuid,
            'brand_id' => $brand->id,
            'brand_uuid' => $brand->uuid,
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
