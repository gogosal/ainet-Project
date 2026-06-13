<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class TshirtPreview extends Component
{
    public string $size;
    public string $baseFile;
    public ?string $resolvedUrl;

    public function __construct(string $colorCode = 'fafafa', ?string $imageUrl = null, string $size = '200px')
    {
        $this->size = $size;

        $code = strtolower(trim($colorCode, '#'));
        $nameMap = [
            'white' => 'fafafa',
            'black' => '1e1e21',
            'gray' => 'c7c6cf',
            'grey' => 'c7c6cf',
            'red' => 'dc192d',
            'blue' => '284d9d',
            'green' => '1fba8f',
            'yellow' => 'ecdb2e',
            'purple' => '73336a',
            'pink' => 'fd4083',
            'orange' => 'fd890f',
            'navy' => '201f30',
            'brown' => '49302c',
            'cyan' => '4bd7ef',
        ];
        $code = $nameMap[$code] ?? $code;

        if (file_exists(public_path('storage/tshirt_base/' . $code . '.jpg'))) {
            $this->baseFile = asset('storage/tshirt_base/' . $code . '.jpg');
        } else {
            $this->baseFile = file_exists(public_path('storage/tshirt_base/fafafa.jpg'))
                ? asset('storage/tshirt_base/fafafa.jpg')
                : asset('storage/tshirt_base/plain_white.png');
        }

        $this->resolvedUrl = null;
        if ($imageUrl) {
            $bare = basename($imageUrl);

            if (Str::startsWith($imageUrl, 'tshirt_images_private/') || Str::startsWith($imageUrl, 'tshirt_images_private')) {
                $this->resolvedUrl = route('private-image', $bare);
            } elseif (str_contains($imageUrl, '/')) {
                $this->resolvedUrl = asset('storage/' . $imageUrl);
            } elseif (file_exists(public_path('storage/tshirt_images/' . $bare))) {
                $this->resolvedUrl = asset('storage/tshirt_images/' . $bare);
            } else {
                $this->resolvedUrl = route('private-image', $bare);
            }
        }
    }

    public function render()
    {
        return view('components.tshirt-preview');
    }
}
