<div
    {{ $attributes->merge(['class' => 'shrink-0 relative overflow-hidden', 'style' => "width:{$size}; height:{$size};"]) }}>

    {{-- T-shirt Base --}}
    <img src="{{ $baseFile }}" alt="T-Shirt" class="w-full h-full object-contain block">

    {{-- Design Aplicado --}}
    @if ($resolvedUrl)
        <img src="{{ $resolvedUrl }}" alt="Design"
            class="absolute top-[26%] left-1/2 -translate-x-1/2 w-[32%] h-[32%] object-contain mix-blend-multiply pointer-events-none">
    @endif

</div>
