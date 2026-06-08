<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){
        return view('pages.auth.signin', ['title' => 'Sign In']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return ApiResponseHelper::error('Email does not exist', null, 404);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return ApiResponseHelper::error('Password is incorrect', null, 401);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return ApiResponseHelper::error('Account is disabled', null, 403);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return ApiResponseHelper::success('Login successful', [
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
