<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public static function sendToRole($notification, $role)
    {
        $users = User::where('role', $role)->get();
        Notification::send($users, $notification);
    }

    public static function sendToUser($notification, $user)
    {
        Notification::send($user, $notification);
    }
}
