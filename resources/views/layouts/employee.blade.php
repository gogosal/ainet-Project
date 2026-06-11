<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Funcionário' }} — FunShirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>* { box-sizing: border-box; margin: 0; padding: 0; }</style>
</head>
<body style="background:#f5f4f1;color:#1a1a1a;font-family:'Inter',system-ui,sans-serif;min-height:100vh;">

    <header style="background:#fff;border-bottom:1px solid #e0ddd8;padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('catalog') }}" style="text-decoration:none;">
                <span style="font-size:.72rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#1a1a1a;">Funshirt</span>
            </a>
            <div style="width:1px;height:14px;background:#e0ddd8;"></div>
            <span style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;">Funcionário</span>
        </div>
        <div style="display:flex;align-items:center;gap:1.5rem;">
            <span style="color:#888;font-size:.82rem;">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="background:transparent;border:none;color:#aaa;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:inherit;transition:color .15s;"
                    onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#aaa'">Sair</button>
            </form>
        </div>
    </header>

    <main style="max-width:1100px;margin:0 auto;padding:2rem 2rem;">
        {{ $slot }}
    </main>

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
