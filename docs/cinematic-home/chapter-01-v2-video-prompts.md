# Capitolo 01 — produzione delle clip Veo 3.1

> Revisione dopo la prova dell'utente: questa traiettoria produce un movimento
> troppo lieve e la variante mobile è stata rifiutata dal generatore.
> Non ripetere la produzione con questi stessi endpoint. Vedi la proposta in
> [Revisione del movimento](chapter-01-motion-revision.md).

Frame finali consegnati e ispezionati il 7 ottobre 2026. Generazione esterna
dell'utente, due clip separate da 8 secondi, una desktop e una mobile.
Il sito conserva la versione precedente finché non controlliamo questi video.

## Riferimenti da usare

| Variante | Primo frame | Ultimo frame | Video da consegnare |
| --- | --- | --- | --- |
| Desktop 16:9 | desktop/first-frame.png | desktop/last-frame.png | desktop/chapter-01.mp4 |
| Mobile 9:16 | mobile/first-frame-v2.png | mobile/last-frame.png | mobile/chapter-01.mp4 |

Tutti i percorsi sono relativi a `assets/img/home/cinematic-v2/chapter-01/`.
Sul mobile usare il primo frame corretto v2, non il tentativo iniziale.
Impostare le due immagini come apertura e chiusura della rispettiva clip.
Durata 8 secondi, proporzioni della variante, qualità originale più alta disponibile.
Non ridimensionare una clip desktop per ottenere la variante mobile.
Non aggiungere i titoli, pulsanti o il logo della navigazione ai video.

## Esito del controllo dei frame

Il finale desktop conserva identità, abbigliamento, dispositivi e luce; mostra
un avvicinamento e variazione di prospettiva, lasciando la parete sinistra libera.
Il mobile conserva la fascia alta per i testi e un'inquadratura ambientata.
Nel finale mobile compare un diverso taglio superiore dei listelli in legno:
durante il video verificare che le strutture restino rigide e coerenti, senza
comparire, accorciarsi o ricostruirsi. La mano a destra è vicina al bordo del
frame finale: evitare movimenti aggiuntivi o crop che ne taglino le dita.
Le immagini chiave non provano ancora che la clip possa collegarle senza artefatti.

## Prompt E — desktop, 16:9, 8 secondi

```text
Create a single continuous 8-second photorealistic cinematic brand-film shot.
Landscape 16:9. The supplied first and last images define the opening and ending
compositions. Start on the supplied first frame and finish on the supplied last
frame. Preserve the identity of the man, Vito Moro, throughout the entire shot.

The same man stands behind the same graphite counter, wearing the same white
open-collar shirt with folded sleeves. He already looks towards the camera with
a relaxed smile in the opening frame. Keep his face, apparent age, natural
hairline, grey temples, eyes, nose, mouth, smile and real skin texture consistent.
Do not start with him looking down or introduce a new pose. Allow only restrained
natural breathing and a small natural blink; no speaking, lip-sync, waving or
large head movement. Both hands remain calmly resting at their original places.

The physical counter, notebook, phone, shelves, plant, wood detailing and window
stay fixed in the SAME environment. Preserve the notebook's geometry, hinge,
keyboard, abstract screen content and unbranded surfaces. The phone never slides
or changes shape. Preserve the white shirt's texture and sleeves. Shelf lights
and daylight remain stable; reflections change only through camera perspective.

CAMERA: Perform a real, controlled forward dolly with a small lateral arc to
connect the two compositions. Create tangible depth and parallax: the near
counter edge and devices shift in perspective more than the distant shelving.
Do not simulate the movement by scaling a flat photograph or moving furniture.
Maintain a natural lens perspective and stable focus on Vito's face.

0–1 seconds: begin the forward camera motion gently from the exact opening view.
1–6.5 seconds: continue the smooth approach and restrained arc. Keep the man on
the right and the left graphite wall quiet for website text added separately.
6.5–8 seconds: decelerate naturally and settle into the exact final composition.
No extra orbit, overshoot or abrupt stop. Keep both hands and recognisable parts
of the devices inside the frame. Preserve the warm photographic colour treatment.

One shot, no cut, dissolve, scene replacement, morph, background reconstruction,
focus pumping, flickering light, shaking camera or heavy motion blur. No facial
identity drift, changing teeth, extra fingers, new objects, people, text, logos,
watermarks, holograms or particles. Silent: no dialogue, music or sound effects.
The camera path must remain coherent when the film is scrubbed backwards.
```

Salva l'MP4 originale in:
`assets/img/home/cinematic-v2/chapter-01/desktop/chapter-01.mp4`.

## Prompt F — mobile, 9:16, 8 secondi

```text
Create a dedicated vertical 9:16, single continuous 8-second photorealistic
cinematic brand-film shot. The supplied portrait first and last images define
the opening and ending compositions. Start on the supplied first frame and
finish on the supplied last frame. This is a separately composed portrait shot,
not a crop or stretched version of landscape footage.

Preserve the identity of Vito Moro and his apparent age throughout. Keep the
same natural hairline, short dark hair with grey temples, eyes, nose, cheeks,
mouth, distinctive smile and detailed natural skin. He starts already facing
the visitor with a relaxed smile. Preserve his upright stance, the white
open-collar shirt and folded sleeves. Only restrained breathing and a small
natural blink; no speech, exaggerated smile, pose change or arm gesture.
Both hands remain resting in their existing positions on the counter.

The graphite counter, notebook, phone, wood details, shelves, plants and window
are rigid parts of one physical environment. Keep all objects stationary.
Preserve the notebook's geometry, hinge angle, keyboard and abstract warm display.
Do not show an Apple logo or any new branding. Preserve the phone and its position.
The architecture must not shorten, grow, disappear, rebuild or morph between
the two keyframes. Resolve perspective changes through camera movement only.
Lighting direction and practical shelf lights remain constant.

CAMERA: A controlled forward dolly and small lateral arc connect the two
portrait compositions, with natural parallax between the near counter, Vito
and the background. Maintain the intentionally quiet upper area: Vito's hair
and face must remain below the top 28–30 percent throughout, leaving room for
website navigation and headline overlays added later. Keep his facial proportions
natural. Keep both hands and their fingers visible, especially the hand near
the right edge of the final frame. Do not add zoom beyond the supplied end view.

0–1 seconds: gently begin movement from the exact opening image.
1–6.5 seconds: continue the steady physical approach and gentle arc, preserving
headroom, the same scene and recognisable devices. No sudden upward pan.
6.5–8 seconds: smoothly decelerate and settle into the supplied final composition.
Keep focus stable on Vito, with moderate natural background softness. Preserve
daylight, warm amber reflections, cotton and metal textures and skin colour.

No cut, dissolve, new location, flat-image zoom, perspective warping, face drift,
changing teeth, skin smoothing, moving props, extra fingers, detached hands,
new people, logos, words, glyphs, captions or watermarks. No flicker, camera
shake, heavy blur, particles or holograms. Silent: no speech, music or effects.
Motion must remain spatially coherent when scrubbed forwards and backwards.
```

Salva l'MP4 originale in:
`assets/img/home/cinematic-v2/chapter-01/mobile/chapter-01.mp4`.

## Controllo dopo la consegna

Controllare realmente la clip e campionare apertura, centro e finale:

- Volto stabile e riconoscibile, senza cambiamenti di denti o sorriso.
- Mani, dita e dispositivi stabili; nessun oggetto che scivola per simulare la camera.
- Parallasse visibile, camera continua e assenza di ricostruzione dello sfondo.
- Spazio per la copia mantenuto durante tutto il movimento, soprattutto sul mobile.
- Primo e ultimo frame effettivi coerenti con i riferimenti, senza promettere
  che il modello li raggiunga esattamente solo perché sono stati allegati.

Conservare gli originali. Dopo l'ispezione estrarremo gli ultimi frame effettivi
per progettare il raccordo al capitolo 02 in ciascuna variante. Niente produzione
del capitolo successivo usando automaticamente i last-frame.png di progetto.
