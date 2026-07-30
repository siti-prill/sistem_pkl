<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KompetensiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_kompetensi' => $this->faker->unique()->bothify('KMP-####'),
            'nama_kompetensi' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->paragraph(),
        ];
    }
}
