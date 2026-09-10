<?php

namespace Database\Factories;

use App\Models\AdminRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AdminRole>
 */
class AdminRoleFactory extends Factory
{
    protected $model = AdminRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->jobTitle();

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(3),
            'status' => fake()->randomElement(['active', 'inactive']),
            'description' => fake()->sentence(),
        ];
    }
}
