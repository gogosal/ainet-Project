<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public int $cartCount;
    public string $ordersRoute = '';
    public bool $ordersActive = false;
    public ?string $profileImageSrc = null;

    public function __construct(int $cartCount = 0)
    {
        $this->cartCount = $cartCount;

        if (Auth::check()) {
            $user = Auth::user();

            $this->ordersRoute = match ($user->user_type) {
                'A' => route('admin.orders'),
                'F' => route('employee.orders'),
                default => route('orders.index'),
            };

            $this->ordersActive = request()->routeIs('orders*')
                || request()->routeIs('admin.orders')
                || request()->routeIs('employee.orders');

            if ($user->photo_url) {
                $this->profileImageSrc = str_contains($user->photo_url, '/')
                    ? asset('storage/' . $user->photo_url)
                    : asset('storage/photos/' . $user->photo_url);
            }
        }
    }

    public function render()
    {
        return view('components.navbar');
    }
}
