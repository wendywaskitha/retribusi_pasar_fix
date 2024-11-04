<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Pasar;

class PasarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pasar::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->text(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'kecamatan_id' => Kecamatan::factory(),
            'desa_id' => Desa::factory(),
        ];
    }
}
