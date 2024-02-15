<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\barang>
 */
class barangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'kategori_id' => rand(1,3),
            'nama' => $this->faker->words(rand(1,6), true),
            'harga' => $this->faker->numberBetween(1000, 100000000),
            'jumlah' => $this->faker->numberBetween(1,500),
            'foto' => $this->faker->words(1, true),
            'deskripsi' =>$this->faker->paragraphs(rand(1,7), true),
        ];
    }
}
