// Builds the chapter-04 frame sequence: the bridge clip from the chapter-03 counter to K0
// (or a 24-frame dissolve when it is missing), then rot-1, rot-2, rot-3 back to back (the first frame of rot-2/3 is
// dropped because it repeats the previous clip's last frame).
// Mobile reuses the desktop clips: each frame is scaled and cropped around the phone and
// placed at the bottom of a 720x1280 page-graphite canvas, leaving room for the copy.
// Usage: node prepare-chapter-04.js <desktop|mobile>
// Needs sharp and ffmpeg-static resolvable from NODE_PATH.
const sharp = require('sharp'), fs = require('fs'), path = require('path'), { execFileSync } = require('child_process');
const ffmpeg = require('ffmpeg-static');
const variant = process.argv[2] || 'desktop';
const ROOT = path.resolve(__dirname, '../assets/img/home/cinematic-v2');
const DIR = path.join(ROOT, 'chapter-04', variant);
const SRC = path.join(ROOT, 'chapter-04', 'desktop');
const BG = { r: 14, g: 15, b: 19 };
const OUT = path.join(DIR, 'sequence');
const [W, H] = variant === 'desktop' ? [1440, 810] : [720, 1280];
const TMP = path.join(require('os').tmpdir(), 'ks-ch04-' + variant);
fs.rmSync(TMP, { recursive: true, force: true }); fs.mkdirSync(TMP, { recursive: true });
fs.rmSync(OUT, { recursive: true, force: true }); fs.mkdirSync(OUT, { recursive: true });
const cover = file => sharp(file).resize(W, H, { fit: 'cover' }).removeAlpha().raw().toBuffer();
// Desktop frame -> phone-centred portrait frame (same geometry as a 390x767 viewport at 0.6 of cover).
const SCALE = 1280 / 810 * 0.6, FOCUS = 0.67;
async function portrait(input) {
  const w = Math.round(1440 * SCALE), h = Math.round(810 * SCALE);
  const left = Math.max(0, Math.min(w - 720, Math.round(FOCUS * w - 360)));
  const phone = await sharp(input).resize(1440, 810, { fit: 'cover' }).resize(w, h).extract({ left, top: 0, width: 720, height: h }).png().toBuffer();
  return sharp({ create: { width: 720, height: 1280, channels: 3, background: BG } }).composite([{ input: phone, left: 0, top: 1280 - h }]).removeAlpha().raw().toBuffer();
}
const keyframe = file => variant === 'mobile' ? portrait(file) : cover(file);
(async () => {
  const frames = [];
  // 1) Bridge from the counter to the isolated phone: a generated clip per variant
  //    (bridge.mp4, already in the right format), or a 24-frame dissolve as fallback.
  const bridge = path.join(DIR, 'bridge.mp4');
  if (fs.existsSync(bridge)) {
    const dir = path.join(TMP, 'bridge'); fs.mkdirSync(dir);
    execFileSync(ffmpeg, ['-v', 'error', '-i', bridge, '-an', '-vf', `scale=${W}:${H}:force_original_aspect_ratio=increase,crop=${W}:${H}`, path.join(dir, 'f-%04d.png')]);
    fs.readdirSync(dir).sort().forEach(f => frames.push({ file: path.join(dir, f), native: true }));
  } else {
    const from = await cover(path.join(ROOT, 'chapter-03', variant, 'sequence', 'frame-0191.webp'));
    const to = await keyframe(path.join(SRC, 'k0-cracked.png'));
    for (let i = 0; i < 24; i++) {
      const t = i / 23, e = t * t * (3 - 2 * t), dip = 1 - .35 * Math.sin(Math.PI * t);
      const buf = Buffer.alloc(from.length);
      for (let p = 0; p < from.length; p++) buf[p] = Math.round((from[p] * (1 - e) + to[p] * e) * dip);
      frames.push({ raw: buf });
    }
  }
  const bridged = fs.existsSync(bridge);
  // 2) Rotations.
  for (const [n, clip] of ['rot-1', 'rot-2', 'rot-3'].entries()) {
    const dir = path.join(TMP, clip); fs.mkdirSync(dir);
    execFileSync(ffmpeg, ['-v', 'error', '-i', path.join(SRC, clip + '.mp4'), '-an', '-vf', 'scale=1440:810:force_original_aspect_ratio=increase,crop=1440:810', path.join(dir, 'f-%04d.png')]);
    const list = fs.readdirSync(dir).sort();
    (n || bridged ? list.slice(1) : list).forEach(f => frames.push({ file: path.join(dir, f) }));
  }
  let i = 0, webp = 0, avif = 0;
  const work = async () => { while (i < frames.length) { const k = i++, f = frames[k], name = 'frame-' + String(k).padStart(4, '0');
    const raw = f.raw || (variant === 'mobile' && !f.native ? await portrait(f.file) : null);
    const img = raw ? sharp(raw, { raw: { width: W, height: H, channels: 3 } }) : sharp(f.file);
    const png = await img.png().toBuffer();
    await sharp(png).webp({ quality: 80 }).toFile(path.join(OUT, name + '.webp'));
    await sharp(png).avif({ quality: 55, effort: 4 }).toFile(path.join(OUT, name + '.avif'));
    webp += fs.statSync(path.join(OUT, name + '.webp')).size; avif += fs.statSync(path.join(OUT, name + '.avif')).size; } };
  await Promise.all([work(), work(), work(), work()]);
  fs.mkdirSync(DIR, { recursive: true });
  await sharp(await keyframe(path.join(SRC, 'k3-repaired.png')), { raw: { width: W, height: H, channels: 3 } }).webp({ quality: 82 }).toFile(path.join(DIR, 'k3-repaired.webp'));
  console.log(JSON.stringify({ variant, frames: frames.length, webpKB: Math.round(webp / 1024), avifKB: Math.round(avif / 1024) }));
})();
