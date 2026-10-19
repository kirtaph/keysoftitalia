// Per-frame screen corner tracking for chapter 02 (desktop + mobile).
// Mask = saturated amber pixels (the wallpaper), component grown from the previous
// quad's centre, boundary points assigned to the previous quad's edges, each edge
// refit with RANSAC + least squares, corners = intersections of adjacent edges.
const sharp = require('sharp'), fs = require('fs');
const B = 'D:/webprojects/keysoftitalia/assets/img/home/cinematic-v2/chapter-02/';
// Manual keyframes (source units: desktop 1920 wide, mobile 1080 wide), from home-screen-assistance.js.
const manual = {
  desktop: [[24, [[120, 78], [596, 163], [772, 607], [358, 695]]], [48, [[208, 80], [720, 165], [892, 635], [433, 722]]], [72, [[239, 61], [865, 163], [1070, 692], [476, 802]]], [96, [[248, 30], [1048, 152], [1251, 750], [484, 884]]], [120, [[239, -15], [1220, 95], [1450, 879], [462, 1020]]], [144, [[174, -98], [1398, 69], [1669, 938], [375, 1190]]], [168, [[66, -260], [1588, -23], [1850, 1040], [284, 1400]]]],
  mobile: [[24, [[-180, 400], [220, 500], [420, 1070], [-80, 1230]]], [48, [[-115, 350], [508, 471], [718, 1137], [133, 1284]]], [72, [[-30, 235], [701, 420], [992, 1230], [217, 1405]]], [96, [[20, 70], [927, 335], [1260, 1300], [296, 1560]]], [120, [[0, -65], [1045, 256], [1438, 1442], [242, 1660]]], [144, [[-55, -160], [1070, 187], [1560, 1450], [15, 1750]]], [168, [[-270, -520], [1270, -15], [1830, 1660], [-185, 2120]]]],
};
function interp(track, i) {
  const r = track.findIndex(k => k[0] >= i), e = track[r < 0 ? track.length - 1 : r], s = track[Math.max(0, r - 1)];
  const t = Math.max(0, Math.min(1, (i - s[0]) / Math.max(1, e[0] - s[0])));
  if (r < 0) return track.at(-1)[1];
  return s[1].map((p, n) => p.map((v, a) => v + (e[1][n][a] - v) * t));
}
function lineFrom(p, q) { const a = q[1] - p[1], b = p[0] - q[0]; const n = Math.hypot(a, b) || 1; return [a / n, b / n, -(a * p[0] + b * p[1]) / n]; }
const dist = (l, p) => Math.abs(l[0] * p[0] + l[1] * p[1] + l[2]);
function fit(points) { // total least squares
  const n = points.length, mx = points.reduce((s, p) => s + p[0], 0) / n, my = points.reduce((s, p) => s + p[1], 0) / n;
  let sxx = 0, sxy = 0, syy = 0; for (const [x, y] of points) { sxx += (x - mx) ** 2; sxy += (x - mx) * (y - my); syy += (y - my) ** 2; }
  const th = .5 * Math.atan2(2 * sxy, sxx - syy), dx = Math.cos(th), dy = Math.sin(th);
  return [-dy, dx, dy * mx - dx * my];
}
function ransac(points, prior, tol) {
  if (points.length < 30) return null;
  let best = null, bestIn = [];
  for (let k = 0; k < 120; k++) {
    const p = points[(Math.random() * points.length) | 0], q = points[(Math.random() * points.length) | 0];
    if (Math.hypot(p[0] - q[0], p[1] - q[1]) < 20) continue;
    const l = lineFrom(p, q);
    if (Math.abs(l[0] * prior[0] + l[1] * prior[1]) < .985) continue; // keep the orientation close to the prior edge
    const inl = points.filter(pt => dist(l, pt) < tol);
    if (inl.length > bestIn.length) { best = l; bestIn = inl; }
  }
  return bestIn.length >= 30 ? fit(bestIn) : null;
}
const cross = (l, m) => { const d = l[0] * m[1] - l[1] * m[0]; return [(l[1] * m[2] - l[2] * m[1]) / d, (l[2] * m[0] - l[0] * m[2]) / d]; };
async function track(variant) {
  const dir = B + variant + '/sequence/', unit = variant === 'desktop' ? 1920 : 1080;
  const out = {}; let prev = null, stats = { fitted: 0, fallback: 0 };
  for (let i = 0; i < 192; i++) {
    const { data, info } = await sharp(dir + `frame-${String(i).padStart(4, '0')}.webp`).raw().toBuffer({ resolveWithObject: true });
    const w = info.width, h = info.height, ch = info.channels, k = w / unit; // source px -> image px
    const guess = interp(manual[variant], i).map(([x, y]) => [x * k, y * k]);
    // Amber mask.
    const mask = new Uint8Array(w * h);
    for (let p = 0, o = 0; p < w * h; p++, o += ch) {
      const r = data[o], g = data[o + 1], b = data[o + 2], mx = Math.max(r, g, b), mn = Math.min(r, g, b);
      if (mx < 34) continue; const s = (mx - mn) / mx; if (s < .28) continue;
      let hue; if (mx === r) hue = 60 * (((g - b) / (mx - mn)) % 6); else if (mx === g) hue = 60 * ((b - r) / (mx - mn) + 2); else hue = 60 * ((r - g) / (mx - mn) + 4);
      if (hue < 0) hue += 360; if (hue > 6 && hue < 52) mask[p] = 1;
    }
    // Restrict to a band around the guessed quad (generous), grow from its centre.
    const cx = guess.reduce((s, p) => s + p[0], 0) / 4, cy = guess.reduce((s, p) => s + p[1], 0) / 4;
    const edges = [0, 1, 2, 3].map(n => lineFrom(guess[n], guess[(n + 1) % 4]));
    const inside = (x, y) => edges.every((l, n) => { const c = guess[(n + 2) % 4]; const sgn = Math.sign(l[0] * c[0] + l[1] * c[1] + l[2]); return sgn * (l[0] * x + l[1] * y + l[2]) > -0.06 * w; });
    const comp = new Uint8Array(w * h), stack = [];
    let sx = Math.round(Math.max(0, Math.min(w - 1, cx))), sy = Math.round(Math.max(0, Math.min(h - 1, cy)));
    // Find a seed near the centre.
    for (let rad = 0; rad < 200 && !mask[sy * w + sx]; rad += 4) { sx = Math.round(Math.max(0, Math.min(w - 1, cx + rad))); sy = Math.round(Math.max(0, Math.min(h - 1, cy + rad * .3))); }
    if (mask[sy * w + sx]) { comp[sy * w + sx] = 1; stack.push(sy * w + sx); }
    while (stack.length) { const p = stack.pop(), x = p % w, y = (p / w) | 0; for (const [dx, dy] of [[1, 0], [-1, 0], [0, 1], [0, -1]]) { const nx = x + dx, ny = y + dy; if (nx < 0 || ny < 0 || nx >= w || ny >= h) continue; const q = ny * w + nx; if (comp[q] || !mask[q] || !inside(nx, ny)) continue; comp[q] = 1; stack.push(q); } }
    // Boundary points away from the image border.
    const bound = [];
    for (let y = 3; y < h - 3; y += 1) for (let x = 3; x < w - 3; x += 1) { const p = y * w + x; if (!comp[p]) continue; if (!comp[p - 1] || !comp[p + 1] || !comp[p - w] || !comp[p + w]) bound.push([x, y]); }
    const tol = Math.max(3, w * .003), band = w * (variant === 'mobile' ? .09 : .05), span = variant === 'mobile' ? .18 : .3;
    const lines = edges.map((l, n) => {
      const a = guess[n], b2 = guess[(n + 1) % 4], len = Math.hypot(b2[0] - a[0], b2[1] - a[1]);
      const pts = bound.filter(pt => { if (dist(l, pt) > band) return false; const t = ((pt[0] - a[0]) * (b2[0] - a[0]) + (pt[1] - a[1]) * (b2[1] - a[1])) / (len * len); return t > .06 && t < .94; });
      const f = ransac(pts, [l[0], l[1]], tol);
      if (!f) return null;
      // Sanity: the refit must stay near the expected edge and be supported along it.
      if (dist(f, a) > band * .6 || dist(f, b2) > band * .6) return null;
      const sup = pts.filter(pt => dist(f, pt) < tol).map(pt => ((pt[0] - a[0]) * (b2[0] - a[0]) + (pt[1] - a[1]) * (b2[1] - a[1])) / (len * len));
      if (Math.max(...sup) - Math.min(...sup) < span) return null;
      return f;
    });
    const quad = guess.map(p => p.slice());
    let ok = 0;
    for (let n = 0; n < 4; n++) { const l1 = lines[(n + 3) % 4] || edges[(n + 3) % 4], l2 = lines[n] || edges[n]; quad[n] = cross(l1, l2); }
    lines.forEach(l => { if (l) ok++; });
    if (ok >= 2) stats.fitted++; else stats.fallback++;
    const src = quad.map(([x, y]) => [Math.round(x / k), Math.round(y / k)]);
    out[i] = { quad: src, edges: lines.map(Boolean) };
    prev = src;
  }
  // Temporal smoothing: 5-frame median then 5-frame mean, per coordinate.
  const keys = Object.keys(out).map(Number).sort((a, b) => a - b);
  const raw = keys.map(i => out[i].quad);
  const med = raw.map((_, i) => raw[i].map((pt, n) => pt.map((_, a) => { const w = []; for (let d = -2; d <= 2; d++) { const j = Math.max(0, Math.min(raw.length - 1, i + d)); w.push(raw[j][n][a]); } w.sort((x, y) => x - y); return w[2]; })));
  keys.forEach((k, i) => { out[k].quad = med[i].map((pt, n) => pt.map((_, a) => { let s = 0, c = 0; for (let d = -2; d <= 2; d++) { const j = i + d; if (j < 0 || j >= med.length) continue; s += med[j][n][a]; c++; } return Math.round(s / c); })); });
  return { out, stats };
}
(async () => {
  const result = {};
  for (const v of ['desktop', 'mobile']) { const { out, stats } = await track(v); result[v] = out; console.log(v, JSON.stringify(stats)); }
  fs.writeFileSync(process.argv[2] || 'screentrack.json', JSON.stringify(result));
})();
