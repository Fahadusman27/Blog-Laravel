{{--
    PAGE: Register
    Route: frontend.auth.register
    Layout: standalone (asymmetric panel)
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register — {{ config('app.name', 'Curahan Hati') }}</title>
    <meta name="description" content="Create your Curahan Hati account and start your story.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,700&family=DM+Serif+Display&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="noise">

<x-auth.asymmetric-panel
    mode="register"
    quote="Every great writer started with a blank page and the audacity to fill it."
>

    {{-- ── Register Form Content ── --}}
    <form method="POST" action="{{ route('frontend.auth.register') }}"
          x-data="{
              showPass: false,
              loading: false,
              name: '',
              email: '',
              password: '',
              passwordConfirm: '',
              terms: false,

              get passwordStrength() {
                  const p = this.password;
                  if (!p) return 0;
                  let score = 0;
                  if (p.length >= 8) score++;
                  if (p.length >= 12) score++;
                  if (/[A-Z]/.test(p)) score++;
                  if (/[0-9]/.test(p)) score++;
                  if (/[^A-Za-z0-9]/.test(p)) score++;
                  return score;
              },

              get strengthLabel() {
                  const labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
                  return labels[this.passwordStrength] || '';
              },

              get strengthColor() {
                  const colors = ['', 'bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-400', 'bg-[var(--color-neon-cyan)]'];
                  return colors[this.passwordStrength] || '';
              },

              get passwordsMatch() {
                  return this.passwordConfirm === '' || this.password === this.passwordConfirm;
              }
          }"
          @submit="loading = true"
          class="space-y-4">
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

        {{-- Name --}}
        <div class="flex flex-col gap-1.5">
            <label for="reg-name" class="text-ui text-[0.65rem] opacity-50">Full Name</label>
            <input
                type="text"
                name="name"
                id="reg-name"
                x-model="name"
                value="{{ old('name') }}"
                placeholder="Alex Rivera"
                required
                autofocus
                maxlength="100"
                autocomplete="name"
                class="comment-textarea !min-h-0 !py-3 focus-accent @error('name') input-neon-error @enderror"
            >
        </div>

        {{-- Email --}}
        <div class="flex flex-col gap-1.5">
            <label for="reg-email" class="text-ui text-[0.65rem] opacity-50">Email Address</label>
            <input
                type="email"
                name="email"
                id="reg-email"
                x-model="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                required
                maxlength="255"
                autocomplete="email"
                class="comment-textarea !min-h-0 !py-3 focus-accent @error('email') input-neon-error @enderror"
            >
        </div>

        {{-- Password --}}
        <div class="flex flex-col gap-1.5">
            <label for="reg-password" class="text-ui text-[0.65rem] opacity-50">Password</label>
            <div class="relative">
                <input
                    :type="showPass ? 'text' : 'password'"
                    name="password"
                    id="reg-password"
                    x-model="password"
                    placeholder="Min. 8 characters"
                    required
                    maxlength="100"
                    autocomplete="new-password"
                    class="comment-textarea !min-h-0 !py-3 pr-12 w-full focus-accent @error('password') input-neon-error @enderror"
                >
                <button type="button" @click="showPass = !showPass"
                        class="absolute right-4 top-1/2 -translate-y-1/2 opacity-40 hover:opacity-80 transition-opacity">
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            {{-- Strength Meter --}}
            <div x-show="password.length > 0" class="space-y-1">
                <div class="flex gap-1">
                    <template x-for="i in 5" :key="i">
                        <div class="h-1 flex-1 rounded-full transition-all duration-300"
                             :class="i <= passwordStrength ? strengthColor : 'bg-[var(--color-border)]'">
                        </div>
                    </template>
                </div>
                <p class="text-[0.65rem] font-medium" :class="{
                    'text-red-400': passwordStrength <= 1,
                    'text-orange-400': passwordStrength === 2,
                    'text-yellow-400': passwordStrength === 3,
                    'text-green-400': passwordStrength === 4,
                    'text-[var(--color-neon-cyan)]': passwordStrength === 5,
                }" x-text="strengthLabel"></p>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="flex flex-col gap-1.5">
            <label for="reg-password-confirm" class="text-ui text-[0.65rem] opacity-50">Confirm Password</label>
            <input
                :type="showPass ? 'text' : 'password'"
                name="password_confirmation"
                id="reg-password-confirm"
                x-model="passwordConfirm"
                placeholder="Repeat your password"
                required
                maxlength="100"
                autocomplete="new-password"
                class="comment-textarea !min-h-0 !py-3 focus-accent"
                :class="!passwordsMatch ? 'input-neon-error' : ''"
            >
            <p x-show="!passwordsMatch" class="text-[0.65rem] text-[var(--color-neon-red)] font-medium animate-bounce-in">
                Passwords don't match
            </p>
        </div>

        {{-- Terms --}}
        <div class="flex items-start gap-3 py-1">
            <input type="checkbox" name="terms" id="terms" x-model="terms"
                   required
                   class="w-4 h-4 mt-0.5 rounded-[2px] border-[var(--color-border)] accent-[var(--color-accent)] shrink-0">
            <label for="terms" class="text-sm opacity-60 leading-relaxed select-none cursor-pointer text-black">
                I agree to the
                <a href="{{ route('frontend.pages.terms') }}" target="_blank" class="text-[var(--color-accent)] font-semibold hover:underline">Terms & Conditions</a>
                and
                <a href="{{ url('/privacy') }}" target="_blank" class="text-[var(--color-accent)] font-semibold hover:underline">Privacy Policy</a>
            </label>
        </div>

        {{-- Submit --}}
        <x-ui.magnetic-button
            type="submit"
            id="register-submit-btn"
            class="w-full justify-center !text-sm !py-4"
            ::disabled="!terms || !passwordsMatch || loading"
        >
            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            <span x-text="loading ? 'Creating account…' : 'Create Account'">Create Account</span>
            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </x-ui.magnetic-button>

        {{-- Divider --}}
        <div class="relative my-1">
            <div class="absolute inset-0 flex items-center"><div class="w-full h-px bg-[var(--color-border)]"></div></div>
            <div class="relative flex justify-center">
                <span class="bg-[var(--color-canvas)] px-3 text-xs opacity-40">already have an account?</span>
            </div>
        </div>

        <x-ui.magnetic-button
            href="{{ route('frontend.auth.login') }}"
            variant="ghost"
            class="w-full justify-center !text-sm !py-4"
            id="login-link-btn"
        >
            Sign In Instead
        </x-ui.magnetic-button>

    </form>

</x-auth.asymmetric-panel>

</body>
</html>
