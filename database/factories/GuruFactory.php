<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'guru'])->id,
            'nip' => $this->faker->unique()->numerify('NIP#####'),
            'nama_guru' => $this->faker->name(),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}
