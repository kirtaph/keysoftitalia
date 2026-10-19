from pathlib import Path
import sys, subprocess, json
sys.path.insert(0,str(Path(__file__).parent/'media-runtime'))
import imageio_ffmpeg
from PIL import Image,ImageDraw,ImageChops,ImageStat
root=Path(__file__).resolve().parents[1]
source=root/'assets/img/home/story-continuation'
output=source/'sequence-03';output.mkdir(exist_ok=True)
exe=imageio_ffmpeg.get_ffmpeg_exe()
reader=imageio_ffmpeg.read_frames(str(source/'chapter-03.mp4'),pix_fmt='rgb24')
meta=next(reader);chosen=[]
for i,data in enumerate(reader):
 frame=Image.frombytes('RGB',meta['size'],data)
 if i==0:first=frame.copy()
 if i%24==0:chosen.append((i,frame.copy()))
 last=frame.copy()
count=i+1
last.save(source/'chapter-03-actual-last.png')
chosen.append((count-1,last))
rows=(len(chosen)+2)//3
sheet=Image.new('RGB',(1440,300*rows),'#101114');draw=ImageDraw.Draw(sheet)
for n,(index,frame) in enumerate(chosen):
 x=(n%3)*480;y=(n//3)*300;sheet.paste(frame.resize((480,270)),(x,y));draw.text((x+10,y+280),f'{index/meta["fps"]:.2f}s / {index}',fill='white')
sheet.save(root/'artifacts/chapter-03-contact-sheet.jpg')
ref=Image.open(source/'chapter-03-first.png').convert('RGB').resize((320,180))
delta=sum(ImageStat.Stat(ImageChops.difference(first.resize((320,180)),ref)).mean)/3
info={'metadata':meta,'frames':count,'joinMeanPixelDelta':delta,'derivatives':{}}
for name,width in [('desktop',1280),('mobile',640)]:
 folder=output/name;folder.mkdir(exist_ok=True)
 subprocess.run([exe,'-hide_banner','-loglevel','error','-i',str(source/'chapter-03.mp4'),'-an','-vf',f'fps=24,scale={width}:-2','-c:v','libwebp','-quality','76','-start_number','0',str(folder/'frame-%04d.webp')],check=True)
 files=list(folder.glob('*.webp'));assert len(files)==count
 info['derivatives'][name]={'frames':len(files),'bytes':sum(f.stat().st_size for f in files)}
(root/'artifacts/chapter-03-inspection.json').write_text(json.dumps(info,indent=2),encoding='utf-8')
print(json.dumps(info))
