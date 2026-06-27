<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GenericNotification;
use Spatie\Permission\Models\Role;

class NotificationService{

    public function sendNotification(array $data): void{
        $users= User::role(['admin','super admin'])->get();

        foreach ($users as $user){
            $user->notify(new GenericNotification($data));
        }
    }
}
