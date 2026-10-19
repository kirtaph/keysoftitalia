from pathlib import Path
import sys, json, subprocess, re
sys.path.insert(0, str(Path(__file__).parent / 'media-runtime'))
import imageio_ffmpeg
from PIL import Image, ImageDraw, ImageStat, ImageChops

root=Path(__file__).resolve().parents[1]
out=root/'artifacts'/'cinematic-inspection'
out.mkdir(exist_ok=True)
video=root/'assets'/'img'/'storyboard.mp4'
exe=imageio_ffmpeg.get_ffmpeg_exe()
probe=subprocess.run([exe,'-hide_banner','-i',str(video)],capture_output=True,text=True)
(out/'video-metadata.txt').write_text(probe.stderr,encoding='utf-8')
reader=imageio_ffmpeg.read_frames(str(video),pix_fmt='rgb24')
meta=next(reader)
fps=meta['fps']; width,height=meta['size']; selected=[]; count=0; previous=None; deltas=[]
for i,data in enumerate(reader):
    frame=Image.frombytes('RGB',(width,height),data)
    if i==0 or i%round(fps)==0:
        frame.save(out/f'frame-{i:04d}.jpg',quality=92)
        selected.append((i,frame.copy()))
    if i==0: first=frame.copy()
    last=frame.copy();count=i+1
    small=frame.resize((160,90))
    if previous is not None:
        deltas.append(sum(ImageStat.Stat(ImageChops.difference(small,previous)).mean)/3)
    previous=small
last.save(out/'actual-last.jpg',quality=94)
if selected[-1][0]!=count-1:selected.append((count-1,last))
tilew=512;tileh=round(height/width*tilew);rows=(len(selected)+2)//3
sheet=Image.new('RGB',(tilew*3,(tileh+32)*rows),'#101114');draw=ImageDraw.Draw(sheet)
for j,(i,frame) in enumerate(selected):
    x=j%3*tilew;y=j//3*(tileh+32)
    sheet.paste(frame.resize((tilew,tileh)),(x,y))
    draw.text((x+12,y+tileh+8),f'{i/fps:.2f}s / frame {i}',fill='white')
sheet.save(out/'contact-sheet.jpg',quality=91)
still_info=[]
for name,ref in [('first_frame.jpg',first),('last_frame.jpg',last)]:
    img=Image.open(root/'assets'/'img'/name).convert('RGB')
    normalized=img.resize((320,180));actual=ref.resize((320,180))
    still_info.append({'name':name,'size':img.size,'aspect':round(img.width/img.height,5),'meanPixelDifferenceToVideoEndpoint':round(sum(ImageStat.Stat(ImageChops.difference(normalized,actual)).mean)/3,2)})
summary={'video':meta,'decodedFrames':count,'actualDurationFromFrames':round(count/fps,3),'hasAudio':bool(re.search(r'Stream.*Audio:',probe.stderr)),'referenceImages':still_info,'largestFrameChanges':[{'time':round((i+1)/fps,3),'meanPixelDelta':round(v,2)} for i,v in sorted(enumerate(deltas),key=lambda p:p[1],reverse=True)[:8]],'ffmpeg':exe}
(out/'inspection.json').write_text(json.dumps(summary,indent=2),encoding='utf-8')
print(json.dumps(summary,indent=2))
