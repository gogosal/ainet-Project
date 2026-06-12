<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Funcionário' }} — FunShirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { box-sizing: border-box; margin: 0; padding: 0; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#f5f4f1] text-[#1a1a1a] min-h-screen" style="font-family:'Inter',system-ui,sans-serif;">

    <x-navbar :cartCount="0" />

    <main style="max-width:1100px;margin:0 auto;padding:2rem 2rem;">
        @yield('content')
    </main>

    {{-- Global Toast --}}
    <div x-data="{
        message: '{{ session('success') ?? (session('passwordSuccess') ?? (session('error') ?? ($errors->any() ? $errors->first() : ''))) }}',
        show: {{ session()->has('success') || session()->has('passwordSuccess') || session()->has('error') || $errors->any() ? 'true' : 'false' }},
        type: '{{ session()->has('success') || session()->has('passwordSuccess') ? 'success' : 'error' }}'
    }" x-init="if (show) setTimeout(() => show = false, 5000)" class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-2">
        <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:leave="transition ease-in duration-200"
            :class="type === 'success' ? 'border-green-500' : 'border-red-500'"
            class="bg-white border-l-4 shadow-lg rounded-lg p-4 flex items-center gap-3">
            <span x-text="type === 'success' ? '✓' : '⚠'"
                :class="type === 'success' ? 'text-green-500' : 'text-red-500'" class="font-bold"></span>
            <p class="text-sm font-medium text-gray-800 m-0" x-text="message"></p>
        </div>
    </div>

    @fluxScripts
    @livewireScripts
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script>
        const lenis = new Lenis({ lerp: 0.1, smoothWheel: true });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);
    </script>
</body>
</html>
