<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'division'    => fake()->randomElement(['Customer Service', 'Finance', 'Technical Support', 'Content Moderator']),
        'accessLevel' => fake()->randomElement(['Superadmin', 'Manager', 'Staff']),
        'createAt'    => now(),
        'updateAt'    => now(),
    ];
}
}