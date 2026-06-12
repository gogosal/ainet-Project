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
        $staff = User::whereIn('user_type', ['F', 'A'])->orderBy('name')->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function store(StaffRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'user_type' => $data['user_type'],
            'gender'    => $data['gender'] ?? null,
        ]);

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

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
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
