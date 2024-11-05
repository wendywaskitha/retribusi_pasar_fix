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
        $user = Auth::user();
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(), // Get the first role
            'pasars' => $user->pasars->pluck('name')->toArray(), // Get assigned pasar names
            'last_login' => $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'N/A',
        ];
    }
}
