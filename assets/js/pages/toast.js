class Toast {
    constructor() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
            document.body.appendChild(this.container);
        }
    }

    show(message, type = 'info', duration = 4000) {
        const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        const colors = { success: '#22c55e', error: '#ef4444', warning: '#eab308', info: '#3b82f6' };

        const el = document.createElement('div');
        el.style.cssText = `
            display:flex;align-items:center;gap:12px;padding:14px 20px;
            background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);
            border-left:4px solid ${colors[type] || colors.info};
            min-width:300px;max-width:450px;
            animation:slideIn 0.3s ease;font-family:Inter,sans-serif;
            transform:translateX(120%);opacity:0;
            transition:transform 0.3s ease,opacity 0.3s ease;
        `;
        el.innerHTML = `
            <i class="fas ${icons[type] || icons.info}" style="color:${colors[type] || colors.info};font-size:1.3rem;"></i>
            <span style="flex:1;font-size:0.9rem;color:#1f2937;">${message}</span>
            <button style="background:none;border:none;color:#9ca3af;cursor:pointer;padding:4px;font-size:1.1rem;">&times;</button>
        `;

        el.querySelector('button').addEventListener('click', () => this.dismiss(el));
        this.container.appendChild(el);

        requestAnimationFrame(() => {
            el.style.transform = 'translateX(0)';
            el.style.opacity = '1';
        });

        if (duration > 0) {
            setTimeout(() => this.dismiss(el), duration);
        }

        return el;
    }

    dismiss(el) {
        el.style.transform = 'translateX(120%)';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 300);
    }

    success(msg, duration) { return this.show(msg, 'success', duration); }
    error(msg, duration) { return this.show(msg, 'error', duration); }
    warning(msg, duration) { return this.show(msg, 'warning', duration); }
    info(msg, duration) { return this.show(msg, 'info', duration); }
}

const toast = new Toast();

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @media (max-width: 500px) { #toast-container { right: 10px; left: 10px; } #toast-container > div { min-width: auto; max-width: none; } }
`;
document.head.appendChild(style);
