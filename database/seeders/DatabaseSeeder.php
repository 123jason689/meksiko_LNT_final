<?php

namespace Database\Seeders;

use App\Models\barang;
use App\Models\kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $banyakBarang = 25; // range dari sebanyak (banyakBarang) hinga sebanyak (banyakBarang * multiplier)
        $multiplier = 5; // pengkali random banyaknya barang yang akan terbentuk
        $banyakKategori =15;

        // \App\Models\User::factory(10)->create();
        kategori::factory($banyakKategori)->create();

        $allcategory = kategori::all();

        foreach (range(1, $banyakBarang) as $i) {
            barang::factory(rand(1,$multiplier))->create([
                'kategori_id' => $allcategory->random()->id,
            ]);
        }

    }
}
