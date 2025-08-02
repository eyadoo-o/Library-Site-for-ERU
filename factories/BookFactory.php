<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'authors' => $this->faker->randomElements([
                $this->faker->name(),
                $this->faker->name(),
                $this->faker->name()
            ], $this->faker->numberBetween(1, 3)),
            'isbn' => $this->faker->unique()->isbn13(),
            'edition' => $this->faker->optional(0.7)->randomElement(['First Edition', 'Second Edition', 'Third Edition', 'Revised Edition']),
            'quantity' => $this->faker->numberBetween(0, 10),
            'format' => $this->faker->randomElement(['physical', 'digital']),
            'image' => $this->faker->optional(0.8)->randomElement([
                'books/book1.jpg',
                'books/book2.jpg',
                'books/book3.jpg',
                'books/book4.jpg',
                'books/book5.jpg',
                'books/book6.jpg',
            ]),
            'category_id' => Category::factory(),
            'added_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
