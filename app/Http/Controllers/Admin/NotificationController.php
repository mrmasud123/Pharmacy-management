<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get all notifications
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'data'    => $n->data,
                'read'    => !is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ]);

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    // Mark a single notification as read
    public function markAsRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['message' => 'Marked as read']);
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
