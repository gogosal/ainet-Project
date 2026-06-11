<div style="display:flex;flex-direction:column;height:calc(100vh - 64px - 4rem);">
    {{-- Cart flash --}}
    @if ($cartMessage)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            style="position:fixed;bottom:1.5rem;right:1.5rem;background:#1a1a2e;border:1px solid #7c3aed;border-radius:8px;padding:0.75rem 1.25rem;color:#a78bfa;font-size:0.85rem;z-index:1000;box-shadow:0 8px 24px rgba(124,58,237,.25);">
            ✓ {{ $cartMessage }}
        </div>
    @endif

    {{-- Page header --}}
    <div style="flex-shrink:0;display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;">
        <a href="{{ route('catalog') }}"
            style="display:inline-flex;align-items:center;gap:0.4rem;color:#64748b;text-decoration:none;font-size:0.85rem;padding:0.4rem 0.75rem;border:1px solid #1e1e30;border-radius:6px;transition:all .15s;"
            onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'"
            onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
            ← Catálogo
        </a>
        <div>
            <h1 style="color:#e2e8f0;font-size:1.4rem;font-weight:700;margin:0;">Provador Virtual 3D</h1>
            <p style="color:#64748b;font-size:0.82rem;margin:0;">Arrasta para rodar · Escolhe design e cor</p>
        </div>
    </div>

    {{-- Main 3-panel layout --}}
    <div style="display:grid;grid-template-columns:230px 1fr 260px;gap:1.25rem;flex:1;min-height:0;">

        {{-- LEFT: Design list --}}
        <div
            style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;display:flex;flex-direction:column;">
            <div style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;">
                <p
                    style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0;text-transform:uppercase;letter-spacing:.05em;">
                    Designs</p>
            </div>
            <div style="overflow-y:auto;flex:1;padding:0.5rem;">
                @foreach ($designs as $design)
                    @php
                        $imgUrl = \Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images/')
                            ? route('private-image', $design->image_url)
                            : asset('storage/' . $design->image_url);
                        $isSelected = $selectedImageId === $design->id;
                    @endphp
                    <button wire:click="selectDesign({{ $design->id }})"
                        style="width:100%;background:{{ $isSelected ? 'rgba(124,58,237,.18)' : 'transparent' }};border:1px solid {{ $isSelected ? '#7c3aed' : 'transparent' }};border-radius:8px;padding:0.5rem;cursor:pointer;display:flex;align-items:center;gap:0.65rem;margin-bottom:0.3rem;transition:all .15s;text-align:left;"
                        onmouseover="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='rgba(255,255,255,.04)'"
                        onmouseout="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='transparent'">
                        {{-- Actual design image thumbnail --}}
                        <div
                            style="width:52px;height:52px;flex-shrink:0;background:#0d0d1a;border-radius:7px;overflow:hidden;border:1px solid rgba(255,255,255,.06);">
                            <img src="{{ $imgUrl }}" alt="{{ $design->name }}"
                                style="width:100%;height:100%;object-fit:cover;display:block;"
                                onerror="this.parentElement.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#333;font-size:1.2rem;\'>◈</span>'">
                        </div>
                        <div style="min-width:0;flex:1;">
                            <p
                                style="color:{{ $isSelected ? '#e2e8f0' : '#94a3b8' }};font-size:0.80rem;font-weight:{{ $isSelected ? '600' : '400' }};margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $design->name }}</p>
                            @if ($design->category)
                                <span style="color:#64748b;font-size:0.70rem;">{{ $design->category->name }}</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- CENTER: 3D Viewport --}}
        <div wire:ignore
            style="background:#0a0a0f;border:1px solid #1e1e30;border-radius:12px;position:relative;overflow:hidden;min-height:0;">
            <div
                style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.03) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;">
            </div>
            <canvas id="tshirt-canvas" style="width:100%;height:100%;display:block;cursor:grab;"></canvas>
            <div id="drag-hint"
                style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.6);border:1px solid #1e1e30;border-radius:20px;padding:0.3rem 0.9rem;color:#64748b;font-size:0.75rem;pointer-events:none;transition:opacity .5s;">
                ↔ Arrasta para rodar
            </div>
            <button id="reset-btn" onclick="window.resetRotation && window.resetRotation()"
                style="position:absolute;top:1rem;right:1rem;background:rgba(17,17,32,.85);border:1px solid #1e1e30;border-radius:6px;color:#64748b;font-size:0.75rem;padding:0.35rem 0.7rem;cursor:pointer;transition:all .15s;"
                onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'"
                onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
                ⟳ Resetar
            </button>
        </div>

        {{-- RIGHT: Controls --}}
        <div
            style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:0.85rem;display:flex;flex-direction:column;gap:0.6rem;">
            @if ($selectedImage)
                <div style="border-bottom:1px solid #1e1e30;padding-bottom:0.55rem;">
                    <p
                        style="color:#a78bfa;font-size:0.68rem;font-weight:600;margin:0 0 0.2rem;text-transform:uppercase;letter-spacing:.05em;">
                        Design</p>
                    <p
                        style="color:#e2e8f0;font-size:0.85rem;font-weight:600;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $selectedImage->name }}</p>
                </div>
            @endif

            <div>
                <p
                    style="color:#a78bfa;font-size:0.68rem;font-weight:600;margin:0 0 0.35rem;text-transform:uppercase;letter-spacing:.05em;">
                    Cor</p>
                <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                    @foreach ($colors as $color)
                        <button wire:click="selectColor('{{ $color->code }}')" title="{{ $color->name }}"
                            style="width:26px;height:26px;border-radius:50%;background:{{ $color->code }};border:2px solid {{ $selectedColor === $color->code ? '#a78bfa' : 'transparent' }};cursor:pointer;box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c3aed' : 'inset 0 0 0 1px rgba(255,255,255,.2)' }};transition:all .15s;outline:none;">
                        </button>
                    @endforeach
                </div>
                @if ($selectedColor)
                    @php $colorName = $colors->firstWhere('code', $selectedColor)?->name @endphp
                    <p style="color:#64748b;font-size:0.72rem;margin:0.2rem 0 0;">{{ $colorName ?? $selectedColor }}
                    </p>
                @endif
            </div>

            <div>
                <p
                    style="color:#a78bfa;font-size:0.68rem;font-weight:600;margin:0 0 0.35rem;text-transform:uppercase;letter-spacing:.05em;">
                    Tamanho</p>
                <div style="display:flex;gap:0.3rem;flex-wrap:wrap;">
                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                        <button wire:click="$set('selectedSize', '{{ $size }}')"
                            style="background:{{ $selectedSize === $size ? '#7c3aed' : '#1a1a2e' }};color:{{ $selectedSize === $size ? 'white' : '#94a3b8' }};border:1px solid {{ $selectedSize === $size ? '#7c3aed' : '#1e1e30' }};border-radius:6px;padding:0.25rem 0.55rem;font-size:0.78rem;cursor:pointer;font-weight:500;transition:all .15s;min-width:34px;">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <p
                    style="color:#a78bfa;font-size:0.68rem;font-weight:600;margin:0 0 0.35rem;text-transform:uppercase;letter-spacing:.05em;">
                    Quantidade</p>
                <div style="display:flex;align-items:center;gap:0.4rem;">
                    <button wire:click="$set('qty', max(1, $qty - 1))"
                        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:30px;height:30px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">−</button>
                    <span
                        style="color:#e2e8f0;font-weight:600;min-width:1.8rem;text-align:center;font-size:0.9rem;">{{ $qty }}</span>
                    <button wire:click="$set('qty', min(99, $qty + 1))"
                        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:30px;height:30px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">+</button>
                </div>
            </div>

            @if ($prices)
                <div style="background:#1a1a2e;border-radius:7px;padding:0.55rem 0.65rem;">
                    <div
                        style="display:flex;justify-content:space-between;color:#94a3b8;font-size:0.78rem;margin-bottom:0.2rem;">
                        <span>Preço/un</span>
                        <span
                            style="color:#a78bfa;font-weight:600;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                    </div>
                    @if ($qty >= $prices->qty_discount)
                        <div
                            style="display:flex;justify-content:space-between;color:#4ade80;font-size:0.72rem;margin-bottom:0.2rem;">
                            <span>✓ Desc. quantidade</span>
                            <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    @endif
                    <div style="height:1px;background:#2d2d45;margin:0.35rem 0;"></div>
                    <div
                        style="display:flex;justify-content:space-between;color:#e2e8f0;font-weight:700;font-size:0.95rem;">
                        <span>Total</span>
                        <span
                            style="color:#a78bfa;">€{{ number_format(($qty >= $prices->qty_discount ? $prices->unit_price_catalog_discount : $prices->unit_price_catalog) * $qty, 2) }}</span>
                    </div>
                </div>
            @endif

            <button wire:click="addToCart"
                style="width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:white;border:none;border-radius:8px;padding:0.65rem;font-size:0.88rem;font-weight:600;cursor:pointer;transition:all .2s;margin-top:auto;"
                onmouseover="this.style.background='linear-gradient(135deg,#8b5cf6,#7c3aed)';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='linear-gradient(135deg,#7c3aed,#6d28d9)';this.style.transform='translateY(0)'">
                + Adicionar ao carrinho
            </button>
        </div>
    </div>

    {{-- Three.js – wire:ignore prevents re-execution on Livewire re-renders --}}
    <div wire:ignore>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
        <script>
            (function() {
                const designImages = {
                    @foreach ($designs as $design)
                        {{ $design->id }}: "{{ \Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images/') ? route('private-image', $design->image_url) : asset('storage/' . $design->image_url) }}",
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
                    return c;
                }

                let currentDesignId = {{ $selectedImageId ?? 'null' }};
                let currentColor = "{{ $selectedColor }}";
                let loadedImages = {};

                // ── Renderer ──
                const canvas = document.getElementById('tshirt-canvas');
                const container = canvas.parentElement;
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                renderer.setClearColor(0x0a0a0f, 1);
                renderer.shadowMap.enabled = true;
                renderer.shadowMap.type = THREE.PCFSoftShadowMap;

                const scene = new THREE.Scene();

                const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 50);
                camera.position.set(0, 0.34, 1.8);
                camera.lookAt(0, 0.34, 0);

                // ── Lighting ──
                scene.add(new THREE.AmbientLight(0x7755aa, 0.55));
                const keyLight = new THREE.DirectionalLight(0xfff6e8, 1.6);
                keyLight.position.set(2.5, 4.0, 3.5);
                keyLight.castShadow = true;
                keyLight.shadow.mapSize.set(2048, 2048);
                keyLight.shadow.radius = 4;
                scene.add(keyLight);
                const fillLight = new THREE.DirectionalLight(0xaabbff, 0.5);
                fillLight.position.set(-3, 1.5, 2);
                scene.add(fillLight);
                const rimLight = new THREE.DirectionalLight(0x6644dd, 0.4);
                rimLight.position.set(0, 3, -3.5);
                scene.add(rimLight);
                const topLight = new THREE.DirectionalLight(0xffffff, 0.25);
                topLight.position.set(0, 6, 0);
                scene.add(topLight);

                // ── Shirt group (rotation target, shirt centered at y≈0.34) ──
                const shirtGroup = new THREE.Group();
                scene.add(shirtGroup);

                function buildShirt() {
                    while (shirtGroup.children.length) shirtGroup.remove(shirtGroup.children[0]);

                    const hex = toHex(currentColor);
                    const base = new THREE.Color(hex);
                    const dark = base.clone().multiplyScalar(0.78);
                    const vdark = base.clone().multiplyScalar(0.63);

                    const matBase = new THREE.MeshStandardMaterial({
                        color: base,
                        roughness: 0.86,
                        metalness: 0
                    });
                    const matDark = new THREE.MeshStandardMaterial({
                        color: dark,
                        roughness: 0.88
                    });
                    const matVDark = new THREE.MeshStandardMaterial({
                        color: vdark,
                        roughness: 0.92
                    });

                    function sm(geo, mat) {
                        const m = new THREE.Mesh(geo, mat || matBase);
                        m.castShadow = true;
                        shirtGroup.add(m);
                        return m;
                    }

                    // Body (hem at y=0, collar at y=0.682)
                    const bodyPts = [
                        new THREE.Vector2(0.192, 0.000),
                        new THREE.Vector2(0.180, 0.080),
                        new THREE.Vector2(0.183, 0.165),
                        new THREE.Vector2(0.198, 0.285),
                        new THREE.Vector2(0.214, 0.390),
                        new THREE.Vector2(0.222, 0.495),
                        new THREE.Vector2(0.216, 0.588),
                        new THREE.Vector2(0.182, 0.645),
                        new THREE.Vector2(0.152, 0.668),
                        new THREE.Vector2(0.140, 0.682),
                    ];
                    sm(new THREE.LatheGeometry(bodyPts, 40));

                    // Shoulder caps
                    [-1, 1].forEach(side => {
                        const cap = sm(new THREE.SphereGeometry(0.098, 18, 14), matDark);
                        cap.position.set(side * 0.248, 0.610, 0);
                        cap.scale.set(0.88, 0.75, 0.82);
                    });

                    // Sleeves
                    [-1, 1].forEach(side => {
                        const slv = sm(new THREE.CylinderGeometry(0.096, 0.080, 0.255, 20), matDark);
                        slv.position.set(side * 0.350, 0.572, 0.005);
                        slv.rotation.z = side * 1.40;
                        slv.rotation.x = 0.04;
                        const cuff = sm(new THREE.TorusGeometry(0.082, 0.012, 8, 22), matVDark);
                        cuff.position.set(side * 0.434, 0.478, 0.005);
                        cuff.rotation.z = side * 1.40;
                        cuff.rotation.x = 0.04;
                    });

                    // Collar
                    const collar = new THREE.Mesh(new THREE.TorusGeometry(0.112, 0.022, 10, 32, Math.PI * 1.88), matVDark);
                    collar.rotation.x = Math.PI / 2;
                    collar.position.set(0, 0.692, 0);
                    shirtGroup.add(collar);

                    const frontSeam = new THREE.Mesh(new THREE.CylinderGeometry(0.007, 0.007, 0.060, 6), matDark);
                    frontSeam.position.set(0, 0.650, 0.142);
                    shirtGroup.add(frontSeam);

                    sm(new THREE.CylinderGeometry(0.196, 0.198, 0.024, 36), matDark).position.set(0, 0.010, 0);

                    // Design decal
                    const designImg = currentDesignId && loadedImages[currentDesignId] ? loadedImages[currentDesignId] :
                        null;
                    if (designImg && designImg.complete && designImg.naturalWidth > 0) {
                        const tc = document.createElement('canvas');
                        tc.width = 512;
                        tc.height = 512;
                        const ctx = tc.getContext('2d');
                        ctx.clearRect(0, 0, 512, 512);
                        ctx.drawImage(designImg, 0, 0, 512, 512);
                        const tex = new THREE.CanvasTexture(tc);
                        const decal = new THREE.Mesh(
                            new THREE.PlaneGeometry(0.30, 0.30),
                            new THREE.MeshStandardMaterial({
                                map: tex,
                                transparent: true,
                                alphaTest: 0.04,
                                depthWrite: false,
                                roughness: 0.84,
                                polygonOffset: true,
                                polygonOffsetFactor: -1
                            })
                        );
                        decal.position.set(0, 0.370, 0.198);
                        shirtGroup.add(decal);
                    }
                }

                // ── Image loading ──
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
                        loadDesignImage(currentDesignId, designImages[currentDesignId], () => buildShirt());
                    } else {
                        buildShirt();
                    }
                }

                updateScene();

                // ── Rotation Controls ──
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

                // ── Resize ──
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
                let lastColor = currentColor,
                    lastDesign = currentDesignId;
                setInterval(() => {
                    const c = document.getElementById('livewire-color')?.value;
                    const d = parseInt(document.getElementById('livewire-design')?.value) || null;
                    if (c !== lastColor || d !== lastDesign) {
                        currentColor = c;
                        currentDesignId = d;
                        lastColor = c;
                        lastDesign = d;
                        updateScene();
                    }
                }, 150);
            })();
        </script>
    </div>

    <input type="hidden" id="livewire-color" value="{{ $selectedColor }}">
    <input type="hidden" id="livewire-design" value="{{ $selectedImageId ?? '' }}">
</div>
