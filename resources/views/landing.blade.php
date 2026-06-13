@extends('layouts.app', ['title' => 'FunShirt'])
@section('content')

    {{-- ══ HERO ══ --}}
    <section class="-mx-8 -mt-10 border-b border-[#e0ddd8]">
        <div class="max-w-[1280px] mx-auto px-8 grid grid-cols-2 min-h-[calc(100vh-56px)] items-stretch">

            {{-- LEFT: Text --}}
            <div class="flex flex-col justify-center py-20 pr-16 border-r border-[#e0ddd8]">
                <div class="text-[.6rem] font-bold tracking-[.2em] uppercase text-[#b8b4ae] mb-6">
                    {{ $totalDesigns }}+ designs · Provador 3D · Entrega rápida
                </div>

                <h1
                    class="text-[clamp(2.6rem,4vw,4rem)] font-light leading-[1.08] tracking-[-.04em] text-[#1a1a1a] mb-8 mt-0">
                    T-shirts feitas<br>
                    para <em class="font-bold italic">ti.</em>
                </h1>

                <p class="text-[#888] text-[.95rem] leading-[1.75] mb-10 mt-0 max-w-[380px]">
                    Escolhe um design, experimenta no Provador 3D, personaliza cor e tamanho. Receberes em casa.
                </p>

                <div class="flex gap-3 items-center">
                    <a href="{{ route('catalog') }}"
                        class="text-[.7rem] font-bold tracking-[.14em] uppercase text-[#f5f4f1] bg-[#1a1a1a] no-underline px-7 py-3 rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                        Ver Catálogo
                    </a>
                    <a href="{{ route('view3d') }}"
                        class="text-[.7rem] font-bold tracking-[.14em] uppercase text-[#888] no-underline px-6 py-3 border border-[#d8d5d0] rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                        Provador 3D
                    </a>
                </div>
            </div>

            {{-- RIGHT: 3D rotating shirt --}}
            <div id="hero-3d-wrap" class="border-l border-[#e0ddd8] relative overflow-hidden">
                <canvas id="hero-canvas" class="w-full h-full block"></canvas>
                <div
                    class="absolute bottom-5 left-1/2 -translate-x-1/2 text-[.58rem] font-bold tracking-[.16em] uppercase text-[#c8c4be] pointer-events-none whitespace-nowrap">
                    ↔ Provador 3D
                </div>
            </div>
        </div>
    </section>

    {{-- ══ STATS BAR ══ --}}
    <section class="border-b border-[#e0ddd8] -mx-8">
        <div class="grid grid-cols-4 px-8">
            @foreach ([[$totalDesigns . '+', 'designs únicos'], ['6', 'cores disponíveis'], ['€15', 'preço desde'], ['3–5 dias', 'entrega em casa']] as $i => $s)
                <div class="py-5 text-center {{ $i < 3 ? 'border-r border-[#e0ddd8]' : '' }}">
                    <div class="text-[1.35rem] font-bold tracking-[-.02em] text-[#1a1a1a]">
                        {{ $s[0] }}
                    </div>
                    <div class="text-[#b8b4ae] text-[.7rem] font-semibold tracking-[.1em] uppercase mt-0.5">
                        {{ $s[1] }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ══ FEATURED PRODUCTS ══ --}}
    @if ($featuredDesigns->count() > 0)
        <section class="py-20 border-b border-[#e0ddd8] -mx-8">
            <div class="px-8">

                <div class="flex items-baseline justify-between mb-10">
                    <div>
                        <div class="text-[.6rem] font-bold tracking-[.2em] uppercase text-[#b8b4ae] mb-1.5">
                            Em destaque
                        </div>
                        <h2 class="text-[1.75rem] font-light tracking-[-.03em] text-[#1a1a1a] m-0">
                            Os mais <em class="font-bold italic">populares.</em>
                        </h2>
                    </div>
                    <a href="{{ route('catalog') }}"
                        class="text-[.68rem] font-bold tracking-[.12em] uppercase text-[#7c6fa0] no-underline transition-colors duration-150 hover:text-[#1a1a1a]">
                        Ver todos →
                    </a>
                </div>

                <div class="grid grid-cols-4 gap-0 border border-[#e0ddd8] rounded-[2px] overflow-hidden">
                    @foreach ($featuredDesigns as $i => $d)
                        <a href="{{ route('catalog') }}"
                            class="group block no-underline bg-white {{ $i < 3 ? 'border-r border-[#e0ddd8]' : '' }} transition-colors duration-150 hover:bg-[#f9f8f6]">

                            <div
                                class="bg-white flex items-center justify-center h-[200px] border-b border-[#e0ddd8] transition-colors duration-150 p-6 overflow-hidden group-hover:border-[#7c6fa0]">
                                <img src="{{ $d->display_url }}" alt="{{ $d->name }}"
                                    class="max-w-full max-h-full object-contain block">
                            </div>

                            <div class="px-4 pt-[0.9rem] pb-[1.1rem]">
                                @if ($d->category)
                                    <div class="text-[.58rem] font-bold tracking-[.12em] uppercase text-[#7c6fa0] mb-1">
                                        {{ $d->category->name }}
                                    </div>
                                @endif
                                <div
                                    class="text-[#1a1a1a] text-[.85rem] font-semibold mb-1 whitespace-nowrap overflow-hidden text-ellipsis transition-colors duration-150 group-hover:text-[#7c6fa0]">
                                    {{ $d->name }}
                                </div>
                                <div class="text-[#888] text-[.8rem] font-medium">€10.00</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══ HOW IT WORKS ══ --}}
    <section class="py-20 border-b border-[#e0ddd8] -mx-8">
        <div class="px-8 grid grid-cols-[1fr_2fr] gap-20 items-start">

            <div>
                <div class="text-[.6rem] font-bold tracking-[.2em] uppercase text-[#b8b4ae] mb-3.5">
                    Processo
                </div>
                <h2 class="text-[1.75rem] font-light tracking-[-.03em] text-[#1a1a1a] mb-5 mt-0">
                    Como <em class="font-bold italic">funciona.</em>
                </h2>
                <p class="text-[#aaa] text-[.85rem] leading-[1.7] m-0">
                    Em três passos tens a tua t-shirt à porta — sem complicações.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-0 border-l border-[#e0ddd8]">
                @foreach ([['01', 'Escolhe o design', 'Explora o catálogo com mais de ' . $totalDesigns . ' designs. Filtra por categoria ou pesquisa o que queres.'], ['02', 'Experimenta em 3D', 'Usa o Provador 3D para escolher cor e tamanho. Vê o resultado real antes de encomendar.'], ['03', 'Recebe em casa', 'Checkout seguro. Recebes confirmação por e-mail e a t-shirt em 3 a 5 dias úteis.']] as $step)
                    <div class="p-6 border-r border-[#e0ddd8]">
                        <div class="text-[.62rem] font-bold tracking-[.16em] uppercase text-[#c8c4be] mb-4">
                            {{ $step[0] }}
                        </div>
                        <h3 class="text-[.92rem] font-bold text-[#1a1a1a] mb-2 mt-0 tracking-[-.01em]">
                            {{ $step[1] }}
                        </h3>
                        <p class="text-[#aaa] text-[.8rem] leading-[1.65] m-0">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ CTA BANNER ══ --}}
    <section class="py-20 -mx-8">
        <div class="mx-8 grid grid-cols-2 items-center border border-[#e0ddd8] rounded-[2px] overflow-hidden">

            <div class="p-16">
                <div class="text-[.6rem] font-bold tracking-[.2em] uppercase text-[#b8b4ae] mb-3.5">
                    Começa agora
                </div>
                <h2 class="text-[2rem] font-light tracking-[-.03em] text-[#1a1a1a] mb-6 mt-0 leading-[1.1]">
                    A tua t-shirt<br><em class="font-bold italic">perfeita espera.</em>
                </h2>
                <div class="flex gap-3 items-center flex-wrap">
                    <a href="{{ route('catalog') }}"
                        class="text-[.7rem] font-bold tracking-[.14em] uppercase text-[#f5f4f1] bg-[#1a1a1a] no-underline px-7 py-3 rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                        Explorar catálogo
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                            class="text-[.7rem] font-bold tracking-[.14em] uppercase text-[#888] no-underline px-6 py-3 border border-[#d8d5d0] rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                            Criar conta
                        </a>
                    @endguest
                </div>
            </div>

            <div class="bg-[#eeecea] h-full flex items-center justify-center p-12 border-l border-[#e0ddd8] min-h-[300px]">
                @if ($ctaUrl)
                    <img src="{{ $ctaUrl }}" alt="Featured Design"
                        class="max-w-[200px] max-h-[200px] object-contain block">
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

                currentRotY = 0;
                targetRotY = 0;
                shirtGroup.rotation.y = 0;
                shirtGroup.add(gltf.scene);
                applyDesign();
            });

            function drawDesign(ctx, cx, cy, R) {
                ctx.save();
                ctx.translate(cx, cy);

                ctx.beginPath();
                ctx.arc(0, 0, R * 1.02, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(245,244,241,0)';
                ctx.fill();

                ctx.beginPath();
                ctx.arc(0, 0, R, 0, Math.PI * 2);
                ctx.strokeStyle = '#1a1a1a';
                ctx.lineWidth = R * 0.05;
                ctx.stroke();

                ctx.beginPath();
                ctx.arc(0, 0, R * 0.8, 0, Math.PI * 2);
                ctx.lineWidth = R * 0.02;
                ctx.stroke();

                ctx.fillStyle = '#1a1a1a';
                ctx.font = '700 ' + Math.round(R * 0.52) + 'px Inter,system-ui,sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('FUN', 0, -R * 0.08);

                ctx.beginPath();
                ctx.moveTo(-R * 0.4, R * 0.26);
                ctx.lineTo(R * 0.4, R * 0.26);
                ctx.lineWidth = R * 0.025;
                ctx.stroke();

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
