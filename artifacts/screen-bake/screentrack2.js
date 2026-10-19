// Prior-free screen tracking: find the straight bezel edges around the amber display.
// Mask -> component from the previous centre -> boundary -> sequential RANSAC lines ->
// classify top/bottom/left/right -> corners. Sides outside the frame keep the previous
// frame's line moved with the visible sides' motion; result smoothed over time.
const sharp = require('sharp'), fs = require('fs');
const variant = process.argv[2] || 'mobile';
const B = `D:/webprojects/keysoftitalia/assets/img/home/cinematic-v2/chapter-02/${variant}/sequence/`;
const unitW = variant === 'desktop' ? 1920 : 1080;
const prev = JSON.parse(fs.readFileSync('screentrack.json', 'utf8'))[variant];
function lineFrom(p, q) { const a = q[1] - p[1], b = p[0] - q[0]; const n = Math.hypot(a, b) || 1; return [a / n, b / n, -(a * p[0] + b * p[1]) / n]; }
const dist = (l, p) => Math.abs(l[0] * p[0] + l[1] * p[1] + l[2]);
function fit(points) { const n = points.length, mx = points.reduce((s, p) => s + p[0], 0) / n, my = points.reduce((s, p) => s + p[1], 0) / n; let sxx = 0, sxy = 0, syy = 0; for (const [x, y] of points) { sxx += (x - mx) ** 2; sxy += (x - mx) * (y - my); syy += (y - my) ** 2; } const th = .5 * Math.atan2(2 * sxy, sxx - syy), dx = Math.cos(th), dy = Math.sin(th); return { l: [-dy, dx, dy * mx - dx * my], mx, my, dx, dy }; }
const cross = (l, m) => { const d = l[0] * m[1] - l[1] * m[0]; return [(l[1] * m[2] - l[2] * m[1]) / d, (l[2] * m[0] - l[0] * m[2]) / d]; };
function ransacLines(points, tol, minIn, maxLines) {
  const found = []; let pool = points.slice();
  for (let k = 0; k < maxLines && pool.length > minIn; k++) {
    let best = [];
    for (let t = 0; t < 400; t++) {
      const p = pool[(Math.random() * pool.length) | 0], q = pool[(Math.random() * pool.length) | 0];
      if (Math.hypot(p[0] - q[0], p[1] - q[1]) < 40) continue;
      const l = lineFrom(p, q); const inl = pool.filter(pt => dist(l, pt) < tol);
      if (inl.length > best.length) best = inl;
    }
    if (best.length < minIn) break;
    const f = fit(best);
    // Keep only long straight runs (a bezel edge), not short arcs of the wallpaper dunes.
    const proj = best.map(p => (p[0] - f.mx) * f.dx + (p[1] - f.my) * f.dy); const span = Math.max(...proj) - Math.min(...proj);
    const set = new Set(best); pool = pool.filter(p => !set.has(p));
    if (span > 140) found.push({ ...f, n: best.length, span });
  }
  return found;
}
async function detect(i, centreHint) {
  const { data, info } = await sharp(B + `frame-${String(i).padStart(4, '0')}.webp`).raw().toBuffer({ resolveWithObject: true });
  const w = info.width, h = info.height, ch = info.channels;
  const mask = new Uint8Array(w * h);
  for (let p = 0, o = 0; p < w * h; p++, o += ch) {
    const r = data[o], g = data[o + 1], b = data[o + 2], mx = Math.max(r, g, b), mn = Math.min(r, g, b);
    if (mx < 24) continue; const s = (mx - mn) / mx; if (s < .2) continue;
    let hue; if (mx === r) hue = 60 * (((g - b) / (mx - mn)) % 6); else if (mx === g) hue = 60 * ((b - r) / (mx - mn) + 2); else hue = 60 * ((r - g) / (mx - mn) + 4);
    if (hue < 0) hue += 360; if (hue > 4 && hue < 56) mask[p] = 1;
  }
  let sx = Math.round(Math.max(2, Math.min(w - 3, centreHint[0]))), sy = Math.round(Math.max(2, Math.min(h - 3, centreHint[1])));
  for (let r = 0; r < 300 && !mask[sy * w + sx]; r += 3) sx = Math.round(Math.max(2, Math.min(w - 3, centreHint[0] + (r % 2 ? r : -r))));
  const comp = new Uint8Array(w * h), stack = [];
  if (mask[sy * w + sx]) { comp[sy * w + sx] = 1; stack.push(sy * w + sx); }
  while (stack.length) { const p = stack.pop(), x = p % w; for (const q of [p + 1, p - 1, p + w, p - w]) { if (q < 0 || q >= w * h || Math.abs((q % w) - x) > 1) continue; if (comp[q] || !mask[q]) continue; comp[q] = 1; stack.push(q); } }
  const bound = [];
  for (let y = 4; y < h - 4; y++) for (let x = 4; x < w - 4; x++) { const p = y * w + x; if (!comp[p]) continue; for (const q of [p + 3, p - 3, p + 3 * w, p - 3 * w]) { if (comp[q]) continue; const o = q * ch; if (Math.max(data[o], data[o + 1], data[o + 2]) < 60) { bound.push([x, y]); break; } } }
  return { lines: ransacLines(bound, 2.2, 70, 10), w, h };
}
// Distance between two lines measured at two points of the reference side segment.
function lineGap(l, a, b) { return Math.max(dist(l, a), dist(l, b)); }
(async () => {
  const N = 192, seed = 70, k = (variant === 'desktop' ? 1440 : 720) / unitW;
  const q0 = prev[seed].quad.map(([x, y]) => [x * k, y * k]);
  const cand = []; let hint = [q0.reduce((s, p) => s + p[0], 0) / 4, q0.reduce((s, p) => s + p[1], 0) / 4];
  // Seed: classify frame 70 directly (all four sides visible there).
  const d0 = await detect(seed, hint);
  const cx = hint[0], cy = hint[1], seedSides = {};
  for (const L of d0.lines) { const horiz = Math.abs(L.dx) > Math.abs(L.dy); const key = horiz ? (L.my < cy ? 'top' : 'bottom') : (L.mx < cx ? 'left' : 'right'); if (!seedSides[key] || L.n > seedSides[key].n) seedSides[key] = L; }
  const SIDES = ['top', 'right', 'bottom', 'left'];
  const result = new Array(N);
  const corners = S => [cross(S.left, S.top), cross(S.top, S.right), cross(S.right, S.bottom), cross(S.bottom, S.left)];
  result[seed] = { S: Object.fromEntries(SIDES.map(s => [s, seedSides[s].l])), found: '1111' };
  for (const dir of [1, -1]) {
    let S = result[seed].S, V = Object.fromEntries(SIDES.map(s => [s, [0, 0, 0]]));
    for (let i = seed + dir; i >= 0 && i < N; i += dir) {
      const q = corners(S), c = [q.reduce((s, p) => s + p[0], 0) / 4, q.reduce((s, p) => s + p[1], 0) / 4];
      const { lines, w, h } = await detect(i, c);
      const seg = { top: [q[0], q[1]], right: [q[1], q[2]], bottom: [q[2], q[3]], left: [q[3], q[0]] };
      const next = {}, found = [];
      for (const side of SIDES) {
        // Predicted line = previous + velocity; accept the nearest detected line within tolerance.
        const pred = S[side].map((v, n) => v + V[side][n]);
        const [a, b] = seg[side];
        // Reference points clamped into the image so off-frame corners do not dominate.
        const clampPt = p => [Math.max(0, Math.min(w, p[0])), Math.max(0, Math.min(h, p[1]))];
        let best = null, gap = 14;
        for (const L of lines) { if (Math.abs(L.l[0] * pred[0] + L.l[1] * pred[1]) < .99) continue; const gp = lineGap(L.l, clampPt(a), clampPt(b)); if (gp < gap) { gap = gp; best = L.l; } }
        if (best) { const sgn = Math.sign(best[0] * pred[0] + best[1] * pred[1]); next[side] = best.map(v => v * sgn); found.push(1); }
        else { next[side] = pred; found.push(0); }
        V[side] = next[side].map((v, n) => (v - S[side][n]) * .6 + V[side][n] * .4);
      }
      S = next; result[i] = { S, found: found.join('') };
    }
  }
  const out = {};
  for (let i = 0; i < N; i++) out[i] = { quad: corners(result[i].S).map(([x, y]) => [x / k, y / k]), found: result[i].found };
  const keys = Object.keys(out).map(Number), raw = keys.map(i => out[i].quad);
  const med = raw.map((_, i) => raw[i].map((pt, n) => pt.map((_, a) => { const v = []; for (let d = -2; d <= 2; d++) v.push(raw[Math.max(0, Math.min(raw.length - 1, i + d))][n][a]); v.sort((x, y) => x - y); return v[2]; })));
  keys.forEach((key, i) => { out[key].quad = med[i].map((pt, n) => pt.map((_, a) => { let s = 0, c = 0; for (let d = -2; d <= 2; d++) { const j = i + d; if (j < 0 || j >= med.length) continue; s += med[j][n][a]; c++; } return Math.round(s / c); })); });
  const all = JSON.parse(fs.readFileSync('screentrack.json', 'utf8')); all[variant + '2'] = out;
  fs.writeFileSync('screentrack.json', JSON.stringify(all));
  console.log(variant, keys.filter(i => i % 8 === 0).map(i => i + ':' + out[i].found).join(' '));
})();
