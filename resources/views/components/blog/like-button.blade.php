{{--
    ╔═══════════════════════════════════════════════╗
    ║  <x-blog.like-button>                        ║
    ║  Props:                                       ║
    ║    $postId   - Post ID                        ║
    ║    $count    - Initial like count (int)       ║
    ║    $liked    - Whether current user liked     ║
    ╚═══════════════════════════════════════════════╝
--}}
@props([
    'postId' => 0,
    'count'  => 0,
    'liked'  => false,
])

<div
    x-data="{
        liked: {{ $liked ? 'true' : 'false' }},
        count: {{ (int) $count }},
        animating: false,
        particles: [],

        async toggle() {
            if (this.animating) return;
            this.animating = true;
            this.liked = !this.liked;
            this.count += this.liked ? 1 : -1;

            // Burst particles
            if (this.liked) {
                this.spawnParticles();
            }

            // Haptic feedback (mobile)
            if (navigator.vibrate) navigator.vibrate(30);

            try {
                await fetch(`/api/posts/{{ $postId }}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } catch(e) {
                // Optimistic UI stays in place
            }

            setTimeout(() => this.animating = false, 600);
        },

        spawnParticles() {
            const colors = ['#ff4d00', '#ff6b2b', '#e8ff00', '#ff003c', '#ffffff'];
            this.particles = Array.from({length: 8}, (_, i) => ({
                id: Date.now() + i,
                color: colors[i % colors.length],
                angle: (i / 8) * 360,
                distance: 30 + Math.random() * 20,
            }));
            setTimeout(() => this.particles = [], 700);
        }
    }"
    class="relative inline-flex flex-col items-center gap-1"
    id="like-button-post-{{ $postId }}"
>
    {{-- ── Particle Burst Container ── --}}
    <div class="absolute inset-0 pointer-events-none overflow-visible" aria-hidden="true">
        <template x-for="p in particles" :key="p.id">
            <div
                class="absolute w-2 h-2 rounded-full top-1/2 left-1/2"
                :style="`background: ${p.color}; animation: particle-burst 0.6s ease-out forwards; --angle: ${p.angle}deg; --dist: ${p.distance}px;`"
            ></div>
        </template>
    </div>

    {{-- ── Button ── --}}
    <button
        @click="toggle()"
        :disabled="animating"
        :class="{
            'bg-[var(--color-accent)] border-[var(--color-accent)] text-white shadow-[0_0_20px_rgba(255,77,0,0.4)] scale-105': liked,
            'bg-white dark:bg-[var(--color-abyss-light)] border-[var(--color-border)] dark:border-[var(--color-border-dark)] text-[var(--color-muted)] hover:border-[var(--color-accent)] hover:text-[var(--color-accent)]': !liked,
            'animate-like': animating && liked,
        }"
        class="relative flex items-center gap-2 px-5 py-3 border-2 rounded-full font-sans font-semibold text-sm transition-all duration-300 cursor-pointer select-none"
        :aria-label="liked ? 'Unlike this post' : 'Like this post'"
        :aria-pressed="liked"
    >
        {{-- Heart Icon --}}
        <svg
            class="w-5 h-5 transition-all duration-300"
            :class="liked ? 'fill-white' : 'fill-none'"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
        >
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>

        {{-- Count --}}
        <span
            x-text="count"
            class="font-mono tabular-nums transition-all duration-200"
            :class="liked ? 'text-white' : ''"
        ></span>

        {{-- "Liked!" label --}}
        <span
            x-show="liked"
            x-transition:enter="transition duration-200 ease-out"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            class="text-xs text-white font-bold tracking-wide"
        >Liked!</span>
    </button>

    {{-- ── Encouragement text ── --}}
    <p class="text-[0.65rem] font-sans opacity-40 text-center transition-all duration-300"
       :class="liked ? 'opacity-70 text-[var(--color-accent)]' : ''">
        <span x-text="liked ? 'Thank you ♥' : 'Love this?'"></span>
    </p>
</div>

@push('head')
<style>
@keyframes particle-burst {
    0% {
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(0) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(calc(-1 * var(--dist))) scale(0);
        opacity: 0;
    }
}
</style>
@endpush
