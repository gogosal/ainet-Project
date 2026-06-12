<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(): View
    {
        $colors = Color::orderBy('name')->get();

        return view('admin.colors.index', compact('colors'));
    }

    public function store(ColorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Color::create(['code' => $data['code'], 'name' => $data['name']]);

        if ($request->hasFile('image')) {
            $filename = ltrim($data['code'], '#').'.png';
            $request->file('image')->storeAs('tshirt_base', $filename, 'public');
        }

        return back()->with('success', 'Cor criada.');
    }

    public function update(ColorRequest $request, Color $color): RedirectResponse
    {
        $data = $request->validated();

        $color->update(['name' => $data['name']]);

        if ($request->hasFile('image')) {
            $filename = ltrim($color->code, '#').'.png';
            $request->file('image')->storeAs('tshirt_base', $filename, 'public');
        }

        return back()->with('success', 'Cor atualizada.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $color->delete();

        return back()->with('success', 'Cor eliminada.');
    }
}
