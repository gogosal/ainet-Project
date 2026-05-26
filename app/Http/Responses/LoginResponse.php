<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->isBlocked()) {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors(['email' => 'A tua conta foi bloqueada.']);
        }

        $redirect = match($user->user_type) {
            'A' => route('admin.dashboard'),
            'F' => route('employee.orders'),
            default => route('catalog'),
        };

        return redirect()->intended($redirect);
    }
}
