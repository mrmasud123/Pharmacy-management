<?php

namespace App\View\Components\profile;

use Closure;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfileCard extends Component
{
    public User $admin;
    public function __construct(User $admin)
    {
        $this->admin = $admin;
    }

    public function render(): View|Closure|string
    {
        return view('components.profile.profile-card');
    }
}
