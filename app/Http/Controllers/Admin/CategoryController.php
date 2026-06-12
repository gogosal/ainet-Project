<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('tshirtImages')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name'      => $request->validated()['name'],
            'image_url' => $request->file('image')->store('categories', 'public'),
        ]);

        return back()->with('success', 'Categoria criada.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = ['name' => $request->validated()['name']];

        if ($request->hasFile('image')) {
            if ($category->image_url) {
                Storage::disk('public')->delete($category->image_url);
            }
            $data['image_url'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return back()->with('success', 'Categoria atualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image_url) {
            Storage::disk('public')->delete($category->image_url);
        }

        $category->delete();

        return back()->with('success', 'Categoria eliminada.');
    }
}
