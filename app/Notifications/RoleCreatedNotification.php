<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoleCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public string $roleName, public string $createdBy, public string $redirectRoute)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'      => 'Role Created',
            'message'    => "Role \"{$this->roleName}\" was created by {$this->createdBy}.",
            'role_name'  => $this->roleName,
            'redirect_route' => $this->redirectRoute ?? "",
            'created_by' => $this->createdBy,
        ];
    }

    // ← Make toArray return the same data, NOT empty array
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
