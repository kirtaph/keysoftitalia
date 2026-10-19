/* Progressive motion: content is visible even when JavaScript is unavailable. */
(() => {
  'use strict';
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  const animations = new Set();
  function entrance(element, delay = 0, image = false) {
    if (reduced.matches || !element.animate || document.visibilityState === 'hidden') return;
    const animation = element.animate(
      image ? [{ transform: 'translateY(18px) scale(.985)' }, { transform: 'none' }]
        : [{ transform: 'translateY(14px)', opacity: .65 }, { transform: 'none', opacity: 1 }],
      { duration: image ? 700 : 550, delay, easing: 'cubic-bezier(.23, 1, .32, 1)' }
    );
    animations.add(animation);
    animation.finished.then(() => animations.delete(animation)).catch(() => animations.delete(animation));
  }
  [...document.querySelectorAll('[data-home-enter]')].forEach((element, index) => {
    entrance(element, index * 65, element.classList.contains('home-hero-visual'));
  });
  // A single photographic moment, rather than repeating a reveal on every section.
  if ('IntersectionObserver' in window && !reduced.matches) {
    const photo = document.querySelector('.home-catalogue-photo');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entrance(entry.target, 0, true);
        observer.unobserve(entry.target);
      });
    }, { threshold: .2 });
    if (photo) observer.observe(photo);
    reduced.addEventListener('change', event => { if (event.matches) observer.disconnect(); });
  }
  reduced.addEventListener('change', event => {
    if (event.matches) { animations.forEach(animation => animation.cancel()); animations.clear(); }
  });
  // Own the mobile drawer on this page; shared navigation has two legacy handlers.
  const menu = document.getElementById('ksOffcanvasNav');
  const backdrop = document.getElementById('ksOffcanvasBackdrop');
  const toggle = document.querySelector('.menu-toggle');
  const closeMenu = menu?.querySelector('.offcanvas-close');
  const desktop = window.matchMedia('(min-width: 992px)');
  let menuOpen = false;
  let menuOverflow = '';
  function setMenu(open) {
    if (!menu || !toggle || open === menuOpen) return;
    menuOpen = open;
    menu.classList.toggle('active', open);
    menu.classList.toggle('show', open);
    backdrop?.classList.toggle('active', open);
    backdrop?.classList.toggle('show', open);
    toggle.classList.toggle('active', open);
    toggle.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-hidden', String(!open));
    menu.inert = !open;
    toggle.setAttribute('aria-label', open ? 'Chiudi menu' : 'Apri menu');
    document.querySelector('main').inert = open;
    document.querySelector('.footer').inert = open;
    if (open) {
      menuOverflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      closeMenu?.focus({ preventScroll: true });
    } else {
      document.body.style.overflow = menuOverflow;
      toggle.focus({ preventScroll: true });
    }
  }
  if (menu) menu.inert = true;
  document.addEventListener('click', event => {
    if (toggle?.contains(event.target)) {
      event.preventDefault();
      event.stopPropagation();
      setMenu(!menuOpen);
    } else if (menuOpen && (closeMenu?.contains(event.target) || event.target === backdrop)) {
      event.preventDefault();
      event.stopPropagation();
      setMenu(false);
    } else if (menuOpen && event.target.closest('#ksOffcanvasNav a')) {
      setMenu(false);
    } else if (reduced.matches && (event.target.closest('.home-explore') || event.target.closest('#backToTop'))) {
      event.preventDefault();
      event.stopPropagation();
      if (event.target.closest('.home-explore')) document.getElementById('home-services').scrollIntoView({ behavior: 'instant' });
      else window.scrollTo({ top: 0, behavior: 'instant' });
    }
  }, true);
  document.addEventListener('keydown', event => {
    if (!menuOpen) return;
    if (event.key === 'Escape') {
      event.preventDefault();
      event.stopImmediatePropagation();
      setMenu(false);
    } else if (event.key === 'Tab') {
      const focusable = [...menu.querySelectorAll('a[href], button')].filter(el => el.getClientRects().length);
      const first = focusable[0], last = focusable.at(-1);
      if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
      else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    }
  }, true);
  desktop.addEventListener('change', event => { if (event.matches) setMenu(false); });

  const dialog = document.getElementById('home-video-dialog');
  const trigger = document.querySelector('[data-home-video]');
  const player = document.getElementById('home-video-player');
  if (!dialog || !trigger || !player) return;
  let originalOverflow = '';
  function clearPlayer() {
    player.replaceChildren();
    document.body.style.overflow = originalOverflow;
    trigger.focus({ preventScroll: true });
  }
  trigger.addEventListener('click', () => {
    if (dialog.open) return;
    if (typeof dialog.showModal !== 'function') {
      window.open(dialog.dataset.videoUrl, '_blank', 'noopener');
      return;
    }
    originalOverflow = document.body.style.overflow;
    dialog.showModal();
    document.body.style.overflow = 'hidden';
    const frame = document.createElement('iframe');
    const embed = new URL('https://www.facebook.com/plugins/video.php');
    embed.searchParams.set('href', dialog.dataset.videoUrl);
    embed.searchParams.set('show_text', 'false');
    embed.searchParams.set('autoplay', 'false');
    embed.searchParams.set('width', '720');
    frame.src = embed.href;
    frame.title = document.getElementById('home-dialog-title').textContent;
    frame.allow = 'encrypted-media; picture-in-picture; fullscreen';
    frame.allowFullscreen = true;
    frame.referrerPolicy = 'strict-origin-when-cross-origin';
    player.append(frame);
    dialog.querySelector('[data-home-video-close]').focus();
  });
  function closeVideo() {
    dialog.close();
    clearPlayer();
  }
  dialog.querySelector('[data-home-video-close]').addEventListener('click', closeVideo);
  dialog.addEventListener('cancel', event => { event.preventDefault(); closeVideo(); });
  dialog.addEventListener('close', () => { if (!dialog.open) clearPlayer(); });
  dialog.addEventListener('click', event => {
    const rect = dialog.getBoundingClientRect();
    if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right ||
      event.clientY < rect.top || event.clientY > rect.bottom)) closeVideo();
  });
})();
