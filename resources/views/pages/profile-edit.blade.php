@extends('layouts.antigravity')

@section('title', 'Edit Identity')

@section('content')
<div class="max-w-screen-md mx-auto px-6 py-16">
    <header class="mb-12">
        <p class="text-ui text-xs tracking-widest text-[var(--color-accent)] font-bold mb-2 uppercase">Account Settings</p>
        <h1 class="font-serif text-5xl tracking-tighter">Edit Identity</h1>
        <div class="sep-accent mt-6"></div>
    </header>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            {{-- Nama Lengkap --}}
            <div class="space-y-2">
                <label for="Name" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase tracking-widest">Full Name</label>
                <input type="text" name="Name" id="Name" required value="{{ old('Name', $profile['Name'] ?? '') }}"
                       class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-3 focus:border-[var(--color-accent)] outline-none transition-all font-serif text-2xl placeholder:opacity-20"
                       placeholder="e.g. Alex Rivera">
                @error('Name') <p class="text-[0.6rem] text-red-500 font-mono mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Username --}}
            <div class="space-y-2">
                <label for="Username" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase tracking-widest">Username / Handle</label>
                <div class="relative">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 text-[var(--color-accent)] font-mono text-lg opacity-40">@</span>
                    <input type="text" name="Username" id="Username" required value="{{ old('Username', $profile['Username'] ?? '') }}"
                           class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-3 pl-6 focus:border-[var(--color-accent)] outline-none transition-all font-mono text-lg text-[var(--color-accent)]">
                </div>
                @error('Username') <p class="text-[0.6rem] text-red-500 font-mono mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Picture File Upload --}}
        <div class="space-y-2">
            <label for="PictureFile" class="text-ui text-[0.65rem] font-bold opacity-40 uppercase tracking-widest">Profile Picture (Local Upload)</label>
            <input type="file" name="PictureFile" id="PictureFile" accept="image/*"
                   class="w-full bg-transparent border-b border-[var(--color-border)] dark:border-[var(--color-border-dark)] py-3 focus:border-[var(--color-accent)] outline-none transition-all text-sm opacity-60 font-mono">
            @error('PictureFile') <p class="text-[0.6rem] text-red-500 font-mono mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Identity Card Preview --}}
        <div class="p-8 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] bg-neutral-50 dark:bg-white/[0.01] relative overflow-hidden group">
            <div class="relative z-10 flex items-center gap-8">
                <div class="w-24 h-24 rounded-full bg-[var(--color-accent-pale)] border border-[var(--color-accent)] overflow-hidden shrink-0 shadow-xl">
                    <img id="avatar-preview" 
                         src="{{ $profile['Picture'] ?? 'https://ui-avatars.com/api/?name='.($profile['Username'] ?? 'U').'&background=0D8ABC&color=fff' }}" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name=Error&background=f00&color=fff'">
                </div>
                <div class="space-y-1">
                    <p class="text-ui text-[0.6rem] font-bold text-[var(--color-accent)] uppercase tracking-[0.2em]">Live Preview</p>
                    <h3 class="font-serif text-3xl tracking-tight" id="name-preview">{{ $profile['Name'] ?? 'Your Name' }}</h3>
                    <p class="font-mono text-sm opacity-40" id="username-preview">@<span>{{ $profile['Username'] ?? 'username' }}</span></p>
                </div>
            </div>
            {{-- Background Motif --}}
            <div class="absolute right-0 bottom-0 opacity-[0.03] pointer-events-none">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
            </div>
        </div>

        <div class="pt-8 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-xs font-mono opacity-40 hover:opacity-100 transition-all uppercase tracking-widest border-b border-transparent hover:border-current pb-1">Cancel</a>
            <button type="submit" class="btn-accent !px-12">
                <span>Update Identity</span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Simple live preview logic
    const nameInput = document.getElementById('Name');
    const usernameInput = document.getElementById('Username');
    const pictureInput = document.getElementById('PictureFile');
    
    const namePreview = document.getElementById('name-preview');
    const usernamePreview = document.getElementById('username-preview').querySelector('span');
    const avatarPreview = document.getElementById('avatar-preview');

    nameInput.addEventListener('input', (e) => namePreview.textContent = e.target.value || 'Your Name');
    usernameInput.addEventListener('input', (e) => usernamePreview.textContent = e.target.value || 'username');
    
    pictureInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (f) => avatarPreview.src = f.target.result;
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
