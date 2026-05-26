<!DOCTYPE html>
<html lang="pt" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — FunShirt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1e1e30; border-radius: 10px; }
    </style>
</head>
<body style="background:#07070e; color:#e2e8f0; font-family:'Inter',sans-serif; min-height:100vh; display:flex;">

{{-- ══════════ SIDEBAR ══════════ --}}
<aside style="width:242px; background:#05050b; border-right:1px solid #13132a; height:100vh; position:fixed; top:0; left:0; display:flex; flex-direction:column; z-index:40; overflow-y:auto;">

    {{-- Brand (height matches topbar 58px) --}}
    <div style="height:58px; padding:0 1.25rem; border-bottom:1px solid #13132a; display:flex; align-items:center; flex-shrink:0;">
        <a href="{{ route('catalog') }}" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;">
            <div style="width:38px;height:38px;background:linear-gradient(140deg,#7c3aed,#4c1d95);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 14px rgba(124,58,237,.4);">
                <svg width="19" height="19" fill="none" viewBox="0 0 24 24">
                    <path d="M12 2L21 7v10l-9 5-9-5V7l9-5z" fill="rgba(255,255,255,.92)"/>
                </svg>
            </div>
            <div>
                <div style="color:#f1f5f9;font-weight:700;font-size:1.02rem;line-height:1.25;letter-spacing:-.015em;">FunShirt</div>
                <div style="color:#7c3aed;font-size:0.62rem;letter-spacing:.12em;text-transform:uppercase;font-weight:700;margin-top:1px;">Admin Panel</div>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav style="padding:1.125rem 0.875rem; flex:1;">
        @php
            $ni = function(string $route, string $svg, string $label, string $pattern = '') use (&$ni): string {
                $active = request()->routeIs($pattern ?: $route);
                if ($active) {
                    $s = "background:rgba(124,58,237,.14);color:#c4b5fd;border:1px solid rgba(124,58,237,.22);";
                    $h = "";
                } else {
                    $s = "background:transparent;color:#6b7280;border:1px solid transparent;";
                    $h = "onmouseover=\"this.style.background='rgba(255,255,255,.04)';this.style.color='#94a3b8';\" onmouseout=\"this.style.background='transparent';this.style.color='#6b7280';\"";
                }
                $ico = $active ? "color:#a78bfa;" : "color:#4b5563;";
                return "<a href=\"".route($route)."\" style=\"display:flex;align-items:center;gap:0.7rem;padding:0.55rem 0.75rem;border-radius:8px;text-decoration:none;font-size:0.845rem;font-weight:".($active?'600':'500').";margin-bottom:2px;transition:all .15s;{$s}\" {$h}><span style=\"flex-shrink:0;{$ico}\">{$svg}</span>{$label}</a>";
            };

            $ic = [
                'dash'   => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>',
                'stats'  => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>',
                'img'    => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/></svg>',
                'cat'    => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><circle cx="7" cy="7" r="1" fill="currentColor" stroke="none"/></svg>',
                'col'    => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4" fill="currentColor" stroke="none" opacity=".4"/><path d="M12 2v4M12 18v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M2 12h4M18 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>',
                'price'  => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 000 4h4a2 2 0 010 4H8M12 6v2m0 8v2"/></svg>',
                'orders' => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
                'users'  => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
                'staff'  => '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
            ];
        @endphp

        <p style="color:#2d2d4e;font-size:0.63rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;padding:0 0.75rem 0.5rem;margin:0;">Geral</p>
        {!! $ni('admin.dashboard', $ic['dash'],  'Dashboard',    'admin.dashboard') !!}
        {!! $ni('admin.statistics', $ic['stats'], 'Estatísticas', 'admin.statistics') !!}

        <div style="height:1px;background:#12122a;margin:0.875rem 0 0.75rem;"></div>
        <p style="color:#2d2d4e;font-size:0.63rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;padding:0 0.75rem 0.5rem;margin:0;">Catálogo</p>
        {!! $ni('admin.catalog',    $ic['img'],   'Imagens',      'admin.catalog') !!}
        {!! $ni('admin.categories', $ic['cat'],   'Categorias',   'admin.categories') !!}
        {!! $ni('admin.colors',     $ic['col'],   'Cores',        'admin.colors') !!}
        {!! $ni('admin.prices',     $ic['price'], 'Preços',       'admin.prices') !!}

        <div style="height:1px;background:#12122a;margin:0.875rem 0 0.75rem;"></div>
        <p style="color:#2d2d4e;font-size:0.63rem;text-transform:uppercase;letter-spacing:.12em;font-weight:700;padding:0 0.75rem 0.5rem;margin:0;">Operações</p>
        {!! $ni('admin.orders',    $ic['orders'], 'Encomendas',  'admin.orders*') !!}
        {!! $ni('admin.customers', $ic['users'],  'Clientes',    'admin.customers') !!}
        {!! $ni('admin.staff',     $ic['staff'],  'Funcionários','admin.staff') !!}
    </nav>

    {{-- User / Logout --}}
    <div style="padding:0.875rem;border-top:1px solid #13132a;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.75rem;background:#0c0c1a;border-radius:9px;border:1px solid #13132a;margin-bottom:0.5rem;">
            @if(auth()->user()->photo_url)
                <img src="{{ Storage::url(auth()->user()->photo_url) }}" style="width:30px;height:30px;border-radius:8px;object-fit:cover;flex-shrink:0;">
            @else
                <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#7c3aed,#4c1d95);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;color:white;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="min-width:0;flex:1;">
                <div style="color:#e2e8f0;font-size:0.80rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div style="color:#7c3aed;font-size:0.63rem;font-weight:600;letter-spacing:.04em;margin-top:1px;">Administrador</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="display:flex;align-items:center;gap:0.55rem;width:100%;padding:0.48rem 0.75rem;background:transparent;border:none;color:#6b7280;font-size:0.82rem;cursor:pointer;border-radius:7px;transition:all .15s;font-family:inherit;"
                    onmouseover="this.style.background='rgba(239,68,68,.08)';this.style.color='#f87171'"
                    onmouseout="this.style.background='transparent';this.style.color='#6b7280'">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Terminar sessão
            </button>
        </form>
    </div>
</aside>

{{-- ══════════ MAIN CONTENT ══════════ --}}
<div style="margin-left:242px; flex:1; min-height:100vh; display:flex; flex-direction:column;">

    {{-- Topbar --}}
    <header style="background:#05050b;border-bottom:1px solid #13132a;padding:0 1.75rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:30;">
        <div style="display:flex;align-items:center;gap:0.6rem;">
            <span style="color:#2d2d4e;font-size:0.9rem;font-weight:300;">FunShirt</span>
            <svg width="14" height="14" fill="none" stroke="#2d2d4e" viewBox="0 0 24 24" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span style="color:#c4b5fd;font-size:0.9rem;font-weight:600;letter-spacing:-.01em;">{{ $title ?? 'Dashboard' }}</span>
        </div>
        <a href="{{ route('catalog') }}"
           style="display:inline-flex;align-items:center;gap:0.45rem;color:#6b7280;text-decoration:none;font-size:0.80rem;padding:0.40rem 0.95rem;border:1px solid #13132a;border-radius:7px;transition:all .18s;"
           onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'"
           onmouseout="this.style.borderColor='#13132a';this.style.color='#6b7280'">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Ver loja
        </a>
    </header>

    {{-- Content --}}
    <main style="flex:1;padding:1.75rem;">
        {{ $slot }}
    </main>
</div>

@fluxScripts
@livewireScripts
</body>
</html>
