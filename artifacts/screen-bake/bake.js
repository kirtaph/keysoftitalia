// Screen replacement baked into the film frames (chapter 02).
// For each source frame: frame image + UI projected on the tracked display quad,
// blended with the frame's own light, rendered at the frame's native size.
const { chromium } = require('C:/Users/patri/AppData/Local/Temp/claude/D--webprojects-keysoftitalia/ad5e8f0a-296a-416d-8d24-02dcd8cc5243/scratchpad/pw/node_modules/playwright-core');
const fs = require('fs'), path = require('path'), { pathToFileURL } = require('url');
const [variant = 'desktop', from = '34', to = '150', step = '1', outDir = 'out'] = process.argv.slice(2);
const SRC = `D:/webprojects/keysoftitalia/assets/img/home/cinematic-v2/chapter-02/${variant}/sequence/`;
const track = JSON.parse(fs.readFileSync('C:/Users/patri/AppData/Local/Temp/claude/D--webprojects-keysoftitalia/ad5e8f0a-296a-416d-8d24-02dcd8cc5243/scratchpad/pw/screentrack.json', 'utf8'))[variant === 'mobile' ? 'mobile2' : 'desktop'];
fs.mkdirSync(outDir, { recursive: true });
const page = fs.readFileSync(path.join(__dirname, 'ui.html'), 'utf8');
(async () => {
  const b = await chromium.launch({ channel: 'chrome' });
  const W = variant === 'desktop' ? 1440 : 720, H = variant === 'desktop' ? 810 : 1280, unit = variant === 'desktop' ? 1920 : 1080;
  const p = await b.newPage({ viewport: { width: W, height: H } });
  fs.writeFileSync(path.join(__dirname, 'ui-run.html'), page.replace('__VARIANT__', variant));
  await p.goto(pathToFileURL(path.join(__dirname, 'ui-run.html')).href, { waitUntil: 'load' });
  await p.evaluate(() => document.fonts.ready);
  for (let i = +from; i <= +to; i += +step) {
    const name = `frame-${String(i).padStart(4, '0')}`;
    const quad = track[i].quad.map(([x, y]) => [x * W / unit, y * H / (variant === 'desktop' ? 1080 : 1920)]);
    await p.evaluate(({ src, quad, i }) => window.compose(src, quad, i), { src: pathToFileURL(SRC + name + '.webp').href, quad, i });
    await p.screenshot({ path: `${outDir}/${name}.png` });
  }
  await b.close();
})();
