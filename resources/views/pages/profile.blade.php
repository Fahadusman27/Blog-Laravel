{{--
    PAGE: User Profile
    Route: frontend.user.account (or frontend.user.public)
    Layout: layouts.Curahan Hati
    Variables: $user (User model), $posts (paginated)
--}}
@extends('layouts.Curahan Hati')

@section('title', ($user->name ?? 'Profile') . ' — Profile')
@section('meta_description', 'Read articles by ' . ($user->name ?? 'this author') . ' on Curahan Hati.')

@section('content')

    {{-- ═══════════════════════════════════════════
         PROFILE HERO — Overlapping asymmetric layout
    ═══════════════════════════════════════════ --}}
    <section class="pt-24 pb-0 relative overflow-hidden" aria-label="User profile">

        {{-- Background pattern --}}
        <div class="absolute inset-0 pointer-events-none opacity-30" style="background: repeating-linear-gradient(135deg, transparent, transparent 40px, var(--color-border) 40px, var(--color-border) 41px);"></div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[var(--color-canvas)] dark:from-[var(--color-abyss)] to-transparent"></div>

        <div class="max-w-screen-xl mx-auto px-6 relative z-10">

            {{-- Profile Card — overlaps the section below via negative margin --}}
            <div class="grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-8 lg:gap-12 items-start">

                {{-- ── Avatar (large, editorial) ── --}}
                <div class="relative animate-fade-up">
                    <div class="w-32 h-32 lg:w-44 lg:h-44 rounded-[4px] bg-gradient-to-br from-[var(--color-accent)] via-[var(--color-accent-glow)] to-[var(--color-abyss)] flex items-center justify-center text-white font-bold text-5xl lg:text-7xl font-serif shadow-2xl border-4 border-white dark:border-[var(--color-abyss-light)]">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                    </div>
                    {{-- "Live" indicator if user is online --}}
                    <div class="absolute -bottom-2 -right-2 w-5 h-5 rounded-full bg-[var(--color-neon-cyan)] border-2 border-white dark:border-[var(--color-abyss)] animate-[pulse-dot_2s_ease-in-out_infinite]"></div>
                </div>

                {{-- ── Profile Info ── --}}
                <div class="pb-8 animate-fade-up" style="animation-delay: 0.1s;">

                    <div class="flex flex-wrap items-start gap-4 mb-4">
                        <div>
                            <p class="text-ui text-[0.65rem] text-[var(--color-accent)] mb-1">Author</p>
                            <h1 class="font-serif text-4xl lg:text-6xl tracking-tight leading-none mb-2">
                                {{ $user->name ?? 'Anonymous Writer' }}
                            </h1>
                            <p class="text-sm opacity-50 font-mono">@{{ $user->username ?? 'username' }}</p>
                        </div>

                        {{-- Edit button (own profile) --}}
                        @auth
                            @if (auth()->id() === ($user->id ?? null))
                                <div class="ml-auto">
                                    <x-ui.magnetic-button href="{{ route('frontend.user.account') }}" variant="ghost" size="sm" id="edit-profile-btn">
                                        Edit Profile
                                    </x-ui.magnetic-button>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Bio --}}
                    <p class="text-base opacity-60 max-w-xl leading-relaxed mb-8">
                        {{ $user->bio ?? 'A curious mind exploring the boundaries of design, code, and human creativity. Writing about things that matter.' }}
                    </p>

                    {{-- ── Stats Row ── --}}
                    <div class="flex flex-wrap gap-8">
                        <div>
                            <p class="stat-number">{{ $user->posts_count ?? 24 }}</p>
                            <p class="text-ui text-[0.65rem] opacity-40 mt-1">Articles</p>
                        </div>
                        <div class="w-px bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] hidden sm:block"></div>
                        <div>
                            <p class="stat-number">{{ $user->total_likes ?? '1.2k' }}</p>
                            <p class="text-ui text-[0.65rem] opacity-40 mt-1">Total Likes</p>
                        </div>
                        <div class="w-px bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] hidden sm:block"></div>
                        <div>
                            <p class="stat-number">{{ $user->followers_count ?? 340 }}</p>
                            <p class="text-ui text-[0.65rem] opacity-40 mt-1">Followers</p>
                        </div>
                        <div class="w-px bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] hidden sm:block"></div>
                        <div>
                            <p class="stat-number">{{ $user->created_at?->format('Y') ?? date('Y') }}</p>
                            <p class="text-ui text-[0.65rem] opacity-40 mt-1">Joined</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         POSTS GRID — Anti-conventional layout
    ═══════════════════════════════════════════ --}}
    <section class="max-w-screen-xl mx-auto px-6 mt-16 mb-24" aria-label="Author's articles">

        {{-- Section Header --}}
        <div class="flex items-center gap-4 mb-12">
            <h2 class="font-serif text-3xl tracking-tight shrink-0">Articles</h2>
            <div class="sep-accent flex-1 self-end mb-2"></div>
            <p class="text-ui text-[0.65rem] opacity-40 shrink-0">{{ $user->posts_count ?? 24 }} published</p>
        </div>

        {{-- Asymmetric Grid: first post is LARGE, rest are smaller --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 auto-rows-auto">

            @forelse (($posts ?? []) as $i => $post)
                <article
                    class="group relative {{ $i === 0 ? 'md:col-span-2 xl:col-span-2 md:row-span-2' : '' }} flex flex-col border border-[var(--color-border)] dark:border-[var(--color-border-dark)] bg-white dark:bg-[var(--color-abyss-light)] rounded-[2px] overflow-hidden hover:border-[var(--color-accent)] transition-all duration-300"
                    data-animate
                >
                    {{-- Thumbnail --}}
                    @if (isset($post->image_url))
                        <div class="relative overflow-hidden {{ $i === 0 ? 'h-64 md:h-80' : 'h-44' }}">
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    @else
                        <div class="{{ $i === 0 ? 'h-48' : 'h-28' }} bg-gradient-to-br from-[var(--color-border)] to-[var(--color-border)] dark:from-[var(--color-border-dark)] dark:to-[var(--color-abyss)] relative overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                <span class="font-serif text-6xl font-bold tracking-tighter">{{ strtoupper(substr($post->title ?? 'P', 0, 1)) }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="flex flex-col flex-1 p-5 {{ $i === 0 ? 'lg:p-7' : '' }}">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="tag">{{ $post->category->name ?? 'General' }}</span>
                            <span class="text-[0.65rem] opacity-30 font-mono ml-auto">{{ $post->read_time ?? '5 min' }}</span>
                        </div>

                        <h3 class="font-serif {{ $i === 0 ? 'text-2xl lg:text-3xl' : 'text-lg' }} tracking-tight leading-tight mb-3 group-hover:text-[var(--color-accent)] transition-colors">
                            <a href="{{ route('frontend.blog.show', $post->slug ?? '#') }}">
                                {{ $post->title ?? 'Article Title' }}
                            </a>
                        </h3>

                        @if ($i === 0)
                            <p class="text-sm opacity-60 leading-relaxed line-clamp-3 mb-4">
                                {{ $post->excerpt ?? 'A compelling excerpt that draws the reader in and makes them want to read more...' }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)]">
                            <time class="text-xs opacity-30 font-mono">
                                {{ isset($post->created_at) ? $post->created_at->format('M d, Y') : 'Jan 01, 2025' }}
                            </time>
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1 text-xs opacity-40">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    {{ $post->likes_count ?? rand(10, 200) }}
                                </span>
                                <a href="{{ route('frontend.blog.show', $post->slug ?? '#') }}"
                                   class="text-[var(--color-accent)] opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1 text-xs font-semibold">
                                    Read
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-24">
                    <p class="font-serif text-4xl opacity-20 mb-4">✦</p>
                    <p class="text-lg opacity-40 mb-2">No articles yet.</p>
                    <p class="text-sm opacity-30">Check back soon.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if (isset($posts) && $posts->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $posts->links('vendor.pagination.Curahan Hati') }}
            </div>
        @endif

    </section>

@endsection
