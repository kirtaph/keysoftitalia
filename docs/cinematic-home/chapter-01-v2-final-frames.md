# Capitolo 01 — frame finali desktop e mobile

> Questi finali hanno prodotto un movimento troppo lieve. Sono conservati come
> prove; per la nuova produzione usare [i finali dinamici](chapter-01-dynamic-final-frames.md).

Entrambi i finali sono stati consegnati e ispezionati. I prompt Veo del passaggio
successivo sono in [Produzione video](chapter-01-v2-video-prompts.md).
La continuità delle immagini è una base di produzione; il movimento e la stabilità
del volto saranno valutati sui filmati effettivi.

Frame iniziali selezionati per proseguire:

- Desktop: assets/img/home/cinematic-v2/chapter-01/desktop/first-frame.png.
- Mobile: assets/img/home/cinematic-v2/chapter-01/mobile/first-frame-v2.png.

Il mobile corretto conserva spazio sopra al volto, notebook senza marchio,
entrambe le mani visibili e materiali coerenti con la direzione desktop.
La somiglianza è stata valutata visivamente rispetto alla foto fornita: non
è una certificazione di identità pixel per pixel. Nessun video v2 ancora prodotto.

## Movimento del capitolo

Un avvicinamento della camera con un arco laterale contenuto, producendo
parallasse tra banco in primo piano, Vito e scaffali sullo sfondo. Vito e i
dispositivi rimangono nello stesso luogo. La camera si muove; il set non cambia.
Vito parte già guardando la camera e sorridendo, come nei frame reali consegnati.
Non chiedere un inizio con lo sguardo abbassato o una nuova azione delle mani.

La futura clip sarà di 8 secondi: partenza dolce, movimento centrale, rallentamento
finale. Le pause per leggere i titoli verranno aggiunte dalla timeline del sito.
Manteniamo spazio a sinistra su desktop e sopra al volto su mobile anche alla fine.
Il frame finale conserva il banco per consentire un raccordo successivo verso
il lavoro e i dispositivi; non chiudiamo con un volto isolato su fondo vuoto.

## Prompt C — finale desktop, GPT Image

Allega:
1. Desktop first-frame.png, riferimento principale della scena.
2. Foto aggiornata originale di Vito, riferimento della fisionomia.

```text
Create the END keyframe of an 8-second continuous cinematic camera shot.
Landscape 16:9. Image 1 is the exact opening frame and authoritative reference
for the physical scene. Image 2 is the original identity reference for Vito Moro,
the central man in the white shirt. Recreate the SAME scene at the end of a
controlled forward camera movement with a small lateral arc.

Preserve Vito's facial identity and apparent age from image 2: his natural
hairline, short dark hair with grey temples, forehead, eyebrows, eye shape and
spacing, nose, cheeks, ears, jaw, mouth and distinctive smile. Real skin texture
and expression lines. Preserve the white shirt, folded sleeves and open collar
from image 1. He still looks towards the visitor with the same relaxed smile.
No new pose, exaggerated expression, rejuvenation or beauty retouching.

Keep the exact graphite counter, warm wood, illuminated shelves, plant, window,
device arrangement and light direction from image 1. The notebook and phone
remain stationary at their original physical positions. Keep the same notebook
geometry, hinge angle, screen content, keyboard and trackpad. Both hands remain
resting in their original places; do not transfer a device or introduce tools.

FINAL CAMERA COMPOSITION: The camera has moved closer by approximately 15–20
percent and travelled through a gentle lateral arc of about 6–8 degrees, without
crossing behind Vito or the notebook. Create visible but restrained parallax:
the near counter edge changes perspective more than the distant shelves.
Vito's face remains around 66–70 percent of frame width, with enough room around
his head. Preserve quiet graphite architecture across the LEFT 35 percent for
HTML typography added later. Show both complete hands and identifiable parts
of the notebook and phone. Do not crop to a floating head or extreme close-up.
If necessary, favour a slightly wider final view to keep the hands intact.

The change must be achieved through physical camera perspective, not by simply
enlarging Vito or moving the furniture. Maintain natural lens perspective,
soft daylight, warm shelf lights, coherent contact shadows and real reflections.
Premium photographic realism, retaining the opening frame's colour treatment.

No new people, props, devices, logos, words, glyphs, captions or watermarks.
No extra fingers, distorted hands, altered facial identity, changed architecture,
floating objects, futuristic effects or drastic changes in depth of field.
```

Salva: `assets/img/home/cinematic-v2/chapter-01/desktop/last-frame.png`.

## Prompt D — finale mobile, GPT Image

Allega:
1. Mobile first-frame-v2.png, riferimento principale della scena e della composizione.
2. Foto aggiornata originale di Vito, riferimento della fisionomia.
3. Finale desktop appena generato, se disponibile: guida di espressione e atmosfera,
   senza copiarne l'inquadratura orizzontale.

```text
Create the END keyframe of an 8-second continuous cinematic camera shot for
a mobile website. Dedicated vertical 9:16 composition. Image 1 is the selected
portrait opening frame and authoritative reference for the physical scene.
Image 2 is the original identity reference for Vito Moro, the central man in
the white shirt. If a landscape ending frame is supplied as image 3, use it
only for continuity of facial expression and photographic atmosphere; keep
the portrait set and layout of image 1.

Keep the exact same Vito: natural hairline, short dark hair with grey temples,
forehead, eyebrows, eye shape and spacing, nose, ears, cheeks, jaw, mouth and
distinctive smile. Preserve his apparent age, natural skin texture and expression
lines. Keep the same white open-collar shirt and folded sleeves. He remains
upright, looking towards the visitor with a relaxed smile, and both hands
remain resting on the counter in their original positions.

Keep the exact counter, graphite wall, vertical wood detailing, shelving,
plants, window, lighting and two stationary devices from image 1. The notebook
stays unbranded, with its screen facing the visitor, the same hinge angle and
abstract warm display. The phone stays in its original physical place. Nothing
in the scene changes position to achieve the new composition.

END CAMERA VIEW: Move the camera forward by approximately 10–15 percent and
through a small lateral arc of about 6 degrees. Show perceptible parallax between
the near counter, Vito and the distant shelves. Reframe naturally as the camera
approaches to retain a calm upper zone: the TOP 28–30 percent must remain quiet
architecture for website navigation and headline overlays. Keep the top of
Vito's head below that reserved zone, with his eyes near 43–46 percent of image
height. Preserve natural face proportions, both complete hands and recognisable
device details. Keep hands and devices above the bottom 10 percent reserved
for website controls. Use a smaller approach if needed to retain this framing.
Do not enlarge the face into the text zone or produce a tight portrait crop.

Maintain the same natural lens perspective, daylight direction, restrained
amber practical lights, material textures, colour treatment and realistic
contact shadows. The physical environment fills all frame edges. No stretched
landscape picture, black border, floating subject or replacement background.

No additional people, tools, props, devices, logos, words, glyphs or watermarks.
No changed facial identity, rejuvenation, artificial skin smoothing, extra
fingers, duplicate hands, altered wardrobe or reshuffled shelf contents.
```

Salva: `assets/img/home/cinematic-v2/chapter-01/mobile/last-frame.png`.

## Controllo prima di Veo

Confrontare apertura e finale di ciascuna variante: identità, sorriso, posizione
delle mani, disposizione di banco e dispositivi, architettura e luce. Deve essere
possibile collegarli muovendo una camera nel medesimo spazio. Un effetto di
parallasse credibile è più utile di due immagini molto diverse fra loro.
I prompt dei due video verranno scritti dopo il controllo dei finali reali.
Conservare tutte le immagini iniziali e la foto originale senza sovrascriverle.
