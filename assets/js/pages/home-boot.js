// This small blocking script runs before the body can paint its static layout.
(() => {
  if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  const root = document.documentElement;
  root.classList.add('welcome-loading');
  let timer = 0;
  const boot = window.__ksWelcomeBoot = {
    expired: false,
    finish() {
      clearTimeout(timer);
      document.removeEventListener('visibilitychange', arm);
      root.classList.remove('welcome-loading');
      root.classList.add('welcome-ready');
    }
  };
  // Background tabs throttle network and frames: the curtain timeout only counts
  // while the visitor can actually see the page, otherwise the film never starts.
  function arm() {
    clearTimeout(timer);
    if (document.hidden) return;
    timer = setTimeout(() => {
      boot.expired = true;
      dispatchEvent(new Event('welcome-boot-timeout'));
      boot.finish();
    }, 5000);
  }
  document.addEventListener('visibilitychange', arm);
  arm();
})();
