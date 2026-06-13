<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — Funshirt</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d8d5d0;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#f5f4f1] text-[#1a1a1a] font-sans min-h-screen flex">

    {{-- Chamada simples ao novo componente da Sidebar --}}
    <x-admin-sidebar />

    {{-- Main Container --}}
    <div class="ml-[232px] flex-1 min-h-screen flex flex-col">

        {{-- Topbar --}}
        <header
            class="bg-[#f5f4f1] border-b border-[#e0ddd8] px-7 h-[52px] flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-2">
                <span class="text-[.68rem] font-normal text-[#c8c4be] tracking-[.06em]">Funshirt</span>
                <span class="text-[#d8d5d0] text-[.7rem]">/</span>
                <span
                    class="text-[.78rem] font-semibold text-[#1a1a1a] tracking-[-.01em]">{{ $title ?? 'Dashboard' }}</span>
            </div>
            <a href="{{ route('catalog') }}"
                class="text-[.65rem] font-bold tracking-[.12em] uppercase text-[#888] no-underline px-[.85rem] py-[.38rem] border border-[#d8d5d0] rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                ← Loja
            </a>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-7">
            @yield('content')
        </main>
    </div>

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

    @stack('scripts')

    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script>
        const lenis = new Lenis({
            lerp: 0.1,
            smoothWheel: true
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    </script>
</body>

</html>
