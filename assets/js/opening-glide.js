// Opening glide, shared by the pages with a full-screen opening (Servizi, Laboratorio, Prodotti).
// The opening is a title card: one gesture down takes you to the start of the section marked
// [data-glide-to], one gesture up from there brings you back to the top. Only this stretch is
// guided; everything else scrolls natively. Reduced motion jumps instead of gliding.
(function () {
  var opening = document.querySelector('[data-glide-from]'), next = document.querySelector('[data-glide-to]');
  if (!opening || !next) return;
  var header = document.querySelector('.header'), reduced = matchMedia('(prefers-reduced-motion: reduce)');
  var busy = false, touchY = null;
  function target() { return Math.round(next.getBoundingClientRect().top + scrollY - (header ? header.offsetHeight : 0)); }
  // Pages set scroll-behavior: smooth; every step here must land instantly.
  function jump(y) { scrollTo({ top: y, behavior: 'instant' }); }
  function glide(to) {
    if (reduced.matches) { jump(to); if (to > 0) setTimeout(function () { jump(target()); }, 250); return; }
    busy = true;
    var from = scrollY, d = to - from, t0 = performance.now(), dur = Math.min(1000, 500 + Math.abs(d) * .4);
    (function step(now) {
      var k = Math.min(1, (now - t0) / dur), e = 1 - Math.pow(1 - k, 4);
      if (to > 0) d = target() - from; // the header compacts on the way down
      jump(from + d * e);
      if (k < 1) requestAnimationFrame(step); else setTimeout(function () { busy = false; }, 120);
    })(t0);
  }
  // Menus and dialogs own their gestures.
  function blocked() { return !!document.querySelector('.ks-offcanvas[aria-hidden="false"],.modal.show'); }
  // Returns true when the gesture was taken over.
  function guide(dir) {
    if (blocked()) return false;
    var y = scrollY, t = target();
    if (dir > 0 && y < t - 2) { glide(t); return true; }
    if (dir < 0 && y > 2 && y <= t + 2) { glide(0); return true; }
    return false;
  }
  addEventListener('wheel', function (e) {
    if (e.ctrlKey || Math.abs(e.deltaY) < Math.abs(e.deltaX)) return;
    if (busy) { if (scrollY <= target() + 2) e.preventDefault(); return; }
    if (e.deltaY !== 0 && guide(e.deltaY)) e.preventDefault();
  }, { passive: false });
  addEventListener('keydown', function (e) {
    if (busy || e.altKey || e.ctrlKey || e.metaKey || e.defaultPrevented) return;
    // Leave keys to fields, buttons, links and open menus or dialogs.
    if (e.target.closest && e.target.closest('input,textarea,select,button,a,[contenteditable],[role="dialog"],.modal')) return;
    var down = ['ArrowDown', 'PageDown', ' '].indexOf(e.key) > -1 && !e.shiftKey;
    var up = ['ArrowUp', 'PageUp'].indexOf(e.key) > -1 || (e.key === ' ' && e.shiftKey);
    if ((down && guide(1)) || (up && guide(-1))) e.preventDefault();
  });
  addEventListener('touchstart', function (e) { touchY = e.touches[0].clientY; }, { passive: true });
  addEventListener('touchmove', function (e) {
    if (touchY === null) return;
    var dy = touchY - e.touches[0].clientY;
    if (busy) { e.preventDefault(); return; }
    if (Math.abs(dy) > 12 && guide(dy)) { touchY = null; e.preventDefault(); }
  }, { passive: false });
  addEventListener('touchend', function () { touchY = null; }, { passive: true });
})();
