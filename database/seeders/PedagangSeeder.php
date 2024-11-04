<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Pasar;
use App\Models\Pedagang;
use App\Models\Kecamatan;
use App\Models\Tipepedagang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PedagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = Kecamatan::all();
        $desas = Desa::all();
        $pasars = Pasar::all();
        $tipePedagangs = Tipepedagang::all();

        foreach (range(1, 5362) as $index) {
            Pedagang::create([
                'name' => fake()->name(),
                'nik' => fake()->unique()->numerify('################'),
                'alamat' => fake()->address(),
                'tipepedagang_id' => $tipePedagangs->random()->id,
                'kecamatan_id' => $kecamatans->random()->id,
                'desa_id' => $desas->random()->id,
                'pasar_id' => $pasars->random()->id,
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
