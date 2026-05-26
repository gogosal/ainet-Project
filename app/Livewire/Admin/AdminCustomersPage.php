<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AdminCustomersPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all'; // all | active | blocked

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilter(): void { $this->resetPage(); }

    public function toggleBlock(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['blocked' => !$user->blocked]);
        // Re-fetch to get the updated value
        $user->refresh();
        session()->flash('success', $user->blocked ? 'Cliente bloqueado.' : 'Cliente desbloqueado.');
    }

    public function deleteCustomer(int $userId): void
    {
        $user = User::with('customer')->findOrFail($userId);
        $user->customer?->delete();
        $user->delete();
        session()->flash('success', 'Cliente eliminado.');
    }

    public function render()
    {
        $users = User::where('user_type', 'C')
            ->when($this->search, fn($q) => $q->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filter === 'active', fn($q) => $q->where('blocked', false))
            ->when($this->filter === 'blocked', fn($q) => $q->where('blocked', true))
            ->with('customer')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.admin-customers-page', compact('users'))
            ->layout('layouts.admin', ['title' => 'Clientes']);
    }
}
