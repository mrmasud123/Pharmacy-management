<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
