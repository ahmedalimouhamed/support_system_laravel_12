<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->delete();
        DB::statement('ALTER TABLE notifications AUTO_INCREMENT = 1');
        User::all()->each(function ($user) {
            $notificationsCount = rand(0, 10);

            $unreadPercentage = 0.3;

            for($i = 0; $i < $notificationsCount; $i++) {
                $isUnread = $this->faker->boolean($unreadPercentage * 100);

                $user->notifications()->create([
                    'id' => $this->faker->uuid(),
                    'type' => $this->getRandomNotificationType(),
                    'data' => $this->getRandomNotificationData($user),
                    'read_at' => $isUnread ? null : $this->faker->dateTimeThisMonth,
                    'created_at' => $this->faker->dateTimeThisMonth,
                    'updated_at' => $this->faker->dateTimeThisMonth,
                ]);
            }

        });
    }

    protected function getRandomNotificationType()
    {
        $types = [
            'App\Notifications\TicketCreatedNotification',
            'App\Notifications\TicketAssignedNotification',
            'App\Notifications\TicketStatusChangedNotification',
            'App\Notifications\TicketRestoredNotification',
            'App\Notifications\ResponseAddedNotification',
        ];

        return $this->faker->randomElement($types);
    }

    protected function getRandomNotificationData(User $user): array
    {
        $ticketId = $this->faker->randomNumber();
        $otherUser = User::where('id', '!=', $user->id)->inRandomOrder()->first();

        $data = [
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'link' => '/tickets/' . $ticketId,
            'ticket_id' => $ticketId,
        ];

        if($user->isAdmin()) {
            $data['role'] = 'admin';
        }

        if($user->isAgent()) {
            $data['role'] = 'agent';
        }

        $type = $this->getRandomNotificationType();

        switch($type) {
            case 'App\Notifications\TicketCreatedNotification':
                $data['title'] = "Nouveau ticket créé";
                $data['message'] = "Un nouveau ticket a été créé par " . $otherUser->name;
                break;
            case 'App\Notifications\TicketAssignedNotification':
                $data['title'] = "Ticket assigné";
                $data['message'] = "Un nouveau ticket (#{$ticketId}) vous a été assigné par " . $otherUser->name;
                break;
            case 'App\Notifications\TicketStatusChangedNotification':
                $statuses = ['open', 'in_progress', 'resolved', 'closed'];
                $data['title'] = 'Statut Modifié';
                $data['message'] = "Le statut du ticket (#{$ticketId}) a été modifié par " . $otherUser->name;
                $data['old_status'] = $this->faker->randomElement($statuses);
                $data['new_status'] = $this->faker->randomElement($statuses);
                break;
            case 'App\Notifications\TicketRestoredNotification':
                $data['title'] = "Ticket restauré";
                $data['message'] = "Le ticket (#{$ticketId}) a été restauré par " . $otherUser->name;
                break;
            case 'App\Notifications\ResponseAddedNotification':
                $data['title'] = "Nouvelle réponse";
                $data['message'] = "Une réponse a été ajoutée au ticket (#{$ticketId}) par " . $otherUser->name;
                $data['responder_id'] = $otherUser->id;
                break;
        }

        return $data;
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
}
