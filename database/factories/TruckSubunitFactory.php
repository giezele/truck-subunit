<?php

namespace Database\Factories;

use App\Models\TruckSubunit;
use App\Models\Truck;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TruckSubunitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TruckSubunit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'main_truck' => Truck::factory(),
            'subunit' => Truck::factory(),
            'start_date' => Carbon::parse($this->faker->date),
            'end_date' => Carbon::parse($this->faker->date)->addDays(5),
        ];
    }
}
