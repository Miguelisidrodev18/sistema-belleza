<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'alumno',
            'dni' => fake()->unique()->numerify('########'),
            'phone' => fake()->numerify('9## ### ###'),
            'is_active' => true,
            'must_change_password' => false,
            'gender' => fake()->randomElement(['M', 'F']),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'administrador']);
    }

    public function docente(): static
    {
        return $this->state(fn () => ['role' => 'docente']);
    }

    public function alumno(): static
    {
        return $this->state(fn () => ['role' => 'alumno']);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
