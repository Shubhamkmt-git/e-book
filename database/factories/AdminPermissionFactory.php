<?php

namespace Database\Factories;

use App\Models\AdminPermission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AdminPermission>
 */
class AdminPermissionFactory extends Factory
{
    protected $model = AdminPermission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(3),
            'status' => fake()->randomElement(['active', 'inactive']),
            'description' => fake()->sentence(),
        ];
    }
}
