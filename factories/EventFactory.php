<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'event_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'location' => $this->faker->randomElement([
                'Main Auditorium',
                'Library Conference Room',
                'Digital Media Lab',
                'Study Room A',
                'Seminar Hall B'
            ]),
            'status' => 'published',
            'event_type' => $this->faker->randomElement(['public', 'private', 'workshop', 'seminar', 'reading', 'discussion']),
            'max_attendees' => $this->faker->numberBetween(20, 100),
            'current_attendees' => 0,
            'created_by' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
