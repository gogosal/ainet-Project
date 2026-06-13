@extends('layouts.app', ['title' => 'Carrinho'])

@section('content')
    <div>
        <div class="mb-8">
            <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-fs-muted mb-2">Carrinho</div>
            <h1 class="text-[1.6rem] font-light tracking-tight text-fs-dark m-0">A tua <em
                    class="italic font-bold">selecção.</em></h1>
        </div>

        @if (empty($items))
            <div class="text-center py-20 px-8 border border-fs-border rounded-[2px] bg-fs-bg">
                <svg class="w-12 h-12 mx-auto mb-4 stroke-[#d8d5d0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="text-fs-gray text-base font-normal mb-2">O teu carrinho está vazio</h2>
                <p class="text-fs-muted text-[0.82rem] mb-6">Explora o catálogo e escolhe os teus designs.</p>
                <a href="{{ route('catalog') }}"
                    class="inline-block bg-fs-dark text-fs-light no-underline px-6 py-[0.65rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                    Ver catálogo →
                </a>
            </div>
        @else
            <div class="grid gap-6 items-start" style="grid-template-columns:1fr 300px;">

                {{-- Items --}}
                <div>
                    <div class="flex justify-end mb-3">
                        <form method="POST" action="{{ route('cart.clear') }}"
                            onsubmit="return confirm('Tens a certeza que queres limpar o carrinho?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-transparent text-fs-muted border-none text-[0.68rem] font-semibold tracking-[0.1em] uppercase cursor-pointer transition-colors duration-150 font-[inherit] hover:text-fs-red">
                                Limpar tudo
                            </button>
                        </form>
                    </div>

                    <div class="flex flex-col gap-3">
                        @foreach ($items as $item)
                            <div
                                class="bg-white border border-fs-border rounded-[2px] p-[1.1rem] flex gap-[1.1rem] items-start">
                                <div class="shrink-0">
                                    <x-tshirt-preview :colorCode="$item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="76px" />
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-fs-dark text-[0.9rem] font-semibold mt-0 mb-3">
                                        {{ $item['image']?->name ?? 'Design removido' }}
                                    </h3>

                                    <form method="POST" action="{{ route('cart.update', $item['index']) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="grid gap-3 items-end" style="grid-template-columns:1fr 1fr auto;">
                                            <div>
                                                <label
                                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.4rem]">Cor</label>
                                                <select name="color_code" onchange="this.form.submit()"
                                                    class="w-full bg-transparent border-none border-b border-fs-mid py-[0.35rem] text-fs-dark text-[0.8rem] outline-none font-[inherit] cursor-pointer">
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->code }}"
                                                            {{ $item['color_code'] === $color->code ? 'selected' : '' }}>
                                                            {{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.4rem]">Tamanho</label>
                                                <select name="size" onchange="this.form.submit()"
                                                    class="w-full bg-transparent border-none border-b border-fs-mid py-[0.35rem] text-fs-dark text-[0.8rem] outline-none font-[inherit] cursor-pointer">
                                                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $s)
                                                        <option value="{{ $s }}"
                                                            {{ $item['size'] === $s ? 'selected' : '' }}>{{ $s }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.4rem]">Qtd</label>
                                                <div class="flex items-center gap-[0.3rem]">
                                                    <input type="hidden" name="qty" id="qty-{{ $item['index'] }}"
                                                        value="{{ $item['qty'] }}">
                                                    <button type="button"
                                                        onclick="var i=document.getElementById('qty-{{ $item['index'] }}');var v=Math.max(0,parseInt(i.value)-1);i.value=v;this.closest('form').submit();"
                                                        class="bg-transparent border border-[#d8d5d0] w-[26px] h-[26px] text-fs-gray cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark">
                                                        −
                                                    </button>
                                                    <span
                                                        class="text-fs-dark font-semibold min-w-[2rem] text-center text-[0.82rem]">{{ $item['qty'] }}</span>
                                                    <button type="button"
                                                        onclick="var i=document.getElementById('qty-{{ $item['index'] }}');var v=Math.min(99,parseInt(i.value)+1);i.value=v;this.closest('form').submit();"
                                                        class="bg-transparent border border-[#d8d5d0] w-[26px] h-[26px] text-fs-gray cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="flex flex-col items-end gap-[0.4rem] shrink-0">
                                    <span
                                        class="{{ $item['has_discount'] ? 'text-fs-green' : 'text-fs-dark' }} font-bold text-[0.95rem]">
                                        €{{ number_format($item['sub_total'], 2) }}
                                    </span>
                                    <span class="text-fs-muted text-[0.7rem]">{{ $item['qty'] }}×
                                        €{{ number_format($item['unit_price'], 2) }}</span>
                                    @if ($item['has_discount'])
                                        <span class="text-fs-green text-[0.68rem]">✓ Desc.</span>
                                    @endif
                                    <form method="POST" action="{{ route('cart.destroy', $item['index']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-transparent border-none text-[#c8c4be] cursor-pointer text-[0.72rem] font-semibold tracking-[0.08em] uppercase p-[0.2rem] font-[inherit] transition-colors duration-150 hover:text-fs-red">
                                            Remover
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Summary --}}
                <div class="bg-fs-bg border border-fs-border rounded-[2px] p-[1.35rem] sticky top-[72px]">
                    <div class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[1.1rem]">Resumo</div>

                    <div class="flex flex-col gap-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-fs-gray text-[0.82rem]">Artigos ({{ count($items) }})</span>
                            <span class="text-fs-dark text-[0.82rem]">€{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-fs-gray text-[0.82rem]">Envio</span>
                            <span class="text-fs-green text-[0.82rem]">Grátis</span>
                        </div>
                    </div>

                    <div class="border-t border-fs-border pt-3 mb-5">
                        <div class="flex justify-between items-baseline">
                            <span class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-gray">Total</span>
                            <span class="text-fs-dark font-bold text-[1.15rem]">€{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @auth
                        @if (auth()->user()->isClient())
                            <a href="{{ route('checkout') }}"
                                class="block text-center bg-fs-dark text-fs-light no-underline py-[0.7rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] transition-colors duration-150 mb-[0.6rem] hover:bg-[#333]">
                                Finalizar compra →
                            </a>
                        @else
                            <p class="text-fs-muted text-[0.75rem] text-center mb-3">Só clientes podem fazer encomendas.</p>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-center bg-fs-dark text-fs-light no-underline py-[0.7rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] mb-[0.6rem] hover:bg-[#333]">
                            Entrar para finalizar →
                        </a>
                        <p class="text-[#c8c4be] text-[0.7rem] text-center mb-3">O carrinho mantém-se após o login.</p>
                    @endauth

                    <a href="{{ route('catalog') }}"
                        class="block text-center text-fs-muted no-underline text-[0.7rem] font-semibold tracking-[0.08em] uppercase py-[0.4rem] transition-colors duration-150 hover:text-fs-purple">
                        ← Continuar a comprar
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
