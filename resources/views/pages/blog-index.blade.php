{{--
    PAGE: Blog Index / Home Feed
    Route: frontend.blog.index (or equivalent)
    Layout: layouts.Curahan Hati
    Variables: $featuredPost, $posts (paginated), $categories
--}}
@extends('layouts.Curahan Hati')

@section('title', 'Articles')
@section('meta_description', 'Explore bold ideas, editorial-grade writing on design, technology, and culture.')

@section('content')

    {{-- ═══════════════════════════════════════════
         HERO HEADER — Breaking the grid
    ═══════════════════════════════════════════ --}}
    <section class="pt-28 pb-12 relative overflow-hidden" aria-label="Blog hero">

        {{-- Decorative oversized text in background --}}
        <div class="absolute -top-8 right-0 font-serif text-[clamp(8rem,25vw,22rem)] leading-none text-[var(--color-ink)]/[0.025] dark:text-white/[0.03] select-none tracking-tighter pointer-events-none translate-x-8" aria-hidden="true">
            BLOG
        </div>

        <div class="max-w-screen-xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl">
                <p class="text-ui text-xs text-[var(--color-accent)] mb-4">— Editorial</p>
                <h1 class="font-serif text-[clamp(3rem,8vw,7rem)] leading-[0.92] tracking-tight mb-6">
                    Ideas that<br>
                    <em class="not-italic text-[var(--color-accent)]">defy</em> gravity.
                </h1>
                <p class="text-base opacity-50 max-w-lg leading-relaxed">
                    An editorial-grade publication for curious minds. Bold writing, sharp ideas, zero filler.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         FEATURED POST — Full bleed with overlap
    ═══════════════════════════════════════════ --}}
    @if (isset($featuredPost))
        <section class="max-w-screen-xl mx-auto px-6 mb-16" aria-label="Featured article">
            <a href="{{ route('frontend.blog.show', $featuredPost->slug) }}"
               class="group block relative" data-animate>
                <div class="relative overflow-hidden rounded-[2px] h-[60vh] min-h-[400px]">
                    @if ($featuredPost->image_url)
                        <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->title }}"
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[var(--color-abyss)] via-[var(--color-abyss-mid)] to-[var(--color-accent)]/40"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                    {{-- Content overlay --}}
                    <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12">
                        <span class="tag !bg-[var(--color-accent)] !text-white !border-[var(--color-accent)] mb-4 inline-block">
                            ★ Featured
                        </span>
                        <h2 class="font-serif text-white text-3xl lg:text-5xl tracking-tight leading-tight mb-4 group-hover:text-[var(--color-accent-glow)] transition-colors" style="text-shadow: 0 2px 20px rgba(0,0,0,0.5);">
                            {{ $featuredPost->title }}
                        </h2>
                        <div class="flex items-center gap-4 text-white/60 text-sm">
                            <span>{{ $featuredPost->author->name ?? 'Staff' }}</span>
                            <span>·</span>
                            <span>{{ $featuredPost->read_time ?? '8 min read' }}</span>
                            <span>·</span>
                            <span>{{ $featuredPost->created_at->format('M d') }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════
         CATEGORY FILTER ROW
    ═══════════════════════════════════════════ --}}
    <div class="max-w-screen-xl mx-auto px-6 mb-10" x-data="{ active: 'all' }">
        <div class="flex flex-wrap gap-2 items-center">
            <button @click="active = 'all'"
                    :class="active === 'all' ? 'bg-[var(--color-ink)] dark:bg-white text-[var(--color-canvas)] dark:text-[var(--color-abyss)] border-[var(--color-ink)] dark:border-white' : ''"
                    class="tag hover:border-[var(--color-accent)] hover:text-[var(--color-accent)] transition-all">
                All Posts
            </button>
            @foreach (($categories ?? []) as $cat)
                <button @click="active = '{{ $cat->slug }}'"
                        :class="active === '{{ $cat->slug }}' ? 'bg-[var(--color-ink)] dark:bg-white text-[var(--color-canvas)] dark:text-[var(--color-abyss)] border-[var(--color-ink)] dark:border-white' : ''"
                        class="tag hover:border-[var(--color-accent)] hover:text-[var(--color-accent)] transition-all">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         POSTS MOSAIC — Anti-grid layout
    ═══════════════════════════════════════════ --}}
    <section class="max-w-screen-xl mx-auto px-6 mb-24" aria-label="Article list">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse (($posts ?? []) as $i => $post)
                <article class="group flex flex-col border border-[var(--color-border)] dark:border-[var(--color-border-dark)] bg-white dark:bg-[var(--color-abyss-light)] rounded-[2px] overflow-hidden hover:border-[var(--color-accent)] hover:shadow-[0_8px_40px_rgba(255,77,0,0.08)] transition-all duration-300"
                         data-animate>
                    {{-- Thumbnail --}}
                    <div class="relative overflow-hidden h-48">
                        @if (isset($post->image_url))
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center"
                                 style="background: linear-gradient(135deg, hsl({{ ($i * 47) % 360 }}, 20%, 90%), hsl({{ ($i * 47 + 60) % 360 }}, 15%, 85%));">
                                <span class="font-serif text-5xl opacity-20 font-bold">
                                    {{ strtoupper(substr($post->title ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        {{-- Overlay on hover --}}
                        <div class="absolute inset-0 bg-[var(--color-accent)] opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-col flex-1 p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <a href="{{ isset($post->category) ? route('frontend.blog.category', $post->category->slug) : '#' }}"
                               class="tag hover:border-[var(--color-accent)] hover:text-[var(--color-accent)]">
                                {{ $post->category->name ?? 'General' }}
                            </a>
                        </div>

                        <h2 class="font-serif text-xl tracking-tight leading-snug mb-3 group-hover:text-[var(--color-accent)] transition-colors">
                            <a href="{{ route('frontend.blog.show', $post->slug ?? '#') }}">
                                {{ $post->title ?? 'Article Title' }}
                            </a>
                        </h2>

                        <p class="text-sm opacity-55 leading-relaxed line-clamp-2 mb-4">
                            {{ $post->excerpt ?? 'An insightful excerpt that draws the reader in.' }}
                        </p>

                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)]">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white text-[0.55rem] font-bold">
                                    {{ strtoupper(substr($post->author->name ?? 'A', 0, 1)) }}
                                </div>
                                <span class="text-xs opacity-50">{{ $post->author->name ?? 'Staff' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs opacity-30 font-mono">{{ $post->read_time ?? '5m' }}</span>
                                <span class="flex items-center gap-1 text-xs opacity-30">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    {{ $post->likes_count ?? rand(5, 150) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-24">
                    <p class="font-serif text-5xl opacity-15 mb-6">∅</p>
                    <p class="text-xl opacity-30 mb-2">No articles found.</p>
                    <p class="text-sm opacity-20">Be the first to publish something bold.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if (isset($posts) && method_exists($posts, 'hasPages') && $posts->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

@endsection
