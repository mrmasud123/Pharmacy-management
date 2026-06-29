<?php

namespace App\View\Components\profile;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersonalInfoCard extends Component
{
    /**
     * Create a new component instance.
     */
    public User $admin;
    public function __construct(User $admin)
    {
        $this->admin = $admin->load('roles');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile.personal-info-card', [
            'admin' => $this->admin,
        ]);
    }
}
