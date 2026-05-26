<div>
    {{-- Cart flash --}}
    @if($cartMessage)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             style="position:fixed;bottom:1.5rem;right:1.5rem;background:#1a1a2e;border:1px solid #7c3aed;border-radius:8px;padding:0.75rem 1.25rem;color:#a78bfa;font-size:0.85rem;z-index:1000;box-shadow:0 8px 24px rgba(124,58,237,.25);">
            ✓ {{ $cartMessage }}
        </div>
    @endif

    {{-- Page header --}}
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
        <a href="{{ route('catalog') }}" style="display:inline-flex;align-items:center;gap:0.4rem;color:#64748b;text-decoration:none;font-size:0.85rem;padding:0.4rem 0.75rem;border:1px solid #1e1e30;border-radius:6px;transition:all .15s;"
           onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'" onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
            ← Catálogo
        </a>
        <div>
            <h1 style="color:#e2e8f0;font-size:1.4rem;font-weight:700;margin:0;">Provador Virtual 3D</h1>
            <p style="color:#64748b;font-size:0.82rem;margin:0;">Arrasta para rodar · Escolhe design e cor</p>
        </div>
    </div>

    {{-- Main 3-panel layout --}}
    <div style="display:grid;grid-template-columns:230px 1fr 260px;gap:1.25rem;min-height:600px;">

        {{-- LEFT: Design list --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;display:flex;flex-direction:column;">
            <div style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;">
                <p style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0;text-transform:uppercase;letter-spacing:.05em;">Designs</p>
            </div>
            <div style="overflow-y:auto;flex:1;padding:0.5rem;">
                @foreach($designs as $design)
                    @php
                        $imgUrl = \Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/')
                            ? route('private-image', $design->image_url)
                            : asset('storage/' . $design->image_url);
                        $isSelected = $selectedImageId === $design->id;
                    @endphp
                    <button wire:click="selectDesign({{ $design->id }})"
                            style="width:100%;background:{{ $isSelected ? 'rgba(124,58,237,.18)' : 'transparent' }};border:1px solid {{ $isSelected ? '#7c3aed' : 'transparent' }};border-radius:8px;padding:0.5rem;cursor:pointer;display:flex;align-items:center;gap:0.65rem;margin-bottom:0.3rem;transition:all .15s;text-align:left;"
                            onmouseover="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='rgba(255,255,255,.04)'"
                            onmouseout="if(!{{ $isSelected ? 'true' : 'false' }})this.style.background='transparent'">
                        {{-- Actual design image thumbnail --}}
                        <div style="width:52px;height:52px;flex-shrink:0;background:#0d0d1a;border-radius:7px;overflow:hidden;border:1px solid rgba(255,255,255,.06);">
                            <img src="{{ $imgUrl }}" alt="{{ $design->name }}"
                                 style="width:100%;height:100%;object-fit:cover;display:block;"
                                 onerror="this.parentElement.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#333;font-size:1.2rem;\'>◈</span>'">
                        </div>
                        <div style="min-width:0;flex:1;">
                            <p style="color:{{ $isSelected ? '#e2e8f0' : '#94a3b8' }};font-size:0.80rem;font-weight:{{ $isSelected ? '600' : '400' }};margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $design->name }}</p>
                            @if($design->category)
                                <span style="color:#64748b;font-size:0.70rem;">{{ $design->category->name }}</span>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- CENTER: 3D Viewport --}}
        <div style="background:#0a0a0f;border:1px solid #1e1e30;border-radius:12px;position:relative;overflow:hidden;min-height:560px;">
            <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(124,58,237,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(124,58,237,.03) 1px,transparent 1px);background-size:48px 48px;pointer-events:none;"></div>
            <canvas id="tshirt-canvas" style="width:100%;height:100%;display:block;cursor:grab;"></canvas>
            <div id="drag-hint" style="position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);background:rgba(0,0,0,.6);border:1px solid #1e1e30;border-radius:20px;padding:0.3rem 0.9rem;color:#64748b;font-size:0.75rem;pointer-events:none;transition:opacity .5s;">
                ↔ Arrasta para rodar
            </div>
            <button id="reset-btn" onclick="window.resetRotation && window.resetRotation()"
                    style="position:absolute;top:1rem;right:1rem;background:rgba(17,17,32,.85);border:1px solid #1e1e30;border-radius:6px;color:#64748b;font-size:0.75rem;padding:0.35rem 0.7rem;cursor:pointer;transition:all .15s;"
                    onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'" onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
                ⟳ Resetar
            </button>
        </div>

        {{-- RIGHT: Controls --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:1.25rem;">
            @if($selectedImage)
                <div>
                    <p style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0 0 0.4rem;text-transform:uppercase;letter-spacing:.05em;">Design</p>
                    <p style="color:#e2e8f0;font-size:0.9rem;font-weight:600;margin:0;">{{ $selectedImage->name }}</p>
                    @if($selectedImage->description)
                        <p style="color:#64748b;font-size:0.78rem;margin:0.25rem 0 0;">{{ $selectedImage->description }}</p>
                    @endif
                </div>
            @endif

            <div>
                <p style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0 0 0.5rem;text-transform:uppercase;letter-spacing:.05em;">Cor da T-shirt</p>
                <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                    @foreach($colors as $color)
                        <button wire:click="selectColor('{{ $color->code }}')"
                                title="{{ $color->name }}"
                                style="width:30px;height:30px;border-radius:50%;background:{{ $color->code }};border:2px solid {{ $selectedColor === $color->code ? '#a78bfa' : 'transparent' }};cursor:pointer;box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c3aed' : 'inset 0 0 0 1px rgba(255,255,255,.2)' }};transition:all .15s;outline:none;">
                        </button>
                    @endforeach
                </div>
                @if($selectedColor)
                    @php $colorName = $colors->firstWhere('code', $selectedColor)?->name @endphp
                    <p style="color:#64748b;font-size:0.75rem;margin:0.35rem 0 0;">{{ $colorName ?? $selectedColor }}</p>
                @endif
            </div>

            <div>
                <p style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0 0 0.5rem;text-transform:uppercase;letter-spacing:.05em;">Tamanho</p>
                <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                    @foreach(['XS','S','M','L','XL'] as $size)
                        <button wire:click="$set('selectedSize', '{{ $size }}')"
                                style="background:{{ $selectedSize === $size ? '#7c3aed' : '#1a1a2e' }};color:{{ $selectedSize === $size ? 'white' : '#94a3b8' }};border:1px solid {{ $selectedSize === $size ? '#7c3aed' : '#1e1e30' }};border-radius:6px;padding:0.3rem 0.65rem;font-size:0.8rem;cursor:pointer;font-weight:500;transition:all .15s;min-width:38px;">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <p style="color:#a78bfa;font-size:0.78rem;font-weight:600;margin:0 0 0.5rem;text-transform:uppercase;letter-spacing:.05em;">Quantidade</p>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <button wire:click="$set('qty', max(1, $qty - 1))"
                            style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:34px;height:34px;color:#94a3b8;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">−</button>
                    <span style="color:#e2e8f0;font-weight:600;min-width:2rem;text-align:center;">{{ $qty }}</span>
                    <button wire:click="$set('qty', min(99, $qty + 1))"
                            style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:34px;height:34px;color:#94a3b8;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">+</button>
                </div>
            </div>

            @if($prices)
                <div style="background:#1a1a2e;border-radius:8px;padding:0.75rem;">
                    <div style="display:flex;justify-content:space-between;color:#94a3b8;font-size:0.82rem;margin-bottom:0.25rem;">
                        <span>Preço/un</span>
                        <span style="color:#a78bfa;font-weight:600;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                    </div>
                    @if($qty >= $prices->qty_discount)
                        <div style="display:flex;justify-content:space-between;color:#4ade80;font-size:0.78rem;margin-bottom:0.25rem;">
                            <span>✓ Desc. quantidade</span>
                            <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    @endif
                    <div style="height:1px;background:#2d2d45;margin:0.5rem 0;"></div>
                    <div style="display:flex;justify-content:space-between;color:#e2e8f0;font-weight:700;font-size:1rem;">
                        <span>Total</span>
                        <span style="color:#a78bfa;">
                            €{{ number_format(($qty >= $prices->qty_discount ? $prices->unit_price_catalog_discount : $prices->unit_price_catalog) * $qty, 2) }}
                        </span>
                    </div>
                </div>
            @endif

            <button wire:click="addToCart"
                    style="width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:white;border:none;border-radius:8px;padding:0.75rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all .2s;margin-top:auto;"
                    onmouseover="this.style.background='linear-gradient(135deg,#8b5cf6,#7c3aed)';this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='linear-gradient(135deg,#7c3aed,#6d28d9)';this.style.transform='translateY(0)'">
                + Adicionar ao carrinho
            </button>
        </div>
    </div>

    {{-- Three.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script>
    (function () {
        const designImages = {
            @foreach($designs as $design)
            {{ $design->id }}: "{{ \Illuminate\Support\Str::startsWith($design->image_url, 'tshirt_images_private/') ? route('private-image', $design->image_url) : asset('storage/' . $design->image_url) }}",
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
            return c;
        }

        let currentDesignId = {{ $selectedImageId ?? 'null' }};
        let currentColor = "{{ $selectedColor }}";
        let loadedImages = {};

        // ── Renderer ──
        const canvas = document.getElementById('tshirt-canvas');
        const container = canvas.parentElement;
        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setClearColor(0x0a0a0f, 1);
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;

        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 50);
        camera.position.set(0, 0.90, 5.2);
        camera.lookAt(0, 0.80, 0);

        // ── Lighting ──
        scene.add(new THREE.AmbientLight(0x7755aa, 0.50));

        const keyLight = new THREE.DirectionalLight(0xfff6e8, 1.55);
        keyLight.position.set(2.5, 5.0, 3.5);
        keyLight.castShadow = true;
        keyLight.shadow.mapSize.set(2048, 2048);
        keyLight.shadow.camera.near = 0.5;
        keyLight.shadow.camera.far = 18;
        keyLight.shadow.camera.left = -2;
        keyLight.shadow.camera.right = 2;
        keyLight.shadow.camera.top = 4;
        keyLight.shadow.camera.bottom = -2;
        keyLight.shadow.radius = 4;
        scene.add(keyLight);

        const fillLight = new THREE.DirectionalLight(0xaabbff, 0.48);
        fillLight.position.set(-3, 1.5, 2);
        scene.add(fillLight);

        const rimLight = new THREE.DirectionalLight(0x6644dd, 0.38);
        rimLight.position.set(0, 3, -3.5);
        scene.add(rimLight);

        const topLight = new THREE.DirectionalLight(0xffffff, 0.22);
        topLight.position.set(0, 6, 0);
        scene.add(topLight);

        // ── Ground ──
        const groundMesh = new THREE.Mesh(
            new THREE.CircleGeometry(1.5, 56),
            new THREE.MeshStandardMaterial({ color: 0x110820, roughness: 0.95, transparent: true, opacity: 0.88 })
        );
        groundMesh.rotation.x = -Math.PI / 2;
        groundMesh.position.y = -0.90;
        groundMesh.receiveShadow = true;
        scene.add(groundMesh);

        // ── Materials ──
        const skinMat   = new THREE.MeshStandardMaterial({ color: 0xd4a47c, roughness: 0.80, metalness: 0 });
        const hairMat   = new THREE.MeshStandardMaterial({ color: 0x1c0e06, roughness: 0.94 });
        const pantsMat  = new THREE.MeshStandardMaterial({ color: 0x15152a, roughness: 0.90 });
        const shoeMat   = new THREE.MeshStandardMaterial({ color: 0x0a0a0a, roughness: 0.62 });
        const soleMat   = new THREE.MeshStandardMaterial({ color: 0xe4e4e4, roughness: 0.95 });
        const eyeMat    = new THREE.MeshStandardMaterial({ color: 0x0d0d0d, roughness: 0.38, metalness: 0.05 });
        const scleraMat = new THREE.MeshStandardMaterial({ color: 0xf5eee6, roughness: 0.86 });

        const humanGroup = new THREE.Group();
        scene.add(humanGroup);

        function h(geo, mat) {
            const m = new THREE.Mesh(geo, mat);
            m.castShadow = true;
            humanGroup.add(m);
            return m;
        }

        // ══════════════════════════════════
        //  HEAD
        // ══════════════════════════════════
        // Cranium (slightly elongated, less deep)
        const cranium = h(new THREE.SphereGeometry(0.200, 36, 28), skinMat);
        cranium.position.set(0, 1.750, 0);
        cranium.scale.set(1, 1.06, 0.92);

        // Cheekbones/jaw widening
        const jaw = h(new THREE.SphereGeometry(0.172, 24, 18), skinMat);
        jaw.position.set(0, 1.560, 0.010);
        jaw.scale.set(1, 0.52, 0.82);

        // Chin
        const chin = h(new THREE.SphereGeometry(0.072, 16, 12), skinMat);
        chin.position.set(0, 1.532, 0.118);
        chin.scale.set(0.78, 0.60, 0.82);

        // ── Hair (LatheGeometry — smooth, natural cap) ──
        // Profile: radius vs height, from ear level upward
        const hairPts = [
            new THREE.Vector2(0.005, 0.000),
            new THREE.Vector2(0.085, 0.046),
            new THREE.Vector2(0.158, 0.110),
            new THREE.Vector2(0.206, 0.190),
            new THREE.Vector2(0.216, 0.278),
            new THREE.Vector2(0.210, 0.352),
            new THREE.Vector2(0.192, 0.406),
            new THREE.Vector2(0.158, 0.440),
            new THREE.Vector2(0.102, 0.460),
            new THREE.Vector2(0.038, 0.470),
            new THREE.Vector2(0.002, 0.472),
        ];
        const hairMesh = h(new THREE.LatheGeometry(hairPts, 34), hairMat);
        hairMesh.position.set(0, 1.536, 0);

        // ── Ears ──
        [-1, 1].forEach(s => {
            const ear = h(new THREE.SphereGeometry(0.044, 14, 10), skinMat);
            ear.position.set(s * 0.208, 1.748, -0.010);
            ear.scale.set(0.42, 0.72, 0.54);
        });

        // ── Eyes ──
        [-1, 1].forEach(s => {
            const ew = h(new THREE.SphereGeometry(0.034, 14, 12), scleraMat);
            ew.position.set(s * 0.076, 1.764, 0.172);
            ew.scale.set(0.95, 0.82, 0.70);

            const ep = h(new THREE.SphereGeometry(0.020, 12, 10), eyeMat);
            ep.position.set(s * 0.076, 1.764, 0.186);
            ep.scale.set(0.95, 0.82, 0.70);
        });

        // ── Eyebrows (thin flat torus arcs) ──
        [-1, 1].forEach(s => {
            const brow = new THREE.Mesh(
                new THREE.TorusGeometry(0.042, 0.009, 6, 16, Math.PI * 0.65),
                new THREE.MeshStandardMaterial({ color: 0x220e06, roughness: 0.95 })
            );
            brow.position.set(s * 0.076, 1.790, 0.172);
            brow.rotation.x = Math.PI / 2;
            brow.rotation.y = s * 0.18;
            brow.rotation.z = s * 0.30;
            humanGroup.add(brow);
        });

        // ── Nose ──
        const nose = h(new THREE.SphereGeometry(0.026, 12, 10), skinMat);
        nose.position.set(0, 1.700, 0.194);
        nose.scale.set(0.68, 0.62, 1.0);

        // Nostrils
        [-1, 1].forEach(s => {
            const n = h(new THREE.SphereGeometry(0.014, 8, 6), skinMat);
            n.position.set(s * 0.022, 1.686, 0.198);
            n.scale.set(0.9, 0.6, 0.7);
        });

        // ── Lips ──
        const upperLip = h(new THREE.SphereGeometry(0.055, 14, 8), skinMat);
        upperLip.position.set(0, 1.652, 0.183);
        upperLip.scale.set(1.0, 0.30, 0.55);

        const lowerLip = h(new THREE.SphereGeometry(0.060, 14, 8), skinMat);
        lowerLip.position.set(0, 1.636, 0.184);
        lowerLip.scale.set(0.90, 0.28, 0.58);

        // ── Neck ──
        const neck = h(new THREE.CylinderGeometry(0.064, 0.082, 0.26, 18), skinMat);
        neck.position.set(0, 1.490, 0);

        // ══════════════════════════════════
        //  ARMS (skin-visible parts)
        // ══════════════════════════════════
        function buildArm(side) {
            // Upper arm (visible below sleeve)
            const ua = h(new THREE.CylinderGeometry(0.068, 0.060, 0.44, 16), skinMat);
            ua.position.set(side * 0.388, 1.012, 0.012);
            ua.rotation.z = side * 0.13;
            ua.rotation.x = 0.04;

            // Elbow sphere
            const el = h(new THREE.SphereGeometry(0.062, 14, 10), skinMat);
            el.position.set(side * 0.404, 0.784, 0.022);
            el.scale.set(0.88, 0.80, 0.82);

            // Forearm
            const fa = h(new THREE.CylinderGeometry(0.056, 0.046, 0.42, 14), skinMat);
            fa.position.set(side * 0.414, 0.564, 0.028);
            fa.rotation.z = side * 0.09;
            fa.rotation.x = 0.03;

            // Wrist
            const wr = h(new THREE.SphereGeometry(0.050, 14, 10), skinMat);
            wr.position.set(side * 0.424, 0.345, 0.030);
            wr.scale.set(0.90, 0.72, 0.70);

            // Hand (slightly flattened sphere)
            const hand = h(new THREE.SphereGeometry(0.058, 16, 12), skinMat);
            hand.position.set(side * 0.428, 0.274, 0.030);
            hand.scale.set(0.85, 0.62, 0.60);

            // Fingers (3 small cylinders)
            [0, 1, 2].forEach(i => {
                const f = h(new THREE.CylinderGeometry(0.012, 0.010, 0.068, 8), skinMat);
                f.position.set(side * (0.408 + (i - 1) * 0.020), 0.224, 0.036);
                f.rotation.x = 0.18;
            });
        }
        buildArm(-1);
        buildArm(1);

        // ══════════════════════════════════
        //  SHIRT (LatheGeometry body)
        // ══════════════════════════════════
        const shirtGroup = new THREE.Group();
        humanGroup.add(shirtGroup);
        shirtGroup.position.set(0, 0.620, 0); // hem position in world

        function buildShirt() {
            while (shirtGroup.children.length) shirtGroup.remove(shirtGroup.children[0]);

            const hex  = toHex(currentColor);
            const base = new THREE.Color(hex);
            const dark = base.clone().multiplyScalar(0.78);
            const vdark= base.clone().multiplyScalar(0.63);

            const matBase  = new THREE.MeshStandardMaterial({ color: base,  roughness: 0.86, metalness: 0 });
            const matDark  = new THREE.MeshStandardMaterial({ color: dark,  roughness: 0.88 });
            const matVDark = new THREE.MeshStandardMaterial({ color: vdark, roughness: 0.92 });

            function sm(geo, mat) {
                const m = new THREE.Mesh(geo, mat || matBase);
                m.castShadow = true;
                shirtGroup.add(m);
                return m;
            }

            // Main shirt body — smooth organic shape using LatheGeometry
            // Profile: (radius, height) from hem (y=0) to collar (y=0.730)
            const bodyPts = [
                new THREE.Vector2(0.192, 0.000),  // hem
                new THREE.Vector2(0.180, 0.080),  // waist tuck (shirt fitted)
                new THREE.Vector2(0.183, 0.165),  // stomach
                new THREE.Vector2(0.198, 0.285),  // lower chest
                new THREE.Vector2(0.214, 0.390),  // chest
                new THREE.Vector2(0.222, 0.495),  // armpit
                new THREE.Vector2(0.216, 0.588),  // shoulder base
                new THREE.Vector2(0.182, 0.645),  // neck base
                new THREE.Vector2(0.152, 0.668),  // collar lower
                new THREE.Vector2(0.140, 0.682),  // collar top
            ];
            sm(new THREE.LatheGeometry(bodyPts, 40));

            // Shoulder caps (smooth rounded shoulder)
            [-1, 1].forEach(side => {
                const cap = sm(new THREE.SphereGeometry(0.098, 18, 14), matDark);
                cap.position.set(side * 0.248, 0.610, 0);
                cap.scale.set(0.88, 0.75, 0.82);
            });

            // Sleeves (tapered cylinders, angled like a real t-shirt)
            [-1, 1].forEach(side => {
                const slv = sm(new THREE.CylinderGeometry(0.096, 0.080, 0.255, 20), matDark);
                slv.position.set(side * 0.350, 0.572, 0.005);
                slv.rotation.z = side * 1.40;
                slv.rotation.x = 0.04;

                // Sleeve cuff ring
                const cuff = sm(new THREE.TorusGeometry(0.082, 0.012, 8, 22), matVDark);
                cuff.position.set(side * 0.434, 0.478, 0.005);
                cuff.rotation.z = side * 1.40;
                cuff.rotation.x = 0.04;
            });

            // Collar (partial torus)
            const collar = new THREE.Mesh(
                new THREE.TorusGeometry(0.112, 0.022, 10, 32, Math.PI * 1.88),
                matVDark
            );
            collar.rotation.x = Math.PI / 2;
            collar.position.set(0, 0.692, 0);
            shirtGroup.add(collar);

            // Collar front seam (slight raised ridge)
            const frontSeam = new THREE.Mesh(
                new THREE.CylinderGeometry(0.007, 0.007, 0.060, 6),
                matDark
            );
            frontSeam.position.set(0, 0.650, 0.142);
            shirtGroup.add(frontSeam);

            // Hem band (slightly darker, slight flare)
            const hem = sm(new THREE.CylinderGeometry(0.196, 0.198, 0.024, 36), matDark);
            hem.position.set(0, 0.010, 0);

            // ── DESIGN DECAL ──
            const designImg = currentDesignId && loadedImages[currentDesignId]
                ? loadedImages[currentDesignId] : null;
            if (designImg && designImg.complete && designImg.naturalWidth > 0) {
                const tc = document.createElement('canvas');
                tc.width = 512; tc.height = 512;
                const ctx = tc.getContext('2d');
                ctx.clearRect(0, 0, 512, 512);
                ctx.drawImage(designImg, 0, 0, 512, 512);
                const tex = new THREE.CanvasTexture(tc);
                const decal = new THREE.Mesh(
                    new THREE.PlaneGeometry(0.30, 0.30),
                    new THREE.MeshStandardMaterial({
                        map: tex, transparent: true, alphaTest: 0.04,
                        depthWrite: false, roughness: 0.84,
                        polygonOffset: true, polygonOffsetFactor: -1,
                    })
                );
                // Front of shirt at chest level (y=0.390 in local → world y≈1.01)
                decal.position.set(0, 0.370, 0.198);
                shirtGroup.add(decal);
            }
        }

        // ══════════════════════════════════
        //  HIPS / LOWER BODY (pants)
        // ══════════════════════════════════
        // Pelvis block (LatheGeometry)
        const hipPts = [
            new THREE.Vector2(0.192, 0.000),  // top (matches shirt hem)
            new THREE.Vector2(0.198, 0.065),  // hip flare
            new THREE.Vector2(0.196, 0.130),
            new THREE.Vector2(0.188, 0.190),  // narrowing
            new THREE.Vector2(0.172, 0.250),  // crotch area
            new THREE.Vector2(0.148, 0.295),  // thigh split
        ];
        const hipsMesh = new THREE.Mesh(new THREE.LatheGeometry(hipPts, 34), pantsMat);
        hipsMesh.castShadow = true;
        hipsMesh.position.set(0, 0.608, 0);
        humanGroup.add(hipsMesh);

        // ── Legs ──
        [-1, 1].forEach(side => {
            const x = side * 0.120;

            // Upper leg (slightly tapered)
            const ul = new THREE.Mesh(new THREE.CylinderGeometry(0.102, 0.090, 0.54, 16), pantsMat);
            ul.castShadow = true;
            ul.position.set(x, 0.282, 0);
            ul.rotation.z = side * 0.022;
            humanGroup.add(ul);

            // Knee sphere
            const kn = new THREE.Mesh(new THREE.SphereGeometry(0.088, 16, 12), pantsMat);
            kn.castShadow = true;
            kn.position.set(x, 0.005, 0);
            kn.scale.set(0.92, 0.78, 0.86);
            humanGroup.add(kn);

            // Lower leg
            const ll = new THREE.Mesh(new THREE.CylinderGeometry(0.084, 0.068, 0.52, 14), pantsMat);
            ll.castShadow = true;
            ll.position.set(x, -0.270, 0);
            humanGroup.add(ll);

            // Ankle
            const an = new THREE.Mesh(new THREE.SphereGeometry(0.062, 14, 10), pantsMat);
            an.castShadow = true;
            an.position.set(x, -0.546, 0);
            an.scale.set(0.90, 0.72, 0.82);
            humanGroup.add(an);

            // Shoe body
            const shoe = new THREE.Mesh(new THREE.BoxGeometry(0.138, 0.068, 0.300), shoeMat);
            shoe.castShadow = true;
            shoe.position.set(x, -0.836, 0.036);
            humanGroup.add(shoe);

            // Shoe toe (rounded front)
            const toe = new THREE.Mesh(new THREE.SphereGeometry(0.070, 14, 10), shoeMat);
            toe.castShadow = true;
            toe.position.set(x, -0.836, 0.174);
            toe.scale.set(0.98, 0.50, 0.65);
            humanGroup.add(toe);

            // Sole
            const sole = new THREE.Mesh(new THREE.BoxGeometry(0.144, 0.025, 0.308), soleMat);
            sole.position.set(x, -0.878, 0.036);
            humanGroup.add(sole);
        });

        // ── Image loading ──
        function loadDesignImage(id, url, cb) {
            if (loadedImages[id] !== undefined) { cb(loadedImages[id]); return; }
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload  = () => { loadedImages[id] = img;  cb(img); };
            img.onerror = () => { loadedImages[id] = null; cb(null); };
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
            humanGroup.rotation.y = rotY;
            humanGroup.rotation.x = rotX;
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
            humanGroup.rotation.y = rotY;
            humanGroup.rotation.x = rotX;
            prevX = e.touches[0].clientX; prevY = e.touches[0].clientY;
        }, { passive: true });
        canvas.addEventListener('touchend', () => { isPointerDown = false; });

        window.resetRotation = () => {
            rotY = 0; rotX = 0;
            humanGroup.rotation.set(0, 0, 0);
            autoRotate = true;
        };

        // ── Resize ──
        function resize() {
            const w = container.clientWidth, h = container.clientHeight;
            renderer.setSize(w, h);
            camera.aspect = w / h;
            camera.updateProjectionMatrix();
        }
        new ResizeObserver(resize).observe(container);
        resize();

        // ── Render Loop ──
        let lastTime = 0;
        function animate(t) {
            requestAnimationFrame(animate);
            const dt = Math.min((t - lastTime) / 1000, 0.05);
            lastTime = t;
            if (autoRotate && !isPointerDown) {
                rotY += dt * 0.28;
                humanGroup.rotation.y = rotY;
            }
            renderer.render(scene, camera);
        }
        animate(0);

        // ── Livewire State Sync ──
        let lastColor = currentColor, lastDesign = currentDesignId;
        setInterval(() => {
            const c = document.getElementById('livewire-color')?.value;
            const d = parseInt(document.getElementById('livewire-design')?.value) || null;
            if (c !== lastColor || d !== lastDesign) {
                currentColor = c; currentDesignId = d;
                lastColor = c; lastDesign = d;
                updateScene();
            }
        }, 150);
    })();
    </script>

    <input type="hidden" id="livewire-color" value="{{ $selectedColor }}">
    <input type="hidden" id="livewire-design" value="{{ $selectedImageId ?? '' }}">
</div>
