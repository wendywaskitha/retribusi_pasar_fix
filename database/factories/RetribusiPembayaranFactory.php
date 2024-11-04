<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Pasar;
use App\Models\Pedagang;
use App\Models\Retribusi;
use App\Models\RetribusiPembayaran;

class RetribusiPembayaranFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RetribusiPembayaran::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'pedagang_id' => Pedagang::factory(),
            'retribusi_id' => Retribusi::factory(),
            'pasar_id' => Pasar::factory(),
            'tanggal_bayar' => $this->faker->date(),
            'status' => $this->faker->randomElement(["pending","lunas"]),
        ];
    }
}
