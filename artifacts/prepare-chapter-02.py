from pathlib import Path
import sys, subprocess, json
sys.path.insert(0,str(Path(__file__).parent/'media-runtime'))
import imageio_ffmpeg
from PIL import Image,ImageDraw,ImageChops,ImageStat
root=Path(__file__).resolve().parents[1]
source=root/'assets/img/home/story-continuation'
output=source/'sequence';output.mkdir(exist_ok=True)
exe=imageio_ffmpeg.get_ffmpeg_exe()
reader=imageio_ffmpeg.read_frames(str(source/'chapter-02.mp4'),pix_fmt='rgb24')
meta=next(reader);chosen=[]
for i,data in enumerate(reader):
 frame=Image.frombytes('RGB',meta['size'],data)
 if i==0:first=frame.copy()
 if i%24==0:chosen.append((i,frame.copy()))
 last=frame.copy()
count=i+1
assert count==192
last.save(source/'chapter-03-first.png')
chosen.append((count-1,last))
sheet=Image.new('RGB',(1440,900),'#101114');draw=ImageDraw.Draw(sheet)
for n,(index,frame) in enumerate(chosen):
 x=(n%3)*480;y=(n//3)*300;sheet.paste(frame.resize((480,270)),(x,y));draw.text((x+10,y+280),f'{index/24:.2f}s / {index}',fill='white')
sheet.save(root/'artifacts/chapter-02-contact-sheet.jpg')
ref=Image.open(source/'chapter-02-first.png').convert('RGB').resize((320,180))
delta=sum(ImageStat.Stat(ImageChops.difference(first.resize((320,180)),ref)).mean)/3
print(json.dumps({'metadata':meta,'frames':count,'joinMeanPixelDelta':delta}))
for name,width in [('desktop',1280),('mobile',640)]:
 folder=output/name;folder.mkdir(exist_ok=True)
 subprocess.run([exe,'-hide_banner','-loglevel','error','-i',str(source/'chapter-02.mp4'),'-an','-vf',f'fps=24,scale={width}:-2','-c:v','libwebp','-quality','76','-start_number','0',str(folder/'frame-%04d.webp')],check=True)
 files=list(folder.glob('*.webp'));assert len(files)==count
 print(name,len(files),sum(f.stat().st_size for f in files))
original=json.loads((root/'assets/img/home/cinematic-v1/manifest.json').read_text())
original.update(version=2,frameCount=432,duration=18,activeViewportHeights=7.9)
ratio=5.5/7.9
for beat in original['beats']:beat['scroll']=[round(v*ratio,8) for v in beat['scroll']]
original['beats'] += [{'id':'join','scroll':[ratio,5.7/7.9],'frames':[239,240]},{'id':'choose-reveal','scroll':[5.7/7.9,.92],'frames':[240,407]},{'id':'choose','scroll':[.92,1],'frames':[407,431]}]
original['frameSources']=[{'start':0,'end':239,'desktopPattern':'cinematic-v1/desktop/frame-{index}.webp','mobilePattern':'cinematic-v1/mobile/frame-{index}.webp'}, {'start':240,'end':431,'desktopPattern':'story-continuation/sequence/desktop/frame-{index}.webp','mobilePattern':'story-continuation/sequence/mobile/frame-{index}.webp'}]
original['desktopPattern']='desktop';original['mobilePattern']='mobile'
original['poster']='cinematic-v1/poster.webp'
original['source']='../storyboard.mp4'
(source.parent/'story-manifest.json').write_text(json.dumps(original,indent=2),encoding='utf-8')
