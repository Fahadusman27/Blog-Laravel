{{--
    PAGE: Post Detail / Article View
    Route: frontend.blog.show (or equivalent)
    Layout: layouts.Curahan Hati
    Variables passed from controller:
      $post   - Post model (title, body, excerpt, image, created_at, read_time)
      $author - User model (name, profile_slug)
      $category - Category model (name, slug)
      $liked  - bool
      $likeCount - int
      $comments - Collection
--}}
@extends('layouts.Curahan Hati')

@section('title', $post->title ?? 'Article')
@section('meta_description', $post->excerpt ?? '')

@section('content')

    {{-- ═══════════════════════════════════════════
         POST HEADER COMPONENT
         (overlapping editorial hero)
    ═══════════════════════════════════════════ --}}
    <x-blog.post-header
        :title="$post->title ?? 'The Art of Breaking Design Conventions'"
        :category="$category->name ?? 'Design'"
        :categorySlug="isset($category) ? route('frontend.blog.category', $category->slug) : '#'"
        :author="$author->name ?? 'Anonymous'"
        :authorSlug="isset($author) ? route('frontend.user.public', $author->username) : '#'"
        :date="$post->created_at ?? now()"
        :readTime="$post->read_time ?? '7 min read'"
        :image="$post->image_url ?? null"
        :excerpt="$post->excerpt ?? ''"
    />

    {{-- ═══════════════════════════════════════════
         FLOATING SHARE SIDEBAR
    ═══════════════════════════════════════════ --}}
    <x-blog.share-floater
        :url="url()->current()"
        :title="$post->title ?? 'Article'"
    />

    {{-- ═══════════════════════════════════════════
         MAIN ARTICLE AREA — Asymmetric Grid
    ═══════════════════════════════════════════ --}}
    <div class="max-w-screen-xl mx-auto px-6 mt-16 mb-24">
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_280px] gap-12 xl:gap-16">

            {{-- ─── LEFT: Article Body ─── --}}
            <article class="max-w-3xl" id="article-body">

                {{-- ── Tags --}}
                <div class="flex flex-wrap gap-2 mb-10" data-animate>
                    @foreach (($post->tags ?? ['Design', 'Typography', 'Editorial']) as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>

                {{-- ── Article Body (Prose / Editorial) ── --}}
                {{-- Use font-serif for article body — the contrast against sans-serif UI elements is intentional --}}
                <div class="prose prose-lg max-w-none
                            font-serif text-[1.05rem] leading-[1.85] tracking-[0.01em]
                            text-[var(--color-ink)] dark:text-[#e8e8e2]
                            prose-headings:font-serif prose-headings:tracking-tight
                            prose-h2:text-3xl prose-h2:mt-16 prose-h2:mb-6 prose-h2:text-editorial
                            prose-h3:text-xl prose-h3:mt-10 prose-h3:mb-4
                            prose-a:text-[var(--color-accent)] prose-a:no-underline hover:prose-a:underline
                            prose-blockquote:border-l-[var(--color-accent)] prose-blockquote:border-l-4
                            prose-blockquote:pl-6 prose-blockquote:italic prose-blockquote:text-xl
                            prose-code:font-mono prose-code:text-sm prose-code:bg-[var(--color-border)]/50
                            prose-strong:font-bold
                            prose-img:rounded-[2px]
                            " data-animate>
                    {!! $post->body ?? '<p>Article content goes here. The editorial typography uses <strong>DM Serif Display</strong> for that premium magazine feel, contrasting sharply with the sans-serif UI elements surrounding it.</p><h2>A New Design Language</h2><p>Vivamus lacinia odio vitae vestibulum. Donec in efficitur leo, in commodo orci. Donec in efficitur leo, in commodo orci.</p><blockquote>Design is not just what it looks like and feels like. Design is how it works.</blockquote><p>Pellentesque in ipsum id orci porta dapibus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.</p>' !!}
                </div>

                {{-- ── Separator ── --}}
                <div class="sep-accent mt-16 mb-12"></div>

                {{-- ── Action Row: Like + Share ── --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 py-8 border-y border-[var(--color-border)] dark:border-[var(--color-border-dark)]" data-animate>
                    <div class="flex flex-col gap-1">
                        <p class="text-ui text-[0.65rem] opacity-40">Did this article resonate?</p>
                        <p class="font-serif text-lg">Show some love ↓</p>
                    </div>

                    <x-blog.like-button
                        :postId="$post->id ?? 1"
                        :count="$likeCount ?? 42"
                        :liked="$liked ?? false"
                    />
                </div>

                {{-- ── Author Bio Card ── --}}
                <div class="mt-12 p-6 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] rounded-[2px] flex items-start gap-5 bg-white dark:bg-[var(--color-abyss-light)]" data-animate>
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white font-bold text-lg shrink-0">
                        {{ strtoupper(substr($author->name ?? 'AU', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <p class="font-semibold text-sm mb-0.5">{{ $author->name ?? 'The Author' }}</p>
                                <p class="text-ui text-[0.6rem] text-[var(--color-accent)] mb-2">Author</p>
                            </div>
                            <a href="{{ isset($author) ? route('frontend.user.public', $author->username) : '#' }}"
                               class="btn-ghost !py-1.5 !px-3 !text-[0.65rem] shrink-0">View Profile</a>
                        </div>
                        <p class="text-sm opacity-60 leading-relaxed">
                            {{ $author->bio ?? 'A passionate writer exploring the intersection of design, technology, and human experience.' }}
                        </p>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════
                     COMMENTS SECTION
                ═══════════════════════════════════════ --}}
                <div class="mt-16">

                    {{-- Existing Comments --}}
                    @if (isset($comments) && $comments->count() > 0)
                        <div class="mb-12">
                            <div class="flex items-baseline gap-4 mb-8">
                                <h2 class="font-serif text-2xl tracking-tight">
                                    {{ $comments->count() }} {{ Str::plural('Comment', $comments->count()) }}
                                </h2>
                                <div class="sep-accent flex-1"></div>
                            </div>

                            <div class="space-y-6">
                                @foreach ($comments as $comment)
                                    <div class="flex items-start gap-4" data-animate>
                                        <div class="w-9 h-9 rounded-full bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] flex items-center justify-center text-sm font-bold shrink-0">
                                            {{ strtoupper(substr($comment->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-baseline gap-3 flex-wrap">
                                                <p class="font-semibold text-sm">{{ $comment->name ?? 'Anonymous' }}</p>
                                                <p class="text-xs opacity-30 font-mono">
                                                    {{ isset($comment->created_at) ? $comment->created_at->diffForHumans() : '2 days ago' }}
                                                </p>
                                            </div>
                                            <p class="text-sm mt-1 opacity-70 leading-relaxed">{{ $comment->body ?? 'A thoughtful comment about this article.' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Comment Form Component --}}
                    <x-form.comment-input
                        :postId="$post->id ?? 0"
                        :action="route('frontend.blog.comments.store', $post->id ?? 0)"
                    />
                </div>
            </article>

            {{-- ─── RIGHT: Sticky Sidebar ─── --}}
            <aside class="hidden xl:block" aria-label="Article sidebar">
                <div class="sticky top-28 space-y-8">

                    {{-- ── Table of Contents ── --}}
                    <div class="border border-[var(--color-border)] dark:border-[var(--color-border-dark)] rounded-[2px] p-5 bg-white dark:bg-[var(--color-abyss-light)]"
                         x-data="{ activeSection: '' }"
                         x-init="
                            const headings = document.querySelectorAll('article h2, article h3');
                            const obs = new IntersectionObserver(entries => {
                                entries.forEach(e => { if (e.isIntersecting) activeSection = e.target.id; });
                            }, { rootMargin: '-20% 0% -60% 0%' });
                            headings.forEach((h, i) => { if(!h.id) h.id = 'section-' + i; obs.observe(h); });
                         ">
                        <p class="text-ui text-[0.6rem] mb-4 opacity-40">Contents</p>
                        <nav aria-label="Table of contents">
                            <div id="toc-container" class="space-y-1 text-sm">
                                {{-- Auto-generated by JS, or you can @foreach post headings --}}
                                <p class="text-xs opacity-30 italic">Headings appear here</p>
                            </div>
                        </nav>
                    </div>

                    {{-- ── Related / More Posts ── --}}
                    <div>
                        <p class="text-ui text-[0.6rem] mb-4 opacity-40">More from {{ $category->name ?? 'Design' }}</p>
                        <div class="space-y-4">
                            @foreach (range(1, 3) as $i)
                                <a href="#" class="group flex items-start gap-3">
                                    <div class="w-12 h-12 rounded-[2px] bg-gradient-to-br from-[var(--color-border)] to-[var(--color-border)] dark:from-[var(--color-border-dark)] dark:to-[var(--color-abyss-mid)] shrink-0 overflow-hidden">
                                        <div class="w-full h-full skeleton"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium line-clamp-2 group-hover:text-[var(--color-accent)] transition-colors leading-snug">
                                            Related Article Title {{ $i }}
                                        </p>
                                        <p class="text-[0.65rem] opacity-30 mt-0.5 font-mono">5 min read</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Newsletter Nudge ── --}}
                    <div class="bg-[var(--color-abyss)] text-white rounded-[2px] p-5 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 rounded-full opacity-20"
                             style="background: radial-gradient(circle, var(--color-accent), transparent); transform: translate(30%, -30%);"></div>
                        <p class="text-ui text-[0.6rem] text-[var(--color-accent)] mb-2">Newsletter</p>
                        <p class="font-serif text-lg mb-3 relative z-10">Get the best articles weekly</p>
                        <x-ui.magnetic-button size="sm" class="w-full justify-center !py-2.5" id="newsletter-sidebar-btn">
                            Subscribe Free
                        </x-ui.magnetic-button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

@push('scripts')
<script>
// Auto-build Table of Contents
document.addEventListener('DOMContentLoaded', () => {
    const toc = document.getElementById('toc-container');
    const headings = document.querySelectorAll('#article-body h2, #article-body h3');
    if (headings.length === 0 || !toc) return;

    toc.innerHTML = '';
    headings.forEach((h, i) => {
        if (!h.id) h.id = 'section-' + i;
        const a = document.createElement('a');
        a.href = '#' + h.id;
        a.textContent = h.textContent;
        a.className = `block py-1 text-xs transition-colors duration-200 ${
            h.tagName === 'H3' ? 'pl-3 opacity-50' : 'font-medium'
        } hover:text-[var(--color-accent)] truncate`;
        toc.appendChild(a);
    });
});
</script>
@endpush

@endsection
