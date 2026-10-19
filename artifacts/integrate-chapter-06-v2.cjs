// Documentary continuation: native HTML on the same stage, no additional generated frames.
const fs = require('node:fs'), path = require('node:path');
const root = path.resolve(__dirname, '..');
const file = path.join(root, 'assets/img/home/cinematic-v2/manifest.json');
const manifest = JSON.parse(fs.readFileSync(file, 'utf8'));
if (manifest.copy.people) throw new Error('People chapter already integrated');
fs.writeFileSync(path.join(__dirname, 'manifest-before-chapter-06-v2.json'), JSON.stringify(manifest, null, 2) + '\n', { flag: 'wx' });
const oldHeight = manifest.activeViewportHeights, height = oldHeight + 2.5;
const ratio = oldHeight / height, scale = n => Number((n * ratio).toFixed(8));
for (const beat of manifest.beats) beat.scroll = beat.scroll.map(scale);
for (const timing of Object.values(manifest.copy)) {
  timing.visible = timing.visible.map(scale);
  for (const kind of ['enter', 'exit']) if (timing[kind]) for (const key of ['at', 'span', 'stagger']) if (key in timing[kind]) timing[kind][key] = scale(timing[kind][key]);
}
manifest.copy.choosing.exit = { at: (oldHeight - .5) / height, span: .35 / height, stagger: .02 / height };
manifest.beats.push({ id: 'people', scroll: [ratio, 1], frames: [manifest.frameCount - 1, manifest.frameCount - 1] });
manifest.copy.people = { visible: [(oldHeight + .15) / height, 1], minFrame: manifest.frameCount - 1, side: 'left', enter: { at: (oldHeight + .15) / height, span: .6 / height, stagger: .1 / height } };
manifest.entryProgress = scale(manifest.entryProgress);
manifest.activeViewportHeights = height;
manifest.filmOutro = { at: ratio, span: .55 / height };
manifest.outro = { at: (height - .35) / height, span: .35 / height };
// Frame URLs remain stable: this chapter adds HTML, not media.
fs.writeFileSync(file, JSON.stringify(manifest, null, 2) + '\n');
const includePath = path.join(root, 'includes/home-cinematic-v2.php');
let include = fs.readFileSync(includePath, 'utf8');
include = include.replace(/data-(goto|from|to)="([\d.]+)"/g, (_, attr, value) => `data-${attr}="${scale(Number(value))}"`);
// The preceding chapter's final marker ends exactly where the documentary begins.
include = include.replace(/(data-goto="[\d.]+" data-from="[\d.]+" data-to=")[\d.]+("><b>05)/, `$1${ratio}$2`);
include = include.replace('   </ol>', `    <li><button type="button" data-goto="${(oldHeight + 1.35) / height}" data-from="${ratio}" data-to="1.005"><b>06</b> <span>Le persone</span></button></li>\n   </ol>`);
fs.writeFileSync(includePath, include);
console.log(JSON.stringify({ chapters: 6, frames: manifest.frameCount, viewportHeights: height }));
