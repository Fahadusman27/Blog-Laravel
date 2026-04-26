{{--
    ╔═══════════════════════════════════════════════╗
    ║  <x-blog.post-card>                          ║
    ║  Reusable post card for listings             ║
    ║  Props:                                       ║
    ║    $title    - Post title                     ║
    ║    $excerpt  - Short excerpt                  ║
    ║    $image    - Thumbnail URL                  ║
    ║    $slug     - Post slug for linking          ║
    ║    $category - Category name                  ║
    ║    $author   - Author name                    ║
    ║    $date     - Published date                 ║
    ║    $readTime - Read time string               ║
    ║    $likes    - Like count                     ║
    ║    $index    - Card index (for hue rotation)  ║
    ╚═══════════════════════════════════════════════╝
--}}
@props([
    'title'    => 'Article Title',
    'excerpt'  => '',
    'image'    => null,
    'slug'     => '#',
    'category' => 'General',
    'categorySlug' => '#',
    'author'   => 'Staff',
    'date'     => null,
    'readTime' => '5 min',
    'likes'    => 0,
    'index'    => 0,
    'featured' => false,
])

<article class="group flex flex-col border border-[var(--color-border)] dark:border-[var(--color-border-dark)] bg-white dark:bg-[var(--color-abyss-light)] rounded-[2px] overflow-hidden hover:border-[var(--color-accent)] hover:shadow-[0_8px_40px_rgba(255,77,0,0.08)] transition-all duration-300"
         {{ $attributes }}>

    {{-- Thumbnail --}}
    <a href="{{ $slug }}" class="block relative overflow-hidden {{ $featured ? 'h-64' : 'h-48' }}" tabindex="-1" aria-hidden="true">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $title }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center"
                 style="background: linear-gradient(135deg, hsl({{ ($index * 47) % 360 }}, 20%, 90%), hsl({{ ($index * 47 + 60) % 360 }}, 15%, 85%));">
                <span class="font-serif text-5xl opacity-20 font-bold">{{ strtoupper(substr($title, 0, 1)) }}</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-[var(--color-accent)] opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
    </a>

    {{-- Body --}}
    <div class="flex flex-col flex-1 p-5">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ $categorySlug }}" class="tag hover:border-[var(--color-accent)] hover:text-[var(--color-accent)]">
                {{ $category }}
            </a>
            @if ($featured)
                <span class="tag !border-[var(--color-accent)] !text-[var(--color-accent)]">★ Featured</span>
            @endif
        </div>

        <h3 class="{{ $featured ? 'text-2xl' : 'text-xl' }} font-serif tracking-tight leading-snug mb-3 group-hover:text-[var(--color-accent)] transition-colors">
            <a href="{{ $slug }}">{{ $title }}</a>
        </h3>

        @if ($excerpt && $featured)
            <p class="text-sm opacity-55 leading-relaxed line-clamp-2 mb-4">{{ $excerpt }}</p>
        @endif

        <div class="mt-auto flex items-center justify-between pt-4 border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white text-[0.55rem] font-bold">
                    {{ strtoupper(substr($author, 0, 1)) }}
                </div>
                <span class="text-xs opacity-50">{{ $author }}</span>
            </div>
            <div class="flex items-center gap-3">
                @if ($date)
                    <time class="text-xs opacity-30 font-mono hidden sm:block">
                        {{ is_string($date) ? $date : $date->format('M d') }}
                    </time>
                @endif
                <span class="text-xs opacity-30 font-mono">{{ $readTime }}</span>
                <span class="flex items-center gap-1 text-xs opacity-30">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    {{ $likes }}
                </span>
            </div>
        </div>
    </div>
</article>
