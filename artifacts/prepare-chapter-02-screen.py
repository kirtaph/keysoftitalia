from pathlib import Path
import sys, subprocess, json
sys.path.insert(0, str(Path(__file__).parent / 'media-runtime'))
import imageio_ffmpeg
root = Path(__file__).resolve().parents[1]
dest = root / 'assets/img/home/cinematic-v2'
for variant, width in [('desktop', 1440), ('mobile', 720)]:
    source = dest / 'chapter-02' / variant
    folder = source / 'sequence'
    folder.mkdir(exist_ok=True)
    subprocess.run([imageio_ffmpeg.get_ffmpeg_exe(), '-hide_banner', '-loglevel', 'error',
        '-i', str(source / 'chapter-02-screen.mp4'), '-an', '-vf', f'fps=24,scale={width}:-2',
        '-c:v', 'libwebp', '-quality', '80', '-start_number', '0', str(folder / 'frame-%04d.webp')], check=True)
    frames = sorted(folder.glob('frame-*.webp'))
    assert len(frames) == 192
    print(variant, len(frames), sum(f.stat().st_size for f in frames))
manifest = {
    'version': 2, 'frameCount': 432, 'fps': 24, 'activeViewportHeights': 6.4,
    'entryProgress': .08,
    'sources': [
        {'start': 0, 'end': 239, 'desktopPattern': 'chapter-01/desktop/sequence/frame-{index}.webp', 'mobilePattern': 'chapter-01/mobile/sequence/frame-{index}.webp'},
        {'start': 240, 'end': 431, 'desktopPattern': 'chapter-02/desktop/sequence/frame-{index}.webp', 'mobilePattern': 'chapter-02/mobile/sequence/frame-{index}.webp'},
    ],
    'beats': [
        {'id': 'welcome', 'scroll': [0, .08], 'frames': [0, 0]},
        {'id': 'approach', 'scroll': [.08, .45], 'frames': [0, 144]},
        {'id': 'settle', 'scroll': [.45, .48], 'frames': [144, 239]},
        {'id': 'computer-link', 'scroll': [.48, .49], 'frames': [240, 252]},
        {'id': 'computer', 'scroll': [.49, .88], 'frames': [252, 431]},
        {'id': 'computer-reading', 'scroll': [.88, 1], 'frames': [431, 431]},
    ],
}
(dest / 'manifest.json').write_text(json.dumps(manifest, indent=2), encoding='utf-8')
