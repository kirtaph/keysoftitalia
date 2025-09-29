/**
 * Key Soft Italia - Main JavaScript
 * Funzionalità principali e interazioni
 */

// ===== CONFIGURAZIONE GLOBALE =====
const KS = {
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
const Utils = {
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
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
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

// ===== INITIALIZE ON DOM READY =====
document.addEventListener('DOMContentLoaded', () => {
    // Initialize navigation
    new Navigation();
    
    // Initialize forms
    document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
        new FormHandler(`#${form.id}`);
    });
    
    // Initialize lazy loading
    new LazyLoader();
    
    // Initialize AOS animations (if available)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
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