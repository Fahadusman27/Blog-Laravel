@extends('layouts.antigravity')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 py-12">
    {{-- Header --}}
    <header class="mb-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-[var(--color-accent-pale)] border border-[var(--color-accent)] overflow-hidden shrink-0">
                    <img src="{{ is_array(session('user_data')) ? (session('user_data')['picture'] ?? 'https://ui-avatars.com/api/?name='.(session('user_data')['username'] ?? 'U')) : 'https://ui-avatars.com/api/?name=U' }}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="space-y-4">
                    <p class="text-ui text-xs tracking-widest text-[var(--color-accent)] font-bold">CONTROL CENTER</p>
                    <h1 class="font-serif text-5xl md:text-7xl tracking-tighter leading-none">
                        Welcome back,<br/>
                        <span class="opacity-40">{{ is_array(session('user_data')) ? (session('user_data')['name'] ?: session('user_data')['username']) : session('user_data') }}</span>
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-ghost !text-xs !px-6">Sign Out</button>
                </form>
                @if(($role['role'] ?? '') === 'admin')
                    <a href="#" class="btn-accent">
                        <span>New Entry</span>
                    </a>
                @endif
            </div>
        </div>
        <div class="sep-accent mt-8"></div>
    </header>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
        <div class="md:col-span-2 p-8 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] relative group overflow-hidden">
            <div class="relative z-10">
                <p class="text-ui text-[0.65rem] opacity-40 mb-1">TOTAL ENGAGEMENT</p>
                <p class="font-serif text-6xl tracking-tighter mb-4">4.8k</p>
                <p class="text-sm opacity-60 max-w-[200px]">Across all your published editorial pieces this month.</p>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] dark:opacity-[0.07] group-hover:scale-110 transition-transform duration-700 pointer-events-none">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
            </div>
        </div>

        <div class="p-8 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] bg-[var(--color-accent-pale)] border-[var(--color-accent)]/30">
            <p class="text-ui text-[0.65rem] text-[var(--color-accent)] font-bold mb-1 uppercase">Published</p>
            <p class="font-serif text-5xl tracking-tighter">12</p>
            <div class="mt-4 h-1 w-full bg-[var(--color-accent)]/10">
                <div class="h-full bg-[var(--color-accent)] w-2/3"></div>
            </div>
        </div>

        <div class="p-8 border border-[var(--color-border)] dark:border-[var(--color-border-dark)]">
            <p class="text-ui text-[0.65rem] opacity-40 mb-1 uppercase">Role</p>
            <p class="font-serif text-4xl tracking-tighter capitalize">{{ $role['role'] ?? 'Contributor' }}</p>
            <p class="text-[0.65rem] font-mono opacity-40 mt-4">ID: {{ is_array(session('user_data')) ? (session('user_data')['id'] ?? '???') : '???' }}</p>
        </div>
    </div>

    {{-- Trending Section (Viral) --}}
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-ui text-xs font-bold tracking-widest text-[var(--color-accent)] uppercase">Viral & Trending</h2>
            <div class="h-px flex-1 mx-8 bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] opacity-30"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($trending as $post)
                <div class="p-8 border border-[var(--color-accent)]/20 bg-[var(--color-accent-pale)] relative group overflow-hidden">
                    <span class="font-mono text-[0.6rem] text-[var(--color-accent)] mb-4 block uppercase tracking-widest">Trending #{{ $loop->iteration }}</span>
                    <h3 class="font-serif text-2xl leading-tight mb-6 group-hover:underline decoration-[var(--color-accent)] decoration-2 underline-offset-4 cursor-pointer">
                        {{ $post['title'] ?? 'The Future of Editorial Digital Design' }}
                    </h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span class="text-[0.65rem] font-bold opacity-60">1.2k</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span class="text-[0.65rem] font-bold opacity-60">450</span>
                        </div>
                    </div>
                    {{-- Glassmorphic Glow --}}
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-[var(--color-accent)] opacity-0 group-hover:opacity-10 blur-3xl transition-opacity"></div>
                </div>
            @empty
                <div class="col-span-full p-12 border border-dashed border-[var(--color-border)] dark:border-[var(--color-border-dark)] text-center">
                    <p class="text-sm opacity-40 italic">Trending algorithm is currently processing data...</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Main Content Split --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Recent Activity --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center justify-between">
                <h2 class="text-ui text-xs font-bold tracking-widest opacity-40 uppercase">Your Recent Activity</h2>
                <a href="#" class="text-[0.65rem] font-mono hover:text-[var(--color-accent)] transition-colors underline decoration-dotted">View All</a>
            </div>

            <div class="space-y-px bg-[var(--color-border)] dark:bg-[var(--color-border-dark)]">
                @forelse(range(1, 3) as $i)
                <div class="bg-[var(--color-bg)] dark:bg-[var(--color-bg-dark)] p-6 flex items-start gap-6 hover:bg-neutral-50 dark:hover:bg-white/[0.02] transition-colors group">
                    <span class="font-mono text-[0.65rem] opacity-20 mt-1">0{{ $i }}</span>
                    <div class="flex-1">
                        <h3 class="font-serif text-xl group-hover:text-[var(--color-accent)] transition-colors">How to scale editorial design without losing soul</h3>
                        <p class="text-sm opacity-50 mt-1">Draft saved 2 hours ago • <span class="text-[var(--color-accent)] italic">In Progress</span></p>
                    </div>
                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                </div>
                @empty
                <div class="bg-[var(--color-bg)] dark:bg-[var(--color-bg-dark)] p-12 text-center">
                    <p class="text-sm opacity-40 italic">No recent activity recorded.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar / Quick Actions --}}
        <div class="space-y-12">
            <section>
                <h2 class="text-ui text-xs font-bold tracking-widest opacity-40 uppercase mb-6">Quick Actions</h2>
                <div class="flex flex-col gap-3">
                    <a href="#" class="flex items-center justify-between p-4 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] hover:border-[var(--color-accent)] transition-colors group">
                        <span class="text-sm font-medium">Edit Profile</span>
                        <svg class="w-4 h-4 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </a>
                    <a href="#" class="flex items-center justify-between p-4 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] hover:border-[var(--color-accent)] transition-colors group">
                        <span class="text-sm font-medium">Security Settings</span>
                        <svg class="w-4 h-4 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </a>
                </div>
            </section>

            <section class="p-6 bg-[var(--color-abyss)] text-white rounded-[2px] relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-serif text-lg mb-2">Need help?</h3>
                    <p class="text-xs opacity-60 mb-4">Access our documentation or contact the Curahan Hati support team.</p>
                    <a href="#" class="text-xs font-bold border-b border-white/20 hover:border-white transition-all pb-1">Read Docs →</a>
                </div>
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 blur-3xl rounded-full -mr-12 -mt-12"></div>
            </section>
        </div>
    </div>
</div>
@endsection
