@props(['title' => 'FunShirt'])
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — FunShirt</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f5f4f1;
            color: #1a1a1a;
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-input {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid #ccc9c3;
            padding: .5rem 0;
            font-size: .9rem;
            color: #1a1a1a;
            font-family: inherit;
            outline: none;
            transition: border-color .2s;
        }

        .auth-input::placeholder {
            color: #c8c4be;
        }

        .auth-input:focus {
            border-bottom-color: #7c6fa0;
        }

        .auth-input.is-error {
            border-bottom-color: #c0392b !important;
        }

        .auth-input:-webkit-autofill,
        .auth-input:-webkit-autofill:hover,
        .auth-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #f5f4f1 inset !important;
            -webkit-text-fill-color: #1a1a1a !important;
            border-bottom-color: #ccc9c3;
        }

        .auth-label {
            display: block;
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #b8b4ae;
            margin-bottom: .55rem;
        }

        .auth-error {
            color: #c0392b;
            font-size: .72rem;
            margin-top: .35rem;
        }

        .auth-btn {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #f5f4f1;
            background: #1a1a1a;
            border: none;
            padding: .7rem 1.5rem;
            cursor: pointer;
            transition: background .2s;
            border-radius: 1px;
        }

        .auth-btn:hover {
            background: #333;
        }

        .auth-btn-full {
            width: 100%;
        }

        .auth-btn-ghost {
            background: transparent;
            color: #888;
            border: 1px solid #d8d5d0;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .65rem 1.5rem;
            cursor: pointer;
            transition: all .2s;
            border-radius: 1px;
        }

        .auth-btn-ghost:hover {
            border-color: #1a1a1a;
            color: #1a1a1a;
        }

        .auth-link {
            color: #7c6fa0;
            text-decoration: none;
            font-size: .82rem;
        }

        .auth-link:hover {
            color: #6b5f90;
        }

        .auth-status-ok {
            background: #f0faf5;
            border: 1px solid #b7e1cb;
            color: #2d6a4f;
            padding: .7rem 1rem;
            font-size: .82rem;
            border-radius: 1px;
            margin-bottom: 1.25rem;
        }
    </style>
</head>

<body>

    {{-- Top bar --}}
    <div
        style="padding:1.1rem 2rem;border-bottom:1px solid #e0ddd8;flex-shrink:0;display:flex;align-items:center;justify-content:space-between;">
        <a href="{{ route('catalog') }}" style="text-decoration:none;">
            <span
                style="font-size:.72rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#1a1a1a;">Funshirt</span>
        </a>
        <a href="{{ route('catalog') }}"
            style="font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#aaa;text-decoration:none;transition:color .15s;"
            onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">← Catálogo</a>
    </div>

    {{-- Split layout --}}
    <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;min-height:0;">

        {{-- Left panel: brand content --}}
        <div
            style="background:#eeecea;border-right:1px solid #e0ddd8;display:flex;flex-direction:column;justify-content:space-between;padding:3.5rem;">

            <div>
                <div
                    style="font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#c8c4be;margin-bottom:1.25rem;">
                    T-shirts com personalidade</div>
                <h2
                    style="font-size:2.6rem;font-weight:300;letter-spacing:-.04em;line-height:1.06;color:#1a1a1a;margin:0 0 1.5rem;">
                    Designs únicos,<br>entregues <em style="font-weight:700;font-style:italic;">em casa.</em>
                </h2>
                <p style="color:#aaa;font-size:.88rem;line-height:1.75;max-width:360px;">
                    Mais de 50 designs originais. Experimenta no Provador 3D, escolhe cor e tamanho, recebe em 3–5 dias.
                </p>
            </div>

            <div
                style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:#e0ddd8;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;margin-top:3rem;">
                @foreach ([['50+', 'Designs'], ['6', 'Cores'], ['€15', 'Desde'], ['3–5 dias', 'Entrega']] as $s)
                    <div style="background:#f5f4f1;padding:1rem 1.1rem;">
                        <div
                            style="font-size:1.1rem;font-weight:700;letter-spacing:-.02em;color:#1a1a1a;margin-bottom:.15rem;">
                            {{ $s[0] }}</div>
                        <div
                            style="font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#c8c4be;">
                            {{ $s[1] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right panel: form --}}
        <div style="display:flex;align-items:center;justify-content:center;padding:3rem 4rem;">
            <div style="width:100%;max-width:360px;">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div
        style="padding:.85rem 2rem;border-top:1px solid #e0ddd8;display:flex;justify-content:space-between;font-size:.6rem;letter-spacing:.1em;text-transform:uppercase;color:#c8c4be;flex-shrink:0;">
        <span>© {{ date('Y') }} Funshirt</span>
        <a href="{{ route('catalog') }}" style="color:#c8c4be;text-decoration:none;">Catálogo</a>
    </div>

    @fluxScripts
    @livewireScripts
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script>
        const lenis = new Lenis({
            lerp: 0.1,
            smoothWheel: true
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
    </script>
</body>

</html>
