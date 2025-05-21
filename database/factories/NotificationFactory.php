<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            'App\Notifications\TicketCreatedNotification',
            'App\Notifications\TicketAssignedNotification',
            'App\Notifications\TicketStatusChangedNotification',
            'App\Notifications\TicketRestoredNotification',
            'App\Notifications\ResponseAddedNotification',
        ];


        return [
            'id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement($types),
            'notifiable_id' => function () {
                return User::factory()->create()->id;
            },
            'notifiable_type' => 'App\Models\User',
            'data' => [
                'title' => $this->faker->sentence,
                'message' => $this->faker->paragraph,
                'link' => '/tickets/' . $this->faker->randomNumber(),
                'role' => $this->faker->randomElement(['user', 'agent', null]),
            ],
            'read_at' => $this->faker->optional(0.3)->dateTimeThisYear,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
