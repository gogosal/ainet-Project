<?php

namespace App\Livewire\MyImages;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Importante para gerar o nome aleatório
use App\Models\TshirtImage;
use App\Models\Category;

class MyImagesPage extends Component
{
    use WithFileUploads, WithPagination;

    // List/filter state
    public string $search = '';

    // Create/edit modal
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $modalName = '';
    public string $modalDescription = '';
    public ?int $modalCategoryId = null;
    public $modalImage = null; // temp upload

    // Delete confirm
    public ?int $deleteId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->editingId = null;
        $this->modalName = '';
        $this->modalDescription = '';
        $this->modalCategoryId = null;
        $this->modalImage = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $image = TshirtImage::where('customer_id', Auth::user()->customer->id)->findOrFail($id);
        $this->editingId = $id;
        $this->modalName = $image->name;
        $this->modalDescription = $image->description ?? '';
        $this->modalCategoryId = $image->category_id;
        $this->modalImage = null;
        $this->showModal = true;
    }

    public function saveImage(): void
    {
        $rules = [
            'modalName' => 'required|string|max:255',
            'modalDescription' => 'nullable|string|max:1000',
            'modalCategoryId' => 'nullable|exists:categories,id',
        ];

        if (!$this->editingId) {
            $rules['modalImage'] = 'required|image|max:4096';
        } else {
            $rules['modalImage'] = 'nullable|image|max:4096';
        }

        $this->validate($rules);

        $customerId = Auth::user()->customer->id;

        if ($this->editingId) {
            $image = TshirtImage::where('customer_id', $customerId)->findOrFail($this->editingId);
            $data = [
                'name' => $this->modalName,
                'description' => $this->modalDescription ?: null,
                'category_id' => $this->modalCategoryId,
            ];

            if ($this->modalImage) {
                // Apaga a imagem antiga do disco
                if ($image->image_url) {
                    Storage::disk('public')->delete('tshirt_images/' . basename($image->image_url));
                }

                // Formata o nome para ficar igual aos do Seeder (ex: 00012_aBcDeFgHiJ.png)
                $extensao = $this->modalImage->getClientOriginalExtension();
                $nomePersonalizado = sprintf('%05d_%s.%s', $customerId, Str::random(10), $extensao);

                // Guarda com o nome exato e atualiza o array de dados para a DB
                $this->modalImage->storeAs('tshirt_images', $nomePersonalizado, 'public');
                $data['image_url'] = $nomePersonalizado;
            }

            $image->update($data);
            session()->flash('success', 'Imagem atualizada com sucesso.');
        } else {
            // Formata o nome para ficar igual aos do Seeder
            $extensao = $this->modalImage->getClientOriginalExtension();
            $nomePersonalizado = sprintf('%05d_%s.%s', $customerId, Str::random(10), $extensao);

            // Guarda com o nome exato no disco
            $this->modalImage->storeAs('tshirt_images', $nomePersonalizado, 'public');

            TshirtImage::create([
                'customer_id' => $customerId,
                'category_id' => $this->modalCategoryId,
                'name' => $this->modalName,
                'description' => $this->modalDescription ?: null,
                'image_url' => $nomePersonalizado,
            ]);

            session()->flash('success', 'Imagem adicionada com sucesso.');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
    }

    public function deleteImage(): void
    {
        $image = TshirtImage::where('customer_id', Auth::user()->customer->id)->findOrFail($this->deleteId);

        // Garante que o ficheiro é apagado da pasta correta
        if ($image->image_url) {
            Storage::disk('public')->delete('tshirt_images/' . basename($image->image_url));
        }

        $image->delete();
        $this->deleteId = null;
        session()->flash('success', 'Imagem eliminada.');
    }

    public function render()
    {
        $customerId = Auth::user()->customer?->id;
        $images = TshirtImage::where('customer_id', $customerId ?? 0)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::orderBy('name')->get();

        return view('livewire.my-images.my-images-page', compact('images', 'categories'))
            ->layout('layouts.app', ['title' => 'As minhas imagens']);
    }
}
