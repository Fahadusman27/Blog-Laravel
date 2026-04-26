@extends('layouts.Curahan Hati')

@section('title', 'Taxonomy — Categories')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 py-16">
    <header class="mb-20">
        <p class="text-ui text-xs tracking-widest text-[var(--color-accent)] font-bold mb-4 uppercase text-center">Curated Knowledge</p>
        <h1 class="font-serif text-6xl md:text-8xl tracking-tighter text-center">Browse by<br/><span class="opacity-30 italic">Category</span></h1>
        <div class="sep-accent mt-12 mx-auto max-w-xs"></div>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-[var(--color-border)] dark:bg-[var(--color-border-dark)] border border-[var(--color-border)] dark:border-[var(--color-border-dark)]">
        @forelse($categories as $category)
            <a href="{{ url('/blog?category=' . $category['id']) }}" 
               class="bg-[var(--color-bg)] dark:bg-[var(--color-bg-dark)] p-12 flex flex-col justify-between aspect-square hover:bg-[var(--color-accent-pale)] transition-all group relative overflow-hidden">
                <div>
                    <span class="font-mono text-xs opacity-30 group-hover:opacity-100 group-hover:text-[var(--color-accent)] transition-all">0{{ $loop->iteration }}</span>
                    <h3 class="font-serif text-4xl mt-4 group-hover:translate-x-2 transition-transform duration-500">{{ $category['name'] }}</h3>
                </div>
                
                <div class="flex items-center justify-between">
                    <p class="text-[0.65rem] font-bold tracking-widest opacity-40 uppercase group-hover:opacity-100 transition-opacity">Explore Articles</p>
                    <svg class="w-6 h-6 opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                </div>

                {{-- Hover Decoration --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--color-accent)] opacity-0 group-hover:opacity-[0.03] blur-3xl rounded-full -mr-16 -mt-16 transition-opacity"></div>
            </a>
        @empty
            <div class="col-span-full bg-[var(--color-bg)] dark:bg-[var(--color-bg-dark)] p-20 text-center">
                <p class="font-serif text-2xl opacity-40 italic">No categories found in the database.</p>
            </div>
        @endforelse
    </div>

    {{-- Call to Action --}}
    <footer class="mt-20 text-center">
        <p class="text-sm opacity-50 mb-6">Looking for something specific?</p>
        <button @click="$dispatch('open-search')" class="btn-ghost !px-12">Open Global Search</button>
    </footer>
</div>
@endsection
