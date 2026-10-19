from pathlib import Path
import sys,json
sys.path.insert(0,str(Path('artifacts/media-runtime').resolve()))
import imageio_ffmpeg
from PIL import Image,ImageDraw,ImageChops,ImageStat
root=Path('.').resolve();out=root/'artifacts/chapter-01-v2-review';out.mkdir(exist_ok=True)
results={}
for variant in ['desktop','mobile']:
 folder=root/'assets/img/home/cinematic-v2/chapter-01'/variant
 reader=imageio_ffmpeg.read_frames(str(folder/'chapter-01-dynamic.mp4'),pix_fmt='rgb24');meta=next(reader);samples=[]
 interval=max(1,round(meta['fps']/2))
 for i,data in enumerate(reader):
  frame=Image.frombytes('RGB',meta['size'],data)
  if i==0:first=frame.copy()
  if i%interval==0:samples.append((i,frame.copy()))
  if i in [round(meta['fps']*2),round(meta['fps']*4),round(meta['fps']*6)]:frame.save(out/f'{variant}-{i:04d}.png')
  last=frame.copy()
 samples.append((i,last));first.save(out/f'{variant}-actual-first.png');last.save(folder/'chapter-01-actual-last.png')
 tw,th=(420,236) if variant=='desktop' else (235,418)
 rows=(len(samples)+3)//4;sheet=Image.new('RGB',(tw*4,(th+24)*rows),'#101114');draw=ImageDraw.Draw(sheet)
 for n,(idx,f) in enumerate(samples):
  x=n%4*tw;y=n//4*(th+24);sheet.paste(f.resize((tw,th)),(x,y));draw.text((x+8,y+th+4),f'{idx/meta["fps"]:.2f}s / {idx}',fill='white')
 sheet.save(out/f'{variant}-contact-sheet.jpg')
 def delta(frame,path):
  ref=Image.open(path).convert('RGB').resize((320,180));sample=frame.resize((320,180));return sum(ImageStat.Stat(ImageChops.difference(sample,ref)).mean)/3
 results[variant]={'metadata':meta,'frames':i+1,'bytes':(folder/'chapter-01-dynamic.mp4').stat().st_size,'firstMeanRGBDelta':delta(first,folder/('first-frame.png' if variant=='desktop' else 'first-frame-v2.png')),'lastMeanRGBDelta':delta(last,folder/'last-frame-dynamic.png')}
(out/'inspection.json').write_text(json.dumps(results,indent=2),encoding='utf-8');print(json.dumps(results))
