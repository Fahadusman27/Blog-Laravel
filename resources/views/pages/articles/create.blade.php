@extends('layouts.Curahan Hati')

@section('title', 'Submit Article')

@section('content')
<div class="max-w-screen-md mx-auto px-6 py-16">
    <header class="mb-12">
        <p class="text-ui text-xs tracking-widest text-[var(--color-accent)] font-bold mb-2 uppercase">Editorial Submission</p>
        <h1 class="font-serif text-5xl tracking-tighter">New Entry</h1>
        <div class="sep-accent mt-6"></div>
    </header>

    <form action="{{ route('articles.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Title --}}
        <div class="space-y-2">
            <label for="title" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase">Article Title</label>
            <input type="text" name="title" id="title" required value="{{ old('title') }}"
                   placeholder="Capturing the essence of digital brutalism..."
                   class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-4 font-serif text-3xl focus:border-[var(--color-accent)] outline-none transition-colors placeholder:opacity-20">
            @error('title') <p class="text-xs text-red-500 font-mono mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Category --}}
            <div class="space-y-2">
                <label for="category_id" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase">Category</label>
                <select name="category_id" id="category_id" required
                        class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-4 text-sm focus:border-[var(--color-accent)] outline-none transition-colors appearance-none">
                    <option value="" disabled selected>Select Taxonomy</option>
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Excerpt --}}
            <div class="space-y-2">
                <label for="excerpt" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase">Short Summary</label>
                <input type="text" name="excerpt" id="excerpt" required value="{{ old('excerpt') }}"
                       placeholder="A brief hook for your readers..."
                       class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-4 text-sm focus:border-[var(--color-accent)] outline-none transition-colors placeholder:opacity-30">
            </div>
        </div>

        {{-- Content Body --}}
        <div class="space-y-2">
            <label for="body" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase">Article Body (Markdown Supported)</label>
            <textarea name="body" id="body" rows="12" required
                      placeholder="Begin your narrative here..."
                      class="w-full bg-transparent border border-[var(--color-border)] dark:border-[var(--color-border-dark)] p-6 text-base leading-relaxed focus:border-[var(--color-accent)] outline-none transition-colors placeholder:opacity-20 resize-none font-serif">{{ old('body') }}</textarea>
            @error('body') <p class="text-xs text-red-500 font-mono mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-8 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-xs font-mono opacity-40 hover:opacity-100 transition-opacity uppercase tracking-widest">Cancel Draft</a>
            <button type="submit" class="btn-accent">
                <span>Publish Article</span>
            </button>
        </div>
    </form>
</div>
@endsection
