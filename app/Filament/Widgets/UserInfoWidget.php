<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-info-widget';

    // Add this property to make widget span full width
    protected int | string | array $columnSpan = 'full';

    public function getUserInfo()
    {
        $user = Auth::user()->load('pasars');

        // Update last login if it's null
        if (!$user->last_login_at) {
            $user->update(['last_login_at' => now()]);
        }

        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(), // Get the first role
            'pasars' => $user->pasars->pluck('name')->toArray(), // Get assigned pasar names
            'last_login' => $user->last_login_at
                ? $user->last_login_at->diffForHumans() // This will show "2 hours ago" instead of exact time
                : 'First login',
        ];
    }

    public function mount()
    {
        // Update last login when widget is mounted
        $user = Auth::user();
        if ($user) {
            $user->update(['last_login_at' => now()]);
        }
    }
}
