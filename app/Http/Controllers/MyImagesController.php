<?php

namespace App\Http\Controllers;

use App\Http\Requests\MyImageRequest;
use App\Models\Category;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $file = $request->file('image');

        $image = TshirtImage::create([
            'customer_id' => $customerId,
            'category_id' => $data['category_id'] ?? null,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'image_url'   => 'temp_file',
        ]);

        $id = str_pad($image->id, 5, '0', STR_PAD_LEFT);
        $randomString = Str::random(10);
        $extension = $file->getClientOriginalExtension();
        $filename = "{$id}_{$randomString}.{$extension}";

        $file->storeAs('tshirt_images', $filename, 'public'); // Publica
        $file->storeAs('private/tshirt_images_private', $filename, 'local'); // Privada

        $image->update(['image_url' => $filename]);

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
            $file = $request->file('image');

            if ($image->image_url) {
                $oldFilename = basename($image->image_url);
                Storage::disk('public')->delete('tshirt_images/' . $oldFilename);
                Storage::disk('local')->delete('private/tshirt_images_private/' . $oldFilename);
            }

            $id = str_pad($image->id, 5, '0', STR_PAD_LEFT);
            $randomString = Str::random(10);
            $extension = $file->getClientOriginalExtension();
            $filename = "{$id}_{$randomString}.{$extension}";

            $file->storeAs('tshirt_images', $filename, 'public');
            $file->storeAs('private/tshirt_images_private', $filename, 'local');

            $updateData['image_url'] = $filename;
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
            $filename = basename($image->image_url);
            Storage::disk('public')->delete('tshirt_images/' . $filename);
            Storage::disk('local')->delete('private/tshirt_images_private/' . $filename);
        }

        $image->delete();

        return back()->with('success', 'Imagem eliminada.');
    }

    public function servePrivateImage(string $filename): BinaryFileResponse
    {
        $filename = basename($filename);

        foreach (['tshirt_images_private', 'tshirt_images'] as $dir) {
            $path = storage_path('app/private/' . $dir . '/' . $filename);

            if (file_exists($path)) {
                $isJpeg = str_ends_with(strtolower($filename), '.jpg') || str_ends_with(strtolower($filename), '.jpeg');
                $mime = $isJpeg ? 'image/jpeg' : 'image/png';

                return response()->file($path, ['Content-Type' => $mime]);
            }
        }
        abort(404);
    }
}
