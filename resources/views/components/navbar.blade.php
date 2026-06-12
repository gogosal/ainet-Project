@props(['cartCount' => 0])

<header class="bg-[#f5f4f1] border-b border-[#e0ddd8] sticky top-0 z-50">
    <div class="max-w-[1280px] mx-auto px-8 h-14 flex items-center gap-8">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
            class="text-xs font-bold tracking-[.22em] uppercase text-[#1a1a1a] no-underline shrink-0">
            Funshirt
        </a>

        {{-- Nav Links --}}
        <nav class="flex gap-7 ml-2">
            <a href="{{ route('catalog') }}"
                class="text-[.7rem] font-semibold tracking-[.1em] uppercase no-underline transition-colors duration-150 hover:text-[#1a1a1a] {{ request()->routeIs('catalog') ? 'text-[#7c6fa0]' : 'text-[#aaa8a3]' }}">
                Catálogo
            </a>

            <a href="{{ route('view3d') }}"
                class="text-[.7rem] font-semibold tracking-[.1em] uppercase no-underline transition-colors duration-150 hover:text-[#1a1a1a] {{ request()->routeIs('view3d') ? 'text-[#7c6fa0]' : 'text-[#aaa8a3]' }}">
                Provador 3D
            </a>

            @auth
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
                    class="text-[.7rem] font-semibold tracking-[.1em] uppercase no-underline transition-colors duration-150 hover:text-[#1a1a1a] {{ $ordersActive ? 'text-[#7c6fa0]' : 'text-[#aaa8a3]' }}">
                    Encomendas
                </a>

                @if (auth()->user()->isClient())
                    <a href="{{ route('my-images') }}"
                        class="text-[.7rem] font-semibold tracking-[.1em] uppercase no-underline transition-colors duration-150 hover:text-[#1a1a1a] {{ request()->routeIs('my-images') ? 'text-[#7c6fa0]' : 'text-[#aaa8a3]' }}">
                        Imagens
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Right Menu --}}
        <div class="ml-auto flex items-center gap-5" x-data>

            {{-- Cart button --}}
            <div class="relative">
                <button @click="$dispatch('open-cart')"
                    class="relative inline-flex items-center justify-center w-9 h-9 bg-transparent border border-[#e0ddd8] rounded-full text-[#888] cursor-pointer transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if ($cartCount > 0)
                        <span
                            class="absolute -top-[5px] -right-[5px] bg-[#1a1a1a] text-[#f5f4f1] rounded-full w-4 h-4 flex items-center justify-center text-[9px] font-bold">
                            {{ $cartCount > 9 ? '9+' : $cartCount }}
                        </span>
                    @endif
                </button>
            </div>

            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-[.45rem] bg-transparent border border-[#e0ddd8] rounded-full px-3 py-[.3rem] cursor-pointer text-[#1a1a1a] text-xs font-[inherit] transition-colors duration-150 hover:border-[#1a1a1a]">
                        @if (auth()->user()->photo_url)
                            @php
                                $pUrl = auth()->user()->photo_url;
                                $pSrc = str_contains($pUrl, '/')
                                    ? asset('storage/' . $pUrl)
                                    : asset('storage/photos/' . $pUrl);
                            @endphp
                            <img src="{{ $pSrc }}" class="w-5 h-5 rounded-full object-cover"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex'">
                            <span
                                class="hidden w-5 h-5 rounded-full bg-[#1a1a1a] items-center justify-center text-[.62rem] text-[#f5f4f1] font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @else
                            <span
                                class="w-5 h-5 rounded-full bg-[#1a1a1a] inline-flex items-center justify-center text-[.62rem] text-[#f5f4f1] font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                        <span class="font-medium">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <svg class="w-2.5 h-2.5 text-[#aaa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition x-cloak
                        class="absolute right-0 top-[calc(100%+6px)] bg-[#f5f4f1] border border-[#e0ddd8] rounded-[2px] p-1.5 min-w-[148px] z-[100] shadow-[0_8px_24px_rgba(0,0,0,.08)]">
                        <a href="{{ route('profile') }}"
                            class="block px-3 py-2 text-[#888] no-underline text-xs font-medium tracking-[.04em] transition-colors duration-150 hover:text-[#1a1a1a]">Perfil</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-3 py-2 text-[#888] no-underline text-xs font-medium tracking-[.04em] transition-colors duration-150 hover:text-[#1a1a1a]">Dashboard</a>
                        @elseif(auth()->user()->isEmployee())
                            <a href="{{ route('employee.orders') }}"
                                class="block px-3 py-2 text-[#888] no-underline text-xs font-medium tracking-[.04em] transition-colors duration-150 hover:text-[#1a1a1a]">Encomendas</a>
                        @endif
                        <div class="h-px bg-[#e0ddd8] my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-3 py-2 text-[#c0392b] bg-transparent border-none text-xs font-medium tracking-[.04em] cursor-pointer font-[inherit] transition-colors duration-150 hover:text-[#922b21]">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="text-[.7rem] font-semibold tracking-[.1em] uppercase text-[#888] no-underline transition-colors duration-150 hover:text-[#1a1a1a]">Entrar</a>
                <a href="{{ route('register') }}"
                    class="text-[.7rem] font-bold tracking-[.1em] uppercase text-[#f5f4f1] bg-[#1a1a1a] no-underline px-4 py-2 rounded-[1px] transition-colors duration-150 hover:bg-[#333]">Registar</a>
            @endauth
        </div>
    </div>
</header>
