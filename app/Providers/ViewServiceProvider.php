<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        View::composer('layouts.app-header', function($view){
            $notifications=[];
            $unreadCount=0;

            if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))){
                $notifications= Auth::user()->notifications()->latest()->get()->toArray();
                $unreadCount= Auth::user()->unreadNotifications()->count();
            }
            $view->with('notifications', $notifications);
            $view->with('unreadCount', $unreadCount);
            $view->with('user', Auth::user());
        });
    }
}
