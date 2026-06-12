<div class="flex flex-col h-[calc(100vh-56px-5rem)]">
    {{-- Cart flash --}}
    @if ($cartMessage ?? false)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-6 right-6 bg-[#f0faf5] border border-[#b7e1cb] text-[#2d6a4f] py-[0.7rem] px-[1.1rem] text-[0.8rem] z-[1000] rounded-[1px]">
            ✓ {{ $cartMessage }}
        </div>
    @endif

    {{-- Page header --}}
    <div class="shrink-0 flex items-center gap-4 mb-4">
        <a href="{{ route('catalog') }}"
            class="text-[0.68rem] font-bold tracking-[0.1em] uppercase text-[#aaa] no-underline transition-colors duration-150 hover:text-[#1a1a1a]">
            ← Catálogo
        </a>
        <div class="w-px h-[14px] bg-[#e0ddd8]"></div>
        <div>
            <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-[#b8b4ae]">Provador</div>
            <h1 class="text-base font-semibold text-[#1a1a1a] m-0 tracking-[-0.01em]">Provador Virtual 3D</h1>
        </div>
    </div>

    {{-- Main 3-panel layout (Grelha original restaurada para manter o ThreeJS no meio) --}}
    <div class="grid grid-cols-[220px_1fr_250px] grid-rows-1 gap-5 flex-1 min-h-0 overflow-hidden">

        {{-- LEFT: Design list + Filtros Embutidos --}}
        <div class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] overflow-hidden flex flex-col min-h-0 h-full">

            {{-- Secção de Filtros adicionada ao topo da própria barra de designs --}}
            <div class="p-3 bg-[#eeecea] border-b border-[#e0ddd8] flex flex-col gap-3">
                <div>
                    <label
                        class="block text-[0.55rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-1">Pesquisa</label>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Pesquisar..."
                        class="w-full bg-transparent border-0 border-b border-[#ccc9c3] py-1 px-0 text-[#1a1a1a] text-[0.8rem] outline-none font-inherit transition-colors duration-200 focus:border-[#7c6fa0] focus:ring-0">
                </div>
                <div>
                    <label
                        class="block text-[0.55rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-1">Categoria</label>
                    <div class="relative">
                        <select wire:model.live="categoryId"
                            class="w-full bg-white border border-[#d8d5d0] text-[#1a1a1a] text-[0.75rem] py-2 pl-3 pr-8 rounded-md outline-none appearance-none cursor-pointer transition-colors duration-200 focus:border-[#7c6fa0] focus:ring-1 focus:ring-[#7c6fa0]">
                            <option value="">Todas</option>
                            @if (isset($categories))
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            @endif
                            <option value="-1">Sem categoria</option>
                        </select>

                        {{-- Seta simples encostada à direita --}}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#aaa]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-[0.75rem] px-4 border-b border-[#e0ddd8]">
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0">Designs</p>
            </div>

            <div data-lenis-prevent class="overflow-y-auto flex-1 p-[0.4rem] touch-pan-y overscroll-contain">
                @foreach ($designs as $design)
                    @php
                        $bare = basename($design->image_url);
                        if (\Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                            $imgUrl = route('private-image', $bare);
                        } elseif (str_contains($design->image_url, '/')) {
                            $imgUrl = asset('storage/' . $design->image_url);
                        } else {
                            $imgUrl = asset('storage/tshirt_images/' . $bare);
                        }
                        $isSelected = $selectedImageId === $design->id;
                    @endphp
                    <button wire:click="selectDesign({{ $design->id }})"
                        class="w-full rounded-[1px] p-[0.45rem] cursor-pointer flex items-center gap-[0.6rem] mb-1 transition-all duration-150 text-left {{ $isSelected ? 'bg-white border border-[#7c6fa0]' : 'bg-transparent border border-transparent hover:bg-black/5' }}">
                        <div
                            class="w-[48px] h-[48px] shrink-0 bg-[#f5f4f1] rounded-[1px] overflow-hidden border border-[#e0ddd8]">
                            <img src="{{ $imgUrl }}" alt="{{ $design->name }}"
                                class="w-full h-full object-cover block"
                                onerror="this.parentElement.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#ccc;font-size:1rem;\'>?</span>'">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p
                                class="text-[0.78rem] m-0 whitespace-nowrap overflow-hidden text-ellipsis {{ $isSelected ? 'text-[#1a1a1a] font-semibold' : 'text-[#888] font-normal' }}">
                                {{ $design->name }}
                            </p>
                            @if ($design->category)
                                <span
                                    class="text-[#b8b4ae] text-[0.68rem] block truncate">{{ $design->category->name }}</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- CENTER: 3D Viewport (Intacto no centro) --}}
        <div wire:ignore
            class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] relative overflow-hidden min-h-0 shadow-[inset_0_2px_20px_rgba(0,0,0,0.06)]">
            <canvas id="tshirt-canvas" class="w-full h-full block cursor-grab"></canvas>
            <div id="drag-hint"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/75 border border-[#d8d5d0] rounded-[20px] py-[0.3rem] px-[0.9rem] text-[#aaa] text-[0.72rem] pointer-events-none transition-opacity duration-500 backdrop-blur-[4px]">
                ↔ Arrasta para rodar
            </div>
            <button id="reset-btn" onclick="window.resetRotation && window.resetRotation()"
                class="absolute top-[0.75rem] right-[0.75rem] bg-white/80 border border-[#d8d5d0] text-[#888] text-[0.68rem] font-bold tracking-[0.1em] uppercase py-[0.3rem] px-[0.65rem] cursor-pointer transition-all duration-150 rounded-[1px] backdrop-blur-[4px] hover:border-[#7c6fa0] hover:text-[#7c6fa0]">
                ⟳ Resetar
            </button>
        </div>

        {{-- RIGHT: Controls --}}
        <div
            class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] p-[0.85rem] flex flex-col gap-[0.75rem] overflow-y-auto">
            @if ($selectedImage)
                <div class="border-b border-[#e0ddd8] pb-[0.6rem]">
                    <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0 mb-[0.2rem]">
                        Design</p>
                    <p
                        class="text-[#1a1a1a] text-[0.84rem] font-semibold m-0 whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ $selectedImage->name }}</p>
                </div>
            @endif

            {{-- Side toggle --}}
            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0 mb-[0.5rem]">Posição
                    da estampa</p>
                <div class="grid grid-cols-2 gap-px bg-[#e0ddd8] border border-[#e0ddd8] rounded-[1px] overflow-hidden">
                    <button wire:click="selectSide('front')"
                        class="border-none py-[0.45rem] px-[0.5rem] text-[0.65rem] font-bold tracking-[0.1em] uppercase cursor-pointer font-inherit transition-all duration-150 flex items-center justify-center gap-[0.3rem] {{ $selectedSide === 'front' ? 'bg-[#1a1a1a] text-[#f5f4f1]' : 'bg-white text-[#888]' }}">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" class="opacity-70">
                            <rect x="1" y="0" width="8" height="12" rx="1" />
                            <rect x="3" y="3" width="4" height="4" rx=".5"
                                fill="{{ $selectedSide === 'front' ? '#eeecea' : '#ccc' }}" />
                        </svg>
                        Frente
                    </button>
                    <button wire:click="selectSide('back')"
                        class="border-none py-[0.45rem] px-[0.5rem] text-[0.65rem] font-bold tracking-[0.1em] uppercase cursor-pointer font-inherit transition-all duration-150 flex items-center justify-center gap-[0.3rem] {{ $selectedSide === 'back' ? 'bg-[#1a1a1a] text-[#f5f4f1]' : 'bg-white text-[#888]' }}">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" class="opacity-70">
                            <rect x="1" y="0" width="8" height="12" rx="1" />
                        </svg>
                        Verso
                    </button>
                </div>
            </div>

            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0 mb-[0.4rem]">Cor</p>
                <div class="flex gap-[0.35rem] flex-wrap">
                    @foreach ($colors as $color)
                        <button wire:click="selectColor('{{ $color->code }}')" title="{{ $color->name }}"
                            class="w-[24px] h-[24px] rounded-full cursor-pointer transition-all duration-150 outline-none"
                            style="background:#{{ $color->code }}; border:2px solid {{ $selectedColor === $color->code ? '#7c6fa0' : 'transparent' }}; box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c6fa0' : 'inset 0 0 0 1px rgba(0,0,0,.12)' }};">
                        </button>
                    @endforeach
                </div>
                @if ($selectedColor)
                    @php $colorName = $colors->firstWhere('code', $selectedColor)?->name @endphp
                    <p class="text-[#aaa] text-[0.7rem] m-0 mt-[0.25rem]">{{ $colorName ?? $selectedColor }}</p>
                @endif
            </div>

            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0 mb-[0.4rem]">Tamanho
                </p>
                <div class="flex gap-[0.3rem] flex-wrap">
                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button wire:click="$set('selectedSize', '{{ $size }}')"
                            class="border py-[0.25rem] px-[0.5rem] text-[0.75rem] font-semibold cursor-pointer rounded-[1px] transition-colors duration-150 min-w-[32px] font-inherit {{ $selectedSize === $size ? 'bg-[#1a1a1a] text-[#f5f4f1] border-[#1a1a1a]' : 'bg-transparent text-[#888] border-[#d8d5d0]' }}">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] m-0 mb-[0.4rem]">
                    Quantidade</p>
                <div class="flex items-center gap-[0.4rem]">
                    <button wire:click="$set('qty', max(1, $qty - 1))"
                        class="bg-transparent border border-[#d8d5d0] w-[28px] h-[28px] text-[#888] cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-inherit transition-colors duration-150 hover:border-[#1a1a1a]">
                        −
                    </button>
                    <span
                        class="text-[#1a1a1a] font-semibold min-w-[1.8rem] text-center text-[0.88rem]">{{ $qty }}</span>
                    <button wire:click="$set('qty', min(99, $qty + 1))"
                        class="bg-transparent border border-[#d8d5d0] w-[28px] h-[28px] text-[#888] cursor-pointer text-[0.9rem] flex items-center justify-center rounded-[1px] font-inherit transition-colors duration-150 hover:border-[#1a1a1a]">
                        +
                    </button>
                </div>
            </div>

            @if ($prices ?? false)
                <div class="bg-[#f5f4f1] border border-[#e0ddd8] py-[0.6rem] px-[0.75rem] text-[0.75rem] rounded-[1px]">
                    <div class="flex justify-between text-[#888] mb-[0.2rem]">
                        <span>Por unidade</span>
                        <span
                            class="text-[#1a1a1a] font-bold">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                    </div>
                    @if ($qty >= $prices->qty_discount)
                        <div class="flex justify-between text-[#2d6a4f] text-[0.7rem] mb-[0.2rem]">
                            <span>✓ Desc. quantidade</span>
                            <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    @endif
                    <div class="h-px bg-[#e0ddd8] my-[0.35rem]"></div>
                    <div class="flex justify-between text-[#1a1a1a] font-bold text-[0.9rem]">
                        <span>Total</span>
                        <span>€{{ number_format(($qty >= $prices->qty_discount ? $prices->unit_price_catalog_discount : $prices->unit_price_catalog) * $qty, 2) }}</span>
                    </div>
                </div>
            @endif

            <button wire:click="addToCart"
                class="w-full bg-[#1a1a1a] text-[#f5f4f1] border-none p-[0.65rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase cursor-pointer transition-colors duration-200 mt-auto rounded-[1px] font-inherit hover:bg-[#333]">
                + Adicionar ao carrinho
            </button>
        </div>
    </div>

    {{-- Three.js + GLTFLoader – Usa $allDesigns para mapear tudo sem quebras no wire:ignore --}}
    <div wire:ignore>
        <script src="https://unpkg.com/three@0.134.0/build/three.min.js"></script>
        <script src="https://unpkg.com/three@0.134.0/examples/js/loaders/GLTFLoader.js"></script>
        <script>
            (function() {
                const designImages = {
                    @foreach ($allDesigns as $design)
                        @php
                            $bare = basename($design->image_url);
                            if (\Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                                $jsImgUrl = route('private-image', $bare);
                            } elseif (str_contains($design->image_url, '/')) {
                                $jsImgUrl = asset('storage/' . $design->image_url);
                            } else {
                                $jsImgUrl = asset('storage/tshirt_images/' . $bare);
                            }
                        @endphp
                        {{ $design->id }}: "{{ $jsImgUrl }}",
                    @endforeach
                };

                const CSS_COLORS = {
                    'white': '#f2f2f2',
                    'black': '#111111',
                    'gray': '#888888',
                    'grey': '#888888',
                    'red': '#cc2222',
                    'blue': '#2255cc',
                    'green': '#1a8a3a',
                    'yellow': '#ddb000',
                    'purple': '#7c3aed',
                    'pink': '#e63888',
                    'orange': '#d95200',
                    'navy': '#1a2f5a',
                    'brown': '#6b3410',
                    'cyan': '#0891b2',
                    'lime': '#65a30d',
                    'indigo': '#4338ca',
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
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
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

                let gltfScene = null;
                let shirtMeshes = [];
                let modelBounds = null;
                let chestUV = null;
                let backUV = null;

                const loader = new THREE.GLTFLoader();
                loader.load(
                    '/tshirt/scene.gltf',
                    function(gltf) {
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
                            const mat = new THREE.MeshStandardMaterial({
                                roughness: 0.75,
                                metalness: 0.02
                            });
                            child.material = Array.isArray(child.material) ? child.material.map(() => mat
                                .clone()) : mat;
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
                    },
                    undefined,
                    err => console.error('GLTF error:', err)
                );

                function _raycastChest(fromZ, dirZ) {
                    if (!modelBounds || shirtMeshes.length === 0) return null;
                    const h = modelBounds.max.y - modelBounds.min.y;
                    const chestY = modelBounds.min.y + h * 0.65;
                    const ray = new THREE.Raycaster(
                        new THREE.Vector3(0, chestY, fromZ),
                        new THREE.Vector3(0, 0, dirZ)
                    );
                    const sorted = [...shirtMeshes].sort((a, b) =>
                        b.geometry.attributes.position.count - a.geometry.attributes.position.count
                    );
                    for (const mesh of sorted) {
                        const hits = ray.intersectObject(mesh, false);
                        if (hits.length > 0 && hits[0].uv) return hits[0].uv.clone();
                    }
                    return null;
                }

                function findChestUV() {
                    return _raycastChest(-5, 1);
                }

                function findBackUV() {
                    return _raycastChest(5, -1);
                }

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
                        mats.forEach(m => {
                            m.color.set(0xffffff);
                            m.map = tex;
                            m.needsUpdate = true;
                        });
                    });
                }

                function loadDesignImage(id, url, cb) {
                    if (loadedImages[id] !== undefined) {
                        cb(loadedImages[id]);
                        return;
                    }
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => {
                        loadedImages[id] = img;
                        cb(img);
                    };
                    img.onerror = () => {
                        loadedImages[id] = null;
                        cb(null);
                    };
                    img.src = url;
                }

                function updateScene() {
                    if (currentDesignId && designImages[currentDesignId]) {
                        loadDesignImage(currentDesignId, designImages[currentDesignId], () => rebuildTextures());
                    } else {
                        rebuildTextures();
                    }
                }

                let isPointerDown = false,
                    prevX = 0,
                    prevY = 0;
                let rotY = 0,
                    rotX = 0,
                    autoRotate = true;

                canvas.addEventListener('pointerdown', e => {
                    isPointerDown = true;
                    prevX = e.clientX;
                    prevY = e.clientY;
                    autoRotate = false;
                    canvas.style.cursor = 'grabbing';
                    document.getElementById('drag-hint').style.opacity = '0';
                });
                window.addEventListener('pointermove', e => {
                    if (!isPointerDown) return;
                    rotY += (e.clientX - prevX) * 0.012;
                    rotX += (e.clientY - prevY) * 0.007;
                    rotX = Math.max(-0.52, Math.min(0.52, rotX));
                    shirtGroup.rotation.y = rotY;
                    shirtGroup.rotation.x = rotX;
                    prevX = e.clientX;
                    prevY = e.clientY;
                });
                window.addEventListener('pointerup', () => {
                    isPointerDown = false;
                    canvas.style.cursor = 'grab';
                });

                canvas.addEventListener('touchstart', e => {
                    if (e.touches.length !== 1) return;
                    isPointerDown = true;
                    prevX = e.touches[0].clientX;
                    prevY = e.touches[0].clientY;
                    autoRotate = false;
                    document.getElementById('drag-hint').style.opacity = '0';
                }, {
                    passive: true
                });
                canvas.addEventListener('touchmove', e => {
                    if (!isPointerDown || e.touches.length !== 1) return;
                    rotY += (e.touches[0].clientX - prevX) * 0.012;
                    rotX += (e.touches[0].clientY - prevY) * 0.007;
                    rotX = Math.max(-0.52, Math.min(0.52, rotX));
                    shirtGroup.rotation.y = rotY;
                    shirtGroup.rotation.x = rotX;
                    prevX = e.touches[0].clientX;
                    prevY = e.touches[0].clientY;
                }, {
                    passive: true
                });
                canvas.addEventListener('touchend', () => {
                    isPointerDown = false;
                });

                window.resetRotation = () => {
                    rotY = 0;
                    rotX = 0;
                    shirtGroup.rotation.set(0, 0, 0);
                    autoRotate = true;
                };

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

                let lastColor = currentColor,
                    lastDesign = currentDesignId,
                    lastSide = currentSide;
                setInterval(() => {
                    const c = document.getElementById('livewire-color')?.value;
                    const d = parseInt(document.getElementById('livewire-design')?.value) || null;
                    const s = document.getElementById('livewire-side')?.value || 'front';
                    let changed = false;
                    if (c !== lastColor) {
                        currentColor = c;
                        lastColor = c;
                        changed = true;
                    }
                    if (d !== lastDesign) {
                        currentDesignId = d;
                        lastDesign = d;
                        changed = true;
                    }
                    if (s !== lastSide) {
                        currentSide = s;
                        lastSide = s;
                        changed = true;
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

    <input type="hidden" id="livewire-color" value="{{ $selectedColor }}">
    <input type="hidden" id="livewire-design" value="{{ $selectedImageId ?? '' }}">
    <input type="hidden" id="livewire-side" value="{{ $selectedSide }}">
</div>
