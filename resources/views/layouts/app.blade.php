<!DOCTYPE html>
<html lang="pt" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'FunShirt' }} — FunShirt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background:#0a0a0f; color:#e2e8f0; font-family:'Inter',sans-serif; min-height:100vh;">

    {{-- Header --}}
    <header style="background:#111120; border-bottom:1px solid #1e1e30; position:sticky; top:0; z-index:50;">
        <div
            style="max-width:1280px; margin:0 auto; padding:0 1.5rem; height:64px; display:flex; align-items:center; gap:1.5rem;">

            {{-- Logo --}}
            <a href="{{ route('catalog') }}"
                style="display:flex; align-items:center; gap:0.6rem; text-decoration:none; flex-shrink:0;">
                <div style="width:32px;height:32px;background:linear-gradient(135deg,#7c3aed,#5b21b6);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(124,58,237,.35);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                    </svg>
                </div>
                <span style="color:#e2e8f0; font-weight:700; font-size:1.1rem; letter-spacing:-0.025em;">FunShirt</span>
            </a>

            {{-- Nav --}}
            <nav style="display:flex; gap:1.5rem; margin-left:1rem;">
                <a href="{{ route('catalog') }}"
                    style="color:{{ request()->routeIs('catalog') ? '#a78bfa' : '#94a3b8' }}; text-decoration:none; font-size:0.9rem; font-weight:500; transition:color .2s;"
                    onmouseover="this.style.color='#e2e8f0'"
                    onmouseout="this.style.color='{{ request()->routeIs('catalog') ? '#a78bfa' : '#94a3b8' }}'">
                    Catálogo
                </a>

                <a href="{{ route('try-on') }}"
                    style="color:{{ request()->routeIs('try-on') ? '#a78bfa' : '#94a3b8' }}; text-decoration:none; font-size:0.9rem; font-weight:500; transition:color .2s; display:inline-flex; align-items:center; gap:0.3rem;"
                    onmouseover="this.style.color='#e2e8f0'"
                    onmouseout="this.style.color='{{ request()->routeIs('try-on') ? '#a78bfa' : '#94a3b8' }}'">
                    <span style="font-size:0.85rem;">◈</span> Provador 3D
                </a>

                @auth
                    {{-- Lógica para o botão de Encomendas (Todos veem, mas com links diferentes) --}}
                    @php
                        $ordersRoute = match (auth()->user()->user_type) {
                            'A' => route('admin.orders'),
                            'F' => route('employee.orders'),
                            default => route('orders.index'),
                        };
                        $ordersActive =
                            request()->routeIs('orders*') ||
                            request()->routeIs('admin.orders') ||
                            request()->routeIs('employee.orders');
                    @endphp

                    <a href="{{ $ordersRoute }}"
                        style="color:{{ $ordersActive ? '#a78bfa' : '#94a3b8' }}; text-decoration:none; font-size:0.9rem; font-weight:500;"
                        onmouseover="this.style.color='#e2e8f0'"
                        onmouseout="this.style.color='{{ $ordersActive ? '#a78bfa' : '#94a3b8' }}'">
                        As minhas encomendas
                    </a>

                    {{-- Lógica para o botão As Minhas Imagens (Apenas Funciona para A e F) --}}
                    @if (auth()->user()->isAdmin() || auth()->user()->isEmployee())
                        <a href="{{ route('my-images') }}"
                            style="color:{{ request()->routeIs('my-images') ? '#a78bfa' : '#94a3b8' }}; text-decoration:none; font-size:0.9rem; font-weight:500;"
                            onmouseover="this.style.color='#e2e8f0'"
                            onmouseout="this.style.color='{{ request()->routeIs('my-images') ? '#a78bfa' : '#94a3b8' }}'">
                            As minhas imagens
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- Right side --}}
            <div style="margin-left:auto; display:flex; align-items:center; gap:1rem;">

                {{-- Cart button --}}
                <livewire:cart.cart-button />

                {{-- User menu --}}
                @auth
                    <div x-data="{ open: false }" style="position:relative;">
                        <button @click="open = !open"
                            style="display:flex; align-items:center; gap:0.5rem; background:transparent; border:1px solid #1e1e30; border-radius:9999px; padding:0.35rem 0.75rem; cursor:pointer; color:#e2e8f0; font-size:0.85rem;">
                            @if (auth()->user()->photo_url)
                                <img src="{{ Storage::url(auth()->user()->photo_url) }}"
                                    style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                            @else
                                <span
                                    style="width:24px;height:24px;border-radius:50%;background:#7c3aed;display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;color:white;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                            <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <svg style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            style="position:absolute;right:0;top:calc(100%+8px);background:#111120;border:1px solid #1e1e30;border-radius:8px;padding:0.5rem;min-width:160px;z-index:100;box-shadow:0 20px 40px rgba(0,0,0,.5);">
                            @if (auth()->user()->isClient())
                                <a href="{{ route('profile') }}"
                                    style="display:block;padding:0.5rem 0.75rem;color:#94a3b8;text-decoration:none;border-radius:6px;font-size:0.85rem;transition:all .15s;"
                                    onmouseover="this.style.background='#1a1a2e';this.style.color='#e2e8f0'"
                                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">Perfil</a>
                            @elseif(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    style="display:block;padding:0.5rem 0.75rem;color:#94a3b8;text-decoration:none;border-radius:6px;font-size:0.85rem;transition:all .15s;"
                                    onmouseover="this.style.background='#1a1a2e';this.style.color='#e2e8f0'"
                                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">Dashboard</a>
                            @elseif(auth()->user()->isEmployee())
                                <a href="{{ route('employee.orders') }}"
                                    style="display:block;padding:0.5rem 0.75rem;color:#94a3b8;text-decoration:none;border-radius:6px;font-size:0.85rem;transition:all .15s;"
                                    onmouseover="this.style.background='#1a1a2e';this.style.color='#e2e8f0'"
                                    onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">Encomendas</a>
                            @endif
                            <div style="height:1px;background:#1e1e30;margin:0.4rem 0;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    style="display:block;width:100%;text-align:left;padding:0.5rem 0.75rem;color:#f87171;background:transparent;border:none;border-radius:6px;font-size:0.85rem;cursor:pointer;transition:all .15s;"
                                    onmouseover="this.style.background='rgba(239,68,68,.1)'"
                                    onmouseout="this.style.background='transparent'">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        style="color:#94a3b8;text-decoration:none;font-size:0.9rem;font-weight:500;padding:0.4rem 1rem;border:1px solid #1e1e30;border-radius:6px;transition:all .2s;"
                        onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'"
                        onmouseout="this.style.borderColor='#1e1e30';this.style.color='#94a3b8'">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                        style="color:white;text-decoration:none;font-size:0.9rem;font-weight:500;padding:0.4rem 1rem;background:#7c3aed;border-radius:6px;transition:all .2s;"
                        onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                        Registar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Cart Drawer (always present) --}}
    <livewire:cart.cart-drawer />

    {{-- Main content --}}
    <main style="max-width:1280px; margin:0 auto; padding:2rem 1.5rem;">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @fluxScripts
    @livewireScripts
</body>

</html>
