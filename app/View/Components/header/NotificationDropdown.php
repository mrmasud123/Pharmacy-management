<?php

namespace App\View\Components\header;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public array $notifications;
    public int $unreadCount;

    public function __construct()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $this->notifications = Auth::user()
                ->notifications()
                ->latest()
                ->take(20)
                ->get()
                ->map(fn($n) => [
                    'id'      => $n->id,
                    'data'    => $n->data,
                    'read'    => !is_null($n->read_at),
                    'time'    => $n->created_at->diffForHumans(),
                ])
                ->toArray();

            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        } else {
            $this->notifications = [];
            $this->unreadCount   = 0;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.header.notification-dropdown');
    }
}
