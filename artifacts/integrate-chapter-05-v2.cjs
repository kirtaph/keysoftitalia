// Append the approved fifth chapter while preserving the first four chapters' scroll distances.
const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const media = path.join(root, 'assets/img/home/cinematic-v2');
const manifestPath = path.join(media, 'manifest.json');
const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
if (manifest.sources.some(source => source.desktopPattern.includes('chapter-05/'))) throw new Error('Chapter 05 already integrated');
const desktop = JSON.parse(fs.readFileSync(path.join(media, 'chapter-05/desktop/sequence-info.json'), 'utf8'));
const mobile = JSON.parse(fs.readFileSync(path.join(media, 'chapter-05/mobile/sequence-info.json'), 'utf8'));
if (desktop.frames !== mobile.frames) throw new Error('Desktop/mobile frame counts differ');
fs.writeFileSync(path.join(__dirname, 'manifest-before-chapter-05-v2.json'), JSON.stringify(manifest, null, 2) + '\n', { flag: 'wx' });
const oldHeight = manifest.activeViewportHeights, newHeight = oldHeight + 3;
const ratio = oldHeight / newHeight;
const scale = n => Number((n * ratio).toFixed(8));
for (const beat of manifest.beats) beat.scroll = beat.scroll.map(scale);
for (const timing of Object.values(manifest.copy)) {
  timing.visible = timing.visible.map(scale);
  for (const kind of ['enter', 'exit']) if (timing[kind]) for (const key of ['at', 'span', 'stagger']) if (key in timing[kind]) timing[kind][key] = scale(timing[kind][key]);
}
manifest.copy['repair-3'].exit = { at: 19.5 / newHeight, span: .4 / newHeight, stagger: .02 / newHeight };
const start = manifest.frameCount, end = start + desktop.frames - 1;
manifest.sources.push({ start, end, desktopPattern: 'chapter-05/desktop/sequence/frame-{index}.webp', mobilePattern: 'chapter-05/mobile/sequence/frame-{index}.webp' });
manifest.beats.push({ id: 'choosing-reveal', scroll: [ratio, (oldHeight + 2) / newHeight], frames: [start, end] }, { id: 'choosing-reading', scroll: [(oldHeight + 2) / newHeight, 1], frames: [end, end] });
manifest.copy.choosing = { visible: [(oldHeight + 1.6) / newHeight, 1], minFrame: start + 157, side: 'left', enter: { at: (oldHeight + 1.6) / newHeight, span: .5 / newHeight, stagger: .05 / newHeight } };
manifest.frameCount = end + 1;
manifest.activeViewportHeights = newHeight;
manifest.entryProgress = scale(manifest.entryProgress);
manifest.outro = { at: (newHeight - .35) / newHeight, span: .35 / newHeight };
manifest.frameVersion = '2026-10-15-ch05-selection';
fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2) + '\n');
const includePath = path.join(root, 'includes/home-cinematic-v2.php');
let include = fs.readFileSync(includePath, 'utf8');
include = include.replace(/data-(goto|from|to)="([\d.]+)"/g, (_, attr, value) => `data-${attr}="${scale(Number(value))}"`);
include = include.replace('   </ol>', `    <li><button type="button" data-goto="${22.45 / newHeight}" data-from="${ratio}" data-to="1.005"><b>05</b> <span>Scegliere</span></button></li>\n   </ol>`);
fs.writeFileSync(includePath, include);
console.log(JSON.stringify({ frames: manifest.frameCount, viewportHeights: newHeight, appendedFrames: desktop.frames }));
