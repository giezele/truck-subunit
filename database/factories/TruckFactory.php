<?php

namespace Database\Factories;

use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;

class TruckFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Truck::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'unit_number' => $this->faker->unique()->bothify('???####'),
            'year' => $this->faker->numberBetween(1990, date('Y')),
            'notes' => $this->faker->sentence,
        ];
    }
}
