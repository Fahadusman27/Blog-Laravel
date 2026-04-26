{{--
    ╔═══════════════════════════════════════════════╗
    ║  <x-ui.magnetic-button>                      ║
    ║  Props:                                       ║
    ║    $href  - URL (optional, renders <a>)       ║
    ║    $type  - button type (default: 'button')   ║
    ║    $variant - 'accent' | 'ghost' | 'outline'  ║
    ║    $size  - 'sm' | 'md' | 'lg'               ║
    ║    $id    - HTML id attribute                 ║
    ╚═══════════════════════════════════════════════╝
--}}
@props([
    'href'    => null,
    'type'    => 'button',
    'variant' => 'accent',
    'size'    => 'md',
    'id'      => null,
])

@php
    $sizeClasses = match($size) {
        'sm'  => '!py-2 !px-4 !text-[0.65rem]',
        'lg'  => '!py-4 !px-8 !text-sm',
        default => '',
    };

    $variantClass = match($variant) {
        'ghost'   => 'btn-ghost',
        'outline' => 'btn-ghost !border-[var(--color-accent)] !text-[var(--color-accent)] hover:!bg-[var(--color-accent)] hover:!text-white hover:!border-[var(--color-accent)]',
        default   => 'btn-accent',
    };
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $id ? "id={$id}" : '' }}
        class="{{ $variantClass }} {{ $sizeClasses }} focus-accent"
        x-data="{
            el: null,
            move(e) {
                const rect = this.el.getBoundingClientRect();
                const dx = e.clientX - (rect.left + rect.width / 2);
                const dy = e.clientY - (rect.top + rect.height / 2);
                this.el.style.transform = `translate(${dx * 0.15}px, ${dy * 0.15}px)`;
            },
            reset() {
                this.el.style.transform = '';
            }
        }"
        x-init="el = $el"
        @mousemove="move($event)"
        @mouseleave="reset()"
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $id ? "id={$id}" : '' }}
        class="{{ $variantClass }} {{ $sizeClasses }} focus-accent"
        x-data="{
            el: null,
            move(e) {
                const rect = this.el.getBoundingClientRect();
                const dx = e.clientX - (rect.left + rect.width / 2);
                const dy = e.clientY - (rect.top + rect.height / 2);
                this.el.style.transform = `translate(${dx * 0.15}px, ${dy * 0.15}px)`;
            },
            reset() {
                this.el.style.transform = '';
            }
        }"
        x-init="el = $el"
        @mousemove="move($event)"
        @mouseleave="reset()"
        {{ $attributes }}
    >
        {{ $slot }}
    </button>
@endif
