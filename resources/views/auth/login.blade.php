{{--
    PAGE: Login
    Route: frontend.auth.login
    Layout: layouts.Curahan Hati (standalone, no nav/footer needed)
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — {{ config('app.name', 'Curahan Hati') }}</title>
    <meta name="description" content="Sign in to your Curahan Hati account.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700&family=DM+Serif+Display&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="noise">

<x-auth.asymmetric-panel mode="login">

    {{-- ── Login Form Content ── --}}
    <form method="POST" action="{{ route('frontend.auth.login') }}"
          x-data="{ showPass: false, loading: false }"
          @submit="loading = true"
          class="space-y-5">
        @csrf

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-[var(--color-neon-red)]/10 border border-[var(--color-neon-red)]/30 rounded-[2px] px-4 py-3 flex items-start gap-3" role="alert">
                <svg class="w-4 h-4 text-[var(--color-neon-red)] mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-xs text-[var(--color-neon-red)] font-medium">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Email --}}
        <div class="flex flex-col gap-1.5">
            <label for="email" class="text-ui text-[0.65rem] opacity-50">Email Address</label>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required
                autofocus
                autocomplete="email"
                maxlength="255"
                class="comment-textarea !min-h-0 !py-3.5 focus-accent @error('email') input-neon-error @enderror"
            >
        </div>

        {{-- Password --}}
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="text-ui text-[0.65rem] opacity-50">Password</label>
                <a href="#"
                   class="text-[0.65rem] text-[var(--color-accent)] hover:underline font-medium">
                    Forgot password?
                </a>
            </div>
            <div class="relative">
                <input
                    :type="showPass ? 'text' : 'password'"
                    name="password"
                    id="password"
                    placeholder="••••••••••"
                    required
                    maxlength="100"
                    autocomplete="current-password"
                    class="comment-textarea !min-h-0 !py-3.5 pr-12 w-full focus-accent @error('password') input-neon-error @enderror"
                >
                <button type="button"
                        @click="showPass = !showPass"
                        class="absolute right-4 top-1/2 -translate-y-1/2 opacity-40 hover:opacity-80 transition-opacity focus-accent"
                        :aria-label="showPass ? 'Hide password' : 'Show password'">
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-2.5">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                   class="w-4 h-4 rounded-[2px] border-[var(--color-border)] accent-[var(--color-accent)]">
            <label for="remember" class="text-sm opacity-60 select-none cursor-pointer text-black">Keep me signed in</label>
        </div>

        {{-- Submit --}}
        <x-ui.magnetic-button type="submit" id="login-submit-btn" class="w-full justify-center !text-sm !py-4">
            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <span x-text="loading ? 'Signing in…' : 'Sign In'">Sign In</span>
            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </x-ui.magnetic-button>

        {{-- Divider --}}
        @if (config('boilerplate.access.user.registration'))
            <div class="relative my-2">
                <div class="absolute inset-0 flex items-center"><div class="w-full h-px bg-[var(--color-border)]"></div></div>
                <div class="relative flex justify-center">
                    <span class="bg-[var(--color-canvas)] px-3 text-xs opacity-40">or</span>
                </div>
            </div>

            <x-ui.magnetic-button
                href="{{ route('frontend.auth.register') }}"
                variant="ghost"
                class="w-full justify-center !text-sm !py-4"
                id="register-link-btn"
            >
                Create a free account
            </x-ui.magnetic-button>
        @endif
    </form>

</x-auth.asymmetric-panel>

</body>
</html>
