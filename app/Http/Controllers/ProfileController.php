<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        $customer = $user->customer;

        return view('profile.edit', compact('user', 'customer'));
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        $userData = [
            'name'   => $data['name'],
            'email'  => $data['email'],
            'gender' => $data['gender'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo_url) {
                Storage::delete($user->photo_url);
            }
            $userData['photo_url'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($userData);

        $user->customer()->updateOrCreate(['id' => $user->id], [
            'nif'                  => $data['nif'] ?? null,
            'address'              => $data['address'] ?? null,
            'default_payment_type' => $data['default_payment_type'] ?? null,
            'default_payment_ref'  => $data['default_payment_ref'] ?? null,
        ]);

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A password atual está incorreta.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('password_success', 'Password alterada com sucesso.');
    }
}
