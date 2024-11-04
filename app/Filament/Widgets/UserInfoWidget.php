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
            'role' => $user->getRoleNames()->first(), // Get the first role
            'pasars' => $user->pasars->pluck('name')->toArray(), // Get assigned pasar names
        ];
    }
}