<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyImageRequest;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MyImagesController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $customerId = Auth::user()->customer?->id;

        $images = TshirtImage::where('customer_id', $customerId ?? 0)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->with('category')
            ->latest()
            ->paginate(12)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

        return view('my-images.index', compact('images', 'categories', 'search'));
    }

    public function store(MyImageRequest $request): RedirectResponse
    {
        $customerId = Auth::user()->customer->id;
        $data = $request->validated();

        TshirtImage::create([
            'customer_id' => $customerId,
            'category_id' => $data['category_id'] ?? null,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'image_url'   => $request->file('image')->store('tshirt_images', 'public'),
        ]);

        return back()->with('success', 'Imagem adicionada.');
    }

    public function update(MyImageRequest $request, TshirtImage $image): RedirectResponse
    {
        if ($image->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }

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
        if ($image->customer_id !== Auth::user()->customer->id) {
            abort(403);
        }

        if ($image->image_url) {
            Storage::disk('public')->delete($image->image_url);
        }

        $image->delete();

        return back()->with('success', 'Imagem eliminada.');
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => [
                $this->isMethod('put') ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];
    }
}
