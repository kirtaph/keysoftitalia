const { chromium } = require('playwright-core');
const URL = process.env.URL || 'http://localhost:8000/';
const profiles = [
  { name: 'desktop', viewport: { width: 1440, height: 900 }, dpr: 1, cpu: 1, net: null },
  { name: 'mobile-4G-cpu4x', viewport: { width: 390, height: 844 }, dpr: 3, cpu: 4, mobile: true,
    net: { offline: false, latency: 150, downloadThroughput: 9e6 / 8, uploadThroughput: 1.5e6 / 8 } },
];
const init = () => {
  window.__m = { lcp: [], cls: 0, lt: [], loaf: [], raf: [], lag: [] };
  new PerformanceObserver(l => l.getEntries().forEach(e => __m.lcp.push({ t: e.startTime, el: e.element ? e.element.tagName + '.' + e.element.className : '', url: (e.url || '').split('/').pop() }))).observe({ type: 'largest-contentful-paint', buffered: true });
  new PerformanceObserver(l => l.getEntries().forEach(e => { if (!e.hadRecentInput) __m.cls += e.value; })).observe({ type: 'layout-shift', buffered: true });
  new PerformanceObserver(l => l.getEntries().forEach(e => __m.lt.push(e.duration))).observe({ type: 'longtask', buffered: true });
  new PerformanceObserver(l => l.getEntries().forEach(e => __m.loaf.push({ d: e.duration, block: e.blockingDuration, scripts: e.scripts.map(s => (s.sourceURL || '').split('/').pop() + ':' + s.invoker + ':' + Math.round(s.duration)) }))).observe({ type: 'long-animation-frame', buffered: true });
};
(async () => {
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const out = {};
  for (const p of profiles) {
    const ctx = await browser.newContext({ viewport: p.viewport, deviceScaleFactor: p.dpr, serviceWorkers: 'block', isMobile: !!p.mobile, hasTouch: !!p.mobile });
    const page = await ctx.newPage();
    const cdp = await ctx.newCDPSession(page);
    await cdp.send('Network.enable');
    await cdp.send('Network.setCacheDisabled', { cacheDisabled: true });
    if (p.net) await cdp.send('Network.emulateNetworkConditions', p.net);
    if (p.cpu > 1) await cdp.send('Emulation.setCPUThrottlingRate', { rate: p.cpu });
    await cdp.send('Performance.enable');
    let bytes = 0, frameReq = 0, frameBytes = 0; const big = [];
    cdp.on('Network.loadingFinished', e => { bytes += e.encodedDataLength; });
    const reqUrl = new Map();
    cdp.on('Network.responseReceived', e => reqUrl.set(e.requestId, e.response.url));
    cdp.on('Network.loadingFinished', e => { const u = reqUrl.get(e.requestId) || ''; if (/sequence\/frame/.test(u)) { frameReq++; frameBytes += e.encodedDataLength; } if (e.encodedDataLength > 200e3) big.push(u.split('/').slice(-2).join('/') + ' ' + Math.round(e.encodedDataLength / 1024) + 'KB'); });
    await page.addInitScript(init);
    const t0 = Date.now();
    await page.goto(URL, { waitUntil: 'load' });
    await page.waitForFunction(() => document.documentElement.classList.contains('welcome-ready'), null, { timeout: 30000 });
    const readyMs = Date.now() - t0;
    await page.waitForTimeout(1500);
    const load = await page.evaluate(() => { const n = performance.getEntriesByType('navigation')[0]; const fcp = performance.getEntriesByName('first-contentful-paint')[0]; return { ttfb: n.responseStart, fcp: fcp && fcp.startTime, lcp: __m.lcp.at(-1), cls: __m.cls, tbt: __m.lt.reduce((a, d) => a + Math.max(0, d - 50), 0), renderer: document.getElementById('home-cinematic').dataset.renderer, docH: document.documentElement.scrollHeight }; });
    const loadBytes = bytes, loadFrames = frameReq;
    // Steady scroll through the pinned story: ~ 1 viewport per second.
    await page.evaluate(async () => { const man = await (await fetch(document.getElementById('home-cinematic').dataset.manifest)).json(); const hdr = document.querySelector('.header'); const st = document.querySelector('.cinematic-stage');
      window.__exp = () => { const s = document.getElementById('home-cinematic'); const top = hdr ? hdr.getBoundingClientRect().height : 0; const r = s.getBoundingClientRect(); const pr = Math.max(0, Math.min(1, (top - r.top) / Math.max(1, s.offsetHeight - st.offsetHeight))); const b = man.beats.find(b => pr <= b.scroll[1]) || man.beats.at(-1); const l = Math.max(0, Math.min(1, (pr - b.scroll[0]) / (b.scroll[1] - b.scroll[0]))); return Math.round(b.frames[0] + l * (b.frames[1] - b.frames[0])); };
      __m.lt = []; __m.loaf = []; __m.raf = []; __m.lag = []; let last = performance.now(); const s = document.getElementById('home-cinematic');
      (function tick(t) { __m.raf.push(t - last); last = t; __m.lag.push(Math.abs(__exp() - Number(s.dataset.frame))); if (!__m.stop) requestAnimationFrame(tick); })(last); });
    const m0 = (await cdp.send('Performance.getMetrics')).metrics;
    const story = await page.evaluate(() => { const s = document.getElementById('home-cinematic'); return s.offsetTop + s.offsetHeight; });
    const step = Math.round(p.viewport.height / 15);
    for (let y = 0; y < story; y += step) { await page.mouse.wheel(0, step); await page.waitForTimeout(66); }
    await page.waitForTimeout(800);
    const m1 = (await cdp.send('Performance.getMetrics')).metrics;
    const md = k => Math.round(m1.find(x => x.name === k).value - m0.find(x => x.name === k).value);
    const scroll = await page.evaluate(() => { __m.stop = true; const r = __m.raf.slice(1).sort((a, b) => a - b); const pct = q => r[Math.floor(r.length * q)];
      return { rafFrames: r.length, p50: pct(.5), p95: pct(.95), p99: pct(.99), over50: r.filter(x => x > 50).length, longTasks: __m.lt.length, loafTop: __m.loaf.sort((a, b) => b.d - a.d).slice(0, 4), staleFramesPct: Math.round(100 * __m.lag.filter(x => x > 2).length / __m.lag.length), maxLag: Math.max(...__m.lag), lastFrame: document.getElementById('home-cinematic').dataset.frame }; });
    // Fast jump: how long until the target frame actually appears?
    await page.evaluate(() => scrollTo(0, 0)); await page.waitForTimeout(600);
    const jump = await page.evaluate(async () => { const s = document.getElementById('home-cinematic'); const y = s.offsetTop + (s.offsetHeight - innerHeight) * .7; const t = performance.now(); scrollTo(0, y); await new Promise(r => setTimeout(r, 50));
      const want = null; let first = s.dataset.frame; while (performance.now() - t < 5000) { await new Promise(r => requestAnimationFrame(r)); const f = s.dataset.frame; if (f !== first && Math.abs(f - first) < 1 && false) break; if (performance.now() - t > 100 && s.dataset.frame === s.dataset.frame) {} }
      return { frameAfter5s: s.dataset.frame }; });
    out[p.name] = { readyMs, load, loadKB: Math.round(loadBytes / 1024), loadFrames, scroll, totalKB: Math.round(bytes / 1024), frameReq, frameKB: Math.round(frameBytes / 1024), big: [...new Set(big)],
      layouts: md('LayoutCount'), styleRecalcs: md('RecalcStyleCount'), layoutMs: md('LayoutDuration') * 1000, styleMs: md('RecalcStyleDuration') * 1000, scriptMs: md('ScriptDuration') * 1000, jsHeapMB: Math.round(m1.find(x => x.name === 'JSHeapUsedSize').value / 1e6) };
    await page.screenshot({ path: `artifacts/perf-${p.name}.png` });
    await ctx.close();
  }
  console.log(JSON.stringify(out, null, 1));
  await browser.close();
})().catch(e => { console.error(e); process.exit(1); });
