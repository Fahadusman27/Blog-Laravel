{{--
    ╔══════════════════════════════════════════════════╗
    ║  <x-auth.asymmetric-panel>                      ║
    ║  Split-screen auth layout: Art Panel + Form     ║
    ║  Props:                                          ║
    ║    $mode     - 'login' | 'register'             ║
    ║    $quote    - Inspirational quote (optional)   ║
    ╚══════════════════════════════════════════════════╝
--}}
@props([
    'mode'  => 'login',
    'quote' => 'Ideas that challenge gravity deserve a platform that matches their weight.',
])

<div class="grid-asymmetric min-h-screen" aria-label="{{ $mode === 'login' ? 'Login' : 'Register' }} page">

    {{-- ═══════════════════════════════════════════
         LEFT — Art / Brand Panel (5/9 width)
    ═══════════════════════════════════════════ --}}
    <div class="auth-art-panel hidden lg:flex flex-col justify-between p-12 xl:p-16" aria-hidden="true">

        {{-- ── Abstract Background Art ── --}}
        {{-- Overlapping geometric shapes: the "anti-grid" element --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            {{-- Large circle (negative margin, bleeds out) --}}
            <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full border border-white/5"
                 style="background: radial-gradient(circle at 30% 30%, rgba(255,77,0,0.15), transparent 70%);">
            </div>

            {{-- Overlapping accent rectangle --}}
            <div class="absolute top-1/3 -left-8 w-64 h-1 bg-[var(--color-accent)] opacity-60"></div>
            <div class="absolute top-1/3 -left-8 w-32 h-1 bg-[var(--color-neon-cyan)] opacity-40 mt-3"></div>

            {{-- Floating oversized number (editorial decorative) --}}
            <div class="absolute -bottom-16 -right-16 font-serif text-[20rem] leading-none text-white/[0.02] select-none tracking-tighter pointer-events-none">
                {{ $mode === 'login' ? '01' : '02' }}
            </div>

            {{-- Noise dots grid --}}
            <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="auth-dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#auth-dots)"/>
            </svg>

            {{-- Diagonal accent stripe --}}
            <div class="absolute inset-0"
                 style="background: repeating-linear-gradient(135deg, transparent, transparent 60px, rgba(255,77,0,0.015) 60px, rgba(255,77,0,0.015) 61px);">
            </div>
        </div>

        {{-- ── Top: Logo --}}
        <div class="relative z-10">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group w-fit">
                <span class="font-sans font-black text-2xl tracking-tighter text-[var(--color-accent)]">AG</span>
                <span class="font-serif text-white text-xl opacity-70 group-hover:opacity-100 transition-opacity">Curahan Hati</span>
            </a>
        </div>

        {{-- ── Center: Big Editorial Quote ── --}}
        <div class="relative z-10 max-w-lg">
            <p class="text-[0.6rem] font-mono text-white/30 tracking-[0.3em] uppercase mb-6">{{ $mode === 'login' ? 'Welcome back' : 'Join us today' }}</p>

            <blockquote class="font-serif text-white text-3xl xl:text-4xl leading-[1.15] tracking-tight mb-6 accent-rule border-[var(--color-accent)]">
                "{{ $quote }}"
            </blockquote>

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white text-xs font-bold">
                    CH
                </div>
                <div>
                    <p class="text-white/70 text-xs font-medium">Curahan Hati Editorial</p>
                    <p class="text-white/30 text-[0.65rem]">Bold ideas. Bolder execution.</p>
                </div>
            </div>
        </div>

        {{-- ── Bottom: Stats ── --}}
        <div class="relative z-10 grid grid-cols-3 gap-6 border-t border-white/10 pt-8">
            <div>
                <p class="text-white font-serif text-3xl tracking-tight leading-none mb-1">12k+</p>
                <p class="text-white/30 text-[0.65rem] font-mono uppercase tracking-wider">Readers</p>
            </div>
            <div>
                <p class="text-white font-serif text-3xl tracking-tight leading-none mb-1">340+</p>
                <p class="text-white/30 text-[0.65rem] font-mono uppercase tracking-wider">Articles</p>
            </div>
            <div>
                <p class="text-white font-serif text-3xl tracking-tight leading-none mb-1">48</p>
                <p class="text-white/30 text-[0.65rem] font-mono uppercase tracking-wider">Authors</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         RIGHT — Form Panel (4/9 width)
    ═══════════════════════════════════════════ --}}
    <div class="auth-form-panel !px-6 sm:!px-10 lg:!px-12 xl:!px-16">

        {{-- Mobile logo (only < lg) --}}
        <div class="lg:hidden mb-8">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <span class="font-sans font-black text-xl tracking-tighter text-[var(--color-accent)]">CH</span>
                <span class="font-serif text-lg opacity-70">Curahan Hati</span>
            </a>
        </div>

        {{-- Form Header --}}
        <div class="mb-10">
            <p class="text-ui text-[0.65rem] text-[var(--color-accent)] mb-3">
                {{ $mode === 'login' ? '— Authentication' : '— Create Account' }}
            </p>
            <h1 class="font-serif text-4xl xl:text-5xl tracking-tight leading-tight mb-3">
                {!! $mode === 'login' ? 'Welcome<br>back.' : 'Start your<br>story.' !!}
            </h1>
            <p class="text-sm opacity-50 text-black">
                @if ($mode === 'login')
                    Don't have an account?
                    <a href="{{ route('frontend.auth.register') }}" class="text-[var(--color-accent)] font-semibold hover:underline">Register for free</a>
                @else
                    Already have an account?
                    <a href="{{ route('frontend.auth.login') }}" class="text-[var(--color-accent)] font-semibold hover:underline">Sign in</a>
                @endif
            </p>
        </div>

        {{-- ── Slot: The actual form content ── --}}
        {{ $slot }}

        {{-- Bottom Footer --}}
        <p class="mt-8 text-[0.65rem] opacity-30 text-center font-mono">
            © {{ date('Y') }} Curahan Hati Blog. All rights reserved.
        </p>
    </div>
</div>
