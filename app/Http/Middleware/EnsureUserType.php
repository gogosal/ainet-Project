<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->isBlocked()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['email' => 'A tua conta foi bloqueada.']);
        }

        if (!in_array($request->user()->user_type, $types)) {
            $fallback = match ($request->user()->user_type) {
                'A' => route('landing'),
                'F' => route('landing'),
                default => route('catalog'),
            };
            return redirect($fallback);
        }

        return $next($request);
    }
}
