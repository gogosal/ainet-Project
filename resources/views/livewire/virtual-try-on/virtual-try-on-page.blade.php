<div style="display:flex;flex-direction:column;height:calc(100vh - 56px - 5rem);">
    {{-- Cart flash --}}
    @if ($cartMessage)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            style="position:fixed;bottom:1.5rem;right:1.5rem;background:#f0faf5;border:1px solid #b7e1cb;color:#2d6a4f;padding:.7rem 1.1rem;font-size:.8rem;z-index:1000;border-radius:1px;">
            ✓ {{ $cartMessage }}
        </div>
    @endif

    {{-- Page header --}}
    <div style="flex-shrink:0;display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
        <a href="{{ route('catalog') }}"
            style="font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#aaa;text-decoration:none;transition:color .15s;"
            onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">
            ← Catálogo
        </a>
        <div style="width:1px;height:14px;background:#e0ddd8;"></div>
        <div>
            <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;">Provador</div>
            <h1 style="font-size:1rem;font-weight:600;color:#1a1a1a;margin:0;letter-spacing:-.01em;">Provador Virtual 3D</h1>
        </div>
    </div>

    {{-- Main 3-panel layout --}}
    <div style="display:grid;grid-template-columns:220px 1fr 250px;grid-template-rows:1fr;gap:1.25rem;flex:1;min-height:0;overflow:hidden;">

        {{-- LEFT: Design list --}}
        <div style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;display:flex;flex-direction:column;min-height:0;height:100%;">
            <div style="padding:.75rem 1rem;border-bottom:1px solid #e0ddd8;">
                <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0;">Designs</p>
            </div>
            <div data-lenis-prevent style="overflow-y:auto;flex:1;padding:.4rem;-webkit-overflow-scrolling:touch;overscroll-behavior:contain;touch-action:pan-y;">
                @foreach ($designs as $design)
                    @php
                        if (\Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')) {
                            $imgUrl = route('private-image', $design->image_url);
                        } elseif (str_contains($design->image_url, '/')) {
                            $imgUrl = asset('storage/' . $design->image_url);
                        } else {
                            $imgUrl = asset('storage/tshirt_images/' . $design->image_url);
                        }
                        $isSelected = $selectedImageId === $design->id;
                    @endphp
                    <button wire:click="selectDesign({{ $design->id }})"
                        style="width:100%;background:{{ $isSelected ? '#fff' : 'transparent' }};border:1px solid {{ $isSelected ? '#7c6fa0' : 'transparent' }};border-radius:1px;padding:.45rem;cursor:pointer;display:flex;align-items:center;gap:.6rem;margin-bottom:.25rem;transition:all .15s;text-align:left;"
                        onmouseover="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='rgba(0,0,0,.04)'"
                        onmouseout="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='transparent'">
                        <div style="width:48px;height:48px;flex-shrink:0;background:#f5f4f1;border-radius:1px;overflow:hidden;border:1px solid #e0ddd8;">
                            <img src="{{ $imgUrl }}" alt="{{ $design->name }}"
                                style="width:100%;height:100%;object-fit:cover;display:block;"
                                onerror="this.parentElement.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#ccc;font-size:1rem;\'>?</span>'">
                        </div>
                        <div style="min-width:0;flex:1;">
                            <p style="color:{{ $isSelected ? '#1a1a1a' : '#888' }};font-size:.78rem;font-weight:{{ $isSelected ? '600' : '400' }};margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $design->name }}</p>
                            @if ($design->category)
                                <span style="color:#b8b4ae;font-size:.68rem;">{{ $design->category->name }}</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- CENTER: 3D Viewport --}}
        <div wire:ignore
            style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;position:relative;overflow:hidden;min-height:0;box-shadow:inset 0 2px 20px rgba(0,0,0,.06);">
            <canvas id="tshirt-canvas" style="width:100%;height:100%;display:block;cursor:grab;"></canvas>
            <div id="drag-hint"
                style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);background:rgba(255,255,255,.75);border:1px solid #d8d5d0;border-radius:20px;padding:.3rem .9rem;color:#aaa;font-size:.72rem;pointer-events:none;transition:opacity .5s;backdrop-filter:blur(4px);">
                ↔ Arrasta para rodar
            </div>
            <button id="reset-btn" onclick="window.resetRotation && window.resetRotation()"
                style="position:absolute;top:.75rem;right:.75rem;background:rgba(255,255,255,.8);border:1px solid #d8d5d0;color:#888;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.3rem .65rem;cursor:pointer;transition:all .15s;border-radius:1px;backdrop-filter:blur(4px);"
                onmouseover="this.style.borderColor='#7c6fa0';this.style.color='#7c6fa0'"
                onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                ⟳ Resetar
            </button>
        </div>

        {{-- RIGHT: Controls --}}
        <div style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;padding:.85rem;display:flex;flex-direction:column;gap:.75rem;">
            @if ($selectedImage)
                <div style="border-bottom:1px solid #e0ddd8;padding-bottom:.6rem;">
                    <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0 0 .2rem;">Design</p>
                    <p style="color:#1a1a1a;font-size:.84rem;font-weight:600;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $selectedImage->name }}</p>
                </div>
            @endif

            {{-- Side toggle --}}
            <div>
                <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0 0 .5rem;">Posição da estampa</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:#e0ddd8;border:1px solid #e0ddd8;border-radius:1px;overflow:hidden;">
                    <button wire:click="selectSide('front')"
                        style="background:{{ $selectedSide === 'front' ? '#1a1a1a' : '#fff' }};color:{{ $selectedSide === 'front' ? '#f5f4f1' : '#888' }};border:none;padding:.45rem .5rem;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:inherit;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:.3rem;">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" style="opacity:.7;"><rect x="1" y="0" width="8" height="12" rx="1"/><rect x="3" y="3" width="4" height="4" rx=".5" fill="{{ $selectedSide === 'front' ? '#eeecea' : '#ccc' }}"/></svg>
                        Frente
                    </button>
                    <button wire:click="selectSide('back')"
                        style="background:{{ $selectedSide === 'back' ? '#1a1a1a' : '#fff' }};color:{{ $selectedSide === 'back' ? '#f5f4f1' : '#888' }};border:none;padding:.45rem .5rem;font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;font-family:inherit;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:.3rem;">
                        <svg width="10" height="12" viewBox="0 0 10 12" fill="currentColor" style="opacity:.7;"><rect x="1" y="0" width="8" height="12" rx="1"/></svg>
                        Verso
                    </button>
                </div>
            </div>

            <div>
                <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0 0 .4rem;">Cor</p>
                <div style="display:flex;gap:.35rem;flex-wrap:wrap;">
                    @foreach ($colors as $color)
                        <button wire:click="selectColor('{{ $color->code }}')" title="{{ $color->name }}"
                            style="width:24px;height:24px;border-radius:50%;background:#{{ $color->code }};border:2px solid {{ $selectedColor === $color->code ? '#7c6fa0' : 'transparent' }};cursor:pointer;box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c6fa0' : 'inset 0 0 0 1px rgba(0,0,0,.12)' }};transition:all .15s;outline:none;">
                        </button>
                    @endforeach
                </div>
                @if ($selectedColor)
                    @php $colorName = $colors->firstWhere('code', $selectedColor)?->name @endphp
                    <p style="color:#aaa;font-size:.7rem;margin:.25rem 0 0;">{{ $colorName ?? $selectedColor }}</p>
                @endif
            </div>

            <div>
                <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0 0 .4rem;">Tamanho</p>
                <div style="display:flex;gap:.3rem;flex-wrap:wrap;">
                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button wire:click="$set('selectedSize', '{{ $size }}')"
                            style="background:{{ $selectedSize === $size ? '#1a1a1a' : 'transparent' }};color:{{ $selectedSize === $size ? '#f5f4f1' : '#888' }};border:1px solid {{ $selectedSize === $size ? '#1a1a1a' : '#d8d5d0' }};padding:.25rem .5rem;font-size:.75rem;font-weight:600;cursor:pointer;border-radius:1px;transition:all .15s;min-width:32px;font-family:inherit;">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <p style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin:0 0 .4rem;">Quantidade</p>
                <div style="display:flex;align-items:center;gap:.4rem;">
                    <button wire:click="$set('qty', max(1, $qty - 1))"
                        style="background:transparent;border:1px solid #d8d5d0;width:28px;height:28px;color:#888;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;border-radius:1px;font-family:inherit;transition:all .15s;"
                        onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">−</button>
                    <span style="color:#1a1a1a;font-weight:600;min-width:1.8rem;text-align:center;font-size:.88rem;">{{ $qty }}</span>
                    <button wire:click="$set('qty', min(99, $qty + 1))"
                        style="background:transparent;border:1px solid #d8d5d0;width:28px;height:28px;color:#888;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;border-radius:1px;font-family:inherit;transition:all .15s;"
                        onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">+</button>
                </div>
            </div>

            @if ($prices)
                <div style="background:#f5f4f1;border:1px solid #e0ddd8;padding:.6rem .75rem;font-size:.75rem;border-radius:1px;">
                    <div style="display:flex;justify-content:space-between;color:#888;margin-bottom:.2rem;">
                        <span>Por unidade</span>
                        <span style="color:#1a1a1a;font-weight:700;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                    </div>
                    @if ($qty >= $prices->qty_discount)
                        <div style="display:flex;justify-content:space-between;color:#2d6a4f;font-size:.7rem;margin-bottom:.2rem;">
                            <span>✓ Desc. quantidade</span>
                            <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    @endif
                    <div style="height:1px;background:#e0ddd8;margin:.35rem 0;"></div>
                    <div style="display:flex;justify-content:space-between;color:#1a1a1a;font-weight:700;font-size:.9rem;">
                        <span>Total</span>
                        <span>€{{ number_format(($qty >= $prices->qty_discount ? $prices->unit_price_catalog_discount : $prices->unit_price_catalog) * $qty, 2) }}</span>
                    </div>
                </div>
            @endif

            <button wire:click="addToCart"
                style="width:100%;background:#1a1a1a;color:#f5f4f1;border:none;padding:.65rem;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;transition:background .2s;margin-top:auto;border-radius:1px;font-family:inherit;"
                onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                + Adicionar ao carrinho
            </button>
        </div>
    </div>

    {{-- Three.js + GLTFLoader – wire:ignore prevents re-execution on Livewire re-renders --}}
    <div wire:ignore>
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
                    // bare 6-char hex (stored in DB without #)
                    if (/^[0-9a-fA-F]{6}$/.test(c)) return '#' + c;
                    return '#f2f2f2';
                }

                let currentDesignId = {{ $selectedImageId ?? 'null' }};
                let currentColor = "{{ $selectedColor }}";
                let currentSide = "{{ $selectedSide }}";
                let loadedImages = {};

                // ── Renderer ──
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

                // ── Lighting ──
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

                // ── GLTF Model ──
                let gltfScene   = null;
                let shirtMeshes = [];
                let modelBounds = null;
                let chestUV     = null; // Cached UV hit on front chest
                let backUV      = null; // Cached UV hit on back

                const loader = new THREE.GLTFLoader();
                loader.load(
                    '/tshirt/scene.gltf',
                    function(gltf) {
                        gltfScene = gltf.scene;

                        // Auto-scale & center
                        const box    = new THREE.Box3().setFromObject(gltfScene);
                        const center = box.getCenter(new THREE.Vector3());
                        const size   = box.getSize(new THREE.Vector3());
                        const scale  = 1.6 / Math.max(size.x, size.y, size.z);

                        gltfScene.scale.setScalar(scale);
                        gltfScene.position.set(-center.x * scale, -center.y * scale, -center.z * scale);

                        modelBounds = new THREE.Box3().setFromObject(gltfScene);

                        // Collect meshes, replace materials
                        shirtMeshes = [];
                        gltfScene.traverse(child => {
                            if (!child.isMesh) return;
                            shirtMeshes.push(child);
                            child.castShadow = true;
                            child.receiveShadow = true;
                            // Keep a MeshStandardMaterial per mesh — we'll paint texture into it
                            const mat = new THREE.MeshStandardMaterial({ roughness: 0.75, metalness: 0.02 });
                            child.material = Array.isArray(child.material) ? child.material.map(() => mat.clone()) : mat;
                        });

                        // Force matrix world update so raycaster can work
                        gltfScene.updateMatrixWorld(true);

                        // Find UV hit point on front and back chest
                        chestUV = findChestUV();
                        backUV  = findBackUV();

                        // Fit camera — push back enough to see the whole shirt small
                        const mc   = modelBounds.getCenter(new THREE.Vector3());
                        const ms   = modelBounds.getSize(new THREE.Vector3());
                        const dist = ms.y * 2.2; // enough margin around the shirt
                        camera.position.set(0, mc.y, dist);
                        camera.lookAt(0, mc.y, 0);

                        shirtGroup.add(gltfScene);

                        // Render initial state
                        rebuildTextures();
                    },
                    undefined,
                    err => console.error('GLTF error:', err)
                );

                // ── Find UV at chest via raycasting ──
                function _raycastChest(fromZ, dirZ) {
                    if (!modelBounds || shirtMeshes.length === 0) return null;
                    const h      = modelBounds.max.y - modelBounds.min.y;
                    const chestY = modelBounds.min.y + h * 0.65;
                    const ray    = new THREE.Raycaster(
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
                // Model's front faces -Z: shoot from -Z toward +Z to hit front chest
                function findChestUV() { return _raycastChest(-5, 1); }
                function findBackUV()  { return _raycastChest(5, -1); }

                // ── Build per-mesh canvas texture (color + design baked in) ──
                function buildTexture(mesh) {
                    const size = 1024;
                    const cv   = document.createElement('canvas');
                    cv.width = cv.height = size;
                    const ctx  = cv.getContext('2d');

                    // Base shirt color
                    ctx.fillStyle = toHex(currentColor);
                    ctx.fillRect(0, 0, size, size);

                    // Paint design at the UV chest position (front or back)
                    const activeUV = currentSide === 'back' ? backUV : chestUV;
                    if (activeUV && currentDesignId && loadedImages[currentDesignId]?.complete) {
                        const img   = loadedImages[currentDesignId];
                        const dSize = size * 0.26;
                        // UV (0,0) = bottom-left; canvas (0,0) = top-left → flip Y
                        const px = activeUV.x * size - dSize / 2;
                        const py = (1 - activeUV.y) * size - dSize / 2;
                        ctx.drawImage(img, px, py, dSize, dSize);
                    }

                    const tex = new THREE.CanvasTexture(cv);
                    tex.flipY = true; // default, matches Three.js UV convention
                    return tex;
                }

                function rebuildTextures() {
                    shirtMeshes.forEach(mesh => {
                        const tex  = buildTexture(mesh);
                        const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
                        mats.forEach(m => {
                            m.color.set(0xffffff); // texture carries the color
                            m.map = tex;
                            m.needsUpdate = true;
                        });
                    });
                }

                // ── Image loading ──
                function loadDesignImage(id, url, cb) {
                    if (loadedImages[id] !== undefined) { cb(loadedImages[id]); return; }
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload  = () => { loadedImages[id] = img;  cb(img);  };
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

                // ── Rotation Controls ──
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

                // ── Resize ──
                function resize() {
                    const w = container.clientWidth, h = container.clientHeight;
                    if (!w || !h) return;
                    renderer.setSize(w, h);
                    camera.aspect = w / h;
                    camera.updateProjectionMatrix();
                }
                new ResizeObserver(resize).observe(container);
                resize(); setTimeout(resize, 150);

                // ── Render Loop ──
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

                // ── Livewire State Sync ──
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
                        // Model front faces -Z: PI shows front, 0 shows back
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
