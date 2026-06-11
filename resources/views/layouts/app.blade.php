<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'FunShirt' }} — Funshirt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            background: #f5f4f1;
            color: #1a1a1a;
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            margin: 0;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <header style="background:#f5f4f1;border-bottom:1px solid #e0ddd8;position:sticky;top:0;z-index:50;">
        <div style="max-width:1280px;margin:0 auto;padding:0 2rem;height:56px;display:flex;align-items:center;gap:2rem;">

            {{-- Logo --}}
            <a href="{{ route('catalog') }}"
               style="font-size:.75rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#1a1a1a;text-decoration:none;flex-shrink:0;">
                Funshirt
            </a>

            {{-- Nav --}}
            <nav style="display:flex;gap:1.75rem;margin-left:.5rem;">
                <a href="{{ route('catalog') }}"
                   style="font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;color:{{ request()->routeIs('catalog') ? '#7c6fa0' : '#aaa8a3' }};transition:color .15s;"
                   onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='{{ request()->routeIs('catalog') ? '#7c6fa0' : '#aaa8a3' }}'">
                    Catálogo
                </a>

                <a href="{{ route('try-on') }}"
                   style="font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;color:{{ request()->routeIs('try-on') ? '#7c6fa0' : '#aaa8a3' }};transition:color .15s;"
                   onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='{{ request()->routeIs('try-on') ? '#7c6fa0' : '#aaa8a3' }}'">
                    Provador 3D
                </a>

                @auth
                    @php
                        $ordersRoute = match (auth()->user()->user_type) {
                            'A' => route('admin.orders'),
                            'F' => route('employee.orders'),
                            default => route('orders.index'),
                        };
                        $ordersActive = request()->routeIs('orders*') || request()->routeIs('admin.orders') || request()->routeIs('employee.orders');
                    @endphp

                    <a href="{{ $ordersRoute }}"
                       style="font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;color:{{ $ordersActive ? '#7c6fa0' : '#aaa8a3' }};transition:color .15s;"
                       onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='{{ $ordersActive ? '#7c6fa0' : '#aaa8a3' }}'">
                        Encomendas
                    </a>

                    @if (auth()->user()->isAdmin() || auth()->user()->isEmployee())
                        <a href="{{ route('my-images') }}"
                           style="font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;text-decoration:none;color:{{ request()->routeIs('my-images') ? '#7c6fa0' : '#aaa8a3' }};transition:color .15s;"
                           onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='{{ request()->routeIs('my-images') ? '#7c6fa0' : '#aaa8a3' }}'">
                            Imagens
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- Right --}}
            <div style="margin-left:auto;display:flex;align-items:center;gap:1.25rem;">

                <livewire:cart.cart-button />

                @auth
                    <div x-data="{ open: false }" style="position:relative;">
                        <button @click="open = !open"
                                style="display:flex;align-items:center;gap:.45rem;background:transparent;border:1px solid #e0ddd8;border-radius:999px;padding:.3rem .75rem;cursor:pointer;color:#1a1a1a;font-size:.75rem;font-family:inherit;transition:border-color .15s;"
                                onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#e0ddd8'">
                            @if (auth()->user()->photo_url)
                                @php
                                    $pUrl = auth()->user()->photo_url;
                                    $pSrc = str_contains($pUrl, '/') ? asset('storage/'.$pUrl) : asset('storage/photos/'.$pUrl);
                                @endphp
                                <img src="{{ $pSrc }}"
                                     style="width:20px;height:20px;border-radius:50%;object-fit:cover;"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                                <span style="display:none;width:20px;height:20px;border-radius:50%;background:#1a1a1a;align-items:center;justify-content:center;font-size:.62rem;color:#f5f4f1;font-weight:700;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @else
                                <span style="width:20px;height:20px;border-radius:50%;background:#1a1a1a;display:inline-flex;align-items:center;justify-content:center;font-size:.62rem;color:#f5f4f1;font-weight:700;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                            <span style="font-weight:500;">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <svg style="width:10px;height:10px;color:#aaa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             style="position:absolute;right:0;top:calc(100% + 6px);background:#f5f4f1;border:1px solid #e0ddd8;border-radius:2px;padding:.4rem;min-width:148px;z-index:100;box-shadow:0 8px 24px rgba(0,0,0,.08);">
                                <a href="{{ route('profile') }}"
                                   style="display:block;padding:.45rem .7rem;color:#888;text-decoration:none;font-size:.75rem;font-weight:500;letter-spacing:.04em;transition:color .15s;"
                                   onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#888'">Perfil</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   style="display:block;padding:.45rem .7rem;color:#888;text-decoration:none;font-size:.75rem;font-weight:500;letter-spacing:.04em;transition:color .15s;"
                                   onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#888'">Dashboard</a>
                            @elseif(auth()->user()->isEmployee())
                                <a href="{{ route('employee.orders') }}"
                                   style="display:block;padding:.45rem .7rem;color:#888;text-decoration:none;font-size:.75rem;font-weight:500;letter-spacing:.04em;transition:color .15s;"
                                   onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#888'">Encomendas</a>
                            @endif
                            <div style="height:1px;background:#e0ddd8;margin:.3rem 0;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        style="display:block;width:100%;text-align:left;padding:.45rem .7rem;color:#c0392b;background:transparent;border:none;font-size:.75rem;font-weight:500;letter-spacing:.04em;cursor:pointer;font-family:inherit;transition:color .15s;"
                                        onmouseover="this.style.color='#922b21'" onmouseout="this.style.color='#c0392b'">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       style="font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#888;text-decoration:none;transition:color .15s;"
                       onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#888'">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#f5f4f1;background:#1a1a1a;text-decoration:none;padding:.45rem 1rem;border-radius:1px;transition:background .15s;"
                       onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                        Registar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Cart Drawer --}}
    <livewire:cart.cart-drawer />

    {{-- Main content --}}
    <main style="max-width:1280px;margin:0 auto;padding:2.5rem 2rem;">
        {{ $slot ?? '' }}
        @yield('content')
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
