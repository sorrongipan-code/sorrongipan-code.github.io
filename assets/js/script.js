// /assets/js/script.js
// Production-safe progressive enhancement for the generated static site.

(() => {
    'use strict';

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const storage = {
        get(key, fallback = null) {
            try {
                const v = window.localStorage.getItem(key);
                return v === null ? fallback : v;
            } catch {
                return fallback;
            }
        },
        set(key, value) {
            try {
                window.localStorage.setItem(key, String(value));
            } catch {
                // ignore (private mode / blocked)
            }
        }
    };

    /**
     * 1) Sidebar (mobile)
     * Kept compatible with existing inline onclick="toggleSidebar()".
     */
    const sidebar = $('#sidebar');
    // overlay can be addressed by id (backward compatibility) or data-attr (new)
    const overlay = $('#sidebar-overlay') || $('[data-sidebar-overlay]');
    let isSidebarOpen = false;

    function openSidebar() {
        if (!sidebar || !overlay) return;
        isSidebarOpen = true;
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        window.setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar || !overlay) return;
        isSidebarOpen = false;
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0');
        window.setTimeout(() => overlay.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }

    // Keep compatible with inline onclick="toggleSidebar()"
    window.toggleSidebar = function toggleSidebar() {
        if (!sidebar || !overlay) return;
        if (isSidebarOpen) closeSidebar();
        else openSidebar();
    };

    // Click overlay to close
    if (overlay) {
        overlay.addEventListener('click', () => {
            if (isSidebarOpen) closeSidebar();
        });
    }

    // Prevent "mobile toggle can't be clicked" issues:
    // - stop propagation from the toggle button
    // - make sure sidebar close button works
    document.addEventListener('click', (e) => {
        const target = e.target;
        const toggleBtn = target && target.closest ? target.closest('[data-sidebar-toggle]') : null;
        const closeBtn = target && target.closest ? target.closest('[data-sidebar-close]') : null;

        if (toggleBtn) {
            e.preventDefault();
            e.stopPropagation();
            window.toggleSidebar();
        }
        if (closeBtn) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        }
    }, { capture: true });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isSidebarOpen) {
            closeSidebar();
        }
    });

    /**
     * 2) Lazy loading (supports both native loading=lazy + optional data-src usage)
     */
    const lazyImages = $$('img.lazy');
    if (lazyImages.length > 0 && 'IntersectionObserver' in window) {
        const lazyImageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const img = entry.target;

                if (img.dataset && img.dataset.src) img.src = img.dataset.src;
                if (img.dataset && img.dataset.srcset) img.srcset = img.dataset.srcset;

                img.onload = () => img.classList.add('loaded');
                img.onerror = () => img.classList.add('loaded');

                img.classList.remove('lazy');
                observer.unobserve(img);
            });
        }, { rootMargin: '0px 0px 200px 0px' });

        lazyImages.forEach(img => lazyImageObserver.observe(img));
    }

    /**
     * 3) Smooth scroll (optional)
     */
    $$('a.smooth-scroll[href^="#"]').forEach(a => {
        a.addEventListener('click', (e) => {
            const href = a.getAttribute('href');
            if (!href) return;
            const target = $(href);
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    /**
     * 4) Video player enhancements (volume persistence)
     */
    const mainPlayer = $('#mainPlayer');
    if (mainPlayer && mainPlayer instanceof HTMLMediaElement) {
        const savedVolume = storage.get('preferredPlayerVolume', null);
        if (savedVolume !== null) {
            const v = parseFloat(savedVolume);
            if (!Number.isNaN(v)) {
                mainPlayer.volume = Math.min(1, Math.max(0, v));
            }
        }

        mainPlayer.addEventListener('volumechange', () => {
            if (!mainPlayer.muted) {
                storage.set('preferredPlayerVolume', mainPlayer.volume);
            }
        });
    }

    /**
     * 5) Up Next autoplay
     * User request: remove autoplay toggle because this is static/native.
     * We keep NOTHING here to avoid non-working UI.
     */

    /**
     * 6) Share + copy link (video page)
     */
    const shareBtn = $('[data-share]');
    const copyBtn = $('[data-copy-link]');

    async function copyText(text) {
        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(text);
                return true;
            }
        } catch {
            // fallback below
        }

        // Fallback: temporary input
        try {
            const input = document.createElement('input');
            input.value = text;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.left = '-9999px';
            document.body.appendChild(input);
            input.select();
            const ok = document.execCommand('copy');
            document.body.removeChild(input);
            return ok;
        } catch {
            return false;
        }
    }

    // Use event delegation (prevents “button not clickable” caused by overlay/z-index or nested elements)
    document.addEventListener('click', async (e) => {
        const target = e.target;
        const shareEl = target && target.closest ? target.closest('[data-share]') : null;
        const copyEl = target && target.closest ? target.closest('[data-copy-link]') : null;

        if (!shareEl && !copyEl) return;
        e.preventDefault();
        e.stopPropagation();

        const url = window.location.href;
        const title = document.title;

        if (shareEl) {
            if (navigator.share) {
                try {
                    await navigator.share({ title, url });
                    return;
                } catch {
                    // ignore and fallback to copy
                }
            }
            const ok = await copyText(url);
            window.alert(ok ? 'Link copied!' : 'Unable to copy link.');
            return;
        }

        if (copyEl) {
            const ok = await copyText(url);

            // If the button provides a label span, prefer inline UX (no alert)
            // e.g. image page: <span data-copy-text>Copy</span>
            const label = copyEl.querySelector('[data-copy-text]');
            const icon = copyEl.querySelector('i');

            if (label) {
                const originalText = label.textContent || 'Copy';
                label.textContent = ok ? 'Copied!' : 'Failed';

                if (icon) {
                    // reset basic state
                    icon.classList.remove('ph-link-simple', 'ph-check', 'ph-x', 'text-green-400', 'text-red-400');
                    if (ok) icon.classList.add('ph-check', 'text-green-400');
                    else icon.classList.add('ph-x', 'text-red-400');
                }

                window.setTimeout(() => {
                    label.textContent = originalText;
                    if (icon) {
                        icon.classList.remove('ph-check', 'ph-x', 'text-green-400', 'text-red-400');
                        icon.classList.add('ph-link-simple');
                    }
                }, 2000);
                return;
            }

            // Fallback for older markup: keep alert behavior
            window.alert(ok ? 'Link copied!' : 'Unable to copy link.');
        }
    }, { capture: true });

    /**
     * 7) Sort + View toggle (homepage)
     */
    const videoGrid = $('[data-video-grid]');
    const sortSelect = $('[data-sort-select]');
    const viewButtons = $$('[data-view-toggle]');

    // Keeps a stable "original order" so changing sort back to newest works reliably.
    let originalOrder = null;

    function parseDurationToSeconds(duration) {
        if (!duration) return 0;
        const parts = String(duration).trim().split(':').map(p => parseInt(p, 10));
        if (parts.some(n => Number.isNaN(n))) return 0;
        if (parts.length === 3) return parts[0] * 3600 + parts[1] * 60 + parts[2];
        if (parts.length === 2) return parts[0] * 60 + parts[1];
        return parts[0];
    }

    function ensureOriginalOrder() {
        if (!videoGrid) return;
        if (originalOrder) return;
        originalOrder = $$('article', videoGrid);
    }

    function sortGrid(mode) {
        if (!videoGrid) return;
        ensureOriginalOrder();

        // "Newest" means go back to the original server-rendered order.
        if (!mode || mode === 'newest') {
            if (originalOrder) originalOrder.forEach(el => videoGrid.appendChild(el));
            return;
        }

        const cards = $$('article', videoGrid);
        const normalized = cards.map(el => {
            const views = parseFloat(el.dataset.views || '0') || 0;
            const dateStr = el.dataset.date || '';
            const dateTs = dateStr ? Date.parse(dateStr) : 0;
            const duration = parseDurationToSeconds(el.dataset.duration || '');
            const title = (el.dataset.title || '').toLowerCase();
            return { el, views, dateTs, duration, title };
        });

        normalized.sort((a, b) => {
            if (mode === 'most_viewed') return b.views - a.views;
            // fallback (shouldn't happen)
            return b.dateTs - a.dateTs;
        });

        normalized.forEach(item => videoGrid.appendChild(item.el));
    }

    function applyViewMode(mode) {
        if (!videoGrid) return;
        const isList = mode === 'list';
        videoGrid.classList.toggle('is-list', isList);
        storage.set('viewMode', isList ? 'list' : 'grid');
        viewButtons.forEach(btn => {
            const active = btn.getAttribute('data-view-toggle') === (isList ? 'list' : 'grid');
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            btn.classList.toggle('bg-bg-element', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('shadow-sm', active);
            if (!active) {
                btn.classList.remove('bg-bg-element', 'text-white', 'shadow-sm');
            }
        });
    }

    if (sortSelect && videoGrid) {
        const saved = storage.get('sortMode', 'newest');
        const allowed = new Set(['newest', 'most_viewed']);
        const initial = allowed.has(saved) ? saved : 'newest';
        sortSelect.value = initial;
        sortGrid(initial);
        sortSelect.addEventListener('change', () => {
            const mode = sortSelect.value || 'newest';
            storage.set('sortMode', mode);
            sortGrid(mode);
        });
    }

    if (videoGrid) {
        // Apply initial view mode from storage even if buttons are rendered later.
        const savedMode = storage.get('viewMode', 'grid');
        applyViewMode(savedMode === 'list' ? 'list' : 'grid');

        // Use event delegation to avoid “dead button” issues (e.g. markup changes).
        document.addEventListener('click', (e) => {
            const btn = e.target && e.target.closest ? e.target.closest('[data-view-toggle]') : null;
            if (!btn) return;
            const mode = btn.getAttribute('data-view-toggle') || 'grid';
            applyViewMode(mode === 'list' ? 'list' : 'grid');
        });
    }

    /**
     * 9) Sidebar active state
     */
    function setActiveSidebarLink() {
        const path = window.location.pathname || '';
        const search = window.location.search || '';

        // Decide active key
        let activeKey = 'all';
        if (path.includes('/pictures') || path.includes('/image/')) activeKey = 'pictures';
        else if (path.includes('/search.html') && /[?&]q=trending/i.test(search)) activeKey = 'trending';
        else if (path.includes('/search.html') && /[?&]q=movies/i.test(search)) activeKey = 'movies';
        else activeKey = 'all';

        // Reset then apply classes
        $$('[data-nav]').forEach(a => {
            const isActive = a.getAttribute('data-nav') === activeKey;
            a.classList.toggle('bg-bg-panel', isActive);
            a.classList.toggle('text-white', isActive);
            a.classList.toggle('font-medium', isActive);
            a.classList.toggle('border', isActive);
            a.classList.toggle('border-border-subtle/50', isActive);

            if (!isActive) {
                a.classList.remove('bg-bg-panel', 'text-white', 'font-medium', 'border', 'border-border-subtle/50');
            }
        });
    }

    setActiveSidebarLink();

    /**
     * 8) Fade-in animations
     */
    const animatedElements = $$('.fade-in-up');
    if (animatedElements.length > 0) {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.1 });

            animatedElements.forEach(el => animationObserver.observe(el));
        } else {
            animatedElements.forEach(el => el.classList.add('is-visible'));
        }
    }
})();
