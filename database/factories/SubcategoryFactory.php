<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'category_id' => Category::factory()->create()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($subcategory) {
            Product::factory(5)->create(['subcategory_id' => $subcategory->id]);
        });
    }
}
