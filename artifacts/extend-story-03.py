from pathlib import Path
import json
root=Path(__file__).resolve().parents[1]
path=root/'assets/img/home/story-manifest.json'
manifest=json.loads(path.read_text(encoding='utf-8-sig'))
assert manifest['version']==2, 'Only extend the two-chapter manifest once'
manifest.update(version=3,frameCount=624,duration=26,activeViewportHeights=10.3)
for beat in manifest['beats']:beat['scroll']=[v*7.9/10.3 for v in beat['scroll']]
manifest['beats'] += [
 {'id':'join-create','scroll':[7.9/10.3,8.05/10.3],'frames':[431,432]},
 {'id':'create-reveal','scroll':[8.05/10.3,9.7/10.3],'frames':[432,587]},
 {'id':'create','scroll':[9.7/10.3,1],'frames':[587,623]}
]
manifest['frameSources'].append({'start':432,'end':623,'desktopPattern':'story-continuation/sequence-03/desktop/frame-{index}.webp','mobilePattern':'story-continuation/sequence-03/mobile/frame-{index}.webp'})
path.write_text(json.dumps(manifest,indent=2),encoding='utf-8')
