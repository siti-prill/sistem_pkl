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
            'nis' => $this->faker->unique()->numerify('#######'),
            'nama_siswa' => $this->faker->name(),
            'jurusan' => $this->faker->randomElement(['XII TKJ 1', 'XII TKJ 2', 'XII RPL', 'XII DKV 1', 'XII DKV 2', 'XII PSPT']),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}
