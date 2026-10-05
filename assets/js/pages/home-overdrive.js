/**
 * Key Soft Italia — Homepage Overdrive
 * Scroll choreography: progress spine + odometer heritage stats.
 *
 * Pure progressive enhancement. Nothing here is required for the page to
 * work or to be readable; if it doesn't run, the shipping design + KSReveal
 * still stand. Respects prefers-reduced-motion.
 */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function onReady(fn) {
    if (document.readyState !== 'loading') { fn(); }
    else { document.addEventListener('DOMContentLoaded', fn); }
  }

  /* ----------------------------------------------------------
   * 1. Scroll progress spine
   * Injected here so no-JS users get nothing extra. The CSS
   * drives the fill via scroll-timeline when supported; we only
   * take over with a rAF-throttled scroll listener as fallback.
   * -------------------------------------------------------- */
  function initSpine() {
    if (reduce) return;                     // CSS hides it too
    if (window.innerWidth <= 768) return;   // desktop reading aid only
    if (document.querySelector('.ks-spine')) return;

    var spine = document.createElement('div');
    spine.className = 'ks-spine';
    spine.setAttribute('aria-hidden', 'true');
    var fill = document.createElement('div');
    fill.className = 'ks-spine__fill';
    spine.appendChild(fill);
    document.body.appendChild(spine);

    var supportsScrollTimeline =
      window.CSS && CSS.supports && CSS.supports('animation-timeline: scroll()');
    if (supportsScrollTimeline) return;     // compositor handles it

    var ticking = false;
    function update() {
      var doc = document.documentElement;
      var max = doc.scrollHeight - doc.clientHeight;
      var p = max > 0 ? Math.min(1, Math.max(0, doc.scrollTop / max)) : 0;
      document.documentElement.style.setProperty('--ks-progress', p.toFixed(4));
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
    }, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  }

  /* ----------------------------------------------------------
   * 2. Odometer heritage stats
   * The "20+ years" block counts up and assembles with depth the
   * first time it enters the viewport. Numbers are parsed from the
   * existing markup so copy stays the single source of truth.
   * -------------------------------------------------------- */
  function parseStat(text) {
    // Capture optional prefix, the number, and an optional suffix (+, h, %…).
    var m = String(text).trim().match(/^([^\d]*)([\d][\d.,]*)([^\d]*)$/);
    if (!m) return null;
    var raw = m[2];
    // Strip thousands separators; treat the rest as an integer target.
    var num = parseInt(raw.replace(/[.,\s]/g, ''), 10);
    if (isNaN(num)) return null;
    return { prefix: m[1], value: num, suffix: m[3] };
  }

  function countUp(el, stat, duration) {
    var start = null;
    var easeOut = function (t) { return 1 - Math.pow(1 - t, 3); };
    function frame(now) {
      if (start === null) start = now;
      var t = Math.min(1, (now - start) / duration);
      var current = Math.round(stat.value * easeOut(t));
      el.textContent = stat.prefix + current + stat.suffix;
      if (t < 1) window.requestAnimationFrame(frame);
      else el.textContent = stat.prefix + stat.value + stat.suffix;
    }
    window.requestAnimationFrame(frame);
  }

  function initStats() {
    var box = document.querySelector('.experience-box');
    if (!box) return;

    var nodes = box.querySelectorAll('.experience-number, .stat-number');
    if (!nodes.length) return;

    // Parse targets up front, before we touch the DOM text.
    var stats = [];
    nodes.forEach(function (el) {
      var stat = parseStat(el.textContent);
      if (stat) stats.push({ el: el, stat: stat });
    });

    // Reduced motion (or no IntersectionObserver): leave everything as-is,
    // fully visible with its real values. No choreography.
    if (reduce || !('IntersectionObserver' in window)) return;

    box.classList.add('ks-choreo');

    // The block stays at its real, visible values until it actually enters
    // view in a live browser. Only then do we reset to zero and count up —
    // so headless renderers, hidden tabs, and non-scrolling crawlers always
    // keep the true numbers, and the reveal never leaves anything blank.
    var fired = false;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting || fired) return;
        fired = true;
        box.classList.add('is-in');           // triggers the depth-assemble
        stats.forEach(function (s, i) {
          s.el.textContent = s.stat.prefix + '0' + s.stat.suffix;
          // Bigger figures get a touch longer to breathe.
          var dur = 1100 + (s.stat.value > 999 ? 700 : 300) + i * 90;
          countUp(s.el, s.stat, dur);
        });
        io.disconnect();
      });
    }, { threshold: 0.4, rootMargin: '0px 0px -10% 0px' });

    io.observe(box);
  }

  onReady(function () {
    try { initSpine(); } catch (e) { /* enhancement only */ }
    try { initStats(); } catch (e) { /* enhancement only */ }
  });
})();
