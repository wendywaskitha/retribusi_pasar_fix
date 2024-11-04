<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Pasar;
use App\Models\Pedagang;
use App\Models\Tipepedagang;

class PedagangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pedagang::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'nik' => $this->faker->regexify('[A-Za-z0-9]{16}'),
            'alamat' => $this->faker->text(),
            'tipepedagang_id' => Tipepedagang::factory(),
            'kecamatan_id' => Kecamatan::factory(),
            'desa_id' => Desa::factory(),
            'pasar_id' => Pasar::factory(),
        ];
    }
}
