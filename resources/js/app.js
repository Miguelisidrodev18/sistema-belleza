import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);


// ============================================
// Alpine Components
// ============================================

Alpine.data('navbar', () => ({
    open: false,
    scrolled: false,
    init() {
        this.onScroll();
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    },
    onScroll() {
        this.scrolled = window.scrollY > 50;
    },
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
}));

Alpine.data('accordion', (multiple = false) => ({
    activeItems: [],
    multiple,
    toggle(index) {
        if (this.multiple) {
            const pos = this.activeItems.indexOf(index);
            if (pos === -1) {
                this.activeItems.push(index);
            } else {
                this.activeItems.splice(pos, 1);
            }
        } else {
            this.activeItems = this.activeItems.includes(index) ? [] : [index];
        }
    },
    isOpen(index) {
        return this.activeItems.includes(index);
    },
}));

Alpine.data('carousel', (options = {}) => ({
    current: 0,
    total: 0,
    autoplayInterval: null,
    autoplay: options.autoplay ?? true,
    interval: options.interval ?? 5000,
    loop: options.loop ?? true,
    paused: false,
    init() {
        this.total = this.$refs.track ? this.$refs.track.children.length : 0;
        if (this.autoplay && this.total > 1) {
            this.startAutoplay();
        }
    },
    next() {
        if (this.current < this.total - 1) {
            this.current++;
        } else if (this.loop) {
            this.current = 0;
        }
    },
    prev() {
        if (this.current > 0) {
            this.current--;
        } else if (this.loop) {
            this.current = this.total - 1;
        }
    },
    goTo(index) {
        this.current = index;
    },
    startAutoplay() {
        this.stopAutoplay();
        this.autoplayInterval = setInterval(() => {
            if (!this.paused) this.next();
        }, this.interval);
    },
    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    },
    destroy() {
        this.stopAutoplay();
    },
}));

Alpine.data('gallery', () => ({
    lightboxOpen: false,
    currentImage: 0,
    images: [],
    init() {
        this.images = JSON.parse(this.$el.dataset.images || '[]');
    },
    openLightbox(index) {
        this.currentImage = index;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.lightboxOpen = false;
        document.body.style.overflow = '';
    },
    nextImage() {
        this.currentImage = (this.currentImage + 1) % this.images.length;
    },
    prevImage() {
        this.currentImage = (this.currentImage - 1 + this.images.length) % this.images.length;
    },
}));

Alpine.data('lazySection', () => ({
    visible: false,
    init() {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    this.visible = true;
                    observer.disconnect();
                }
            },
            { threshold: 0.08, rootMargin: '0px 0px -60px 0px' }
        );
        observer.observe(this.$el);
    },
}));

Alpine.data('statsCounter', (target, duration = 2000) => ({
    value: 0,
    target: parseInt(target) || 0,
    started: false,
    init() {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting && !this.started) {
                    this.started = true;
                    this.animate();
                    observer.disconnect();
                }
            },
            { threshold: 0.5 }
        );
        observer.observe(this.$el);
    },
    animate() {
        const startTime = performance.now();
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease-out-expo for premium feel
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            this.value = Math.round(eased * this.target);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.value = this.target;

                // Pop effect on completion
                const el = this.$el.querySelector('[x-text]') || this.$el;
                el.style.transition = 'transform 0.3s cubic-bezier(0.22, 1, 0.36, 1)';
                el.style.transform = 'scale(1.08)';
                setTimeout(() => {
                    el.style.transform = 'scale(1)';
                }, 300);
            }
        };
        requestAnimationFrame(animate);
    },
}));

Alpine.start();

// ============================================
// PREMIUM ENHANCEMENTS (vanilla JS, no Alpine deps)
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // ——————————————————————————
    // 1. HERO PARALLAX — Subtle depth on scroll
    // ——————————————————————————
    const hero = document.getElementById('hero');
    if (hero) {
        const heroContent = hero.querySelector('.section-container');

        let ticking = false;
        const handleHeroParallax = () => {
            if (ticking) return;
            ticking = true;

            requestAnimationFrame(() => {
                const scrollY = window.scrollY;
                const heroHeight = hero.offsetHeight;

                if (scrollY < heroHeight) {
                    const progress = scrollY / heroHeight;

                    // Content moves up slightly & fades
                    if (heroContent) {
                        heroContent.style.transform = `translateY(${scrollY * 0.15}px)`;
                        heroContent.style.opacity = `${1 - progress * 0.4}`;
                    }
                }

                ticking = false;
            });
        };

        window.addEventListener('scroll', handleHeroParallax, { passive: true });
    }

    // ——————————————————————————
    // 2. GALLERY TILT — 3D tilt effect on hover
    // ——————————————————————————
    const galleryItems = document.querySelectorAll('#galeria .group');
    galleryItems.forEach((item) => {
        item.addEventListener('mousemove', (e) => {
            const rect = item.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            const tiltX = (y - 0.5) * 8; // max 4deg
            const tiltY = (x - 0.5) * -8;

            item.style.transform = `perspective(800px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateY(-4px)`;
        });

        item.addEventListener('mouseleave', () => {
            item.style.transform = '';
            item.style.transition = 'transform 0.5s cubic-bezier(0.22, 1, 0.36, 1)';
        });

        item.addEventListener('mouseenter', () => {
            item.style.transition = 'transform 0.1s ease-out';
        });
    });

    // ——————————————————————————
    // 3. CURSOR GLOW — On dark/gradient sections
    // ——————————————————————————
    const darkSections = document.querySelectorAll('#hero, #por-que-ugarte, #cta-final');
    darkSections.forEach((section) => {
        // Create glow element
        const glow = document.createElement('div');
        glow.style.cssText = `
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            opacity: 0;
            transition: opacity 0.4s ease;
            transform: translate(-50%, -50%);
        `;
        section.appendChild(glow);

        section.addEventListener('mousemove', (e) => {
            const rect = section.getBoundingClientRect();
            glow.style.left = `${e.clientX - rect.left}px`;
            glow.style.top = `${e.clientY - rect.top}px`;
            glow.style.opacity = '1';
        });

        section.addEventListener('mouseleave', () => {
            glow.style.opacity = '0';
        });
    });

    // ——————————————————————————
    // 4. SMOOTH ANCHOR SCROLL — Enhanced with offset
    // ——————————————————————————
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (!target) return;

            e.preventDefault();

            const navHeight = document.querySelector('header')?.offsetHeight || 80;
            const targetPosition = target.getBoundingClientRect().top + window.scrollY - navHeight;

            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth',
            });

            // Close mobile menu if open
            const mobileMenu = document.querySelector('[x-data="navbar"]');
            if (mobileMenu && mobileMenu.__x) {
                mobileMenu.__x.$data.open = false;
            }
        });
    });

    // ——————————————————————————
    // 5. CARD HOVER SHINE — Sweeping light effect
    // ——————————————————————————
    const cards = document.querySelectorAll('.relative.bg-white.shadow-card, .relative.bg-white.shadow-card-hover');
    cards.forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            card.style.setProperty('--shine-x', `${x}%`);
            card.style.setProperty('--shine-y', `${y}%`);
            card.style.background = `radial-gradient(circle at ${x}% ${y}%, rgba(83, 43, 124, 0.02) 0%, transparent 50%), white`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.background = '';
        });
    });

    // ——————————————————————————
    // 6. TYPED EFFECT — Hero heading reveal (optional enhancement)
    // ——————————————————————————
    const heroHeading = document.querySelector('#hero h1');
    if (heroHeading) {
        heroHeading.style.opacity = '0';
        heroHeading.style.transform = 'translateY(20px)';
        heroHeading.style.transition = 'all 0.8s cubic-bezier(0.22, 1, 0.36, 1)';

        // Delay to let the page load feel settled
        setTimeout(() => {
            heroHeading.style.opacity = '1';
            heroHeading.style.transform = 'translateY(0)';
        }, 200);
    }

    // Hero subtext reveal
    const heroText = document.querySelector('#hero .text-xl');
    if (heroText) {
        heroText.style.opacity = '0';
        heroText.style.transform = 'translateY(20px)';
        heroText.style.transition = 'all 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.15s';

        setTimeout(() => {
            heroText.style.opacity = '';
            heroText.style.transform = 'translateY(0)';
        }, 300);
    }

    // Hero buttons reveal
    const heroButtons = document.querySelector('#hero [class*="button-group"], #hero .flex.flex-col');
    if (heroButtons) {
        heroButtons.style.opacity = '0';
        heroButtons.style.transform = 'translateY(20px)';
        heroButtons.style.transition = 'all 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.3s';

        setTimeout(() => {
            heroButtons.style.opacity = '1';
            heroButtons.style.transform = 'translateY(0)';
        }, 400);
    }

    // Hero badge reveal
    const heroBadge = document.querySelector('#hero [class*="badge"]');
    if (heroBadge) {
        heroBadge.style.opacity = '0';
        heroBadge.style.transform = 'translateY(12px) scale(0.95)';
        heroBadge.style.transition = 'all 0.6s cubic-bezier(0.22, 1, 0.36, 1)';

        setTimeout(() => {
            heroBadge.style.opacity = '1';
            heroBadge.style.transform = 'translateY(0) scale(1)';
        }, 100);
    }
});
