<!DOCTYPE html>
<html lang="pt" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Funcionário' }} — FunShirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#0a0a0f;color:#e2e8f0;font-family:'Inter',sans-serif;min-height:100vh;">

    <header style="background:#111120;border-bottom:1px solid #1e1e30;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <span style="color:#a78bfa;font-size:1.3rem;">⬡</span>
            <div>
                <div style="color:#e2e8f0;font-weight:700;font-size:0.95rem;">FunShirt</div>
                <div style="color:#64748b;font-size:0.65rem;text-transform:uppercase;letter-spacing:.05em;">Funcionário</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:1rem;">
            <span style="color:#94a3b8;font-size:0.85rem;">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="color:#f87171;background:transparent;border:none;cursor:pointer;font-size:0.85rem;">Logout</button>
            </form>
        </div>
    </header>

    <main style="max-width:1000px;margin:0 auto;padding:2rem 1.5rem;">
        {{ $slot }}
    </main>

    @fluxScripts
    @livewireScripts
</body>
</html>
