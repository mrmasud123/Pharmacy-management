<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        if ($admin == null) {
            return redirect()->route('login');
        }

        return view('pages.profile', [
            'title' => 'Profile',
            'admin' => $admin
        ]);
    }
    public function onlineUsers()
    {
        $onlineUsers = User::where('last_activity_at', '>=', now()->subMinutes(5))
            ->orderByDesc('last_activity_at')
            ->get();

        return view('admin.online-users', compact('onlineUsers'));
    }
}
