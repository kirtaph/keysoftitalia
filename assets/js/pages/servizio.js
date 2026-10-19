// Pagine dei singoli servizi: opening height, block reveals and the scene's blade cut.
(function () {
  var body = document.body, header = document.querySelector('.header');
  var reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // The opening fills the first screen under the header as it is at the top of the page. Read once,
  // so the opening never changes height when the header compacts on scroll.
  if (header && scrollY < 5) body.style.setProperty('--srv-head-offset', header.offsetHeight + 'px');

  if (reduced || !('IntersectionObserver' in window)) return;

  // Blocks rise once as they arrive. Content is visible without JS; the class only opts in.
  var blocks = document.querySelectorAll('[data-srv-reveal]');
  if (blocks.length) {
    body.classList.add('srv-js');
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var siblings = [].slice.call(en.target.parentNode.querySelectorAll(':scope > [data-srv-reveal]'));
        en.target.style.transitionDelay = Math.max(0, siblings.indexOf(en.target)) * 90 + 'ms';
        en.target.classList.add('is-in');
        io.unobserve(en.target);
      });
    }, { rootMargin: '0px 0px -12% 0px' });
    blocks.forEach(function (el) { io.observe(el); });
  }

  // The statement: the chapter pins and native scroll lights the sentence word by word, figures in
  // the accent. Words are wrapped in spans inside the same paragraph, so it still reads as one text.
  document.querySelectorAll('.srv-statement').forEach(function (p) {
    var section = p.closest('section'), track = p.closest('.srv-statement-track'), raf = 0, lit = -1;
    (function wrap(node) {
      [].slice.call(node.childNodes).forEach(function (n) {
        if (n.nodeType === 1) return wrap(n);
        if (n.nodeType !== 3 || !n.textContent.trim()) return;
        var frag = document.createDocumentFragment();
        n.textContent.split(/(\s+)/).forEach(function (part) {
          if (!part) return;
          if (/^\s+$/.test(part)) return frag.appendChild(document.createTextNode(part));
          var s = document.createElement('span'); s.className = 'w'; s.textContent = part; frag.appendChild(s);
        });
        node.replaceChild(frag, n);
      });
    })(p);
    var words = [].slice.call(p.querySelectorAll('.w'));
    section.classList.add('is-pinned');
    function headerH() { return header ? header.offsetHeight : 0; }
    function update() {
      raf = 0;
      body.style.setProperty('--srv-header', headerH() + 'px');
      var r = track.getBoundingClientRect(), travel = track.offsetHeight - (innerHeight - headerH());
      // Light across the first 80% of the pinned travel, then hold the full sentence.
      var k = Math.min(1, Math.max(0, (headerH() - r.top) / (travel * .8)));
      var n = Math.round(k * words.length);
      if (n === lit) return;
      lit = n;
      words.forEach(function (w, i) { w.classList.toggle('is-lit', i < n); });
    }
    function schedule() { if (!raf) raf = requestAnimationFrame(update); }
    addEventListener('scroll', schedule, { passive: true });
    addEventListener('resize', schedule);
    if (header && 'ResizeObserver' in window) new ResizeObserver(schedule).observe(header);
    update();
  });

  // The reel: native scroll through the pinned chapter steps through the categories. Each step is
  // a class change; the CSS owns the dissolve and the expanding line. Reversible.
  document.querySelectorAll('.srv-reel').forEach(function (reel) {
    var stage = reel.querySelector('.srv-reel-stage'), items = [].slice.call(reel.querySelectorAll('.srv-reel-item'));
    var frames = [].slice.call(reel.querySelectorAll('.srv-reel-frames picture')), count = reel.querySelector('.srv-reel-count b');
    var n = items.length, shown = -1, raf = 0;
    reel.classList.add('is-reel');
    frames.forEach(function (f, i) { if (i < 2) f.querySelector('img').loading = 'eager'; });
    function update() {
      raf = 0;
      var hd = header ? header.offsetHeight : 0;
      body.style.setProperty('--srv-header', hd + 'px');
      var r = reel.getBoundingClientRect(), travel = reel.offsetHeight - stage.offsetHeight;
      var p = Math.min(1, Math.max(0, (hd - r.top) / travel));
      reel.style.setProperty('--srv-progress', p.toFixed(4));
      var a = Math.min(n - 1, Math.floor(p * n));
      if (a === shown) return;
      shown = a;
      items.forEach(function (it, i) { it.classList.toggle('is-active', i === a); });
      frames.forEach(function (f, i) { f.classList.toggle('is-active', i === a); if (i === a + 1) f.querySelector('img').loading = 'eager'; });
      if (count) count.textContent = String(a + 1).padStart(2, '0');
    }
    function schedule() { if (!raf) raf = requestAnimationFrame(update); }
    addEventListener('scroll', schedule, { passive: true });
    addEventListener('resize', schedule);
    if (header && 'ResizeObserver' in window) new ResizeObserver(schedule).observe(header);
    update();
    // WhatsApp and back-to-top step aside while the chapter is on screen, as on the home film.
    new IntersectionObserver(function (en) { body.classList.toggle('srv-stage-in-view', en[0].isIntersecting); }, { rootMargin: '-30% 0px -30% 0px' }).observe(reel);
  });

  // The process: the photograph rises out of the graphite as the chapter arrives, then the chapter
  // pins and native scroll advances the steps one at a time. Reversible; idle when off screen.
  document.querySelectorAll('.srv-process').forEach(function (scene) {
    var stage = scene.querySelector('.srv-process-stage'), steps = [].slice.call(scene.querySelectorAll('.srv-process-steps li'));
    var bars = [].slice.call(scene.querySelectorAll('.srv-process-meter i')), n = steps.length, shown = -1, raf = 0, visible = false;
    scene.classList.add('is-pinned');
    function update() {
      raf = 0;
      var hd = header ? header.offsetHeight : 0, vh = innerHeight, r = scene.getBoundingClientRect();
      body.style.setProperty('--srv-header', hd + 'px');
      var arrive = Math.min(1, Math.max(0, (vh - r.top) / (vh - hd)));
      var travel = scene.offsetHeight - stage.offsetHeight, p = Math.min(1, Math.max(0, (hd - r.top) / travel));
      scene.style.setProperty('--srv-arrive', (1 - Math.pow(1 - arrive, 3)).toFixed(4));
      scene.style.setProperty('--srv-progress', p.toFixed(4));
      var a = Math.min(n - 1, Math.floor(p * n));
      bars.forEach(function (b, i) { b.style.setProperty('--fill', Math.min(1, Math.max(0, p * n - i)).toFixed(3)); });
      if (a === shown) return;
      shown = a;
      steps.forEach(function (s, i) { s.classList.toggle('is-active', i === a); });
    }
    function schedule() { if (visible && !raf) raf = requestAnimationFrame(update); }
    new IntersectionObserver(function (en) { visible = en[0].isIntersecting; if (visible) schedule(); }).observe(scene);
    new IntersectionObserver(function (en) { body.classList.toggle('srv-process-in-view', en[0].isIntersecting); }, { rootMargin: '-30% 0px -30% 0px' }).observe(scene);
    addEventListener('scroll', schedule, { passive: true });
    addEventListener('resize', schedule);
    update();
  });
})();
