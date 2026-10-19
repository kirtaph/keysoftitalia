from pathlib import Path
import sys, subprocess, json
sys.path.insert(0,str(Path(__file__).parent/'media-runtime'))
import imageio_ffmpeg
from PIL import Image
root=Path(__file__).resolve().parents[1]
destination=root/'assets'/'img'/'home'/'cinematic-v1'
destination.mkdir(exist_ok=True)
exe=imageio_ffmpeg.get_ffmpeg_exe()
for name,width in [('desktop',1280),('mobile',640)]:
    folder=destination/name;folder.mkdir(exist_ok=True)
    subprocess.run([exe,'-hide_banner','-loglevel','error','-i',str(root/'assets'/'img'/'storyboard.mp4'),'-an','-vf',f'fps=24,scale={width}:-2','-c:v','libwebp','-quality','76','-start_number','0',str(folder/'frame-%04d.webp')],check=True)
    files=sorted(folder.glob('frame-*.webp'))
    assert len(files)==240
    for i,file in enumerate(files):assert file.name==f'frame-{i:04d}.webp'
    print(name,len(files),sum(f.stat().st_size for f in files))
Image.open(destination/'desktop'/'frame-0000.webp').save(destination/'poster.webp','WEBP',quality=85)
manifest={'version':1,'fps':24,'frameCount':240,'duration':10,'width':1280,'height':720,'mobileWidth':640,'mobileHeight':360,'desktopPattern':'desktop/frame-{index}.webp','mobilePattern':'mobile/frame-{index}.webp','poster':'poster.webp','source':'../../storyboard.mp4','activeViewportHeights':5.5,'beats':[{'id':'opening','scroll':[0,.22],'frames':[0,60]},{'id':'inside','scroll':[.22,.49],'frames':[60,100]},{'id':'care','scroll':[.49,.81],'frames':[100,192]},{'id':'restart','scroll':[.81,1],'frames':[192,239]}]}
(destination/'manifest.json').write_text(json.dumps(manifest,indent=2),encoding='utf-8')
