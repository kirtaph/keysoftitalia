// Laboratorio: «Come funziona» scrubs the home's repair footage with native scroll.
// Without JS or with reduced motion the section stays a readable four-step list.
const reduced = matchMedia('(prefers-reduced-motion: reduce)');
document.documentElement.classList.add('lab-js');

// The opening fills the first screen under the header as it is at the top of the page; read once
// so it never changes height when the header compacts on scroll.
const topHeader = document.querySelector('.header');
if (topHeader && scrollY < 5) document.body.style.setProperty('--lab-head-offset', topHeader.offsetHeight + 'px');

const reveals = [...document.querySelectorAll('[data-lab-reveal]')];
if (reduced.matches || !('IntersectionObserver' in window)) {
  reveals.forEach(el => el.classList.add('is-in'));
} else {
  const io = new IntersectionObserver(entries => entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    entry.target.classList.add('is-in');
    io.unobserve(entry.target);
  }), { rootMargin: '0px 0px -12% 0px' });
  reveals.forEach(el => io.observe(el));
}

const section = document.getElementById('come-funziona');
if (section && !reduced.matches && section.querySelector('canvas')?.getContext) film(section);

function film(section) {
  // Frame ranges of the chapter-04 sequence for each step; the end of each step holds for reading.
  const STEPS = [[130, 300], [300, 384], [384, 576], [576, 768]];
  const STRIDE = 2, CONCURRENCY = 6;
  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
  const ease = t => 1 - Math.pow(1 - t, 3);
  const stage = section.querySelector('.lab-process-stage');
  const canvas = section.querySelector('.lab-process-film');
  const ctx = canvas.getContext('2d', { alpha: false });
  const steps = [...section.querySelectorAll('.lab-steps li')];
  const buttons = [...section.querySelectorAll('[data-goto]')];
  const header = document.querySelector('.header');
  const mobile = matchMedia('(max-width:767px)');
  const all = [];
  for (let i = STEPS[0][0]; i <= STEPS.at(-1)[1]; i += STRIDE) all.push(i);
  if (all.at(-1) !== STEPS.at(-1)[1]) all.push(STEPS.at(-1)[1]);

  let base = '', ext = 'avif', frames = new Map(), inflight = 0, target = all[0];
  let drawn = null, raf = 0, near = false, progress = -1, current = -1, headerH = 0;

  section.classList.add('is-film');
  section.style.setProperty('--lab-steps', STEPS.length);

  const url = i => `${base}${String(i).padStart(4, '0')}.${ext}`;
  function setSource() {
    base = mobile.matches ? section.dataset.filmMobile : section.dataset.filmDesktop;
    frames = new Map(); drawn = null; section.classList.remove('has-frame');
  }
  function load(i) {
    frames.set(i, null); inflight++;
    const img = new Image(), src = base;
    img.decoding = 'async';
    img.onload = () => {
      inflight--;
      if (src !== base) return;
      frames.set(i, img);
      if (Math.abs(i - target) <= STRIDE * 4) schedule();
      pump();
    };
    img.onerror = () => {
      inflight--;
      if (src !== base) return;
      if (ext === 'avif') { ext = 'webp'; frames = new Map(); }
      else frames.delete(i);
      pump();
    };
    img.src = url(i);
  }
  // Nearest frames to the requested one first, then the rest of the film.
  function pump() {
    if (!near) return;
    const missing = all.filter(i => !frames.has(i)).sort((a, b) => Math.abs(a - target) - Math.abs(b - target));
    while (inflight < CONCURRENCY && missing.length) load(missing.shift());
  }
  function nearest(i) {
    for (let d = 0; d <= 64; d++) {
      const a = frames.get(i - d) || frames.get(i + d);
      if (a) return a;
    }
    return null;
  }
  function size() {
    headerH = header ? header.offsetHeight : 0;
    document.body.style.setProperty('--lab-header', headerH + 'px');
    const dpr = Math.min(devicePixelRatio || 1, 2);
    canvas.width = Math.round(stage.clientWidth * dpr);
    canvas.height = Math.round(stage.clientHeight * dpr);
    drawn = null;
  }
  function draw(img) {
    if (!img || img === drawn) return;
    const cw = canvas.width, ch = canvas.height, s = Math.max(cw / img.naturalWidth, ch / img.naturalHeight);
    const w = img.naturalWidth * s, h = img.naturalHeight * s;
    ctx.fillStyle = '#0b0c0f'; ctx.fillRect(0, 0, cw, ch);
    ctx.drawImage(img, (cw - w) / 2, (ch - h) / 2, w, h);
    drawn = img;
    section.classList.add('has-frame');
  }
  function measure() {
    const travel = section.offsetHeight - stage.offsetHeight;
    return clamp((headerH - section.getBoundingClientRect().top) / travel);
  }
  function update() {
    raf = 0;
    const p = measure(), n = STEPS.length, seg = Math.min(p * n, n - .0001);
    const index = Math.floor(seg), local = seg - index;
    const [from, to] = STEPS[index];
    const f = from + (to - from) * ease(clamp((local - .04) / .64));
    target = all.reduce((best, i) => Math.abs(i - f) < Math.abs(best - f) ? i : best, all[0]);
    draw(nearest(target));
    if (p !== progress) { progress = p; section.style.setProperty('--lab-progress', p.toFixed(4)); }
    steps.forEach((step, i) => {
      let o = 0, y = 32;
      if (i === index) {
        const enter = i === 0 ? 1 : ease(clamp(local / .14));
        const exit = i === n - 1 ? 0 : ease(clamp((local - .86) / .14));
        o = enter * (1 - exit); y = (1 - enter) * 32 - exit * 32;
      }
      step.style.opacity = o.toFixed(3);
      step.style.transform = `translateY(${y.toFixed(1)}px)`;
      step.style.visibility = o > .01 ? 'visible' : 'hidden';
    });
    if (index !== current) {
      current = index;
      buttons.forEach((b, i) => b.toggleAttribute('aria-current', i === index));
      if (buttons[index]) buttons[index].setAttribute('aria-current', 'step');
    }
    pump();
  }
  function schedule() { if (!raf) raf = requestAnimationFrame(update); }

  buttons.forEach((button, i) => button.addEventListener('click', () => {
    const travel = section.offsetHeight - stage.offsetHeight;
    const top = section.getBoundingClientRect().top + scrollY - headerH + travel * ((i + .8) / STEPS.length);
    scrollTo({ top, behavior: 'smooth' });
  }));

  new IntersectionObserver(([entry]) => { near = entry.isIntersecting; if (near) { pump(); schedule(); } },
    { rootMargin: '150% 0px' }).observe(section);
  // Like the home film, the floating WhatsApp and back-to-top step aside while the stage is pinned.
  new IntersectionObserver(([entry]) => document.body.classList.toggle('lab-film-in-view', entry.isIntersecting),
    { rootMargin: `-${Math.round(innerHeight * .2)}px 0px` }).observe(section);
  addEventListener('scroll', schedule, { passive: true });
  addEventListener('resize', () => { size(); schedule(); });
  // The header compacts once the page scrolls: follow its real height.
  if (header && 'ResizeObserver' in window) new ResizeObserver(() => { size(); schedule(); }).observe(header);
  mobile.addEventListener('change', () => { setSource(); size(); pump(); schedule(); });
  reduced.addEventListener('change', () => location.reload());

  setSource(); size(); schedule();
}
