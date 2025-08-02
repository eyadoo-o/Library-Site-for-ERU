<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Literature',
                'Science',
                'Technology',
                'History',
                'Philosophy',
                'Mathematics',
                'Computer Science',
                'Engineering',
                'Arts',
                'Business'
            ]),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
