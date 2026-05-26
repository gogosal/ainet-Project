<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class AdminCategoriesPage extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public ?int $editingId = null;
    public string $modalName = '';
    public $modalImage = null;
    public ?int $deleteId = null;

    public function openCreate(): void
    {
        $this->editingId = null; $this->modalName = ''; $this->modalImage = null;
        $this->resetValidation(); $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $id; $this->modalName = $cat->name; $this->modalImage = null;
        $this->resetValidation(); $this->showModal = true;
    }

    public function save(): void
    {
        $rules = ['modalName' => 'required|string|max:255'];
        if (!$this->editingId) $rules['modalImage'] = 'required|image|max:2048';
        else $rules['modalImage'] = 'nullable|image|max:2048';
        $this->validate($rules);

        if ($this->editingId) {
            $cat = Category::findOrFail($this->editingId);
            $data = ['name' => $this->modalName];
            if ($this->modalImage) {
                if ($cat->image_url) Storage::disk('public')->delete($cat->image_url);
                $data['image_url'] = $this->modalImage->store('categories', 'public');
            }
            $cat->update($data);
        } else {
            Category::create(['name' => $this->modalName, 'image_url' => $this->modalImage->store('categories', 'public')]);
        }
        $this->showModal = false;
        session()->flash('success', $this->editingId ? 'Categoria atualizada.' : 'Categoria criada.');
    }

    public function confirmDelete(int $id): void { $this->deleteId = $id; }
    public function cancelDelete(): void { $this->deleteId = null; }
    public function deleteCategory(): void
    {
        $cat = Category::findOrFail($this->deleteId);
        if ($cat->image_url) Storage::disk('public')->delete($cat->image_url);
        $cat->delete();
        $this->deleteId = null;
        session()->flash('success', 'Categoria eliminada.');
    }

    public function render()
    {
        $categories = Category::withCount('tshirtImages')->orderBy('name')->get();
        return view('livewire.admin.admin-categories-page', compact('categories'))
            ->layout('layouts.admin', ['title' => 'Categorias']);
    }
}
