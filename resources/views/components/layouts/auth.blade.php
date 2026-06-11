@props(['title' => 'FunShirt'])
<!DOCTYPE html>
<html lang="pt" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — FunShirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float-shirt { 0%,100%{transform:translateY(0) rotate(-3deg)} 50%{transform:translateY(-10px) rotate(-3deg)} }
        @keyframes glow-in { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
        @keyframes fade-right { from{opacity:0;transform:translateX(-12px)} to{opacity:1;transform:translateX(0)} }
        .brand-panel-item { animation: fade-right .5s ease both; }
        .brand-panel-item:nth-child(1){animation-delay:.1s}
        .brand-panel-item:nth-child(2){animation-delay:.2s}
        .brand-panel-item:nth-child(3){animation-delay:.3s}
        .brand-panel-item:nth-child(4){animation-delay:.4s}
        .auth-card { animation: glow-in .4s ease both; }
        .input-field:focus { border-color:#7c3aed!important; box-shadow:0 0 0 3px rgba(124,58,237,.15)!important; outline:none; }
    </style>
</head>
<body style="background:#07070e;color:#e2e8f0;font-family:'Inter',sans-serif;min-height:100vh;display:flex;">

    <div style="display:grid;grid-template-columns:1fr 1fr;width:100%;min-height:100vh;">

        {{-- ── LEFT: Brand Panel ── --}}
        <div style="position:relative;background:#0a0a14;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:3rem;">

            {{-- Ambient glow --}}
            <div style="position:absolute;top:-120px;left:-80px;width:500px;height:500px;background:radial-gradient(circle,rgba(109,40,217,.2) 0%,transparent 65%);pointer-events:none;"></div>
            <div style="position:absolute;bottom:-60px;right:-80px;width:360px;height:360px;background:radial-gradient(circle,rgba(124,58,237,.13) 0%,transparent 65%);pointer-events:none;"></div>

            {{-- Grid overlay --}}
            <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.04) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;mask-image:radial-gradient(ellipse 90% 90% at 50% 50%,black 30%,transparent 100%);"></div>

            {{-- Logo --}}
            <a href="{{ route('catalog') }}" class="brand-panel-item" style="text-decoration:none;display:inline-flex;align-items:center;gap:0.65rem;position:relative;z-index:1;">
                <div style="width:38px;height:38px;background:linear-gradient(135deg,#7c3aed,#5b21b6);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(124,58,237,.4);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                    </svg>
                </div>
                <span style="color:#e2e8f0;font-weight:700;font-size:1.25rem;letter-spacing:-0.02em;">FunShirt</span>
            </a>

            {{-- Center: floating shirt + headline --}}
            <div style="flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;">
                {{-- Floating t-shirt SVG --}}
                <div style="text-align:center;margin-bottom:2.5rem;">
                    <svg style="width:160px;height:160px;filter:drop-shadow(0 16px 40px rgba(124,58,237,.45));animation:float-shirt 4s ease-in-out infinite;" viewBox="0 0 200 220" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="shirt-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#7c3aed"/>
                                <stop offset="100%" style="stop-color:#5b21b6"/>
                            </linearGradient>
                        </defs>
                        <path d="M 65,22 Q 76,42 100,42 Q 124,42 135,22 L 178,54 L 152,73 L 152,192 L 48,192 L 48,73 L 22,54 Z"
                              fill="url(#shirt-grad)" stroke="rgba(167,139,250,.3)" stroke-width="1"/>
                        <path d="M 65,22 L 48,73 L 22,54 Z" fill="rgba(0,0,0,0.15)"/>
                        <path d="M 135,22 L 152,73 L 178,54 Z" fill="rgba(0,0,0,0.15)"/>
                        <path d="M 65,22 Q 76,42 100,42 Q 124,42 135,22" fill="none" stroke="rgba(167,139,250,.4)" stroke-width="3" stroke-linecap="round"/>
                        {{-- Star/sparkle on shirt --}}
                        <text x="100" y="135" text-anchor="middle" font-size="32" fill="rgba(255,255,255,.85)">✦</text>
                    </svg>
                </div>

                <div class="brand-panel-item" style="text-align:center;margin-bottom:2rem;">
                    <h1 style="color:#e2e8f0;font-size:1.75rem;font-weight:700;letter-spacing:-0.03em;margin:0 0 0.5rem;line-height:1.2;">
                        Veste a tua<br><span style="color:#a78bfa;">criatividade.</span>
                    </h1>
                    <p style="color:#64748b;font-size:0.9rem;margin:0;line-height:1.6;">
                        Designs únicos, qualidade premium,<br>entrega rápida.
                    </p>
                </div>

                {{-- Feature list --}}
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    @foreach([
                        ['icon' => '◈', 'text' => 'Provador 3D interactivo'],
                        ['icon' => '✦', 'text' => 'Designs exclusivos de artistas'],
                        ['icon' => '◉', 'text' => 'Impressão de alta qualidade'],
                    ] as $feat)
                    <div class="brand-panel-item" style="display:flex;align-items:center;gap:0.65rem;">
                        <span style="width:28px;height:28px;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#a78bfa;flex-shrink:0;">{{ $feat['icon'] }}</span>
                        <span style="color:#94a3b8;font-size:0.85rem;">{{ $feat['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <p class="brand-panel-item" style="color:#374151;font-size:0.72rem;margin:0;position:relative;z-index:1;">© {{ date('Y') }} FunShirt</p>
        </div>

        {{-- ── RIGHT: Form Panel ── --}}
        <div style="background:#07070e;display:flex;align-items:center;justify-content:center;padding:3rem 2rem;position:relative;">
            <div class="auth-card" style="width:100%;max-width:400px;">
                {{ $slot }}
            </div>
        </div>

    </div>

    @fluxScripts
    @livewireScripts
</body>
</html>
