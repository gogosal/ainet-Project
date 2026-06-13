<?php

namespace App\Http\Controllers;

use App\Models\TshirtImage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index(): View
    {
        $featuredDesigns = TshirtImage::whereNull('customer_id')
            ->with('category')
            ->take(4)
            ->get()
            ->map(function ($design) {
                $bareName = basename($design->image_url);

                if (Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                    $design->display_url = route('private-image', $bareName);
                } else {
                    $design->display_url = str_contains($design->image_url, '/')
                        ? asset('storage/' . $design->image_url)
                        : asset('storage/tshirt_images/' . $bareName);
                }

                return $design;
            });

        $totalDesigns = TshirtImage::whereNull('customer_id')->count();

        $ctaUrl = $featuredDesigns->first() ? $featuredDesigns->first()->display_url : null;

        return view('landing', compact('featuredDesigns', 'totalDesigns', 'ctaUrl'));
    }
}
