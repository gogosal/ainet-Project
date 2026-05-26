<!DOCTYPE html>
<html lang="pt" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'FunShirt' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#0a0a0f;color:#e2e8f0;font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;">

    <div style="width:100%;max-width:420px;padding:1.5rem;">
        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:2rem;">
            <a href="{{ route('catalog') }}" style="text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;">
                <span style="color:#a78bfa;font-size:2rem;">⬡</span>
                <span style="color:#e2e8f0;font-weight:700;font-size:1.5rem;">FunShirt</span>
            </a>
        </div>

        {{-- Card --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:2rem;box-shadow:0 25px 50px rgba(0,0,0,.5);">
            {{ $slot }}
        </div>
    </div>

    @fluxScripts
    @livewireScripts
</body>
</html>
