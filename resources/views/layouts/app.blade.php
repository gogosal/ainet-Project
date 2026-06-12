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
            <div style="margin-left:auto;display:flex;align-items:center;gap:1.25rem;" x-data>

                {{-- Cart button --}}
                <div style="position:relative;">
                    <button @click="$dispatch('open-cart')"
                            style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:transparent;border:1px solid #e0ddd8;border-radius:999px;color:#888;cursor:pointer;transition:all .15s;"
                            onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
                            onmouseout="this.style.borderColor='#e0ddd8';this.style.color='#888'">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if($cartCount > 0)
                        <span style="position:absolute;top:-5px;right:-5px;background:#1a1a1a;color:#f5f4f1;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;">
                            {{ $cartCount > 9 ? '9+' : $cartCount }}
                        </span>
                        @endif
                    </button>
                </div>

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
    <div x-data="{ open: false }" @open-cart.window="open = true">
        {{-- Backdrop --}}
        <div x-show="open" @click="open = false"
             style="position:fixed;inset:0;background:rgba(26,26,26,.35);z-index:150;"></div>

        {{-- Drawer --}}
        <div :style="{ transform: open ? 'translateX(0)' : 'translateX(100%)' }"
             style="position:fixed;top:0;right:0;height:100vh;width:360px;max-width:100vw;background:#f5f4f1;border-left:1px solid #e0ddd8;z-index:160;display:flex;flex-direction:column;transition:transform .25s ease;">

            {{-- Header --}}
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #e0ddd8;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:.6rem;">
                    <span style="font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#1a1a1a;">Carrinho</span>
                    @if($cartCount > 0)
                        <span style="background:#1a1a1a;color:#f5f4f1;border-radius:999px;padding:1px 7px;font-size:.65rem;font-weight:700;">{{ $cartCount }}</span>
                    @endif
                </div>
                <button @click="open = false"
                        style="background:transparent;border:none;color:#aaa;cursor:pointer;font-size:1.2rem;line-height:1;padding:.25rem;transition:color .15s;"
                        onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">×</button>
            </div>

            {{-- Items --}}
            <div style="flex:1;overflow-y:auto;padding:1rem 1.5rem;">
                @if(empty($cartEnrichedItems))
                    <div style="text-align:center;padding:3.5rem 1rem;color:#b8b4ae;">
                        <svg style="width:40px;height:40px;margin:0 auto 1rem;display:block;stroke:#d8d5d0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p style="font-size:.82rem;margin:0 0 .2rem;color:#888;">Carrinho vazio</p>
                        <p style="font-size:.75rem;margin:0;color:#c8c4be;">Explora o catálogo.</p>
                    </div>
                @else
                    <div style="display:flex;flex-direction:column;gap:.6rem;">
                        @foreach($cartEnrichedItems as $item)
                            <div style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;padding:.85rem;display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="flex-shrink:0;">
                                    <x-tshirt-preview
                                        :colorCode="$item['color_code']"
                                        :imageUrl="$item['image'] ? $item['image']->image_url : null"
                                        size="52px" />
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <p style="color:#1a1a1a;font-size:.82rem;font-weight:600;margin:0 0 .2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $item['image']?->name ?? 'Design removido' }}
                                    </p>
                                    <p style="color:#aaa8a3;font-size:.72rem;margin:0 0 .4rem;">
                                        {{ $item['color']?->name ?? $item['color_code'] }} · {{ $item['size'] }}
                                    </p>
                                    <div style="display:flex;align-items:center;justify-content:space-between;">
                                        <span style="color:{{ $item['has_discount'] ? '#2d6a4f' : '#1a1a1a' }};font-weight:700;font-size:.82rem;">
                                            €{{ number_format($item['sub_total'], 2) }}
                                        </span>
                                        <span style="color:#b8b4ae;font-size:.7rem;">{{ $item['qty'] }}× €{{ number_format($item['unit_price'], 2) }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('cart.destroy', $item['index']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="background:transparent;border:none;color:#c8c4be;cursor:pointer;padding:.15rem;flex-shrink:0;font-size:.95rem;line-height:1;transition:color .15s;"
                                            onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#c8c4be'"
                                            title="Remover">×</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            @if(!empty($cartEnrichedItems))
            <div style="padding:1rem 1.5rem;border-top:1px solid #e0ddd8;flex-shrink:0;background:#eeecea;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:1rem;">
                    <span style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#aaa8a3;">Total</span>
                    <span style="color:#1a1a1a;font-weight:700;font-size:1.1rem;">€{{ number_format($cartTotal, 2) }}</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:.5rem;">
                    <a href="{{ route('cart') }}" @click="open = false"
                       style="display:block;text-align:center;background:transparent;color:#888;text-decoration:none;border:1px solid #d8d5d0;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;transition:all .15s;"
                       onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                        Ver carrinho
                    </a>
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('checkout') }}" @click="open = false"
                               style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;transition:background .15s;"
                               onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                                Finalizar compra →
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" @click="open = false"
                           style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;"
                           onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                            Entrar para finalizar →
                        </a>
                    @endauth
                </div>
            </div>
            @endif
        </div>
    </div>

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
