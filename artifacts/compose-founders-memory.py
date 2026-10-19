"""Assemble the two Higgsfield shots without morphing identities between eras."""
from pathlib import Path
import subprocess
import json
import sys
import io

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT / 'artifacts/media-runtime'))
import imageio_ffmpeg
from PIL import Image, ImageDraw

FFMPEG = imageio_ffmpeg.get_ffmpeg_exe()
MEDIA = ROOT / 'assets/img/home/founders/video'
OUT = MEDIA / 'founders-2004-to-now.mp4'
FILTER = (
    "[0:v]scale=1920:1080:force_original_aspect_ratio=increase,crop=1920:1080,"
    "trim=duration=5,setsar=1,setpts=PTS-STARTPTS,"
    "hue=s='clip((t-0.5)/2.5,0,1)',fps=24,settb=1/24[past];"
    "[1:v]scale=1920:1080:force_original_aspect_ratio=increase,crop=1920:1080,"
    "trim=duration=5,setsar=1,setpts=PTS-STARTPTS,fps=24,settb=1/24[now];"
    "[past][now]xfade=transition=fade:duration=1:offset=4,format=yuv420p[out]"
)
subprocess.run([
    FFMPEG, '-v', 'error', '-y', '-i', str(MEDIA / 'founders-2004-generated.mp4'),
    '-i', str(MEDIA / 'founders-now-generated.mp4'), '-filter_complex', FILTER,
    '-map', '[out]', '-an', '-c:v', 'libx264', '-crf', '18', '-preset', 'medium',
    '-movflags', '+faststart', '-t', '9', str(OUT)
], check=True)

def frame(file, time):
    result = subprocess.run([
        FFMPEG, '-v', 'error', '-ss', str(time), '-i', str(file),
        '-frames:v', '1', '-f', 'image2pipe', '-vcodec', 'png', '-'
    ], check=True, stdout=subprocess.PIPE)
    return Image.open(io.BytesIO(result.stdout)).convert('RGB')

samples = [0, 1, 2, 3.5, 4.5, 5.5, 6.5, 7.5, 8.95]
sheet = Image.new('RGB', (1440, 3 * 294), '#101114')
draw = ImageDraw.Draw(sheet)
for index, time in enumerate(samples):
    im = frame(OUT, time)
    im.thumbnail((480, 270))
    x, y = index % 3 * 480, index // 3 * 294
    sheet.paste(im, (x, y))
    draw.text((x + 12, y + 276), f'{time:.2f} s', fill='#f8f8fa')
sheet.save(ROOT / 'artifacts/founders-2004-to-now-storyboard.jpg', quality=90)
frame(OUT, 0).save(MEDIA / 'founders-2004-to-now-poster.webp', quality=88)
frame(OUT, 8.95).save(MEDIA / 'founders-now-final.webp', quality=88)

# Keep all three people together in portrait, with headroom for the site's HTML copy.
subprocess.run([
    FFMPEG, '-v', 'error', '-y', '-i', str(OUT), '-vf',
    'crop=1120:1080:780:0,scale=720:694,pad=720:1280:0:586:color=0x101114',
    '-an', '-c:v', 'libx264', '-crf', '18', '-preset', 'medium',
    '-movflags', '+faststart', str(MEDIA / 'founders-2004-to-now-mobile.mp4')
], check=True)
frame(MEDIA / 'founders-2004-to-now-mobile.mp4', 8.95).save(MEDIA / 'founders-now-mobile-final.webp', quality=88)

# Inspect each generated shot separately at start, midpoint, and end.
for name in ['2004', 'now']:
    film = MEDIA / f'founders-{name}-generated.mp4'
    strip = Image.new('RGB', (1440, 270), '#101114')
    for index, time in enumerate([0, 2.5, 4.95]):
        im = frame(film, time)
        im.thumbnail((480, 270))
        strip.paste(im, (index * 480, 0))
    strip.save(ROOT / f'artifacts/founders-{name}-generated-check.jpg', quality=90)

reader = imageio_ffmpeg.read_frames(str(OUT), pix_fmt='rgb24')
metadata = next(reader)
count = sum(1 for _ in reader)
assert metadata['size'] == (1920, 1080), metadata
assert metadata['fps'] == 24, metadata
assert 215 <= count <= 217, count
report = {'metadata': metadata, 'frameCount': count, 'bytes': OUT.stat().st_size,
          'dissolve': {'start': 4, 'duration': 1},
          'colorReturn': {'start': .5, 'duration': 2.5}}
(MEDIA / 'founders-2004-to-now-info.json').write_text(json.dumps(report, indent=2), encoding='utf8')
print(json.dumps(report))
