<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Option;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;


use App\Models\Brand;
use App\Models\Value;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    public function configure()
    {
        return $this->afterMaking(function (Car $car) {
            //
        })->afterCreating(function (Car $car) {
            $name = [];
            $description = [];
            $values = [];

            $options = Option::with(['values'])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            foreach ($options as $option) {
                $value = $option->values->random();
                if (in_array($option->id, [1, 3])) {
                    $name[] = $value->name;
                }
                $description[] = $option->name . ': ' . $value->name;
                $values[$value->id] = ['sort_order' => $option->sort_order];
            }

            $car->name = $car->name . ': ' . implode(", ", $name);
            $car->update();

            $car->values()->sync($values);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = DB::table('users')->inRandomOrder()->first();
//        $user = User::inRandomOrder()->first();
        $brand = DB::table('brands')->inRandomOrder()->first();
        $location = DB::table('locations')->inRandomOrder()->first();
        $year = DB::table('years')->inRandomOrder()->first();
        $color = DB::table('colors')->inRandomOrder()->first();
        $createdAt = fake()->dateTimeBetween('-1 year', '-1 week');
        return [
            'user_id' => $user->id,
            'brand_id' => $brand->id,
            'location_id' => $location->id,
            'year_id' => $year->id,
            'color_id' => $color->id,
            'name' => fake()->name(),
            'probeg' => rand(5000, 50000),
            'vin_code' => $this->faker->unique()->isbn13(),
            'description' => fake()->paragraph(),
            'price' => rand(5000, 50000),
            'viewed' => rand(0, 300),
            'credit' => rand(1, 0),
            'change' => rand(1, 0),
            'favorited' => rand(0, 60),
            'created_at' => $createdAt,
            'updated_at' => Carbon::parse($createdAt)->addDays(rand(0, 6))->addHours(rand(0, 23)),
        ];
    }
}
