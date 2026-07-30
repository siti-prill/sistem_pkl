<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IndustriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_perusahaan' => $this->faker->unique()->bothify('IND-####'),
            'nama_perusahaan' => $this->faker->company(),
            'alamat' => $this->faker->address(),
            'no_telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'bidang_usaha' => $this->faker->randomElement(['Teknologi', 'Manufaktur', 'Jasa', 'Perdagangan']),
            'penanggung_jawab' => $this->faker->name(),
            'kuota' => $this->faker->numberBetween(5, 30),
            'status' => 'aktif',
        ];
    }
}
