<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Curahan Hati') — {{ config('app.name', 'Blog') }}</title>
    <meta name="description" content="@yield('meta_description', 'A bold editorial blog.')">
    <meta name="author" content="@yield('meta_author', 'Curahan Hati')">

    {{-- Preconnect for Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    {{-- Vite compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js via CDN (deferred) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('head')
</head>
<body class="noise" x-data="{ darkMode: localStorage.getItem('ag-theme') === 'dark' }"
      :class="{ 'dark': darkMode }">

    {{-- ═══════════════════════════════════════════
         GLOBAL SEARCH OVERLAY (Ctrl+K)
    ═══════════════════════════════════════════ --}}
    <x-ui.search-overlay />

    {{-- ═══════════════════════════════════════════
         NAVIGATION
    ═══════════════════════════════════════════ --}}
    <nav id="ag-nav" class="nav-Curahan Hati py-4 transition-all duration-300"
         x-data="{ mobileOpen: false }">
        <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between gap-8">

            {{-- Logotype --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <span class="text-ui text-lg font-black tracking-tighter text-[var(--color-accent)] group-hover:tracking-normal transition-all duration-300">CH</span>
                <span class="font-serif text-xl tracking-tight opacity-80">Curahan Hati</span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/blog') }}" class="text-ui text-xs opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-all">Articles</a>
                <a href="{{ url('/categories') }}" class="text-ui text-xs opacity-60 hover:opacity-100 transition-all">Categories</a>
                <a href="{{ url('/about') }}" class="text-ui text-xs opacity-60 hover:opacity-100 transition-all">About</a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-3">
                {{-- Search Trigger --}}
                <button id="search-trigger"
                        @click="$dispatch('open-search')"
                        @window:open-search.window="$el.blur()"
                        class="flex items-center gap-2 px-3 py-2 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] rounded-[2px] text-ui text-[0.65rem] opacity-60 hover:opacity-100 hover:border-[var(--color-accent)] transition-all group focus-accent"
                        aria-label="Open search">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <span class="hidden sm:inline">Search</span>
                    <kbd class="hidden sm:inline font-mono text-[0.6rem] bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] px-1 py-0.5 rounded group-hover:bg-[var(--color-accent)] group-hover:text-white transition-all">⌘K</kbd>
                </button>

                {{-- Dark Mode Toggle --}}
                <button id="theme-toggle"
                        @click="darkMode = !darkMode; localStorage.setItem('ag-theme', darkMode ? 'dark' : 'light')"
                        class="w-9 h-9 flex items-center justify-center border border-[var(--color-border)] dark:border-[var(--color-border-dark)] rounded-[2px] opacity-60 hover:opacity-100 hover:border-[var(--color-accent)] transition-all focus-accent"
                        aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>

                {{-- Auth Actions --}}
                <div class="flex items-center gap-4">
                    @if(session('is_logged_in'))
                        {{-- Profile Dropdown with Alpine.js --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="flex items-center gap-3 p-1 pl-3 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] rounded-full hover:border-[var(--color-accent)] transition-all group">
                                <div class="hidden md:flex flex-col items-end leading-tight">
                                    <span class="text-[0.6rem] uppercase tracking-widest font-bold opacity-40">Collector</span>
                                    <span class="text-ui text-sm font-medium">
                                        {{ is_array(session('user_data')) ? (session('user_data')['name'] ?: session('user_data')['username']) : session('user_data') }}
                                    </span>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-[var(--color-accent-pale)] border border-[var(--color-accent)] overflow-hidden transition-transform group-hover:scale-105">
                                    <img src="{{ is_array(session('user_data')) ? (session('user_data')['picture'] ?? 'https://ui-avatars.com/api/?name=' . (session('user_data')['username'] ?? 'U')) : 'https://ui-avatars.com/api/?name=U' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-[var(--color-bg)] dark:bg-[var(--color-bg-dark)] border border-[var(--color-border)] dark:border-[var(--color-border-dark)] shadow-xl z-50 py-2 rounded-[2px]">
                                
                                <a href="{{ route('frontend.user.account') }}" class="block px-4 py-2 text-[0.7rem] uppercase tracking-wider font-bold opacity-70 hover:opacity-100 hover:bg-[var(--color-accent-pale)] transition-all">Edit Profile</a>
                                
                                <div class="border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)] my-1"></div>
                                
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-[0.7rem] uppercase tracking-wider font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('frontend.auth.login') }}" class="text-ui text-[0.7rem] opacity-60 hover:opacity-100 transition-all">Login</a>
                        <a href="{{ route('frontend.auth.register') }}" class="btn-accent !py-2 !px-5 !text-[0.7rem]">
                            <span>Join</span>
                        </a>
                    @endif
                </div>

                {{-- Mobile hamburger --}}
                <button class="md:hidden w-9 h-9 flex flex-col items-center justify-center gap-1.5 group"
                        @click="mobileOpen = !mobileOpen" aria-label="Menu">
                    <span class="w-5 h-px bg-current transition-all" :class="mobileOpen ? 'rotate-45 translate-y-1.5' : ''"></span>
                    <span class="w-5 h-px bg-current transition-all" :class="mobileOpen ? 'opacity-0' : ''"></span>
                    <span class="w-5 h-px bg-current transition-all" :class="mobileOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition duration-200 ease-out"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition duration-150 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="md:hidden border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)] mt-4 pt-4 pb-6 px-6 flex flex-col gap-4">
            <a href="{{ url('/blog') }}" class="text-ui text-xs py-2 border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)]">Articles</a>
            <a href="{{ url('/categories') }}" class="text-ui text-xs py-2 border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)]">Categories</a>
            <a href="{{ url('/about') }}" class="text-ui text-xs py-2">About</a>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════
         FLASH MESSAGES
    ═══════════════════════════════════════════ --}}
    @if (session()->has('flash_success') || session()->has('flash_error') || $errors->any())
        <div class="fixed top-20 right-6 z-[200] flex flex-col gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:leave="transition duration-300"
             x-transition:leave-end="opacity-0 translate-x-4">
            {{-- Success --}}
            @if (session('flash_success'))
                <div class="flex items-center gap-3 bg-[var(--color-abyss)] text-white px-4 py-3 rounded-[2px] text-sm font-medium shadow-xl border-l-4 border-[var(--color-neon-cyan)]">
                    <svg class="w-4 h-4 text-[var(--color-neon-cyan)] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('flash_success') }}
                </div>
            @endif
            {{-- Error Flash --}}
            @if (session('flash_error'))
                <div class="flex items-center gap-3 bg-[var(--color-abyss)] text-white px-4 py-3 rounded-[2px] text-sm font-medium shadow-xl border-l-4 border-[var(--color-neon-red)]">
                    <svg class="w-4 h-4 text-[var(--color-neon-red)] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('flash_error') }}
                </div>
            @endif
            {{-- Validation/Controller Errors --}}
            @if ($errors->any())
                <div class="flex items-center gap-3 bg-[var(--color-abyss)] text-white px-4 py-3 rounded-[2px] text-sm font-medium shadow-xl border-l-4 border-orange-500">
                    <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif
        </div>
    @endif

    {{-- ═══════════════════════════════════════════
         PAGE CONTENT
    ═══════════════════════════════════════════ --}}
    <main>
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════ --}}
    <footer class="mt-24 border-t border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-12">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div>
                    <p class="font-serif text-3xl tracking-tight mb-2">Curahan Hati</p>
                    <p class="text-sm opacity-50 max-w-xs">Bold ideas deserve bold presentation. Editorial-grade content for curious minds.</p>
                </div>
                <div class="flex flex-col gap-2">
                    <p class="text-ui text-xs mb-2 opacity-40">Navigation</p>
                    <a href="{{ url('/blog') }}" class="text-sm opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-colors">Articles</a>
                    <a href="{{ url('/categories') }}" class="text-sm opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-colors">Categories</a>
                    <a href="{{ url('/about') }}" class="text-sm opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-colors">About</a>
                </div>
                <div class="flex flex-col gap-2">
                    <p class="text-ui text-xs mb-2 opacity-40">Legal</p>
                    <a href="{{ route('frontend.pages.terms') }}" class="text-sm opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-colors">Terms & Conditions</a>
                    <a href="{{ url('/privacy') }}" class="text-sm opacity-60 hover:opacity-100 hover:text-[var(--color-accent)] transition-colors">Privacy Policy</a>
                </div>
            </div>
            <div class="sep-accent mt-12 mb-6"></div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <p class="text-xs opacity-30 font-mono">© {{ date('Y') }} Curahan Hati Blog. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
