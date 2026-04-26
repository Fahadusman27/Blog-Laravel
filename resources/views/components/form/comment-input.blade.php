{{--
    ╔══════════════════════════════════════════════════╗
    ║  <x-form.comment-input>                         ║
    ║  Comment form with profanity detection UI       ║
    ║  Props:                                          ║
    ║    $postId   - Post ID for the form action      ║
    ║    $action   - Form action URL                  ║
    ╚══════════════════════════════════════════════════╝
--}}
@props([
    'postId' => 0,
    'action' => '#',
])

<section
    x-data="{
        comment: '',
        name: '',
        email: '',
        hasProfanity: false,
        submitting: false,
        submitted: false,
        charCount: 0,
        maxChars: 1000,

        // — Word blacklist (extend as needed) —
        badWords: ['anjing', 'bangsat', 'kontol', 'memek', 'babi', 'bajingan', 'fuck', 'shit', 'damn', 'asshole', 'bitch', 'idiot'],

        checkProfanity() {
            const lower = this.comment.toLowerCase();
            this.hasProfanity = this.badWords.some(word => lower.includes(word));
            this.charCount = this.comment.length;
        },

        async submitComment() {
            if (this.hasProfanity || this.submitting) return;
            if (!this.comment.trim()) return;

            this.submitting = true;

            try {
                const res = await fetch('{{ $action }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        post_id: {{ $postId }},
                        body: this.comment,
                        name: this.name,
                        email: this.email,
                    })
                });

                if (res.ok) {
                    this.submitted = true;
                    this.comment = '';
                    this.name = '';
                    this.email = '';
                    this.charCount = 0;
                }
            } catch (e) {
                // Handle error
            } finally {
                this.submitting = false;
            }
        }
    }"
    class="py-12"
    id="comments"
    aria-label="Leave a comment"
>
    {{-- ── Section Header ── --}}
    <div class="flex items-baseline gap-4 mb-8">
        <h2 class="font-serif text-3xl tracking-tight">Comments</h2>
        <div class="sep-accent flex-1"></div>
    </div>

    {{-- ── Success State ── --}}
    <div
        x-show="submitted"
        x-transition:enter="transition duration-500 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-[var(--color-neon-cyan)]/10 border border-[var(--color-neon-cyan)]/30 rounded-[2px] p-6 mb-8 flex items-start gap-4"
    >
        <div class="w-10 h-10 rounded-full bg-[var(--color-neon-cyan)]/20 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-[var(--color-neon-cyan)]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <p class="font-semibold text-sm mb-1">Comment submitted!</p>
            <p class="text-sm opacity-60">Your comment is awaiting moderation. Thanks for joining the conversation.</p>
        </div>
    </div>

    {{-- ── Comment Form ── --}}
    <form @submit.prevent="submitComment()" x-show="!submitted" novalidate>

        {{-- Name + Email row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div class="flex flex-col gap-1.5">
                <label for="comment-name" class="text-ui text-[0.65rem] opacity-50">Your Name *</label>
                <input
                    type="text"
                    id="comment-name"
                    x-model="name"
                    placeholder="Alex Rivera"
                    required
                    maxlength="80"
                    class="comment-textarea !min-h-0 !py-3 focus-accent"
                    :class="name.length === 0 && submitting ? 'input-neon-error' : ''"
                >
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="comment-email" class="text-ui text-[0.65rem] opacity-50">Email (not published) *</label>
                <input
                    type="email"
                    id="comment-email"
                    x-model="email"
                    placeholder="alex@example.com"
                    required
                    maxlength="255"
                    class="comment-textarea !min-h-0 !py-3 focus-accent"
                >
            </div>
        </div>

        {{-- Comment Textarea --}}
        <div class="flex flex-col gap-1.5 mb-2">
            <div class="flex items-center justify-between">
                <label for="comment-body" class="text-ui text-[0.65rem] opacity-50">Your Comment *</label>
                <span class="text-xs font-mono opacity-30"
                      :class="charCount > maxChars * 0.9 ? 'text-[var(--color-neon-red)] opacity-80' : ''"
                      x-text="`${charCount} / ${maxChars}`"></span>
            </div>

            <div class="relative">
                <textarea
                    id="comment-body"
                    x-model="comment"
                    @input="checkProfanity()"
                    placeholder="Share your thoughts, questions, or feedback…"
                    maxlength="1000"
                    required
                    class="comment-textarea focus-accent"
                    :class="{
                        'input-neon-error': hasProfanity,
                        'border-[var(--color-neon-cyan)]': comment.length > 0 && !hasProfanity,
                    }"
                    :aria-invalid="hasProfanity"
                    aria-describedby="comment-warning"
                ></textarea>

                {{-- ── PROFANITY WARNING — Glitch/Bounce UI ── --}}
                <div
                    id="comment-warning"
                    x-show="hasProfanity"
                    x-transition:enter="transition duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="absolute -bottom-2 left-0 right-0 translate-y-full z-10"
                    role="alert"
                    aria-live="assertive"
                >
                    <div class="mt-2 flex items-start gap-3 bg-[var(--color-neon-red)]/10 border border-[var(--color-neon-red)]/50 rounded-[2px] px-4 py-3">
                        {{-- Glitch Icon --}}
                        <div class="shrink-0 mt-0.5 relative">
                            <svg class="w-4 h-4 text-[var(--color-neon-red)] animate-glitch" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-[var(--color-neon-red)] text-xs font-bold tracking-wide mb-0.5 animate-bounce-in">
                                ⚡ Inappropriate language detected
                            </p>
                            <p class="text-xs opacity-70">
                                Please keep the conversation civil and respectful. Remove flagged words before submitting.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Char progress bar ── --}}
        <div class="h-0.5 bg-[var(--color-border)] rounded-full mb-6 mt-6">
            <div
                class="h-full rounded-full transition-all duration-300"
                :style="`width: ${Math.min((charCount / maxChars) * 100, 100)}%`"
                :class="charCount > maxChars * 0.9 ? 'bg-[var(--color-neon-red)]' : 'bg-[var(--color-accent)]'"
            ></div>
        </div>

        {{-- ── Submit ── --}}
        <div class="flex items-center gap-4 flex-wrap">
            <button
                type="submit"
                :disabled="hasProfanity || submitting || !comment.trim() || !name.trim()"
                :class="{
                    'opacity-40 cursor-not-allowed': hasProfanity || !comment.trim() || !name.trim(),
                    'cursor-wait': submitting,
                }"
                class="btn-accent flex items-center gap-2"
                id="submit-comment-btn"
            >
                {{-- Spinner --}}
                <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                </svg>

                <span x-text="submitting ? 'Posting…' : 'Post Comment'"></span>

                {{-- Arrow icon --}}
                <svg x-show="!submitting" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
            </button>

            <p class="text-xs opacity-40">
                Comments are moderated. Be respectful.
            </p>
        </div>
    </form>
</section>
