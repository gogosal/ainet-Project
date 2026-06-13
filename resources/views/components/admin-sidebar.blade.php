<aside
    class="w-[232px] bg-[#eeecea] border-r border-[#e0ddd8] h-screen fixed top-0 left-0 flex flex-col z-40 overflow-y-auto">

    {{-- Brand --}}
    <div class="h-[52px] px-5 border-b border-[#e0ddd8] flex items-center shrink-0">
        <a href="{{ route('catalog') }}" class="no-underline flex flex-col gap-[1px]">
            <span class="text-[.72rem] font-bold tracking-[.2em] uppercase text-[#1a1a1a]">Funshirt</span>
            <span class="text-[.58rem] font-bold tracking-[.14em] uppercase text-[#7c6fa0]">Admin</span>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="p-4 flex-1">
        <div
            class="text-[.58rem] font-bold tracking-[.16em] uppercase text-[#c8c4be] px-[.7rem] pt-[.2rem] pb-[.55rem] m-0">
            Geral</div>
        @foreach ($menuGeral as $item)
            <x-nav-link :item="$item" />
        @endforeach

        <div class="h-px bg-[#e0ddd8] my-3"></div>
        <div
            class="text-[.58rem] font-bold tracking-[.16em] uppercase text-[#c8c4be] px-[.7rem] pt-[.2rem] pb-[.55rem] m-0">
            Catálogo</div>
        @foreach ($menuCatalogo as $item)
            <x-nav-link :item="$item" />
        @endforeach

        <div class="h-px bg-[#e0ddd8] my-3"></div>
        <div
            class="text-[.58rem] font-bold tracking-[.16em] uppercase text-[#c8c4be] px-[.7rem] pt-[.2rem] pb-[.55rem] m-0">
            Operações</div>
        @foreach ($menuOperacoes as $item)
            <x-nav-link :item="$item" />
        @endforeach
    </nav>

    {{-- User Section Corrigida --}}
    @if ($userName)
        <div class="p-3 border-t border-[#e0ddd8] shrink-0">
            <div
                class="flex items-center gap-[.65rem] px-[.7rem] py-[.6rem] bg-[#f5f4f1] border border-[#e0ddd8] rounded-[1px] mb-[.4rem]">
                @if ($profileImageSrc)
                    <img src="{{ $profileImageSrc }}" class="w-7 h-7 rounded-[1px] object-cover shrink-0"
                        onerror="this.style.display='none'">
                @else
                    <div
                        class="w-7 h-7 rounded-[1px] bg-[#1a1a1a] flex items-center justify-center text-[.65rem] font-bold text-[#f5f4f1] shrink-0">
                        {{ strtoupper(substr($userName, 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div
                        class="text-[#1a1a1a] text-[.78rem] font-semibold whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ $userName }}
                    </div>
                    <div class="text-[#7c6fa0] text-[.6rem] font-bold tracking-[.1em] uppercase mt-[1px]">Administrador
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 w-full px-[.7rem] py-[.42rem] bg-transparent border-none text-[#b8b4ae] text-[.72rem] font-semibold tracking-[.04em] cursor-pointer rounded-[1px] transition-colors duration-150 font-[inherit] hover:text-[#c0392b]">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Terminar sessão
                </button>
            </form>
        </div>
    @endif
</aside>
