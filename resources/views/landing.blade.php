@extends('layouts.app', ['title' => 'FunShirt'])
@section('content')
    @php
        $featuredDesigns = App\Models\TshirtImage::whereNull('customer_id')->with('category')->take(4)->get();
        $totalDesigns = App\Models\TshirtImage::whereNull('customer_id')->count();
    @endphp

    <style>
        .product-link {
            text-decoration: none;
            display: block;
        }

        .product-link:hover .product-img-wrap {
            border-color: #7c6fa0;
        }

        .product-link:hover .product-name {
            color: #7c6fa0;
        }
    </style>

    {{-- ══ HERO ══ --}}
    <section style="margin: -2.5rem -2rem 0; border-bottom: 1px solid #e0ddd8;">
        <div
            style="max-width: 1280px; margin: 0 auto; padding: 0 2rem; display: grid; grid-template-columns: 1fr 1fr; min-height: calc(100vh - 56px); align-items: stretch;">

            {{-- LEFT: Text --}}
            <div
                style="display: flex; flex-direction: column; justify-content: center; padding: 5rem 4rem 5rem 0; border-right: 1px solid #e0ddd8;">
                <div
                    style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: 1.5rem;">
                    {{ $totalDesigns }}+ designs · Provador 3D · Entrega rápida
                </div>

                <h1
                    style="font-size: clamp(2.6rem, 4vw, 4rem); font-weight: 300; line-height: 1.08; letter-spacing: -.04em; color: #1a1a1a; margin: 0 0 2rem;">
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
                    <a href="{{ route('view3d') }}"
                        style="font-size: .7rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: #888; text-decoration: none; padding: .75rem 1.5rem; border: 1px solid #d8d5d0; border-radius: 1px; transition: all .15s;"
                        onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
                        onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                        Provador 3D
                    </a>
                </div>
            </div>

            {{-- RIGHT: 3D rotating shirt --}}
            <div id="hero-3d-wrap" style="border-left:1px solid #e0ddd8;position:relative;overflow:hidden;">
                <canvas id="hero-canvas" style="width:100%;height:100%;display:block;"></canvas>
                <div
                    style="position:absolute;bottom:1.25rem;left:50%;transform:translateX(-50%);font-size:.58rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#c8c4be;pointer-events:none;white-space:nowrap;">
                    ↔ Provador 3D
                </div>
            </div>
        </div>
    </section>

    {{-- ══ STATS BAR ══ --}}
    <section style="border-bottom: 1px solid #e0ddd8; margin: 0 -2rem;">
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); padding: 0 2rem;">
            @foreach ([[$totalDesigns . '+', 'designs únicos'], ['6', 'cores disponíveis'], ['€15', 'preço desde'], ['3–5 dias', 'entrega em casa']] as $i => $s)
                <div style="padding: 1.25rem 0; text-align: center; {{ $i < 3 ? 'border-right: 1px solid #e0ddd8;' : '' }}">
                    <div style="font-size: 1.35rem; font-weight: 700; letter-spacing: -.02em; color: #1a1a1a;">
                        {{ $s[0] }}</div>
                    <div
                        style="color: #b8b4ae; font-size: .7rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; margin-top: .15rem;">
                        {{ $s[1] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ══ FEATURED PRODUCTS ══ --}}
    @if ($featuredDesigns->count() > 0)
        <section style="padding: 5rem 0; border-bottom: 1px solid #e0ddd8; margin: 0 -2rem;">
            <div style="padding: 0 2rem;">

                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 2.5rem;">
                    <div>
                        <div
                            style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .4rem;">
                            Em destaque</div>
                        <h2
                            style="font-size: 1.75rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0;">
                            Os mais <em style="font-weight: 700; font-style: italic;">populares.</em></h2>
                    </div>
                    <a href="{{ route('catalog') }}"
                        style="font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #7c6fa0; text-decoration: none; transition: color .15s;"
                        onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#7c6fa0'">
                        Ver todos →
                    </a>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; border: 1px solid #e0ddd8; border-radius: 2px; overflow: hidden;">
                    @foreach ($featuredDesigns as $i => $d)
                        <a href="{{ route('catalog') }}" class="product-link"
                            style="background: #fff; {{ $i < 3 ? 'border-right: 1px solid #e0ddd8;' : '' }} transition: background .15s;"
                            onmouseover="this.style.background='#f9f8f6'" onmouseout="this.style.background='#fff'">
                            @php
                                $dBare = basename($d->image_url);
                                $dUrl = \Illuminate\Support\Str::startsWith($d->image_url, 'tshirt_images_private/')
                                    ? route('private-image', $dBare)
                                    : (str_contains($d->image_url, '/')
                                        ? asset('storage/' . $d->image_url)
                                        : asset('storage/tshirt_images/' . $dBare));
                            @endphp
                            <div class="product-img-wrap"
                                style="background:#fff;display:flex;align-items:center;justify-content:center;height:200px;border-bottom:1px solid #e0ddd8;transition:border-color .15s;padding:1.5rem;overflow:hidden;">
                                <img src="{{ $dUrl }}" alt="{{ $d->name }}"
                                    style="max-width:100%;max-height:100%;object-fit:contain;display:block;">
                            </div>
                            <div style="padding: .9rem 1rem 1.1rem;">
                                @if ($d->category)
                                    <div
                                        style="font-size: .58rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #7c6fa0; margin-bottom: .3rem;">
                                        {{ $d->category->name }}</div>
                                @endif
                                <div class="product-name"
                                    style="color: #1a1a1a; font-size: .85rem; font-weight: 600; margin-bottom: .25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color .15s;">
                                    {{ $d->name }}</div>
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
                <div
                    style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .9rem;">
                    Processo</div>
                <h2
                    style="font-size: 1.75rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0 0 1.25rem;">
                    Como <em style="font-weight: 700; font-style: italic;">funciona.</em></h2>
                <p style="color: #aaa; font-size: .85rem; line-height: 1.7; margin: 0;">Em três passos tens a tua t-shirt à
                    porta — sem complicações.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; border-left: 1px solid #e0ddd8;">
                @foreach ([['01', 'Escolhe o design', 'Explora o catálogo com mais de ' . $totalDesigns . ' designs. Filtra por categoria ou pesquisa o que queres.'], ['02', 'Experimenta em 3D', 'Usa o Provador 3D para escolher cor e tamanho. Vê o resultado real antes de encomendar.'], ['03', 'Recebe em casa', 'Checkout seguro. Recebes confirmação por e-mail e a t-shirt em 3 a 5 dias úteis.']] as $step)
                    <div style="padding: 1.5rem; border-right: 1px solid #e0ddd8;">
                        <div
                            style="font-size: .62rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #c8c4be; margin-bottom: 1rem;">
                            {{ $step[0] }}</div>
                        <h3
                            style="font-size: .92rem; font-weight: 700; color: #1a1a1a; margin: 0 0 .6rem; letter-spacing: -.01em;">
                            {{ $step[1] }}</h3>
                        <p style="color: #aaa; font-size: .8rem; line-height: 1.65; margin: 0;">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ CTA BANNER ══ --}}
    <section style="padding: 5rem 0; margin: 0 -2rem;">
        <div
            style="margin: 0 2rem; display: grid; grid-template-columns: 1fr 1fr; align-items: center; border: 1px solid #e0ddd8; border-radius: 2px; overflow: hidden;">

            <div style="padding: 4rem;">
                <div
                    style="font-size: .6rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: #b8b4ae; margin-bottom: .9rem;">
                    Começa agora</div>
                <h2
                    style="font-size: 2rem; font-weight: 300; letter-spacing: -.03em; color: #1a1a1a; margin: 0 0 1.5rem; line-height: 1.1;">
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
                            onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
                            onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                            Criar conta
                        </a>
                    @endguest
                </div>
            </div>

            <div
                style="background:#eeecea;height:100%;display:flex;align-items:center;justify-content:center;padding:3rem;border-left:1px solid #e0ddd8;min-height:300px;">
                @if ($featuredDesigns->first())
                    @php
                        $ctaBare = basename($featuredDesigns->first()->image_url);
                        $ctaUrl = \Illuminate\Support\Str::startsWith(
                            $featuredDesigns->first()->image_url,
                            'tshirt_images_private/',
                        )
                            ? route('private-image', $ctaBare)
                            : (str_contains($featuredDesigns->first()->image_url, '/')
                                ? asset('storage/' . $featuredDesigns->first()->image_url)
                                : asset('storage/tshirt_images/' . $ctaBare));
                    @endphp
                    <img src="{{ $ctaUrl }}" alt=""
                        style="max-width:200px;max-height:200px;object-fit:contain;display:block;">
                @endif
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/three@0.134.0/build/three.min.js"></script>
    <script src="https://unpkg.com/three@0.134.0/examples/js/loaders/GLTFLoader.js"></script>
    <script>
        (function() {
            const canvas = document.getElementById('hero-canvas');
            if (!canvas) return;
            const container = canvas.parentElement;

            const renderer = new THREE.WebGLRenderer({
                canvas,
                antialias: true
            });
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.setClearColor(0xf5f4f1, 1);

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 100);

            scene.add(new THREE.AmbientLight(0xffffff, 0.38));
            const key = new THREE.DirectionalLight(0xfff8f4, 1.05);
            key.position.set(3, 5, 4);
            scene.add(key);
            const fill = new THREE.DirectionalLight(0xdde8ff, 0.32);
            fill.position.set(-4, 2, 2);
            scene.add(fill);
            const rim = new THREE.DirectionalLight(0xffffff, 0.18);
            rim.position.set(0, 4, -5);
            scene.add(rim);

            const shirtGroup = new THREE.Group();
            scene.add(shirtGroup);

            let shirtMeshes = [],
                chestUV = null,
                modelBounds = null;

            new THREE.GLTFLoader().load('/tshirt/scene.gltf', function(gltf) {
                const box = new THREE.Box3().setFromObject(gltf.scene);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());
                const scale = 1.6 / Math.max(size.x, size.y, size.z);
                gltf.scene.scale.setScalar(scale);
                gltf.scene.position.set(-center.x * scale, -center.y * scale, -center.z * scale);
                modelBounds = new THREE.Box3().setFromObject(gltf.scene);

                gltf.scene.traverse(child => {
                    if (!child.isMesh) return;
                    shirtMeshes.push(child);
                    const mat = new THREE.MeshStandardMaterial({
                        roughness: 0.75,
                        metalness: 0.02
                    });
                    child.material = Array.isArray(child.material) ? child.material.map(() => mat
                    .clone()) : mat;
                });
                gltf.scene.updateMatrixWorld(true);

                const h = modelBounds.max.y - modelBounds.min.y;
                const sorted = [...shirtMeshes].sort((a, b) => b.geometry.attributes.position.count - a.geometry
                    .attributes.position.count);
                for (const frac of [0.65, 0.70, 0.60, 0.55, 0.75, 0.50, 0.80]) {
                    const ray = new THREE.Raycaster(new THREE.Vector3(0, modelBounds.min.y + h * frac, -5),
                        new THREE.Vector3(0, 0, 1));
                    for (const mesh of sorted) {
                        const hits = ray.intersectObject(mesh, false);
                        if (hits.length > 0 && hits[0].uv) {
                            chestUV = hits[0].uv.clone();
                            break;
                        }
                    }
                    if (chestUV) break;
                }

                const mc = modelBounds.getCenter(new THREE.Vector3());
                const ms = modelBounds.getSize(new THREE.Vector3());
                camera.position.set(0, mc.y, ms.y * 2.2);
                camera.lookAt(0, mc.y, 0);

                // Force front-facing before adding to scene
                currentRotY = 0;
                targetRotY = 0;
                shirtGroup.rotation.y = 0;
                shirtGroup.add(gltf.scene);
                applyDesign();
            });

            // ── Clean minimal design: thin circle + "FUN" wordmark ──
            function drawDesign(ctx, cx, cy, R) {
                ctx.save();
                ctx.translate(cx, cy);

                // Filled dark background circle so design is always visible
                ctx.beginPath();
                ctx.arc(0, 0, R * 1.02, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(245,244,241,0)';
                ctx.fill();

                // Thin outer ring
                ctx.beginPath();
                ctx.arc(0, 0, R, 0, Math.PI * 2);
                ctx.strokeStyle = '#1a1a1a';
                ctx.lineWidth = R * 0.05;
                ctx.stroke();

                // Inner thin ring
                ctx.beginPath();
                ctx.arc(0, 0, R * 0.8, 0, Math.PI * 2);
                ctx.lineWidth = R * 0.02;
                ctx.stroke();

                // "FUN" large bold text
                ctx.fillStyle = '#1a1a1a';
                ctx.font = '700 ' + Math.round(R * 0.52) + 'px Inter,system-ui,sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('FUN', 0, -R * 0.08);

                // thin divider line
                ctx.beginPath();
                ctx.moveTo(-R * 0.4, R * 0.26);
                ctx.lineTo(R * 0.4, R * 0.26);
                ctx.lineWidth = R * 0.025;
                ctx.stroke();

                // "SHIRT" small caps
                ctx.font = '600 ' + Math.round(R * 0.19) + 'px Inter,system-ui,sans-serif';
                ctx.fillText('SHIRT', 0, R * 0.45);

                ctx.restore();
            }

            function applyDesign() {
                const sz = 1024;
                const cv = document.createElement('canvas');
                cv.width = cv.height = sz;
                const ctx = cv.getContext('2d');
                ctx.fillStyle = '#fafafa';
                ctx.fillRect(0, 0, sz, sz);
                if (chestUV) drawDesign(ctx, chestUV.x * sz, (1 - chestUV.y) * sz, sz * 0.17);
                const tex = new THREE.CanvasTexture(cv);
                tex.flipY = true;
                shirtMeshes.forEach(mesh => {
                    const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
                    mats.forEach(m => {
                        m.color.set(0xffffff);
                        m.map = tex;
                        m.needsUpdate = true;
                    });
                });
            }

            // ── Mouse parallax (small range so front is always visible) ──
            var targetRotY = 0;
            var currentRotY = 0;

            window.addEventListener('mousemove', function(e) {
                var nx = e.clientX / (window.innerWidth || 1);
                nx = Math.max(0, Math.min(1, nx));
                var t = (0.5 - nx) * 0.45;
                targetRotY = Math.max(-0.45, Math.min(0.45, t));
            });

            function resize() {
                const w = container.clientWidth,
                    h = container.clientHeight;
                if (!w || !h) return;
                renderer.setSize(w, h);
                camera.aspect = w / h;
                camera.updateProjectionMatrix();
            }
            new ResizeObserver(resize).observe(container);
            resize();
            setTimeout(resize, 150);

            function animate() {
                requestAnimationFrame(animate);
                var lerped = currentRotY + (targetRotY - currentRotY) * 0.055;
                currentRotY = isFinite(lerped) ? Math.max(-0.5, Math.min(0.5, lerped)) : 0;
                shirtGroup.rotation.y = currentRotY;
                renderer.render(scene, camera);
            }
            animate();
        })();
    </script>
@endsection
