<?php

namespace Database\Factories;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => UuidHelper::generate(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf_cnpj' => $this->generateUniqueCpf(),
            'password' => bcrypt('Password@123'),
            'role_id' => null,
        ];
    }

    protected function generateUniqueCpf()
    {
        return $this->faker->unique()->numerify('###########');
    }

    public function withRole($role)
    {
        return $this->state(fn () => ['role_id' => $role->id]);
    }
}
