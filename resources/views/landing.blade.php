@extends('layouts.app', ['title' => 'FunShirt'])
@section('content')
@php
    $featuredDesigns = App\Models\TshirtImage::whereNull('customer_id')->with('category')->take(4)->get();
    $totalDesigns = App\Models\TshirtImage::whereNull('customer_id')->count();
@endphp

<style>
@keyframes float    { 0%,100%{transform:translateY(0)}    50%{transform:translateY(-14px)} }
@keyframes glow-pulse { 0%,100%{opacity:.35;transform:scale(1)} 50%{opacity:.6;transform:scale(1.06)} }
@keyframes fade-up  { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
@keyframes slide-in { from{opacity:0;transform:translateX(32px)} to{opacity:1;transform:translateX(0)} }
@keyframes shimmer  { 0%{background-position:-400px 0} 100%{background-position:400px 0} }

.fu { animation: fade-up .7s ease both; }
.fu1 { animation-delay:.05s } .fu2 { animation-delay:.18s } .fu3 { animation-delay:.32s } .fu4 { animation-delay:.46s }
.si { animation: slide-in .8s .2s ease both; }

.hero-btn-primary  { display:inline-flex;align-items:center;gap:.55rem;background:#7c3aed;color:#fff;text-decoration:none;font-size:.95rem;font-weight:700;padding:.85rem 2rem;border-radius:10px;border:1px solid #7c3aed;transition:all .2s;box-shadow:0 4px 24px rgba(124,58,237,.4); }
.hero-btn-primary:hover  { background:#6d28d9;box-shadow:0 6px 32px rgba(124,58,237,.6);transform:translateY(-1px); }
.hero-btn-outline  { display:inline-flex;align-items:center;gap:.55rem;background:rgba(255,255,255,.04);color:#e2e8f0;text-decoration:none;font-size:.95rem;font-weight:600;padding:.85rem 1.8rem;border-radius:10px;border:1px solid rgba(255,255,255,.12);transition:all .2s;backdrop-filter:blur(4px); }
.hero-btn-outline:hover  { border-color:#a78bfa;color:#a78bfa;background:rgba(124,58,237,.1); }

.product-card { background:#0e0e1c;border:1px solid #1e1e30;border-radius:16px;overflow:hidden;transition:all .22s;cursor:pointer;text-decoration:none;display:block; }
.product-card:hover { border-color:#7c3aed;transform:translateY(-4px);box-shadow:0 12px 40px rgba(124,58,237,.2); }
.product-card:hover .card-action { opacity:1;transform:translateY(0); }
.card-action { opacity:0;transform:translateY(6px);transition:all .2s; }

.step-num { width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#7c3aed,#5b21b6);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:800;color:#fff;flex-shrink:0; }
.stat-item { text-align:center; }

.badge { display:inline-flex;align-items:center;gap:.4rem;background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);border-radius:999px;padding:.3rem .9rem; }
.badge span { color:#a78bfa;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase; }
</style>

{{-- ═══════════ HERO ═══════════ --}}
<div style="margin:-2rem -1.5rem 0;position:relative;overflow:hidden;background:#07070e;">
    {{-- Glow layers --}}
    <div style="position:absolute;top:-80px;left:-80px;width:700px;height:700px;background:radial-gradient(circle,rgba(109,40,217,.22) 0%,transparent 65%);pointer-events:none;animation:glow-pulse 7s ease-in-out infinite;"></div>
    <div style="position:absolute;top:30%;right:-100px;width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.14) 0%,transparent 65%);pointer-events:none;animation:glow-pulse 9s 2s ease-in-out infinite;"></div>
    <div style="position:absolute;bottom:0;left:30%;width:400px;height:300px;background:radial-gradient(ellipse,rgba(167,139,250,.08) 0%,transparent 70%);pointer-events:none;"></div>

    {{-- Grid texture --}}
    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.05) 1px,transparent 1px);background-size:56px 56px;pointer-events:none;mask-image:radial-gradient(ellipse at 50% 0%,black 30%,transparent 80%);"></div>

    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;min-height:88vh;display:grid;grid-template-columns:1fr 1fr;align-items:center;gap:3rem;">

        {{-- LEFT: Text --}}
        <div style="padding:5rem 0 5rem;">
            <div class="badge fu fu1" style="margin-bottom:1.5rem;">
                <span style="width:6px;height:6px;border-radius:50%;background:#a78bfa;animation:glow-pulse 2s infinite;"></span>
                <span>{{ $totalDesigns }}+ designs exclusivos</span>
            </div>

            <h1 class="fu fu2" style="font-size:clamp(2.8rem,4.5vw,4.2rem);font-weight:900;line-height:1.08;letter-spacing:-.04em;color:#f8fafc;margin:0 0 1.5rem;">
                A tua t-shirt<br>
                <span style="background:linear-gradient(125deg,#c084fc 0%,#a78bfa 40%,#7c3aed 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">à tua maneira.</span>
            </h1>

            <p class="fu fu3" style="color:#94a3b8;font-size:1.1rem;line-height:1.75;margin:0 0 2.5rem;max-width:460px;">
                Escolhe um design, personaliza cor e tamanho no Provador 3D e recebe em casa. Tão simples quanto parece.
            </p>

            <div class="fu fu3" style="display:flex;gap:.85rem;flex-wrap:wrap;">
                <a href="{{ route('catalog') }}" class="hero-btn-primary">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6h18M3 12h18M3 18h18"/></svg>
                    Ver Catálogo
                </a>
                <a href="{{ route('try-on') }}" class="hero-btn-outline">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    Provador 3D
                </a>
            </div>

            {{-- Trust --}}
            <div class="fu fu4" style="display:flex;gap:1.75rem;margin-top:3rem;flex-wrap:wrap;">
                @foreach([['✓','Entrega rápida'],['✓','100% personalizável'],['✓','Pagamento seguro']] as $t)
                <div style="display:flex;align-items:center;gap:.4rem;">
                    <span style="color:#4ade80;font-size:.8rem;font-weight:700;">{{ $t[0] }}</span>
                    <span style="color:#64748b;font-size:.82rem;">{{ $t[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Product showcase --}}
        <div class="si" style="display:flex;justify-content:center;align-items:center;padding:4rem 0;position:relative;">
            {{-- Glow behind shirt --}}
            <div style="position:absolute;width:320px;height:320px;background:radial-gradient(circle,rgba(124,58,237,.35) 0%,transparent 70%);pointer-events:none;animation:glow-pulse 4s ease-in-out infinite;"></div>

            {{-- Main t-shirt --}}
            <div style="position:relative;animation:float 4.5s ease-in-out infinite;filter:drop-shadow(0 24px 48px rgba(124,58,237,.5));">
                @if($featuredDesigns->first())
                    <x-tshirt-preview :colorCode="'white'" :imageUrl="$featuredDesigns->first()->image_url" size="280px" />
                @else
                    <x-tshirt-preview :colorCode="'white'" size="280px" />
                @endif
            </div>

            {{-- Floating mini cards --}}
            @if($featuredDesigns->count() >= 3)
            <div style="position:absolute;top:15%;left:-20px;background:rgba(14,14,28,.9);backdrop-filter:blur(12px);border:1px solid #2d2d45;border-radius:12px;padding:.75rem;box-shadow:0 8px 32px rgba(0,0,0,.5);animation:float 5.5s 0.8s ease-in-out infinite;">
                <x-tshirt-preview :colorCode="'navy'" :imageUrl="$featuredDesigns->get(1)?->image_url" size="60px" />
            </div>
            <div style="position:absolute;bottom:20%;right:-15px;background:rgba(14,14,28,.9);backdrop-filter:blur(12px);border:1px solid #2d2d45;border-radius:12px;padding:.75rem;box-shadow:0 8px 32px rgba(0,0,0,.5);animation:float 6s 1.5s ease-in-out infinite;">
                <x-tshirt-preview :colorCode="'black'" :imageUrl="$featuredDesigns->get(2)?->image_url" size="60px" />
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ═══════════ STATS BAR ═══════════ --}}
<div style="border-top:1px solid #1e1e30;border-bottom:1px solid #1e1e30;background:#0d0d1a;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;">
            @foreach([
                ['12+','Designs únicos'],
                ['6','Cores disponíveis'],
                ['€15','Preço desde'],
                ['3–5','Dias de entrega'],
            ] as $i => $s)
            <div class="stat-item" style="padding:1.5rem;{{ $i < 3 ? 'border-right:1px solid #1e1e30;' : '' }}">
                <div style="font-size:1.6rem;font-weight:800;color:#a78bfa;letter-spacing:-.02em;line-height:1;">{{ $s[0] }}</div>
                <div style="color:#64748b;font-size:.8rem;margin-top:.25rem;font-weight:500;">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══════════ FEATURED PRODUCTS ═══════════ --}}
@if($featuredDesigns->count() > 0)
<div style="padding:5rem 0;">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2.5rem;gap:1rem;flex-wrap:wrap;">
            <div>
                <div class="badge" style="margin-bottom:.75rem;">
                    <span>Em destaque</span>
                </div>
                <h2 style="color:#f1f5f9;font-size:1.8rem;font-weight:800;margin:0;letter-spacing:-.025em;">Designs populares</h2>
            </div>
            <a href="{{ route('catalog') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:#a78bfa;text-decoration:none;font-size:.88rem;font-weight:600;white-space:nowrap;transition:color .15s;"
               onmouseover="this.style.color='#c4b5fd'" onmouseout="this.style.color='#a78bfa'">
                Ver todos
                <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;">
            @foreach($featuredDesigns as $d)
            <a href="{{ route('catalog') }}" class="product-card">
                <div style="background:linear-gradient(160deg,#0b0b18,#0f0f20);padding:1.75rem;display:flex;align-items:center;justify-content:center;min-height:200px;position:relative;">
                    <div style="position:absolute;inset:0;background:radial-gradient(circle at 50% 60%,rgba(124,58,237,.08),transparent 65%);pointer-events:none;"></div>
                    <x-tshirt-preview :colorCode="'white'" :imageUrl="$d->image_url" size="150px" />
                </div>
                <div style="padding:1rem 1.1rem 1.2rem;">
                    @if($d->category)
                    <span style="display:inline-block;background:rgba(124,58,237,.1);color:#a78bfa;border-radius:6px;padding:1px 7px;font-size:.68rem;font-weight:600;margin-bottom:.45rem;letter-spacing:.04em;text-transform:uppercase;">{{ $d->category->name }}</span>
                    @endif
                    <p style="color:#e2e8f0;font-size:.92rem;font-weight:600;margin:0 0 .7rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $d->name }}</p>
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span style="color:#a78bfa;font-size:.9rem;font-weight:700;">€15.00</span>
                        <span class="card-action" style="color:#7c3aed;font-size:.78rem;font-weight:600;display:flex;align-items:center;gap:.3rem;">
                            Adicionar
                            <svg style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ═══════════ HOW IT WORKS ═══════════ --}}
<div style="padding:5rem 0;background:linear-gradient(180deg,transparent,rgba(124,58,237,.04),transparent);">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="text-align:center;margin-bottom:3.5rem;">
            <div class="badge" style="margin-bottom:.85rem;">
                <span>Processo simples</span>
            </div>
            <h2 style="color:#f1f5f9;font-size:1.8rem;font-weight:800;margin:0 0 .5rem;letter-spacing:-.025em;">Como funciona</h2>
            <p style="color:#64748b;font-size:.95rem;margin:0;">Em três passos, a tua t-shirt chega ao teu ecrã — e a tua porta.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;position:relative;">
            {{-- connector lines --}}
            <div style="position:absolute;top:2rem;left:calc(33% + .75rem);right:calc(33% + .75rem);height:1px;background:linear-gradient(90deg,transparent,#2d2d45 30%,#2d2d45 70%,transparent);pointer-events:none;"></div>

            @foreach([
                ['01','Escolhe o design','Explora o catálogo com + de {{ $totalDesigns }} designs criados por artistas. Filtra por categoria, pesquisa pelo que queres.','M4 6h16M4 10h16M4 14h16M4 18h16'],
                ['02','Personaliza no 3D','Usa o Provador 3D para escolher a cor e o tamanho. Vê o resultado antes de encomendar, com rotação 360°.','M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                ['03','Encomenda e recebe','Checkout seguro, pagamento por Visa, PayPal ou MB WAY. Receberes o teu recibo por email e a t-shirt em casa.','M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
            ] as $idx => $step)
            <div style="background:#0e0e1c;border:1px solid #1e1e30;border-radius:16px;padding:2rem;transition:border-color .2s,transform .2s;"
                 onmouseover="this.style.borderColor='#7c3aed';this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#1e1e30';this.style.transform='translateY(0)'">
                <div style="display:flex;align-items:flex-start;gap:1rem;margin-bottom:1.25rem;">
                    <div class="step-num">{{ $step[0] }}</div>
                    <h3 style="color:#e2e8f0;font-size:1rem;font-weight:700;margin:.6rem 0 0;line-height:1.3;">{{ $step[1] }}</h3>
                </div>
                <p style="color:#64748b;font-size:.875rem;line-height:1.7;margin:0;">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══════════ CTA BANNER ═══════════ --}}
<div style="margin:0 -1.5rem -2rem;padding:6rem 1.5rem;background:#07070e;position:relative;overflow:hidden;border-top:1px solid #1e1e30;">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(124,58,237,.22) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(124,58,237,.1) 1px,transparent 1px);background-size:28px 28px;pointer-events:none;mask-image:radial-gradient(ellipse at 50% 50%,black 30%,transparent 75%);"></div>

    <div style="position:relative;max-width:600px;margin:0 auto;text-align:center;">
        <h2 style="color:#f8fafc;font-size:2.2rem;font-weight:900;line-height:1.1;margin:0 0 1rem;letter-spacing:-.03em;">
            Pronto para criar<br>
            <span style="background:linear-gradient(125deg,#c084fc,#7c3aed);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">a tua t-shirt única?</span>
        </h2>
        <p style="color:#94a3b8;font-size:1rem;margin:0 0 2.25rem;line-height:1.7;">Junta-te a centenas de clientes satisfeitos. Sem complicações, sem compromissos.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('catalog') }}" class="hero-btn-primary" style="font-size:1rem;padding:.9rem 2.4rem;">
                Começar agora
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            @guest
            <a href="{{ route('register') }}" class="hero-btn-outline" style="font-size:1rem;padding:.9rem 2rem;">Criar conta grátis</a>
            @endguest
        </div>
    </div>
</div>

@endsection
