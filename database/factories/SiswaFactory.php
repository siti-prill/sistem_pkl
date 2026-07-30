<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nis' => $this->faker->unique()->numerify('NIS#####'),
            'nama_siswa' => $this->faker->name(),
            'kelas' => $this->faker->randomElement(['X', 'XI', 'XII']),
            'jurusan' => $this->faker->randomElement(['RPL', 'TKJ', 'MM', 'AKL', 'OTKP']),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}
