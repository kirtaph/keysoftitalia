"""Master and inspect one continuous Higgsfield ageing take; no crossfades or cuts."""
from pathlib import Path
import io
import json
import subprocess
import sys

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT / 'artifacts/media-runtime'))
import imageio_ffmpeg
from PIL import Image, ImageDraw

FFMPEG = imageio_ffmpeg.get_ffmpeg_exe()
DIR = ROOT / 'assets/img/home/founders/video/morph'
RAW = DIR / 'aging-generated.mp4'
OUT = DIR / 'founders-aging-v2.mp4'
subprocess.run([
    FFMPEG, '-v', 'error', '-y', '-i', str(RAW), '-vf',
    "trim=start=2.6:end=6.4,setpts=(PTS-STARTPTS)*2.63157895,"
    "minterpolate=fps=24:mi_mode=mci:mc_mode=aobmc:me_mode=bidir:vsbmc=1,"
    "tpad=stop_mode=clone:stop_duration=0.3,hue=s='clip((t-0.4)/2.2,0,1)'", '-t', '10', '-an',
    '-c:v', 'libx264', '-crf', '18', '-preset', 'medium', '-movflags', '+faststart', str(OUT)
], check=True)

def frame(file, time):
    r = subprocess.run([FFMPEG, '-v', 'error', '-ss', str(time), '-i', str(file),
        '-frames:v', '1', '-f', 'image2pipe', '-vcodec', 'png', '-'],
        check=True, stdout=subprocess.PIPE)
    return Image.open(io.BytesIO(r.stdout)).convert('RGB')

times = [0, .75, 1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.25, 9.95]
sheet = Image.new('RGB', (1440, 4 * 294), '#101114')
draw = ImageDraw.Draw(sheet)
for n, time in enumerate(times):
    im = frame(OUT, time)
    im.thumbnail((480, 270))
    x, y = n % 3 * 480, n // 3 * 294
    sheet.paste(im, (x, y))
    draw.text((x + 10, y + 276), f'{time:.2f}s', fill='white')
sheet.save(ROOT / 'artifacts/founders-aging-v2-storyboard.jpg', quality=92)

closeups = Image.new('RGB', (1800, 560), '#101114')
draw = ImageDraw.Draw(closeups)
for n, time in enumerate([0, 2, 4, 6, 8, 9.95]):
    im = frame(OUT, time)
    w, h = im.size
    im = im.crop((int(w * .12), 0, int(w * .88), int(h * .62)))
    im.thumbnail((600, 255))
    x, y = n % 3 * 600, n // 3 * 280
    closeups.paste(im, (x, y))
    draw.text((x + 10, y + 260), f'{time:.2f}s', fill='white')
closeups.save(ROOT / 'artifacts/founders-aging-v2-faces.jpg', quality=95)
frame(OUT, 0).save(DIR / 'aging-poster.webp', quality=90)
frame(OUT, 9.95).save(DIR / 'aging-final.webp', quality=90)

reader = imageio_ffmpeg.read_frames(str(OUT), pix_fmt='rgb24')
metadata = next(reader)
count = sum(1 for _ in reader)
report = {'metadata': metadata, 'frames': count, 'bytes': OUT.stat().st_size,
          'editing': 'Continuous source section 2.6–6.4s retimed to 10s with motion-compensated interpolation and saturation ramp; no crossfades or cuts between shots.'}
(DIR / 'aging-info.json').write_text(json.dumps(report, indent=2), encoding='utf8')
print(json.dumps(report))
