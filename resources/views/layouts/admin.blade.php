<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — Funshirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d8d5d0; border-radius: 4px; }
        .nav-item {
            display: flex; align-items: center; gap: .6rem;
            padding: .42rem .7rem; border-radius: 1px;
            text-decoration: none; font-size: .75rem; font-weight: 500;
            letter-spacing: .02em; color: #888; border: 1px solid transparent;
            margin-bottom: 2px; transition: all .15s;
        }
        .nav-item:hover { color: #1a1a1a; background: rgba(0,0,0,.04); }
        .nav-item.active { color: #7c6fa0; background: rgba(124,111,160,.06); border-color: rgba(124,111,160,.2); font-weight: 600; }
        .nav-item.active svg { stroke: #7c6fa0; }
    </style>
</head>
<body style="background:#f5f4f1; color:#1a1a1a; font-family:'Inter',sans-serif; min-height:100vh; display:flex;">

{{-- Sidebar --}}
<aside style="width:232px; background:#eeecea; border-right:1px solid #e0ddd8; height:100vh; position:fixed; top:0; left:0; display:flex; flex-direction:column; z-index:40; overflow-y:auto;">

    {{-- Brand --}}
    <div style="height:52px; padding:0 1.25rem; border-bottom:1px solid #e0ddd8; display:flex; align-items:center; flex-shrink:0;">
        <a href="{{ route('catalog') }}" style="text-decoration:none; display:flex; flex-direction:column; gap:1px;">
            <span style="font-size:.72rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:#1a1a1a;">Funshirt</span>
            <span style="font-size:.58rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#7c6fa0;">Admin</span>
        </a>
    </div>

    {{-- Nav --}}
    <nav style="padding:1rem .75rem; flex:1;">
        @php
            $ni = function(string $route, string $svg, string $label, string $pattern = '') use (&$ni): string {
                $active = request()->routeIs($pattern ?: $route);
                $cls = $active ? 'nav-item active' : 'nav-item';
                $ico = $active
                    ? 'width:14px;height:14px;stroke:#7c6fa0;flex-shrink:0;'
                    : 'width:14px;height:14px;stroke:#bbb;flex-shrink:0;';
                return "<a href=\"".route($route)."\" class=\"{$cls}\"><svg style=\"{$ico}\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">{$svg}</svg>{$label}</a>";
            };
            $ic = [
                'dash'   => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
                'stats'  => '<path d="M18 20V10M12 20V4M6 20v-6"/>',
                'img'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>',
                'cat'    => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><circle cx="7" cy="7" r="1" fill="currentColor" stroke="none"/>',
                'col'    => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4" fill="currentColor" stroke="none" opacity=".4"/>',
                'price'  => '<circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 000 4h4a2 2 0 010 4H8M12 6v2m0 8v2"/>',
                'orders' => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
                'users'  => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
                'staff'  => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>',
            ];
        @endphp

        <div style="font-size:.58rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#c8c4be;padding:.2rem .7rem .55rem;margin:0;">Geral</div>
        {!! $ni('admin.dashboard',  $ic['dash'],  'Dashboard',    'admin.dashboard') !!}
        {!! $ni('admin.statistics', $ic['stats'], 'Estatísticas', 'admin.statistics') !!}

        <div style="height:1px;background:#e0ddd8;margin:.75rem 0 .65rem;"></div>
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#c8c4be;padding:.2rem .7rem .55rem;margin:0;">Catálogo</div>
        {!! $ni('admin.catalog',    $ic['img'],   'Imagens',      'admin.catalog') !!}
        {!! $ni('admin.categories', $ic['cat'],   'Categorias',   'admin.categories') !!}
        {!! $ni('admin.colors',     $ic['col'],   'Cores',        'admin.colors') !!}
        {!! $ni('admin.prices',     $ic['price'], 'Preços',       'admin.prices') !!}

        <div style="height:1px;background:#e0ddd8;margin:.75rem 0 .65rem;"></div>
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#c8c4be;padding:.2rem .7rem .55rem;margin:0;">Operações</div>
        {!! $ni('admin.orders',    $ic['orders'], 'Encomendas',   'admin.orders*') !!}
        {!! $ni('admin.customers', $ic['users'],  'Clientes',     'admin.customers') !!}
        {!! $ni('admin.staff',     $ic['staff'],  'Funcionários', 'admin.staff') !!}
    </nav>

    {{-- User --}}
    <div style="padding:.75rem;border-top:1px solid #e0ddd8;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:.65rem;padding:.6rem .7rem;background:#f5f4f1;border:1px solid #e0ddd8;border-radius:1px;margin-bottom:.4rem;">
            @if(auth()->user()->photo_url)
                @php $ap = auth()->user()->photo_url; $aSrc = str_contains($ap, '/') ? asset('storage/'.$ap) : asset('storage/photos/'.$ap); @endphp
                <img src="{{ $aSrc }}" style="width:28px;height:28px;border-radius:1px;object-fit:cover;flex-shrink:0;"
                     onerror="this.style.display='none'">
            @else
                <div style="width:28px;height:28px;border-radius:1px;background:#1a1a1a;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#f5f4f1;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="min-width:0;flex:1;">
                <div style="color:#1a1a1a;font-size:.78rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div style="color:#7c6fa0;font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-top:1px;">Administrador</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="display:flex;align-items:center;gap:.5rem;width:100%;padding:.42rem .7rem;background:transparent;border:none;color:#b8b4ae;font-size:.72rem;font-weight:600;letter-spacing:.04em;cursor:pointer;border-radius:1px;transition:color .15s;font-family:inherit;"
                    onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#b8b4ae'">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Terminar sessão
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div style="margin-left:232px; flex:1; min-height:100vh; display:flex; flex-direction:column;">

    {{-- Topbar --}}
    <header style="background:#f5f4f1;border-bottom:1px solid #e0ddd8;padding:0 1.75rem;height:52px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:30;">
        <div style="display:flex;align-items:center;gap:.5rem;">
            <span style="font-size:.68rem;font-weight:400;color:#c8c4be;letter-spacing:.06em;">Funshirt</span>
            <span style="color:#d8d5d0;font-size:.7rem;">/</span>
            <span style="font-size:.78rem;font-weight:600;color:#1a1a1a;letter-spacing:-.01em;">{{ $title ?? 'Dashboard' }}</span>
        </div>
        <a href="{{ route('catalog') }}"
           style="font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#888;text-decoration:none;padding:.38rem .85rem;border:1px solid #d8d5d0;border-radius:1px;transition:all .15s;"
           onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
            ← Loja
        </a>
    </header>

    {{-- Content --}}
    <main style="flex:1;padding:1.75rem;">
        {{ $slot }}
    </main>
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
