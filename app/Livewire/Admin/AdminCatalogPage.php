<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use App\Models\TshirtImage;
use App\Models\Category;

class AdminCatalogPage extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';
    public ?int $categoryFilter = null;

    // Modal
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $modalName = '';
    public string $modalDescription = '';
    public ?int $modalCategoryId = null;
    public $modalImage = null;

    // Delete
    public ?int $deleteId = null;

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->editingId = null;
        $this->modalName = $this->modalDescription = '';
        $this->modalCategoryId = null;
        $this->modalImage = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $img = TshirtImage::whereNull('customer_id')->findOrFail($id);
        $this->editingId = $id;
        $this->modalName = $img->name;
        $this->modalDescription = $img->description ?? '';
        $this->modalCategoryId = $img->category_id;
        $this->modalImage = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $rules = [
            'modalName' => 'required|string|max:255',
            'modalDescription' => 'nullable|string|max:1000',
            'modalCategoryId' => 'nullable|exists:categories,id',
        ];
        if (!$this->editingId) $rules['modalImage'] = 'required|image|max:4096';
        else $rules['modalImage'] = 'nullable|image|max:4096';

        $this->validate($rules);

        if ($this->editingId) {
            $img = TshirtImage::findOrFail($this->editingId);
            $data = ['name' => $this->modalName, 'description' => $this->modalDescription ?: null, 'category_id' => $this->modalCategoryId];
            if ($this->modalImage) {
                if ($img->image_url) Storage::disk('public')->delete($img->image_url);
                $data['image_url'] = $this->modalImage->store('tshirt_images', 'public');
            }
            $img->update($data);
        } else {
            TshirtImage::create([
                'customer_id' => null,
                'category_id' => $this->modalCategoryId,
                'name' => $this->modalName,
                'description' => $this->modalDescription ?: null,
                'image_url' => $this->modalImage->store('tshirt_images', 'public'),
            ]);
        }

        $this->showModal = false;
        session()->flash('success', $this->editingId ? 'Imagem atualizada.' : 'Imagem adicionada.');
        $this->resetPage();
    }

    public function confirmDelete(int $id): void { $this->deleteId = $id; }
    public function cancelDelete(): void { $this->deleteId = null; }
    public function deleteImage(): void
    {
        $img = TshirtImage::findOrFail($this->deleteId);
        if ($img->image_url) Storage::disk('public')->delete($img->image_url);
        $img->delete();
        $this->deleteId = null;
        session()->flash('success', 'Imagem eliminada.');
    }

    public function render()
    {
        $images = TshirtImage::whereNull('customer_id')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->with('category')
            ->latest()
            ->paginate(12);
        $categories = Category::orderBy('name')->get();
        return view('livewire.admin.admin-catalog-page', compact('images', 'categories'))
            ->layout('layouts.admin', ['title' => 'Catálogo']);
    }
}
