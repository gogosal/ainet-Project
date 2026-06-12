<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TshirtImageRequest;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $categoryFilter = $request->query('category');

        $images = TshirtImage::whereNull('customer_id')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryFilter, fn($q) => $q->where('category_id', $categoryFilter))
            ->with('category')
            ->latest()
            ->paginate(12)
            ->appends($request->query());

        foreach ($images as $img) {
            if ($img->image_url) {
                $img->catalogUrl = str_contains($img->image_url, '/')
                    ? Storage::url($img->image_url)
                    : asset('storage/tshirt_images/' . $img->image_url);
            } else {
                $img->catalogUrl = null;
            }
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.catalog.index', compact('images', 'categories', 'search', 'categoryFilter'));
    }

    public function store(TshirtImageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        TshirtImage::create([
            'customer_id' => null,
            'category_id' => $data['category_id'] ?? null,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'image_url'   => $request->file('image')->store('tshirt_images', 'public'),
        ]);

        return back()->with('success', 'Imagem adicionada.');
    }

    public function update(TshirtImageRequest $request, TshirtImage $image): RedirectResponse
    {
        $data = $request->validated();
        $updateData = [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($image->image_url) {
                Storage::disk('public')->delete($image->image_url);
            }
            $updateData['image_url'] = $request->file('image')->store('tshirt_images', 'public');
        }

        $image->update($updateData);

        return back()->with('success', 'Imagem atualizada.');
    }

    public function destroy(TshirtImage $image): RedirectResponse
    {
        if ($image->image_url) {
            Storage::disk('public')->delete($image->image_url);
        }

        $image->delete();

        return back()->with('success', 'Imagem eliminada.');
    }
}
