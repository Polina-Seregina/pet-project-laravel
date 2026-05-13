<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ProductsStatus;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->realTextBetween(),
            'price' => fake()->numberBetween(0,100000),
            'image' => fake()->unique()->filePath(),
            'user_id' => User::factory(),
            'status'=> ProductsStatus::ForSale->value,
        ];
    }
}
