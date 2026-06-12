<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Color;

class AdminColorsPage extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public ?string $editingCode = null;
    public string $modalCode = '';
    public string $modalName = '';
    public $modalImage = null;
    public ?string $deleteCode = null;

    public function openCreate(): void
    {
        $this->editingCode = null;
        $this->modalCode = $this->modalName = '';
        $this->modalImage = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(string $code): void
    {
        $color = Color::findOrFail($code);
        $this->editingCode = $code;
        $this->modalCode = $color->code;
        $this->modalName = $color->name;
        $this->modalImage = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'modalName' => 'required|string|max:255',
            'modalImage' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ];
        if (!$this->editingCode) {
            $rules['modalCode'] = 'required|string|max:20|unique:colors,code';
        }
        $this->validate($rules);

        if ($this->editingCode) {
            $color = Color::findOrFail($this->editingCode);
            $data = ['name' => $this->modalName];
            if ($this->modalImage) {
                $filename = ltrim($this->editingCode, '#') . '.png';
                $this->modalImage->storeAs('tshirt_base', $filename, 'public');
            }
            $color->update($data);
        } else {
            Color::create(['code' => $this->modalCode, 'name' => $this->modalName]);
            if ($this->modalImage) {
                $filename = ltrim($this->modalCode, '#') . '.png';
                $this->modalImage->storeAs('tshirt_base', $filename, 'public');
            }
        }
        $this->showModal = false;
        session()->flash('success', $this->editingCode ? 'Cor atualizada.' : 'Cor criada.');
    }

    public function confirmDelete(string $code): void
    {
        $this->deleteCode = $code;
    }
    public function cancelDelete(): void
    {
        $this->deleteCode = null;
    }
    public function deleteColor(): void
    {
        Color::findOrFail($this->deleteCode)->delete();
        $this->deleteCode = null;
        session()->flash('success', 'Cor eliminada.');
    }

    public function render()
    {
        $colors = Color::orderBy('name')->get();
        return view('livewire.admin.admin-colors-page', compact('colors'))
            ->layout('layouts.admin', ['title' => 'Cores']);
    }
}
