@extends('layouts.app', ['title' => 'Provador Virtual 3D'])

@section('content')

<div style="display:flex;flex-direction:column;height:calc(100vh - 56px - 5rem);"
     x-data="{
         selectedDesignId: {{ $selectedImageId ?? 'null' }},
         selectedColor: '{{ $selectedColor }}',
         selectedSide: '{{ $selectedSide }}',
         selectedSize: '{{ $selectedSize }}',
         qty: {{ $qty ?? 1 }}
     }">

    {{-- Flash toast --}}
    @if (session('cart_success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-6 right-6 z-[1000] bg-[#f0faf5] border border-[#b7e1cb] text-fs-green px-[1.1rem] py-[0.7rem] text-[0.8rem] rounded-[1px]">
            ✓ {{ session('cart_success') }}
        </div>
    @endif

    {{-- Page header --}}
    <div class="shrink-0 flex items-center gap-4 mb-4">
        <a href="{{ route('catalog') }}"
           class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-[#aaa] no-underline transition-colors duration-150 hover:text-fs-dark">
            ← Catálogo
        </a>
        <div class="w-px h-[14px] bg-fs-border"></div>
        <div>
            <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-fs-muted">Provador</div>
            <h1 class="text-[1rem] font-semibold text-fs-dark m-0 tracking-[-0.01em]">Provador Virtual 3D</h1>
        </div>
    </div>

    {{-- 3-panel layout --}}
    <div class="grid gap-5 flex-1 min-h-0 overflow-hidden [grid-template-columns:220px_1fr_250px] [grid-template-rows:1fr]">

        {{-- LEFT: Design list --}}
        <div class="bg-fs-bg border border-fs-border rounded-[2px] overflow-hidden flex flex-col min-h-0 h-full">
            <div class="px-4 py-3 border-b border-fs-border shrink-0">
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0">Designs</p>
            </div>
            <div data-lenis-prevent class="overflow-y-auto flex-1 p-[0.4rem] [-webkit-overflow-scrolling:touch] [overscroll-behavior:contain] [touch-action:pan-y]">
                @foreach ($designs as $design)
                    @php
                        if (\Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                            $imgUrl = route('private-image', $design->image_url);
                        } elseif (str_contains($design->image_url, '/')) {
                            $imgUrl = asset('storage/' . $design->image_url);
                        } else {
                            $imgUrl = asset('storage/tshirt_images/' . $design->image_url);
                        }
                    @endphp
                    <button type="button"
                            @click="selectedDesignId = {{ $design->id }}"
                            :class="selectedDesignId === {{ $design->id }} ? 'bg-white border-fs-purple' : 'bg-transparent border-transparent hover:bg-black/[0.04]'"
                            class="w-full border rounded-[1px] px-[0.45rem] py-[0.45rem] cursor-pointer flex items-center gap-[0.6rem] mb-1 transition-all duration-150 text-left">
                        <div class="w-12 h-12 shrink-0 bg-fs-light rounded-[1px] overflow-hidden border border-fs-border">
                            <img src="{{ $imgUrl }}" alt="{{ $design->name }}"
                                 class="w-full h-full object-cover block"
                                 onerror="this.parentElement.innerHTML='<span class=\'flex items-center justify-content-center h-full text-fs-mid text-base flex justify-center\'>?</span>'">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p :class="selectedDesignId === {{ $design->id }} ? 'text-fs-dark font-semibold' : 'text-fs-gray font-normal'"
                               class="text-[0.78rem] m-0 whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ $design->name }}</p>
                            @if ($design->category)
                                <span class="text-fs-muted text-[0.68rem]">{{ $design->category->name }}</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- CENTER: 3D Viewport --}}
        <div class="bg-fs-bg border border-fs-border rounded-[2px] relative overflow-hidden min-h-0 shadow-[inset_0_2px_20px_rgba(0,0,0,0.06)]">
            <canvas id="tshirt-canvas" class="w-full h-full block cursor-grab"></canvas>
            <div id="drag-hint"
                 class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/75 border border-[#d8d5d0] rounded-[20px] px-[0.9rem] py-[0.3rem] text-[#aaa] text-[0.72rem] pointer-events-none transition-opacity duration-500 backdrop-blur-sm">
                ↔ Arrasta para rodar
            </div>
            <button id="reset-btn" onclick="window.resetRotation && window.resetRotation()"
                    class="absolute top-3 right-3 bg-white/80 border border-[#d8d5d0] text-fs-gray text-[0.68rem] font-bold tracking-[0.1em] uppercase px-[0.65rem] py-[0.3rem] cursor-pointer rounded-[1px] backdrop-blur-sm transition-all duration-150 hover:border-fs-purple hover:text-fs-purple">
                ⟳ Resetar
            </button>
        </div>

        {{-- RIGHT: Controls --}}
        <div class="bg-fs-bg border border-fs-border rounded-[2px] p-[0.85rem] flex flex-col gap-3">

            {{-- Selected design name --}}
            @if ($selectedImage)
                <div class="border-b border-fs-border pb-[0.6rem]">
                    <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0 mb-[0.2rem]">Design</p>
                    <p class="text-fs-dark text-[0.84rem] font-semibold m-0 whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ $selectedImage->name }}</p>
                </div>
            @endif

            {{-- Side toggle --}}
            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0 mb-2">Posição da estampa</p>
                <div class="grid grid-cols-2 gap-px bg-fs-border border border-fs-border rounded-[1px] overflow-hidden">
                    <button type="button" @click="selectedSide = 'front'"
                            :class="selectedSide === 'front' ? 'bg-fs-dark text-fs-light' : 'bg-white text-fs-gray'"
                            class="border-0 px-2 py-[0.45rem] text-[0.65rem] font-bold tracking-[0.1em] uppercase cursor-pointer font-[inherit] transition-all duration-150 flex items-center justify-center gap-[0.3rem]">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" class="opacity-70">
                            <rect x="1" y="0" width="8" height="12" rx="1"/>
                            <rect x="3" y="3" width="4" height="4" rx=".5"
                                  :fill="selectedSide === 'front' ? '#eeecea' : '#ccc'"/>
                        </svg>
                        Frente
                    </button>
                    <button type="button" @click="selectedSide = 'back'"
                            :class="selectedSide === 'back' ? 'bg-fs-dark text-fs-light' : 'bg-white text-fs-gray'"
                            class="border-0 px-2 py-[0.45rem] text-[0.65rem] font-bold tracking-[0.1em] uppercase cursor-pointer font-[inherit] transition-all duration-150 flex items-center justify-center gap-[0.3rem]">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" class="opacity-70">
                            <rect x="1" y="0" width="8" height="12" rx="1"/>
                        </svg>
                        Verso
                    </button>
                </div>
            </div>

            {{-- Color --}}
            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0 mb-[0.4rem]">Cor</p>
                <div class="flex gap-[0.35rem] flex-wrap">
                    @foreach ($colors as $color)
                        <button type="button" @click="selectedColor = '{{ $color->code }}'"
                                title="{{ $color->name }}"
                                :class="selectedColor === '{{ $color->code }}' ? 'ring-2 ring-fs-purple ring-offset-1' : 'ring-1 ring-black/10'"
                                class="w-6 h-6 rounded-full cursor-pointer border-0 transition-all duration-150 outline-none"
                                style="background:#{{ $color->code }}">
                        </button>
                    @endforeach
                </div>
                @if ($selectedColor)
                    @php $colorName = $colors->firstWhere('code', $selectedColor)?->name @endphp
                    <p class="text-[#aaa] text-[0.7rem] mt-1 mb-0">{{ $colorName ?? $selectedColor }}</p>
                @endif
            </div>

            {{-- Size --}}
            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0 mb-[0.4rem]">Tamanho</p>
                <div class="flex gap-[0.3rem] flex-wrap">
                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button type="button" @click="selectedSize = '{{ $size }}'"
                                :class="selectedSize === '{{ $size }}' ? 'bg-fs-dark text-fs-light border-fs-dark' : 'bg-transparent text-fs-gray border-[#d8d5d0]'"
                                class="border px-2 py-[0.25rem] text-[0.75rem] font-semibold cursor-pointer rounded-[1px] transition-all duration-150 min-w-[32px] font-[inherit]">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Qty --}}
            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted m-0 mb-[0.4rem]">Quantidade</p>
                <div class="flex items-center gap-[0.4rem]">
                    <button type="button" @click="qty = Math.max(1, qty - 1)"
                            class="bg-transparent border border-[#d8d5d0] w-7 h-7 text-fs-gray cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark">−</button>
                    <span class="text-fs-dark font-semibold min-w-[1.8rem] text-center text-[0.88rem]" x-text="qty"></span>
                    <button type="button" @click="qty = Math.min(99, qty + 1)"
                            class="bg-transparent border border-[#d8d5d0] w-7 h-7 text-fs-gray cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark">+</button>
                </div>
            </div>

            {{-- Price summary --}}
            @if ($prices)
                <div class="bg-fs-light border border-fs-border px-3 py-[0.6rem] text-[0.75rem] rounded-[1px]">
                    <div class="flex justify-between text-fs-gray mb-[0.2rem]">
                        <span>Por unidade</span>
                        <span class="text-fs-dark font-bold">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                    </div>
                    <div x-show="qty >= {{ $prices->qty_discount }}"
                         class="flex justify-between text-fs-green text-[0.7rem] mb-[0.2rem]">
                        <span>✓ Desc. quantidade</span>
                        <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                    </div>
                    <div class="h-px bg-fs-border my-[0.35rem]"></div>
                    <div class="flex justify-between text-fs-dark font-bold text-[0.9rem]">
                        <span>Total</span>
                        <span x-text="'€' + ((qty >= {{ $prices->qty_discount }} ? {{ $prices->unit_price_catalog_discount }} : {{ $prices->unit_price_catalog }}) * qty).toFixed(2)"></span>
                    </div>
                </div>
            @endif

            {{-- Add to cart form --}}
            <form method="POST" action="{{ route('try-on.cart') }}" class="mt-auto">
                @csrf
                <input type="hidden" name="tshirt_image_id" :value="selectedDesignId">
                <input type="hidden" name="color_code" :value="selectedColor">
                <input type="hidden" name="size" :value="selectedSize">
                <input type="hidden" name="qty" :value="qty">
                <input type="hidden" name="side" :value="selectedSide">
                <button type="submit"
                        class="w-full bg-fs-dark text-fs-light border-0 py-[0.65rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase cursor-pointer rounded-[1px] font-[inherit] transition-colors duration-200 hover:bg-[#333]">
                    + Adicionar ao carrinho
                </button>
            </form>
        </div>
    </div>

    {{-- Three.js scripts (no wire:ignore needed in plain Blade) --}}
    <div>
        <script src="https://unpkg.com/three@0.134.0/build/three.min.js"></script>
        <script src="https://unpkg.com/three@0.134.0/examples/js/loaders/GLTFLoader.js"></script>
        <script>
            (function() {
                const designImages = {
                    @foreach ($designs as $design)
                        @php
                            if (\Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                                $jsImgUrl = route('private-image', $design->image_url);
                            } elseif (str_contains($design->image_url, '/')) {
                                $jsImgUrl = asset('storage/' . $design->image_url);
                            } else {
                                $jsImgUrl = asset('storage/tshirt_images/' . $design->image_url);
                            }
                        @endphp
                        {{ $design->id }}: "{{ $jsImgUrl }}",
                    @endforeach
                };

                const CSS_COLORS = {
                    'white':'#f2f2f2','black':'#111111','gray':'#888888','grey':'#888888',
                    'red':'#cc2222','blue':'#2255cc','green':'#1a8a3a','yellow':'#ddb000',
                    'purple':'#7c3aed','pink':'#e63888','orange':'#d95200','navy':'#1a2f5a',
                    'brown':'#6b3410','cyan':'#0891b2','lime':'#65a30d','indigo':'#4338ca',
                };

                function toHex(c) {
                    if (!c) return '#f2f2f2';
                    const lc = c.toLowerCase();
                    if (CSS_COLORS[lc]) return CSS_COLORS[lc];
                    if (c.startsWith('#')) return c;
                    if (/^[0-9a-fA-F]{6}$/.test(c)) return '#' + c;
                    return '#f2f2f2';
                }

                let currentDesignId = {{ $selectedImageId ?? 'null' }};
                let currentColor = "{{ $selectedColor }}";
                let currentSide = "{{ $selectedSide }}";
                let loadedImages = {};

                const canvas = document.getElementById('tshirt-canvas');
                const container = canvas.parentElement;
                const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                renderer.setClearColor(0xeeecea, 1);
                renderer.shadowMap.enabled = true;
                renderer.shadowMap.type = THREE.PCFSoftShadowMap;

                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 100);
                camera.position.set(0, 0, 3.5);
                camera.lookAt(0, 0, 0);

                scene.add(new THREE.AmbientLight(0xffffff, 0.35));
                const keyLight = new THREE.DirectionalLight(0xfff8f4, 1.1);
                keyLight.position.set(3, 5, 4);
                keyLight.castShadow = true;
                keyLight.shadow.mapSize.set(2048, 2048);
                scene.add(keyLight);
                const fillLight = new THREE.DirectionalLight(0xdde8ff, 0.35);
                fillLight.position.set(-4, 2, 2);
                scene.add(fillLight);
                const rimLight = new THREE.DirectionalLight(0xffffff, 0.2);
                rimLight.position.set(0, 4, -5);
                scene.add(rimLight);

                const shirtGroup = new THREE.Group();
                scene.add(shirtGroup);

                let gltfScene = null, shirtMeshes = [], modelBounds = null, chestUV = null, backUV = null;

                const loader = new THREE.GLTFLoader();
                loader.load('/tshirt/scene.gltf', function(gltf) {
                    gltfScene = gltf.scene;
                    const box = new THREE.Box3().setFromObject(gltfScene);
                    const center = box.getCenter(new THREE.Vector3());
                    const size = box.getSize(new THREE.Vector3());
                    const scale = 1.6 / Math.max(size.x, size.y, size.z);
                    gltfScene.scale.setScalar(scale);
                    gltfScene.position.set(-center.x * scale, -center.y * scale, -center.z * scale);
                    modelBounds = new THREE.Box3().setFromObject(gltfScene);
                    shirtMeshes = [];
                    gltfScene.traverse(child => {
                        if (!child.isMesh) return;
                        shirtMeshes.push(child);
                        child.castShadow = true;
                        child.receiveShadow = true;
                        const mat = new THREE.MeshStandardMaterial({ roughness: 0.75, metalness: 0.02 });
                        child.material = Array.isArray(child.material) ? child.material.map(() => mat.clone()) : mat;
                    });
                    gltfScene.updateMatrixWorld(true);
                    chestUV = findChestUV();
                    backUV = findBackUV();
                    const mc = modelBounds.getCenter(new THREE.Vector3());
                    const ms = modelBounds.getSize(new THREE.Vector3());
                    const dist = ms.y * 2.2;
                    camera.position.set(0, mc.y, dist);
                    camera.lookAt(0, mc.y, 0);
                    shirtGroup.add(gltfScene);
                    rebuildTextures();
                }, undefined, err => console.error('GLTF error:', err));

                function _raycastChest(fromZ, dirZ) {
                    if (!modelBounds || shirtMeshes.length === 0) return null;
                    const h = modelBounds.max.y - modelBounds.min.y;
                    const chestY = modelBounds.min.y + h * 0.65;
                    const ray = new THREE.Raycaster(new THREE.Vector3(0, chestY, fromZ), new THREE.Vector3(0, 0, dirZ));
                    const sorted = [...shirtMeshes].sort((a, b) => b.geometry.attributes.position.count - a.geometry.attributes.position.count);
                    for (const mesh of sorted) {
                        const hits = ray.intersectObject(mesh, false);
                        if (hits.length > 0 && hits[0].uv) return hits[0].uv.clone();
                    }
                    return null;
                }
                function findChestUV() { return _raycastChest(-5, 1); }
                function findBackUV()  { return _raycastChest(5, -1); }

                function buildTexture(mesh) {
                    const size = 1024;
                    const cv = document.createElement('canvas');
                    cv.width = cv.height = size;
                    const ctx = cv.getContext('2d');
                    ctx.fillStyle = toHex(currentColor);
                    ctx.fillRect(0, 0, size, size);
                    const activeUV = currentSide === 'back' ? backUV : chestUV;
                    if (activeUV && currentDesignId && loadedImages[currentDesignId]?.complete) {
                        const img = loadedImages[currentDesignId];
                        const dSize = size * 0.26;
                        const px = activeUV.x * size - dSize / 2;
                        const py = (1 - activeUV.y) * size - dSize / 2;
                        ctx.drawImage(img, px, py, dSize, dSize);
                    }
                    const tex = new THREE.CanvasTexture(cv);
                    tex.flipY = true;
                    return tex;
                }

                function rebuildTextures() {
                    shirtMeshes.forEach(mesh => {
                        const tex = buildTexture(mesh);
                        const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
                        mats.forEach(m => { m.color.set(0xffffff); m.map = tex; m.needsUpdate = true; });
                    });
                }

                function loadDesignImage(id, url, cb) {
                    if (loadedImages[id] !== undefined) { cb(loadedImages[id]); return; }
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => { loadedImages[id] = img; cb(img); };
                    img.onerror = () => { loadedImages[id] = null; cb(null); };
                    img.src = url;
                }

                function updateScene() {
                    if (currentDesignId && designImages[currentDesignId]) {
                        loadDesignImage(currentDesignId, designImages[currentDesignId], () => rebuildTextures());
                    } else {
                        rebuildTextures();
                    }
                }

                let isPointerDown = false, prevX = 0, prevY = 0;
                let rotY = 0, rotX = 0, autoRotate = true;

                canvas.addEventListener('pointerdown', e => {
                    isPointerDown = true; prevX = e.clientX; prevY = e.clientY;
                    autoRotate = false; canvas.style.cursor = 'grabbing';
                    document.getElementById('drag-hint').style.opacity = '0';
                });
                window.addEventListener('pointermove', e => {
                    if (!isPointerDown) return;
                    rotY += (e.clientX - prevX) * 0.012;
                    rotX += (e.clientY - prevY) * 0.007;
                    rotX = Math.max(-0.52, Math.min(0.52, rotX));
                    shirtGroup.rotation.y = rotY; shirtGroup.rotation.x = rotX;
                    prevX = e.clientX; prevY = e.clientY;
                });
                window.addEventListener('pointerup', () => { isPointerDown = false; canvas.style.cursor = 'grab'; });

                canvas.addEventListener('touchstart', e => {
                    if (e.touches.length !== 1) return;
                    isPointerDown = true; prevX = e.touches[0].clientX; prevY = e.touches[0].clientY;
                    autoRotate = false; document.getElementById('drag-hint').style.opacity = '0';
                }, { passive: true });
                canvas.addEventListener('touchmove', e => {
                    if (!isPointerDown || e.touches.length !== 1) return;
                    rotY += (e.touches[0].clientX - prevX) * 0.012;
                    rotX += (e.touches[0].clientY - prevY) * 0.007;
                    rotX = Math.max(-0.52, Math.min(0.52, rotX));
                    shirtGroup.rotation.y = rotY; shirtGroup.rotation.x = rotX;
                    prevX = e.touches[0].clientX; prevY = e.touches[0].clientY;
                }, { passive: true });
                canvas.addEventListener('touchend', () => { isPointerDown = false; });

                window.resetRotation = () => {
                    rotY = 0; rotX = 0; shirtGroup.rotation.set(0, 0, 0); autoRotate = true;
                };

                function resize() {
                    const w = container.clientWidth, h = container.clientHeight;
                    if (!w || !h) return;
                    renderer.setSize(w, h);
                    camera.aspect = w / h;
                    camera.updateProjectionMatrix();
                }
                new ResizeObserver(resize).observe(container);
                resize(); setTimeout(resize, 150);

                let lastTime = 0;
                function animate(t) {
                    requestAnimationFrame(animate);
                    const dt = Math.min((t - lastTime) / 1000, 0.05);
                    lastTime = t;
                    if (autoRotate && !isPointerDown) {
                        rotY += dt * 0.28;
                        shirtGroup.rotation.y = rotY;
                    }
                    renderer.render(scene, camera);
                }
                animate(0);

                let lastColor = currentColor, lastDesign = currentDesignId, lastSide = currentSide;
                setInterval(() => {
                    const c = document.getElementById('livewire-color')?.value;
                    const d = parseInt(document.getElementById('livewire-design')?.value) || null;
                    const s = document.getElementById('livewire-side')?.value || 'front';
                    let changed = false;
                    if (c !== lastColor) { currentColor = c; lastColor = c; changed = true; }
                    if (d !== lastDesign) { currentDesignId = d; lastDesign = d; changed = true; }
                    if (s !== lastSide) {
                        currentSide = s; lastSide = s; changed = true;
                        autoRotate = false;
                        rotY = s === 'front' ? Math.PI : 0;
                        rotX = 0;
                        shirtGroup.rotation.y = rotY;
                        shirtGroup.rotation.x = rotX;
                    }
                    if (changed) updateScene();
                }, 150);
            })();
        </script>
    </div>

    {{-- Hidden inputs polled by Three.js setInterval --}}
    <input type="hidden" id="livewire-color" :value="selectedColor">
    <input type="hidden" id="livewire-design" :value="selectedDesignId">
    <input type="hidden" id="livewire-side" :value="selectedSide">

</div>
@endsection
