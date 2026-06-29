<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('pages.auth.signin', ['title' => 'Sign In']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return ApiResponseHelper::error('Email does not exist', null, 404);
        }
        if (!$user->password) {
            return ApiResponseHelper::error('This account uses Google Sign-In. Please use the "Sign in with Google" button.', null, 401);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return ApiResponseHelper::error('Password is incorrect', null, 401);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return ApiResponseHelper::error('Account is disabled', null, 403);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        Auth::user()->update(['last_activity_at' => now()]);
        $request->session()->regenerate();

        return ApiResponseHelper::success('Login successful', ['user' => $user]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user) {
            $user->update(['last_activity_at' => null]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'last_activity_at' => now(),
            ]);
        } else {
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'password'  => null,
                'last_activity_at' => now()
            ]);

            $data = [
                'title'          => 'New user created',
                'message'        => "User \"{$user->name}\".",
                'name'           => $user->name,
                'redirect_route' => "",
                'created_by'     => 0,
            ];
            app(NotificationService::class)->sendNotification($data);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return redirect()->route('login')->with('error', 'Account is disabled.');
        }

        Auth::login($user, true);

        return redirect()->intended('/');
    }
}
