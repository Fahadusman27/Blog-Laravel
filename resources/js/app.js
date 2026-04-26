import './bootstrap';

// Alpine.js is loaded via CDN in the layout for maximum compatibility.
// This file handles vanilla JS global behaviors.

document.addEventListener('DOMContentLoaded', () => {

    // ── Dark Mode Toggle via localStorage ──
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const saved = localStorage.getItem('ag-theme');
    if (saved === 'dark' || (!saved && prefersDark)) {
        document.body.classList.add('dark');
    }

    window.toggleDarkMode = () => {
        document.body.classList.toggle('dark');
        localStorage.setItem('ag-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    };

    // ── Ctrl+K → Open Search Overlay ──
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            window.dispatchEvent(new CustomEvent('open-search'));
        }
        if (e.key === 'Escape') {
            window.dispatchEvent(new CustomEvent('close-search'));
        }
    });

    // ── Scroll-based nav shrink ──
    const nav = document.getElementById('ag-nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 60) {
                nav.classList.add('py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.add('py-4');
                nav.classList.remove('py-2');
            }
        });
    }

    // ── Intersection Observer: animate-on-scroll ──
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-up');
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
});
