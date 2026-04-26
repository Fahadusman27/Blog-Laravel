@extends('layouts.Curahan Hati')

@section('title', 'About — The Curahan Hati Philosophy')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 py-20">
    {{-- Hero --}}
    <header class="max-w-4xl mb-32">
        <p class="text-ui text-xs tracking-[0.3em] text-[var(--color-accent)] font-bold mb-8 uppercase">The Genesis</p>
        <h1 class="font-serif text-6xl md:text-8xl tracking-tighter leading-[0.9] mb-12">
            Redefining the<br/>
            <span class="italic opacity-30">Digital Narrative.</span>
        </h1>
        <p class="text-xl md:text-2xl opacity-60 leading-relaxed font-serif">
            Curahan Hati was born from a simple observation: the modern web has become a sea of generic templates. 
            We believe that bold ideas deserve bold presentation.
        </p>
    </header>

    {{-- Mission Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-20 mb-32">
        <div class="space-y-8">
            <h2 class="text-ui text-xs font-bold tracking-widest uppercase opacity-40">Our Vision</h2>
            <p class="text-lg leading-relaxed">
                Kami adalah mahasiswa Teknik Informatika di Universitas Airlangga (UNAIR) yang berdedikasi untuk menggabungkan kemajuan teknis dengan estetika desain editorial. Curahan Hati bukan sekadar blog; ini adalah eksperimen dalam arsitektur perangkat lunak modern menggunakan Laravel dan Go.
            </p>
            <div class="sep-accent w-24"></div>
        </div>
        <div class="space-y-8">
            <h2 class="text-ui text-xs font-bold tracking-widest uppercase opacity-40">Expertise & Trust</h2>
            <p class="text-lg leading-relaxed opacity-60">
                Dengan memisahkan frontend (Laravel 11) dan backend (Go Fiber), kami memastikan performa maksimal dan skalabilitas tinggi. Setiap baris kode ditulis dengan mempertimbangkan keamanan data dan pengalaman pengguna yang mulus.
            </p>
        </div>
    </div>

    {{-- Team / Developer Profile --}}
    <section class="mb-32">
        <div class="flex flex-col md:flex-row items-center gap-16 p-12 border border-[var(--color-border)] dark:border-[var(--color-border-dark)] relative overflow-hidden">
            <div class="w-48 h-48 bg-[var(--color-accent-pale)] border border-[var(--color-accent)] shrink-0 grayscale hover:grayscale-0 transition-all duration-700">
                {{-- User Avatar Placeholder --}}
                <div class="w-full h-full flex items-center justify-center text-[var(--color-accent)] font-serif text-6xl opacity-20 italic">AG</div>
            </div>
            <div class="space-y-4">
                <h3 class="font-serif text-4xl">The Developer</h3>
                <p class="text-ui text-xs font-bold tracking-widest text-[var(--color-accent)] uppercase">Full-Stack Engineer • UNAIR Student</p>
                <p class="max-w-xl opacity-60">
                    Spesialis dalam pengembangan sistem terdistribusi dan desain UI yang berorientasi pada konten. Fokus pada pembuatan aplikasi web yang tidak hanya berfungsi, tapi juga menginspirasi.
                </p>
                <div class="flex gap-4 pt-4">
                    <a href="#" class="text-xs font-mono border-b border-transparent hover:border-current transition-all pb-1">GitHub</a>
                    <a href="#" class="text-xs font-mono border-b border-transparent hover:border-current transition-all pb-1">LinkedIn</a>
                    <a href="#" class="text-xs font-mono border-b border-transparent hover:border-current transition-all pb-1">Instagram</a>
                </div>
            </div>
            <div class="absolute -right-24 -bottom-24 w-96 h-96 border border-[var(--color-accent)]/5 rounded-full pointer-events-none"></div>
        </div>
    </section>

    {{-- Contact / CTA --}}
    <section class="text-center py-20 bg-[var(--color-accent-pale)] border-y border-[var(--color-accent)]/20">
        <h2 class="font-serif text-4xl mb-6">Have a bold idea?</h2>
        <p class="text-sm opacity-60 mb-10">We are always open to collaboration and technical discussions.</p>
        <a href="mailto:contact@Curahan Hati.blog" class="btn-accent !px-16">
            <span>Get In Touch</span>
        </a>
    </section>
</div>
@endsection
