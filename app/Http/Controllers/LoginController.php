<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticated(Request $request, $user)
{
    $user->update([
        'last_login_at' => now(),
    ]);
}
}
