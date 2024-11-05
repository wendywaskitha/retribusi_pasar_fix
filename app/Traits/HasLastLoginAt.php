<?php
// app/Traits/HasLastLoginAt.php

namespace App\Traits;

use Illuminate\Support\Facades\Event;

trait HasLastLoginAt
{
    protected static function bootHasLastLoginAt()
    {
        static::created(function ($user) {
            $user->update(['last_login_at' => now()]);
        });

        Event::listen('auth.login', function ($event) {
            $user = auth()->user();
            if ($user) {
                $user->update(['last_login_at' => now()]);
            }
        });
    }
}
