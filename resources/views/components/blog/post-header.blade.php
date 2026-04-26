{{--
    ╔═══════════════════════════════════════════════╗
    ║  <x-blog.post-header>                        ║
    ║  Props:                                       ║
    ║    $title    - Article title (string)         ║
    ║    $category - Category name                  ║
    ║    $categorySlug - Category URL slug          ║
    ║    $author   - Author name                    ║
    ║    $authorSlug - Author profile slug          ║
    ║    $date     - Published date (Carbon/string) ║
    ║    $readTime - e.g. "8 min read"             ║
    ║    $image    - Hero image URL                 ║
    ║    $excerpt  - Short description              ║
    ╚═══════════════════════════════════════════════╝
--}}
@props([
    'title'        => 'Untitled Article',
    'category'     => 'Uncategorized',
    'categorySlug' => '#',
    'author'       => 'Anonymous',
    'authorSlug'   => '#',
    'date'         => null,
    'readTime'     => '5 min read',
    'image'        => null,
    'excerpt'      => '',
])

<header class="relative min-h-[85vh] overflow-hidden flex flex-col justify-end" aria-label="Post header">

    {{-- ── Hero Image / Background ── --}}
    <div class="absolute inset-0 z-0">
        @if ($image)
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="w-full h-full object-cover"
                loading="eager"
            >
        @else
            {{-- Gradient fallback --}}
            <div class="w-full h-full bg-gradient-to-br from-[var(--color-abyss)] via-[var(--color-abyss-mid)] to-[var(--color-accent)]/30"></div>
        @endif

        {{-- Overlay gradient → ensures title readability over image --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent"></div>
    </div>

    {{-- ── Content (overlaps image) ── --}}
    <div class="relative z-10 max-w-screen-xl mx-auto w-full px-6 pb-16 pt-32">

        {{-- Category Tag --}}
        <div class="mb-6" data-animate>
            <a href="{{ $categorySlug }}"
               class="inline-flex items-center gap-2 bg-[var(--color-accent)] text-white text-ui text-[0.65rem] px-3 py-1.5 rounded-[2px] hover:bg-white hover:text-[var(--color-accent)] transition-all">
                <span class="w-1.5 h-1.5 rounded-full bg-white/70 animate-[pulse-dot_1.5s_ease-in-out_infinite]"></span>
                {{ $category }}
            </a>
        </div>

        {{-- ── MASSIVE Editorial Title ── --}}
        {{-- This is the key: it overlaps the image with z-index, not tucked below --}}
        <h1 class="post-hero-title text-editorial text-white mb-6 max-w-5xl" data-animate
            style="text-shadow: 0 2px 40px rgba(0,0,0,0.5);">
            {{ $title }}
        </h1>

        @if ($excerpt)
            <p class="text-white/70 text-lg font-light max-w-2xl mb-8 leading-relaxed font-serif italic" data-animate>
                {{ $excerpt }}
            </p>
        @endif

        {{-- ── Meta Bar ── --}}
        <div class="flex flex-wrap items-center gap-4 md:gap-6" data-animate>

            {{-- Author --}}
            <a href="{{ $authorSlug }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white font-bold text-sm border-2 border-white/20 group-hover:border-[var(--color-accent)] transition-all">
                    {{ strtoupper(substr($author, 0, 2)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-semibold leading-tight group-hover:text-[var(--color-accent-glow)] transition-colors">{{ $author }}</p>
                    <p class="text-white/40 text-xs">Author</p>
                </div>
            </a>

            <span class="w-px h-8 bg-white/20 hidden sm:block"></span>

            {{-- Date --}}
            @if ($date)
                <div class="text-center">
                    <p class="text-white/80 text-sm font-medium">
                        {{ is_string($date) ? $date : $date->format('M d, Y') }}
                    </p>
                    <p class="text-white/30 text-xs">Published</p>
                </div>
                <span class="w-px h-8 bg-white/20 hidden sm:block"></span>
            @endif

            {{-- Read time --}}
            <div class="flex items-center gap-1.5 text-white/50 text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $readTime }}
            </div>
        </div>
    </div>

    {{-- ── Diagonal Clip at Bottom ── --}}
    {{-- Creates the "breaking out of the grid" effect --}}
    <div class="absolute bottom-0 left-0 right-0 h-24 z-20"
         style="background: var(--color-canvas); clip-path: polygon(0 100%, 100% 100%, 100% 40%, 0 100%); opacity: 0.15;">
    </div>
</header>
