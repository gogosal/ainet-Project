<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminStaffPage extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $modalName = '';
    public string $modalEmail = '';
    public string $modalUserType = 'F';
    public string $modalGender = '';
    public string $modalPassword = '';
    public string $modalPasswordConfirmation = '';

    public ?int $deleteId = null;

    public function openCreate(): void
    {
        $this->editingId = null;
        $this->modalName = $this->modalEmail = $this->modalPassword = $this->modalPasswordConfirmation = $this->modalGender = '';
        $this->modalUserType = 'F';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->modalName = $user->name;
        $this->modalEmail = $user->email;
        $this->modalUserType = $user->user_type;
        $this->modalGender = $user->gender ?? '';
        $this->modalPassword = $this->modalPasswordConfirmation = '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'modalName' => 'required|string|max:255',
            'modalEmail' => 'required|email|unique:users,email' . ($this->editingId ? ",{$this->editingId}" : ''),
            'modalUserType' => 'required|in:F,A',
            'modalGender' => 'nullable|in:M,F',
        ];
        if (!$this->editingId) {
            $rules['modalPassword'] = 'required|min:8|confirmed';
        } else {
            $rules['modalPassword'] = 'nullable|min:8|confirmed';
        }
        $this->validate($rules);

        if ($this->editingId) {
            $data = [
                'name' => $this->modalName,
                'email' => $this->modalEmail,
                'user_type' => $this->modalUserType,
                'gender' => $this->modalGender ?: null,
            ];
            if ($this->modalPassword) {
                $data['password'] = Hash::make($this->modalPassword);
            }
            User::findOrFail($this->editingId)->update($data);
        } else {
            User::create([
                'name' => $this->modalName,
                'email' => $this->modalEmail,
                'password' => Hash::make($this->modalPassword),
                'user_type' => $this->modalUserType,
                'gender' => $this->modalGender ?: null,
            ]);
        }
        $this->showModal = false;
        session()->flash('success', $this->editingId ? 'Colaborador atualizado.' : 'Colaborador criado.');
    }

    public function confirmDelete(int $id): void { $this->deleteId = $id; }
    public function cancelDelete(): void { $this->deleteId = null; }

    public function deleteStaff(): void
    {
        User::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        session()->flash('success', 'Colaborador eliminado.');
    }

    public function render()
    {
        $staff = User::whereIn('user_type', ['F', 'A'])->orderBy('name')->get();
        return view('livewire.admin.admin-staff-page', compact('staff'))
            ->layout('layouts.admin', ['title' => 'Colaboradores']);
    }
}
