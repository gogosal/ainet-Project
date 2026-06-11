@extends('layouts.app', ['title' => 'FunShirt'])
@section('content')
@php
    $featuredDesigns = App\Models\TshirtImage::whereNull('customer_id')->with('category')->take(4)->get();
    $totalDesigns = App\Models\TshirtImage::whereNull('customer_id')->count();
@endphp

<style>
    .product-link { text-decoration: none; display: block; }
    .product-link:hover .product-img-wrap { border-color: #7c6fa0; }
    .product-link:hover .product-name { color: #7c6fa0; }
</style>

{{-- ══ HERO ══ --}}
<section style="margin: -2.5rem -2rem 0; border-bottom: 1px solid #e0ddd8;">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 2rem; display: grid; grid-template-columns: 1fr 1fr; min-height: 82vh; align-items: stretch;">

        {{-- LEFT: Text --}}
        <div style="display: flex; flex-direction: column; justify-content: center; padding: 5rem 4rem 5rem 0; border-right: 1px solid #e0ddd8;">
            <div style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: 1.5rem;">
                {{ $totalDesigns }}+ designs · Provador 3D · Entrega rápida
            </div>

            <h1 style="font-size: clamp(2.6rem, 4vw, 4rem); font-weight: 300; line-height: 1.08; letter-spacing: -.04em; color: #1a1a1a; margin: 0 0 2rem;">
                T-shirts feitas<br>
                para <em style="font-weight: 700; font-style: italic;">ti.</em>
            </h1>

            <p style="color: #888; font-size: .95rem; line-height: 1.75; margin: 0 0 2.5rem; max-width: 380px;">
                Escolhe um design, experimenta no Provador 3D, personaliza cor e tamanho. Receberes em casa.
            </p>

            <div style="display: flex; gap: .75rem; align-items: center;">
                <a href="{{ route('catalog') }}"
                   style="font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #f5f4f1; background: #1a1a1a; text-decoration: none; padding: .75rem 1.75rem; border-radius: 1px; transition: background .15s;"
                   onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                    Ver Catálogo
                </a>
                <a href="{{ route('try-on') }}"
                   style="font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #888; text-decoration: none; padding: .75rem 1.5rem; border: 1px solid #d8d5d0; border-radius: 1px; transition: all .15s;"
                   onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                    Provador 3D
                </a>
            </div>
        </div>

        {{-- RIGHT: Product grid 2×2 --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; border-left: 1px solid #e0ddd8;">
            @foreach($featuredDesigns->take(4) as $i => $d)
                @php
                    $borders = '';
                    if ($i === 0) $borders = 'border-right:1px solid #e0ddd8; border-bottom:1px solid #e0ddd8;';
                    elseif ($i === 1) $borders = 'border-bottom:1px solid #e0ddd8;';
                    elseif ($i === 2) $borders = 'border-right:1px solid #e0ddd8;';
                @endphp
                <a href="{{ route('catalog') }}" class="product-link"
                   style="{{ $borders }} background: #f5f4f1; display: flex; align-items: center; justify-content: center; padding: 2rem; transition: background .2s;"
                   onmouseover="this.style.background='#eeecea'" onmouseout="this.style.background='#f5f4f1'">
                    <x-tshirt-preview :colorCode="'white'" :imageUrl="$d->image_url" size="120px" />
                </a>
            @endforeach
            @if($featuredDesigns->count() === 0)
                <div style="grid-column:1/-1; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:.8rem;">
                    Nenhum design disponível
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ══ STATS BAR ══ --}}
<section style="border-bottom: 1px solid #e0ddd8; margin: 0 -2rem;">
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); padding: 0 2rem;">
        @foreach([
            [$totalDesigns . '+', 'designs únicos'],
            ['6', 'cores disponíveis'],
            ['€15', 'preço desde'],
            ['3–5 dias', 'entrega em casa'],
        ] as $i => $s)
        <div style="padding: 1.25rem 0; text-align: center; {{ $i < 3 ? 'border-right: 1px solid #e0ddd8;' : '' }}">
            <div style="font-size: 1.35rem; font-weight: 700; letter-spacing: -.02em; color: #1a1a1a;">{{ $s[0] }}</div>
            <div style="color: #b8b4ae; font-size: .7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; margin-top: .15rem;">{{ $s[1] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ══ FEATURED PRODUCTS ══ --}}
@if($featuredDesigns->count() > 0)
<section style="padding: 5rem 0; border-bottom: 1px solid #e0ddd8; margin: 0 -2rem;">
    <div style="padding: 0 2rem;">

        <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 2.5rem;">
            <div>
                <div style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .4rem;">Em destaque</div>
                <h2 style="font-size: 1.75rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0;">Os mais <em style="font-weight: 700; font-style: italic;">populares.</em></h2>
            </div>
            <a href="{{ route('catalog') }}"
               style="font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #7c6fa0; text-decoration: none; transition: color .15s;"
               onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#7c6fa0'">
                Ver todos →
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; border: 1px solid #e0ddd8; border-radius: 2px; overflow: hidden;">
            @foreach($featuredDesigns as $i => $d)
            <a href="{{ route('catalog') }}" class="product-link"
               style="background: #fff; {{ $i < 3 ? 'border-right: 1px solid #e0ddd8;' : '' }} transition: background .15s;"
               onmouseover="this.style.background='#f9f8f6'" onmouseout="this.style.background='#fff'">
                <div class="product-img-wrap" style="background: #f5f4f1; padding: 2rem; display: flex; align-items: center; justify-content: center; height: 200px; border-bottom: 1px solid #e0ddd8; transition: border-color .15s;">
                    <x-tshirt-preview :colorCode="'white'" :imageUrl="$d->image_url" size="130px" />
                </div>
                <div style="padding: .9rem 1rem 1.1rem;">
                    @if($d->category)
                        <div style="font-size: .58rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #7c6fa0; margin-bottom: .3rem;">{{ $d->category->name }}</div>
                    @endif
                    <div class="product-name" style="color: #1a1a1a; font-size: .85rem; font-weight: 600; margin-bottom: .25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color .15s;">{{ $d->name }}</div>
                    <div style="color: #888; font-size: .8rem; font-weight: 500;">€15.00</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ HOW IT WORKS ══ --}}
<section style="padding: 5rem 0; border-bottom: 1px solid #e0ddd8; margin: 0 -2rem;">
    <div style="padding: 0 2rem; display: grid; grid-template-columns: 1fr 2fr; gap: 5rem; align-items: start;">

        <div>
            <div style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .9rem;">Processo</div>
            <h2 style="font-size: 1.75rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0 0 1.25rem;">Como <em style="font-weight: 700; font-style: italic;">funciona.</em></h2>
            <p style="color: #aaa; font-size: .85rem; line-height: 1.7; margin: 0;">Em três passos tens a tua t-shirt à porta — sem complicações.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border-left: 1px solid #e0ddd8;">
            @foreach([
                ['01', 'Escolhe o design', 'Explora o catálogo com mais de ' . $totalDesigns . ' designs. Filtra por categoria ou pesquisa o que queres.'],
                ['02', 'Experimenta em 3D', 'Usa o Provador 3D para escolher cor e tamanho. Vê o resultado real antes de encomendar.'],
                ['03', 'Recebe em casa', 'Checkout seguro. Recebes confirmação por e-mail e a t-shirt em 3 a 5 dias úteis.'],
            ] as $step)
            <div style="padding: 1.5rem; border-right: 1px solid #e0ddd8;">
                <div style="font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #c8c4be; margin-bottom: 1rem;">{{ $step[0] }}</div>
                <h3 style="font-size: .92rem; font-weight: 700; color: #1a1a1a; margin: 0 0 .6rem; letter-spacing: -.01em;">{{ $step[1] }}</h3>
                <p style="color: #aaa; font-size: .8rem; line-height: 1.65; margin: 0;">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA BANNER ══ --}}
<section style="padding: 5rem 0; margin: 0 -2rem;">
    <div style="margin: 0 2rem; display: grid; grid-template-columns: 1fr 1fr; align-items: center; border: 1px solid #e0ddd8; border-radius: 2px; overflow: hidden;">

        <div style="padding: 4rem;">
            <div style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .9rem;">Começa agora</div>
            <h2 style="font-size: 2rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0 0 1.5rem; line-height: 1.1;">
                A tua t-shirt<br><em style="font-weight: 700; font-style: italic;">perfeita espera.</em>
            </h2>
            <div style="display: flex; gap: .75rem; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('catalog') }}"
                   style="font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #f5f4f1; background: #1a1a1a; text-decoration: none; padding: .75rem 1.75rem; border-radius: 1px; transition: background .15s;"
                   onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                    Explorar catálogo
                </a>
                @guest
                <a href="{{ route('register') }}"
                   style="font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #888; text-decoration: none; padding: .75rem 1.5rem; border: 1px solid #d8d5d0; border-radius: 1px; transition: all .15s;"
                   onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                    Criar conta
                </a>
                @endguest
            </div>
        </div>

        <div style="background: #eeecea; height: 100%; display: flex; align-items: center; justify-content: center; padding: 3rem; border-left: 1px solid #e0ddd8; min-height: 300px;">
            @if($featuredDesigns->first())
                <x-tshirt-preview :colorCode="'white'" :imageUrl="$featuredDesigns->first()->image_url" size="200px" />
            @else
                <x-tshirt-preview :colorCode="'white'" size="200px" />
            @endif
        </div>
    </div>
</section>

@endsection
