@props([
    'colorCode' => 'fafafa',
    'imageUrl' => null,
    'size' => '200px',
])

@php
    use Illuminate\Support\Str;

    // Normalize: name → hex code
    $code = strtolower(trim($colorCode, '#'));
    $nameMap = [
        'white' => 'fafafa', 'black' => '1e1e21', 'gray' => 'c7c6cf', 'grey' => 'c7c6cf',
        'red' => 'dc192d', 'blue' => '284d9d', 'green' => '1fba8f', 'yellow' => 'ecdb2e',
        'purple' => '73336a', 'pink' => 'fd4083', 'orange' => 'fd890f', 'navy' => '201f30',
        'brown' => '49302c', 'cyan' => '4bd7ef',
    ];
    if (isset($nameMap[$code])) $code = $nameMap[$code];

    // Find best matching base shirt photo
    $baseFile = file_exists(public_path('storage/tshirt_base/' . $code . '.jpg'))
        ? asset('storage/tshirt_base/' . $code . '.jpg')
        : (file_exists(public_path('storage/tshirt_base/fafafa.jpg'))
            ? asset('storage/tshirt_base/fafafa.jpg')
            : asset('storage/tshirt_base/plain_white.png'));

    // Resolve design URL
    $resolvedUrl = null;
    if ($imageUrl) {
        $bare = basename($imageUrl);
        if (Str::startsWith($imageUrl, 'tshirt_images_private/') || Str::startsWith($imageUrl, 'tshirt_images_private')) {
            $resolvedUrl = route('private-image', $bare);
        } elseif (str_contains($imageUrl, '/')) {
            $resolvedUrl = asset('storage/' . $imageUrl);
        } elseif (file_exists(public_path('storage/tshirt_images/' . $bare))) {
            $resolvedUrl = asset('storage/tshirt_images/' . $bare);
        } else {
            $resolvedUrl = route('private-image', $bare);
        }
    }
@endphp

<div {{ $attributes->merge(['style' => "width:{$size};height:{$size};flex-shrink:0;position:relative;overflow:hidden;"]) }}>
    <img src="{{ $baseFile }}" alt=""
         style="width:100%;height:100%;object-fit:contain;display:block;">
    @if ($resolvedUrl)
        <img src="{{ $resolvedUrl }}" alt=""
             style="position:absolute;top:26%;left:50%;transform:translateX(-50%);width:32%;height:32%;object-fit:contain;mix-blend-mode:multiply;pointer-events:none;">
    @endif
</div>
