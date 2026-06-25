(function() {
    if (window.KS_MAIN_JS_LOADED) return;
    window.KS_MAIN_JS_LOADED = true;

    // Robust DOM Ready helper to ensure initialization even when script runs late
    const ready = (callback) => {
        if (document.readyState !== 'loading') {
            callback();
        } else {
            document.addEventListener('DOMContentLoaded', callback);
        }
    };

// ===== AOS COMPATIBILITY LAYER =====
window.AOS = window.AOS || {
    init: function(options) {
        window.KSReveal?.init(options);
    },
    refresh: function() {
        window.KSReveal?.refresh();
    },
    refreshHard: function() {
        window.KSReveal?.refresh();
    }
};

// ===== CONFIGURAZIONE GLOBALE =====
var KS = window.KS || {
    baseUrl: window.KS_CONFIG?.baseUrl || '/',
    whatsappNumber: window.KS_CONFIG?.whatsappNumber || '393483109840',
    companyName: 'Key Soft Italia',

    // Configurazione prezzi riparazioni
    repairPricing: {
        categories: {
            smartphone: { base: 30, label: 'Smartphone' },
            tablet: { base: 40, label: 'Tablet' },
            laptop: { base: 50, label: 'Laptop/MacBook' },
            desktop: { base: 45, label: 'PC Desktop' },
            tv: { base: 60, label: 'TV/Monitor' }
        },
        problems: {
            display: { weight: 80, label: 'Schermo/Display' },
            battery: { weight: 60, label: 'Batteria' },
            charging: { weight: 40, label: 'Porta di ricarica' },
            camera: { weight: 50, label: 'Fotocamera' },
            audio: { weight: 35, label: 'Audio/Speaker' },
            software: { weight: 30, label: 'Software/Sistema' },
            board: { weight: 120, label: 'Scheda madre' },
            liquid: { weight: 90, label: 'Danni da liquidi' }
        },
        express: { fee: 25, label: 'Servizio Express (24h)' },
        parts: {
            original: { delta: 20, label: 'Ricambio Originale' },
            compatible: { delta: 0, label: 'Ricambio Compatibile' }
        },
        warranty: {
            3: { delta: 0, label: '3 mesi' },
            6: { delta: 10, label: '6 mesi' },
            12: { delta: 20, label: '12 mesi' }
        }
    }
};

// ===== UTILITY FUNCTIONS =====
var Utils = {
    // Formatta prezzo in EUR
    formatPrice: (price) => {
        return new Intl.NumberFormat('it-IT', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 0
        }).format(price);
    },

    // Genera URL con base path
    url: (path = '') => {
        return KS.baseUrl + path.replace(/^\//, '');
    },

    // Genera link WhatsApp
    whatsappLink: (message, utmParams = {}) => {
        const defaultUtm = {
            utm_source: 'site',
            utm_medium: 'whatsapp',
            utm_campaign: 'general'
        };
        const utm = { ...defaultUtm, ...utmParams };
        const utmString = new URLSearchParams(utm).toString();
        const encodedMessage = encodeURIComponent(message);
        return `https://wa.me/${KS.whatsappNumber}?text=${encodedMessage}&${utmString}`;
    },

    // Validazione email
    isValidEmail: (email) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    // Validazione telefono italiano
    isValidPhone: (phone) => {
        const cleaned = phone.replace(/\D/g, '');
        return cleaned.length >= 9 && cleaned.length <= 13;
    },

    // Smooth scroll
    smoothScroll: (target, offset = 100) => {
        const element = document.querySelector(target);
        if (element) {
            const top = element.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({
                top: top,
                behavior: 'smooth'
            });
        }
    },

    // Debounce function
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Show notification
    showNotification: (message, type = 'success') => {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

// ===== HEADER & NAVIGATION =====
class Navigation {
    constructor() {
        this.header = document.querySelector('.header');
        this.menuToggle = document.querySelector('.menu-toggle');
        this.offcanvas = document.querySelector('.offcanvas');
        this.offcanvasBackdrop = document.querySelector('.offcanvas-backdrop');
        this.offcanvasClose = document.querySelector('.offcanvas-close');
        this.lastScroll = 0;

        this.init();
    }

    init() {
        // Sticky header behavior
        this.initStickyHeader();

        // Mobile menu
        this.initMobileMenu();

        // Active link
        this.setActiveLink();

        // Smooth scroll for anchor links
        this.initSmoothScroll();
    }

    initStickyHeader() {
        let ticking = false;

        const updateHeader = () => {
            const currentScroll = window.scrollY;

            if (currentScroll > 100) {
                this.header?.classList.add('scrolled');
            } else {
                this.header?.classList.remove('scrolled');
            }

            this.lastScroll = currentScroll;
            ticking = false;
        };

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(updateHeader);
                ticking = true;
            }
        });
    }

    initMobileMenu() {
        if (!this.menuToggle) return;

        this.menuToggle.addEventListener('click', () => {
            this.toggleMobileMenu();
        });

        this.offcanvasClose?.addEventListener('click', () => {
            this.closeMobileMenu();
        });

        this.offcanvasBackdrop?.addEventListener('click', () => {
            this.closeMobileMenu();
        });

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeMobileMenu();
            }
        });
    }

    toggleMobileMenu() {
        this.menuToggle?.classList.toggle('active');
        this.offcanvas?.classList.toggle('active');
        this.offcanvasBackdrop?.classList.toggle('active');
        document.body.classList.toggle('menu-open');
    }

    closeMobileMenu() {
        this.menuToggle?.classList.remove('active');
        this.offcanvas?.classList.remove('active');
        this.offcanvasBackdrop?.classList.remove('active');
        document.body.classList.remove('menu-open');
    }

    setActiveLink() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const links = document.querySelectorAll('.nav-link, .offcanvas-nav-item');

        links.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href === currentPage || href === './' + currentPage)) {
                link.classList.add('active');
            }
        });
    }

    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = anchor.getAttribute('href');
                if (target && target !== '#') {
                    Utils.smoothScroll(target);
                }
            });
        });
    }
}

// ===== FORM HANDLER =====
class FormHandler {
    constructor(formSelectorOrElement) {
        if (typeof formSelectorOrElement === 'string') {
            try {
                this.form = document.querySelector(formSelectorOrElement);
            } catch (e) {
                console.warn('Invalid selector passed to FormHandler:', formSelectorOrElement);
                this.form = null;
            }
        } else {
            this.form = formSelectorOrElement;
        }
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Real-time validation
        this.form.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
        });

        // Phone number formatting
        const phoneInputs = this.form.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.formatPhoneNumber(e.target);
            });
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const isRequired = field.hasAttribute('required');
        let isValid = true;
        let errorMessage = '';

        if (isRequired && !value) {
            isValid = false;
            errorMessage = 'Questo campo è obbligatorio';
        } else if (field.type === 'email' && value && !Utils.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Inserisci un indirizzo email valido';
        } else if (field.type === 'tel' && value && !Utils.isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Inserisci un numero di telefono valido';
        }

        this.toggleFieldError(field, !isValid, errorMessage);
        return isValid;
    }

    toggleFieldError(field, hasError, message = '') {
        const formGroup = field.closest('.form-group');
        const errorElement = formGroup?.querySelector('.error-message');

        if (hasError) {
            field.classList.add('is-invalid');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            } else if (message) {
                const error = document.createElement('div');
                error.className = 'error-message';
                error.textContent = message;
                formGroup?.appendChild(error);
            }
        } else {
            field.classList.remove('is-invalid');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
    }

    formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.slice(0, 3) + ' ' + value.slice(3);
            } else {
                value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
            }
        }
        input.value = value;
    }

    async handleSubmit() {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        // Validate all fields
        const fields = this.form.querySelectorAll('.form-control[required]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        if (!isValid) {
            Utils.showNotification('Per favore, correggi gli errori nel modulo', 'error');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line spin"></i> Invio in corso...';

        try {
            const formData = new FormData(this.form);
            const response = await fetch(this.form.action || 'api/send-email.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                Utils.showNotification(result.message || 'Messaggio inviato con successo!', 'success');
                this.form.reset();

                // Track event
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submit', {
                        form_name: this.form.dataset.formName || 'contact'
                    });
                }
            } else {
                throw new Error(result.message || 'Errore durante l\'invio');
            }
        } catch (error) {
            Utils.showNotification(error.message || 'Si è verificato un errore. Riprova più tardi.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
}

// ===== LAZY LOADING =====
class LazyLoader {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.imageOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };

        if ('IntersectionObserver' in window) {
            this.init();
        } else {
            this.loadAllImages();
        }
    }

    init() {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, this.imageOptions);

        this.images.forEach(img => imageObserver.observe(img));
    }

    loadImage(img) {
        const src = img.dataset.src;
        if (!src) return;

        img.src = src;
        img.removeAttribute('data-src');
        img.classList.add('loaded');
    }

    loadAllImages() {
        this.images.forEach(img => this.loadImage(img));
    }
}

// ===== MOTORE UNICO DI REVEAL ON SCROLL =====
// Un solo sistema, robusto e fail-safe, per le animazioni d'ingresso legate
// allo scroll. Principi:
//  - COPERTURA AUTOMATICA: funziona su tutte le pagine agganciando elementi di
//    contenuto generici, senza dipendere da attributi data-aos messi a mano
//    (che però vengono rispettati se presenti, con la loro direzione).
//  - FAIL-SAFE: lo stato "nascosto" è applicato SOLO via JS (classe .ks-reveal).
//    Se il JS non parte o va in errore, nulla resta invisibile. In più un timer
//    di sicurezza rivela comunque tutto ciò che è in vista dopo pochi secondi.
//  - BIDIREZIONALE: gli elementi entrano scrollando e si ri-nascondono uscendo.
//  - NIENTE TIMING FRAGILE: nessun requestAnimationFrame (sospeso nelle tab in
//    background); ci si affida al primo callback asincrono dell'IntersectionObserver.
class KSRevealEngine {
    constructor() {
        this.observer = null;
        this.items = [];
        this.failsafeTimer = null;
        this.options = { offset: 8, once: false };
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.mutationObserver = null;
        this.debounceTimer = null;
        this.targetsSet = new Set();

        // Contenuto da animare automaticamente, presente su tutte le pagine.
        this.autoSelectors = [
            'section h1', 'section h2', 'section h3',
            '.section-header', '.section-title', '.section-subtitle',
            '.page-header h1', '.page-header p',
            '.card', '[class*="-card"]',
            '.service-item', '.service-block', '.plan-card',
            '.process-step', '.experience-box', '.savings-calculator',
            '.featured-flyer-cover',
            '.feature-list > li', '.feature-list > div',
            '.stat-item', '.benefit-item',
            '.about-image', '.about-text',
            '.contact-info', '.contact-form-wrapper'
        ].join(',');

        // Mai animare elementi dentro questi contenitori (UI fissa, nav, ecc.).
        this.skipInside = 'nav, .navbar, footer, #cookie-banner, .modal, .offcanvas, .toast, [data-no-anim]';
    }

    init(options = {}) {
        this.options = { ...this.options, ...options };
        this.refresh();

        // Inizializza MutationObserver per rivelare elementi inseriti dinamicamente (es. AJAX o widget)
        if (!this.mutationObserver && typeof MutationObserver !== 'undefined') {
            this.mutationObserver = new MutationObserver((mutations) => {
                let shouldRefresh = false;
                for (const mutation of mutations) {
                    if (mutation.addedNodes.length > 0) {
                        for (const node of mutation.addedNodes) {
                            if (node.nodeType === 1) {
                                if (node.matches && (node.matches(this.autoSelectors) || node.querySelector(this.autoSelectors) || node.matches('[data-aos]') || node.querySelector('[data-aos]'))) {
                                    shouldRefresh = true;
                                    break;
                                }
                            }
                        }
                    }
                    if (shouldRefresh) break;
                }
                if (shouldRefresh) {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => this.refresh(), 100);
                }
            });
            this.mutationObserver.observe(document.body, { childList: true, subtree: true });
        }
    }

    // Ricostruisce la lista (utile dopo contenuti caricati via AJAX o carousel).
    refresh() {
        // Movimento ridotto o IO non supportato → lascia tutto visibile.
        if (this.reduced || !('IntersectionObserver' in window)) return;

        const targets = this.collect();
        if (!targets.length) return;

        // Stato iniziale nascosto applicato SOLO ora, via JS (fail-safe).
        targets.forEach(el => el.classList.add('ks-reveal'));

        this.observer?.disconnect();
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('ks-in');
                    this.checkAndAnimateCounter(entry.target);
                    if (this.options.once) this.observer.unobserve(entry.target);
                } else {
                    const rect = entry.boundingClientRect;
                    if (rect.top < 0) {
                        // Se l'elemento è già sopra la viewport (scrollato oltre), lo teniamo visibile
                        entry.target.classList.add('ks-in');
                        if (this.options.once) this.observer.unobserve(entry.target);
                    } else if (!this.options.once) {
                        // Solo se l'elemento si trova sotto la viewport lo nascondiamo di nuovo
                        entry.target.classList.remove('ks-in');
                    }
                }
            });
        }, {
            root: null,
            threshold: 0.1,
            rootMargin: `0px 0px -${this.options.offset}% 0px`
        });

        this.items = targets;
        targets.forEach(el => this.observer.observe(el));

        // SICUREZZA: se per qualunque motivo qualcosa resta nascosto, dopo 3.5s
        // viene rivelato comunque (nessun contenuto deve mai restare invisibile).
        clearTimeout(this.failsafeTimer);
        this.failsafeTimer = setTimeout(() => {
            const vh = window.innerHeight || document.documentElement.clientHeight;
            this.items.forEach(el => {
                const r = el.getBoundingClientRect();
                if (r.top < vh && r.bottom > 0) el.classList.add('ks-in');
            });
        }, 3500);
    }

    collect() {
        const raw = new Set();
        // 1) rispetta gli attributi data-aos esistenti (intento esplicito)
        document.querySelectorAll('[data-aos]').forEach(el => raw.add(el));
        // 2) copertura automatica del contenuto
        document.querySelectorAll(this.autoSelectors).forEach(el => raw.add(el));

        // scarta gli elementi dentro contenitori da saltare
        const kept = new Set();
        raw.forEach(el => {
            if (el.closest(this.skipInside)) return;
            
            // Non animare elementi all'interno di slider, caroselli o hero secondari (siano essi automatici o con data-aos),
            // poiché le loro animazioni sono gestite interamente via CSS agganciate agli stati attivi della pagina (body.ks-dom-ready, .active)
            if (el.closest('.swiper-wrapper, .carousel-inner, .carousel-item, .hero-secondary')) return;
            
            kept.add(el);
        });

        const out = [];
        kept.forEach(el => {
            // gli elementi con data-aos si tengono sempre (rispetta il lavoro esistente)
            if (!el.hasAttribute('data-aos')) {
                // per la copertura automatica, evita reveal annidati: se un antenato
                // è già un target, salta (altrimenti sembrano "doppi")
                let p = el.parentElement, nested = false;
                while (p) { if (kept.has(p)) { nested = true; break; } p = p.parentElement; }
                if (nested) return;
            }
            out.push(el);
        });

        // Memorizza l'insieme dei target definitivi per una rapida consultazione nello staggerOf
        this.targetsSet = new Set(out);

        // direzione + stagger a cascata tra fratelli vicini
        out.forEach(el => {
            el.dataset.ksDir = this.dirOf(el);
            el.style.setProperty('--ks-d', `${this.staggerOf(el)}ms`);
        });
        return out;
    }

    dirOf(el) {
        const aos = (el.getAttribute('data-aos') || '').toLowerCase();
        if (aos.includes('down')) return 'down';
        if (aos.includes('left')) return 'left';
        if (aos.includes('right')) return 'right';
        if (aos.includes('zoom')) return 'zoom';
        return 'up';
    }

    staggerOf(el) {
        const declared = parseInt(el.getAttribute('data-aos-delay'), 10);
        if (!isNaN(declared)) return Math.min(declared, 400);

        let index = 0;
        let sib = el;
        let hasSiblingTargets = false;

        // Cerca l'indice tra i fratelli diretti
        while (sib = sib.previousElementSibling) {
            if (this.targetsSet?.has(sib)) {
                hasSiblingTargets = true;
                index++;
            }
        }

        // Se non ci sono fratelli animati diretti, controlla se il genitore (es. colonna Bootstrap) ha fratelli con elementi animati
        if (!hasSiblingTargets && el.parentElement) {
            const parent = el.parentElement;
            let parentSib = parent;
            let parentIndex = 0;
            let hasParentSiblingTargets = false;

            while (parentSib = parentSib.previousElementSibling) {
                // Cerca se ci sono elementi animati tra i discendenti dello stesso livello del fratello del genitore
                const descendants = Array.from(parentSib.querySelectorAll('*'));
                const hasTarget = descendants.some(desc => this.targetsSet?.has(desc));
                if (hasTarget) {
                    hasParentSiblingTargets = true;
                    parentIndex++;
                }
            }

            if (hasParentSiblingTargets) {
                index = parentIndex;
            }
        }

        return Math.min(index * 80, 400);
    }

    checkAndAnimateCounter(target) {
        const counters = target.matches('.stat-number, .stat-value, .ks-counter') 
            ? [target] 
            : Array.from(target.querySelectorAll('.stat-number, .stat-value, .ks-counter'));
            
        counters.forEach(el => {
            if (el.dataset.ksCounterDone) return;
            el.dataset.ksCounterDone = 'true';
            
            const originalText = el.textContent.trim();
            const numberRegex = /\d+/g;
            const matches = originalText.match(numberRegex);
            
            if (!matches) return;
            
            const targets = matches.map(m => parseInt(m, 10));
            const duration = 1200; // 1.2 secondi
            const startTime = performance.now();
            
            // Imposta larghezza fissa e display per evitare spostamenti di layout durante il conteggio
            const rect = el.getBoundingClientRect();
            if (rect.width > 0) {
                el.style.width = rect.width + 'px';
                el.style.display = 'inline-block';
            }
            
            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing cubic ease-out
                const ease = 1 - Math.pow(1 - progress, 3);
                
                let index = 0;
                const updatedText = originalText.replace(numberRegex, () => {
                    const targetVal = targets[index++];
                    const currentVal = Math.floor(ease * targetVal);
                    if (targetVal >= 1000) {
                        return currentVal.toLocaleString('it-IT');
                    }
                    return currentVal;
                });
                
                el.textContent = updatedText;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    // Ripristina l'allineamento originario ed esatto testo finale
                    el.textContent = originalText;
                    el.style.width = '';
                    el.style.display = '';
                }
            };
            
            requestAnimationFrame(animate);
        });
    }
}

window.KSReveal = window.KSReveal || new KSRevealEngine();

// ===== INITIALIZE ON DOM READY =====
ready(() => {
    // Initialize navigation
    try {
        new Navigation();
    } catch (e) {
        console.warn('Navigation failed to initialize:', e);
    }

    // Initialize forms
    try {
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            new FormHandler(form);
        });
    } catch (e) {
        console.warn('FormHandler failed to initialize:', e);
    }

    // Initialize lazy loading
    try {
        new LazyLoader();
    } catch (e) {
        console.warn('LazyLoader failed to initialize:', e);
    }

    // Initialize reveal animations (motore unico)
    try {
        window.KSReveal.init({
            once: false,
            offset: 8
        });
    } catch (e) {
        console.warn('Reveal animations failed to initialize:', e);
    }

    // 3D Tilt on cards
    try {
        var tiltCards = document.querySelectorAll('.service-item, .advantage-card, .testimonial-card');
        tiltCards.forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'transform 0.1s ease-out';
            });
            card.addEventListener('mousemove', function(e) {
                var rect = this.getBoundingClientRect();
                var x = e.clientX - rect.left;
                var y = e.clientY - rect.top;
                var centerX = rect.width / 2;
                var centerY = rect.height / 2;
                var rotateX = ((y - centerY) / centerY) * -15;
                var rotateY = ((x - centerX) / centerX) * 15;
                this.style.transform = 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) scale3d(1.03,1.03,1.03)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transition = 'transform 0.5s ease-out';
                this.style.transform = '';
                var self = this;
                setTimeout(function() {
                    self.style.transition = '';
                }, 500);
            });
        });
    } catch (e) {
        console.warn('3D Tilt fallito:', e);
    }

    // Secondary Hero Decorations (theme detection, blob injection, parallax)
    try {
        var path = window.location.pathname;
        var themeMap = {
            '404.php': '_404',
            'assistenza.php': 'teal',
            'chi-siamo.php': 'indigo-pink',
            'contatti.php': 'blue-cyan',
            'prenota-riparazione.php': 'blue-orange',
            'preventivo.php': 'amber-orange',
            'privacy.php': 'gray',
            'prodotti.php': 'red-amber',
            'servizi.php': 'purple-cyan',
            'track_order.php': 'cyan-indigo',
            'valuta-usato.php': 'green-teal',
            'video.php': 'red-purple',
            'volantini.php': 'pink-orange',
            'consulenza-it.php': 'indigo-teal',
            'forniture.php': 'amber-red',
            'liberty-commerce.php': 'blue-purple',
            'riparazioni.php': 'blue-orange',
            'siti-web.php': 'purple-cyan',
            'social-media.php': 'pink-orange',
            'sviluppo-web.php': 'purple-cyan',
            'sviluppo.php': 'purple-indigo',
            'telefonia.php': 'green-blue',
            'vendita.php': 'red-orange'
        };
        var theme = 'default';
        for (var key in themeMap) {
            if (path.indexOf(key) !== -1) {
                theme = themeMap[key];
                break;
            }
        }
        var hero = document.querySelector('.hero-secondary');
        if (hero) {
            hero.dataset.heroTheme = theme;
            var overlay = document.createElement('div');
            overlay.className = 'secondary-overlay';
            hero.insertBefore(overlay, hero.firstChild);
            var blobs = document.createElement('div');
            blobs.className = 'secondary-blobs';
            blobs.innerHTML = '<div class="sblob sblob--1"></div><div class="sblob sblob--2"></div><div class="sblob sblob--3"></div>';
            hero.insertBefore(blobs, overlay.nextElementSibling);
        }
        /* parallax on hero-pattern */
        var pat = document.querySelector('.hero-secondary .hero-pattern');
        if (pat) {
            window.addEventListener('scroll', function() {
                var rect = hero.getBoundingClientRect();
                var scrollY = window.scrollY || window.pageYOffset;
                if (rect.bottom > 0 && rect.top < window.innerHeight) {
                    pat.style.backgroundPositionY = (scrollY * 0.15) + 'px, ' + (scrollY * 0.1) + 'px';
                }
            }, { passive: true });
        }
    } catch (e) {
        console.warn('Hero decorations fallite:', e);
    }

    // Initialize Bootstrap Tooltips
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    } catch (e) {
        console.warn('Bootstrap Tooltips failed to initialize:', e);
    }

    // WhatsApp CTA tracking
    document.querySelectorAll('[data-whatsapp]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'whatsapp_click', {
                    page: window.location.pathname,
                    element: btn.dataset.whatsapp || 'general'
                });
            }
        });
    });

    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const offcanvas = document.querySelector('.offcanvas');
    const offcanvasBackdrop = document.querySelector('.offcanvas-backdrop');
    const offcanvasClose = document.querySelector('.offcanvas-close');

    // Open menu
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.add('active');
            offcanvas.classList.add('show');
            offcanvasBackdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close menu function
    function closeMobileMenu() {
        if (menuToggle) menuToggle.classList.remove('active');
        if (offcanvas) offcanvas.classList.remove('show');
        if (offcanvasBackdrop) offcanvasBackdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close menu - button
    if (offcanvasClose) {
        offcanvasClose.addEventListener('click', closeMobileMenu);
    }

    // Close menu - backdrop click
    if (offcanvasBackdrop) {
        offcanvasBackdrop.addEventListener('click', closeMobileMenu);
    }

    // Close menu - ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && offcanvas && offcanvas.classList.contains('show')) {
            closeMobileMenu();
        }
    });

    // Close menu when clicking on a link (for mobile)
    document.querySelectorAll('.mobile-nav-link, .mobile-nav-dropdown-item, .mobile-nav-cta').forEach(link => {
        link.addEventListener('click', () => {
            closeMobileMenu();
        });
    });
});

// Export utilities for global use
window.KS = KS;
window.Utils = Utils;

ready(function () {
    var el = document.getElementById('heroCarousel');
    if (el && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
        // inizializza/forza autoplay anche se i data-attr non venissero letti
        new bootstrap.Carousel(el, {
            interval: 10000,
            ride: 'carousel',
            pause: false,
            wrap: true,
            touch: true,
            keyboard: true
        });
    }
});

ready(function () {
    if (typeof Swiper === 'undefined' || !document.querySelector('.recond-swiper')) return;

    new Swiper(".recond-swiper", {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 },
            1200: { slidesPerView: 4 }
        }
    });
});

ready(function () {
    if (typeof Swiper === 'undefined' || !document.querySelector('.brand-swiper')) return;

    new Swiper(".brand-swiper", {
        slidesPerView: 5,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        breakpoints: {
            1200: { slidesPerView: 5 },
            992: { slidesPerView: 4 },
            768: { slidesPerView: 3 },
            576: { slidesPerView: 2 },
            0: { slidesPerView: 1 }
        }
    });
});

ready(function () {
    if (typeof Swiper === 'undefined' || !document.querySelector('.testimonial-swiper')) return;

    new Swiper(".testimonial-swiper", {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        slidesPerView: 1,
        spaceBetween: 30,
        pagination: {
            el: ".testimonial-swiper .swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1200: { slidesPerView: 3 }
        }
    });
});

// Smooth scroll per anchor links con classe .smooth-scroll
document.querySelectorAll('a.smooth-scroll').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            window.scrollTo({
                top: target.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

    // Export classes to global scope
    window.Navigation = Navigation;
    window.FormHandler = FormHandler;
    window.KSRevealEngine = KSRevealEngine;
})();
