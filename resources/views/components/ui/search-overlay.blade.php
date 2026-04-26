{{--
    ╔═══════════════════════════════════════════════╗
    ║  <x-ui.search-overlay />                     ║
    ║  Full-screen Search Overlay (Ctrl+K trigger)  ║
    ║  Props: none (listens to Alpine events)        ║
    ╚═══════════════════════════════════════════════╝
--}}
<div
    x-data="{
        open: false,
        query: '',
        results: { articles: [], users: [], categories: [] },
        loading: false,
        activeTab: 'all',
        selectedIndex: -1,

        get allResults() {
            return [
                ...this.results.articles,
                ...this.results.users,
                ...this.results.categories
            ];
        },

        async search() {
            if (this.query.length < 2) {
                this.results = { articles: [], users: [], categories: [] };
                return;
            }
            this.loading = true;
            try {
                const res = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (res.ok) {
                    this.results = await res.json();
                }
            } catch (e) {
                // Fallback mock for demo
                this.results = {
                    articles: [
                        { id: 1, title: 'The Future of Design Systems', excerpt: 'How editorial thinking reshapes UI...', category: 'Design', slug: '#' },
                        { id: 2, title: 'Typography as a Weapon', excerpt: 'Brutalist type in the digital age...', category: 'Typography', slug: '#' },
                    ],
                    users: [
                        { id: 1, name: 'Alex Rivera', role: 'Author', avatar: null, username: 'alex' },
                    ],
                    categories: [
                        { id: 1, name: 'Design', count: 42, slug: '#' },
                        { id: 2, name: 'Technology', count: 18, slug: '#' },
                    ]
                };
            } finally {
                this.loading = false;
            }
        },

        openOverlay() {
            this.open = true;
            this.$nextTick(() => this.$refs.searchInput?.focus());
            document.body.style.overflow = 'hidden';
        },

        closeOverlay() {
            this.open = false;
            this.query = '';
            this.results = { articles: [], users: [], categories: [] };
            document.body.style.overflow = '';
        },

        handleKey(e) {
            const total = this.allResults.length;
            if (e.key === 'ArrowDown') {
                this.selectedIndex = (this.selectedIndex + 1) % total;
            } else if (e.key === 'ArrowUp') {
                this.selectedIndex = (this.selectedIndex - 1 + total) % total;
            } else if (e.key === 'Enter' && this.selectedIndex >= 0) {
                // Navigate to selected result
            }
        }
    }"
    @open-search.window="openOverlay()"
    @close-search.window="closeOverlay()"
    @keydown.escape.window="closeOverlay()"
    x-show="open"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-label="Search"
    class="search-overlay flex flex-col items-center pt-[12vh] px-6"
    @click.self="closeOverlay()"
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    {{-- ── Search Panel ── --}}
    <div class="w-full max-w-2xl animate-search-in" @keydown="handleKey($event)">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-ui text-xs text-white opacity-40">Search Everything</p>
            <button @click="closeOverlay()"
                    class="text-ui text-[0.65rem] text-white opacity-40 hover:opacity-80 flex items-center gap-1.5 transition-opacity">
                <kbd class="font-mono bg-white/10 px-1.5 py-0.5 rounded text-[0.65rem]">ESC</kbd>
                Close
            </button>
        </div>

        {{-- Input Box --}}
        <div class="relative mb-2">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-50">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
            </div>

            <input
                x-ref="searchInput"
                x-model="query"
                @input.debounce.300ms="search()"
                type="search"
                id="global-search-input"
                placeholder="Type to search articles, authors, categories…"
                autocomplete="off"
                class="w-full bg-white/[0.07] hover:bg-white/10 focus:bg-white/[0.12] border border-white/10 focus:border-[var(--color-accent)] text-white placeholder-white/30 text-lg font-light pl-12 pr-4 py-5 rounded-[2px] outline-none transition-all duration-200 font-sans"
            >

            {{-- Loading spinner --}}
            <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                <div class="w-4 h-4 border-2 border-white/20 border-t-[var(--color-accent)] rounded-full animate-spin"></div>
            </div>
        </div>

        {{-- Tab Filters --}}
        <div x-show="query.length >= 2" class="flex gap-1 mb-4 mt-4">
            <template x-for="tab in ['all', 'articles', 'users', 'categories']">
                <button
                    @click="activeTab = tab"
                    :class="activeTab === tab
                        ? 'bg-[var(--color-accent)] text-white border-[var(--color-accent)]'
                        : 'text-white/50 border-white/10 hover:border-white/30 hover:text-white/80'"
                    class="text-ui text-[0.65rem] px-3 py-1.5 border rounded-[2px] transition-all capitalize"
                    x-text="tab"
                ></button>
            </template>
        </div>

        {{-- Results ── --}}
        <div x-show="query.length >= 2 && !loading" class="space-y-1">

            {{-- Articles Section --}}
            <template x-if="(activeTab === 'all' || activeTab === 'articles') && results.articles.length > 0">
                <div>
                    <p class="text-ui text-[0.6rem] text-white/30 mb-2 mt-4">Articles</p>
                    <template x-for="(item, i) in results.articles" :key="item.id">
                        <a :href="item.slug"
                           class="flex items-start gap-4 p-3 rounded-[2px] bg-white/[0.04] hover:bg-white/[0.08] border border-transparent hover:border-white/10 transition-all group cursor-pointer"
                           :class="selectedIndex === i ? 'bg-white/10 border-white/10' : ''">
                            <div class="w-8 h-8 mt-0.5 rounded-[2px] bg-[var(--color-accent)]/20 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-[var(--color-accent)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-medium truncate group-hover:text-[var(--color-accent-glow)] transition-colors" x-text="item.title"></p>
                                <p class="text-white/40 text-xs truncate mt-0.5" x-text="item.excerpt"></p>
                            </div>
                            <span class="tag !bg-transparent !text-white/30 !border-white/10 shrink-0" x-text="item.category"></span>
                        </a>
                    </template>
                </div>
            </template>

            {{-- Users Section --}}
            <template x-if="(activeTab === 'all' || activeTab === 'users') && results.users.length > 0">
                <div>
                    <p class="text-ui text-[0.6rem] text-white/30 mb-2 mt-4">Authors</p>
                    <template x-for="(user, i) in results.users" :key="user.id">
                        <a :href="`/users/${user.username}`"
                           class="flex items-center gap-4 p-3 rounded-[2px] bg-white/[0.04] hover:bg-white/[0.08] border border-transparent hover:border-white/10 transition-all group">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--color-accent)] to-[var(--color-accent-glow)] flex items-center justify-center text-white font-bold text-sm shrink-0"
                                 x-text="user.name.charAt(0)">
                            </div>
                            <div>
                                <p class="text-white text-sm font-medium group-hover:text-[var(--color-accent-glow)] transition-colors" x-text="user.name"></p>
                                <p class="text-white/40 text-xs" x-text="user.role"></p>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            {{-- Categories Section --}}
            <template x-if="(activeTab === 'all' || activeTab === 'categories') && results.categories.length > 0">
                <div>
                    <p class="text-ui text-[0.6rem] text-white/30 mb-2 mt-4">Categories</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="cat in results.categories" :key="cat.id">
                            <a :href="cat.slug"
                               class="flex items-center gap-2 px-3 py-2 bg-white/[0.06] hover:bg-[var(--color-accent)] border border-white/10 hover:border-[var(--color-accent)] rounded-[2px] transition-all group">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--color-accent)] group-hover:bg-white transition-colors"></span>
                                <span class="text-white text-xs font-medium" x-text="cat.name"></span>
                                <span class="text-white/30 text-xs font-mono group-hover:text-white/70" x-text="`(${cat.count})`"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Empty State --}}
            <template x-if="allResults.length === 0 && !loading && query.length >= 2">
                <div class="text-center py-12">
                    <p class="font-serif text-4xl text-white/20 mb-3">∅</p>
                    <p class="text-white/40 text-sm">Nothing found for "<span x-text="query" class="text-white/60"></span>"</p>
                    <p class="text-white/25 text-xs mt-1">Try different keywords or browse categories</p>
                </div>
            </template>
        </div>

        {{-- Hint state --}}
        <div x-show="query.length < 2" class="text-center py-8 opacity-30">
            <p class="text-white text-sm">Start typing to search across all content…</p>
        </div>
    </div>
</div>
