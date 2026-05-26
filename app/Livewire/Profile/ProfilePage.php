<?php
namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;

class ProfilePage extends Component
{
    use WithFileUploads;

    // User fields
    public string $name = '';
    public string $email = '';
    public string $gender = '';
    public $photo; // temp upload

    // Customer fields
    public string $nif = '';
    public string $address = '';
    public string $defaultPaymentType = '';
    public string $defaultPaymentRef = '';

    // Password change
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->gender = $user->gender ?? '';
        $customer = $user->customer;
        if ($customer) {
            $this->nif = $customer->nif ?? '';
            $this->address = $customer->address ?? '';
            $this->defaultPaymentType = $customer->default_payment_type ?? '';
            $this->defaultPaymentRef = $customer->default_payment_ref ?? '';
        }
    }

    public function saveProfile(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'gender' => 'nullable|in:M,F',
            'nif' => 'nullable|digits:9',
            'address' => 'nullable|string|max:500',
            'defaultPaymentType' => 'nullable|in:Visa,PayPal,MB WAY',
            'defaultPaymentRef' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $data = ['name' => $this->name, 'email' => $this->email, 'gender' => $this->gender];

        if ($this->photo) {
            if ($user->photo_url) Storage::delete($user->photo_url);
            $data['photo_url'] = $this->photo->store('photos', 'public');
            $this->photo = null;
        }

        $user->update($data);

        $user->customer()->updateOrCreate(['id' => $user->id], [
            'nif' => $this->nif ?: null,
            'address' => $this->address ?: null,
            'default_payment_type' => $this->defaultPaymentType ?: null,
            'default_payment_ref' => $this->defaultPaymentRef ?: null,
        ]);

        session()->flash('success', 'Perfil atualizado com sucesso.');
    }

    public function changePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'A password atual está incorreta.');
            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        session()->flash('passwordSuccess', 'Password alterada com sucesso.');
    }

    public function render()
    {
        return view('livewire.profile.profile-page')
            ->layout('layouts.app', ['title' => 'Perfil']);
    }
}
