<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'type' => 'admin',
            'password' => bcrypt('password'),
            'confirmed' => true,
        ])->assignRole('admin');

        // Create some categories
        $categories = Category::factory()->count(5)->create();

        // Create some books
        Book::factory()
            ->count(20)
            ->sequence(fn ($sequence) => [
                'category_id' => $categories->random()->id
            ])
            ->create();

        // Create some events
        Event::factory()
            ->count(10)
            ->sequence(fn ($sequence) => [
                'event_date' => now()->addDays(rand(1, 30)),
                'status' => 'published'
            ])
            ->create();
    }
}
