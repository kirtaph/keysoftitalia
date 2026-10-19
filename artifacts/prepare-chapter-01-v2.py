from pathlib import Path
import sys, subprocess, json
sys.path.insert(0, str(Path(__file__).parent / 'media-runtime'))
import imageio_ffmpeg
root = Path(__file__).resolve().parents[1]
dest = root / 'assets/img/home/cinematic-v2/chapter-01'
for variant, width in [('desktop', 1440), ('mobile', 720)]:
    folder = dest / variant / 'sequence'
    folder.mkdir(exist_ok=True)
    subprocess.run([imageio_ffmpeg.get_ffmpeg_exe(), '-hide_banner', '-loglevel', 'error',
        '-i', str(dest / variant / 'chapter-01-dynamic.mp4'), '-an', '-vf', f'fps=24,scale={width}:-2',
        '-c:v', 'libwebp', '-quality', '80', '-start_number', '0', str(folder / 'frame-%04d.webp')], check=True)
    frames = sorted(folder.glob('frame-*.webp'))
    assert len(frames) == 240
    print(variant, len(frames), sum(f.stat().st_size for f in frames))
manifest = {'version': 1, 'frameCount': 240, 'fps': 24, 'activeViewportHeights': 3.2,
    'desktopPattern': 'desktop/sequence/frame-{index}.webp', 'mobilePattern': 'mobile/sequence/frame-{index}.webp',
    'beats': [{'id':'welcome', 'scroll':[0,.16], 'frames':[0,0]},
              {'id':'approach', 'scroll':[.16,.94], 'frames':[0,144]},
              {'id':'settle', 'scroll':[.94,1], 'frames':[144,239]}]}
(dest / 'manifest.json').write_text(json.dumps(manifest, indent=2), encoding='utf-8')
