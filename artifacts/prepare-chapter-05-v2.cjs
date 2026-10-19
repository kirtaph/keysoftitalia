// Usage: NODE_PATH=<sharp modules> node artifacts/prepare-chapter-05-v2.cjs <desktop|mobile>
// FFMPEG_PATH may override the existing project's media runtime.
const fs = require('node:fs');
const path = require('node:path');
const { execFileSync } = require('node:child_process');
const sharp = require('sharp');
const root = path.resolve(__dirname, '..');
const variant = process.argv[2];
if (!['desktop', 'mobile'].includes(variant)) throw new Error('Choose desktop or mobile');
const ffmpeg = process.env.FFMPEG_PATH || path.join(__dirname, 'media-runtime/imageio_ffmpeg/binaries/ffmpeg-win-x86_64-v7.1.exe');
const dir = path.join(root, 'assets/img/home/cinematic-v2/chapter-05', variant);
const out = path.join(dir, 'sequence');
const [width, height] = variant === 'desktop' ? [1440, 810] : [720, 1280];
fs.mkdirSync(out, { recursive: true });
execFileSync(ffmpeg, ['-v', 'error', '-y', '-i', path.join(dir, 'selection.mp4'), '-an', '-vf', `fps=24,scale=${width}:${height}:force_original_aspect_ratio=increase,crop=${width}:${height}`, '-c:v', 'libwebp', '-quality', '80', '-start_number', '0', path.join(out, 'frame-%04d.webp')]);
(async () => {
  const files = fs.readdirSync(out).filter(f => /^frame-\d{4}\.webp$/.test(f)).sort();
  if (files.length < 24) throw new Error('Incomplete sequence');
  // Match the exact preceding frame, avoiding a codec-dependent jump at the cut.
  await sharp(path.join(root, 'assets/img/home/cinematic-v2/chapter-04', variant, 'sequence/frame-0768.webp')).resize(width, height).webp({ quality: 80 }).toFile(path.join(out, files[0]));
  let cursor = 0;
  async function worker() {
    while (cursor < files.length) {
      const file = files[cursor++];
      await sharp(path.join(out, file)).avif({ quality: 55, effort: 4 }).toFile(path.join(out, file.replace('.webp', '.avif')));
    }
  }
  await Promise.all([worker(), worker(), worker(), worker()]);
  await sharp(path.join(out, files.at(-1))).webp({ quality: 82 }).toFile(path.join(dir, 'selection-poster.webp'));
  const indices = [...new Set([0, 24, 48, 72, 96, 120, 144, 168, files.length - 1].filter(i => i < files.length))];
  const thumbW = variant === 'desktop' ? 360 : 180;
  const thumbH = variant === 'desktop' ? 203 : 320;
  const tiles = await Promise.all(indices.map(async (i, n) => ({ input: await sharp(path.join(out, files[i])).resize(thumbW, thumbH).png().toBuffer(), left: (n % 3) * thumbW, top: Math.floor(n / 3) * thumbH })));
  await sharp({ create: { width: thumbW * 3, height: Math.ceil(indices.length / 3) * thumbH, channels: 3, background: '#101114' } }).composite(tiles).jpeg({ quality: 85 }).toFile(path.join(__dirname, `chapter-05-v2-${variant}-contact-sheet.jpg`));
  const total = ext => files.reduce((sum, f) => sum + fs.statSync(path.join(out, f.replace('.webp', ext))).size, 0);
  const report = { variant, frames: files.length, fps: 24, width, height, webpBytes: total('.webp'), avifBytes: total('.avif'), storyboardIndices: indices };
  fs.writeFileSync(path.join(dir, 'sequence-info.json'), JSON.stringify(report, null, 2) + '\n');
  console.log(JSON.stringify(report));
})().catch(error => { console.error(error); process.exitCode = 1; });
