@props([
    'colorCode' => 'white',
    'imageUrl' => null,
    'size' => '200px',
])

@php
    // Map common color names to hex if needed
    $colorMap = [
        'white' => '#ffffff',
        'black' => '#111111',
        'gray' => '#888888',
        'grey' => '#888888',
        'red' => '#dc2626',
        'blue' => '#2563eb',
        'green' => '#16a34a',
        'yellow' => '#eab308',
        'purple' => '#7c3aed',
        'pink' => '#ec4899',
        'orange' => '#ea580c',
        'navy' => '#1e3a5f',
        'brown' => '#78350f',
    ];
    $fillColor = $colorMap[strtolower($colorCode)] ?? $colorCode;
    // Determine shadow color (darker version)
    $shadowColor = 'rgba(0,0,0,0.12)';
@endphp

<div
    {{ $attributes->merge(['style' => "width:{$size};height:{$size};flex-shrink:0;display:flex;align-items:center;justify-content:center;"]) }}>
    <svg viewBox="0 0 200 220" xmlns="http://www.w3.org/2000/svg"
        style="width:100%;height:100%;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.25));">
        <defs>
            <clipPath id="design-clip-{{ md5($imageUrl ?? 'none') }}">
                {{-- Clip the design to the t-shirt body area --}}
                <rect x="55" y="65" width="90" height="115" rx="4" />
            </clipPath>
        </defs>

        {{-- T-shirt body --}}
        <path d="M 65,22 Q 76,42 100,42 Q 124,42 135,22 L 178,54 L 152,73 L 152,192 L 48,192 L 48,73 L 22,54 Z"
            fill="{{ $fillColor }}" stroke="{{ $shadowColor }}" stroke-width="1.5" stroke-linejoin="round" />

        {{-- Sleeve shadows for depth --}}
        <path d="M 65,22 L 48,73 L 22,54 Z" fill="rgba(0,0,0,0.06)" />
        <path d="M 135,22 L 152,73 L 178,54 Z" fill="rgba(0,0,0,0.06)" />

        {{-- Collar detail --}}
        <path d="M 65,22 Q 76,42 100,42 Q 124,42 135,22" fill="none" stroke="rgba(0,0,0,0.08)" stroke-width="3"
            stroke-linecap="round" />

        {{-- Design image overlay --}}
        @if ($imageUrl)
            <image
                href="{{ Str::startsWith($imageUrl, 'tshirt_images/') ? route('private-image', $imageUrl) : asset('storage/' . $imageUrl) }}"
                x="60" y="70" width="80" height="80" preserveAspectRatio="xMidYMid meet"
                style="mix-blend-mode:multiply;" clip-path="url(#design-clip-{{ md5($imageUrl ?? 'none') }})" />
        @endif
    </svg>
</div>
