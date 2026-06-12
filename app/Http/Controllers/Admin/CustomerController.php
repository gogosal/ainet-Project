<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all');

        $users = User::where('user_type', 'C')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->when($filter === 'active', fn($q) => $q->where('blocked', false))
            ->when($filter === 'blocked', fn($q) => $q->where('blocked', true))
            ->with('customer')
            ->orderBy('name')
            ->paginate(15)
            ->appends($request->query());

        foreach ($users as $user) {
            if ($user->photo_url) {
                $user->photoSrc = str_contains($user->photo_url, '/')
                    ? asset('storage/' . $user->photo_url)
                    : asset('storage/photos/' . $user->photo_url);
            } else {
                $user->photoSrc = null;
            }
        }

        return view('admin.customers.index', compact('users', 'search', 'filter'));
    }

    public function toggleBlock(User $user): RedirectResponse
    {
        $user->update(['blocked' => ! $user->blocked]);
        $user->refresh();

        return back()->with('success', $user->blocked ? 'Cliente bloqueado.' : 'Cliente desbloqueado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->customer?->delete();
        $user->delete();

        return back()->with('success', 'Cliente eliminado.');
    }
}
