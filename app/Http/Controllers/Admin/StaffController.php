<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = User::whereIn('user_type', ['F', 'A'])
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        $roles = [
            'A' => 'Administrador',
            'F' => 'Funcionário',
        ];

        foreach ($staff as $member) {
            if ($member->photo_url) {
                $member->photoSrc = str_contains($member->photo_url, '/')
                    ? asset('storage/' . $member->photo_url)
                    : asset('storage/photos/' . $member->photo_url);
            } else {
                $member->photoSrc = null;
            }
        }

        return view('admin.staff.index', compact('staff', 'roles'));
    }

    public function store(StaffRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'gender'    => $data['gender'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            $userData['photo_url'] = $request->file('photo')->store('photos', 'public');
        }

        User::create($userData);

        return back()->with('success', 'Colaborador criado.');
    }

    public function update(StaffRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'user_type' => $data['user_type'],
            'gender'    => $data['gender'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('photo')) {
            $updateData['photo_url'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($updateData);

        return back()->with('success', 'Colaborador atualizado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', 'Colaborador eliminado.');
    }
}
