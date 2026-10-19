# Capitolo 01 — clip dinamiche con Vito

Nuovi finali ispezionati il 7 ottobre 2026. Questa produzione sostituisce i
precedenti prompt con avvicinamento minimo. Generazione esterna dell'utente
con Veo 3.1, durata 8 secondi per ciascuna variante.

## Valutazione dei nuovi finali

- Desktop: cambio di scala evidente; notebook intero in primo piano, camicia
  e mani sullo sfondo, viso fuori dall'inquadratura alta. Materiali e luce
  seguono la direzione dell'apertura.
- Mobile: dettaglio verticale di tastiera, trackpad e schermo parzialmente
  fuori dal bordo sinistro. Torso e mani conservano la presenza umana; il
  volto esce dall'alto. Il ritaglio dello schermo è una scelta del finale.
- Il telefono non appare nei finali: può restare fuori dal campo più stretto;
  non deve dissolversi o spostarsi durante la clip.
- Le immagini rendono possibile una prova di movimento molto più evidente.
  Non certificano ancora una traiettoria fisicamente continua: durante il
  video controllare mani, hinge, tastiera e scaffali per cambiamenti di forma
  o posizione. Non aggiungere una nuova azione del soggetto.

## File e impostazione

Cartella: `assets/img/home/cinematic-v2/chapter-01/`.

| Variante | Start | End | Consegna |
| --- | --- | --- | --- |
| Desktop 16:9 | desktop/first-frame.png | desktop/last-frame-dynamic.png | desktop/chapter-01-dynamic.mp4 |
| Mobile 9:16 | mobile/first-frame-v2.png | mobile/last-frame-dynamic.png | mobile/chapter-01-dynamic.mp4 |

Usare i rispettivi start/end come immagini di apertura e chiusura nella
generazione. Consegna gli MP4 originali alla qualità più alta disponibile.
Non usare i precedenti last-frame.png e non ritagliare il desktop per il mobile.
Per la nuova direzione il testo esce prima del passaggio ravvicinato: nel
finale non occorre una grande area vuota per titoli sovrapposti.

## Prompt desktop — 16:9, 8 secondi

```text
Create one continuous 8-second photorealistic cinematic camera shot connecting
the supplied landscape opening image and close final image. Landscape 16:9.
Begin on the exact first frame and finish on the exact final frame. The shot
starts with Vito Moro behind the counter and ends close to the notebook with
his white shirt and resting hands still visible. His face naturally leaves
the upper camera crop as the camera approaches and lowers.

CAMERA MOTION IS THE MAIN ACTION. Make a decisive forward dolly, lower the
camera towards the counter and travel through a coherent lateral arc of about
20 degrees. Progress from an environmental portrait to a bold low three-quarter
device close-up. Produce strong physical parallax: the near counter and notebook
change perspective faster than the distant shelves. This is a substantial
movement through the room, not a slight zoom or animation of a flat photograph.
Choose the actual path and travel distance to reach the supplied end composition.

0–0.5 seconds: start moving promptly with a short, smooth acceleration. No long
static opening hold.
0.5–3 seconds: advance and descend visibly towards the counter. Vito's face
leaves the top of the image through camera cropping, while his torso and hands
remain physical parts of the same scene.
3–5.5 seconds: carry the motion forward along the counter with the lateral arc.
The notebook grows prominently in the field of view, revealing keyboard, hinge,
trackpad and metal reflections. Pass in front of its solid geometry, never
through the screen or keyboard. Keep the motion energetic and clearly visible.
5.5–7.2 seconds: brake smoothly into the supplied close final composition.
7.2–8 seconds: a brief settled ending; no new action or camera overshoot.

Vito stays in the same position throughout. Preserve his facial identity while
visible, his natural smile, shirt, folded sleeves and both resting hands. He
does not slide, shrink, lean towards the camera or perform a repair gesture.
The notebook and phone remain stationary in their original physical positions.
The phone leaves view only when the camera crops it out. Preserve notebook
geometry, hinge angle, keyboard, trackpad and abstract warm screen content.

Keep the same counter, wood, shelving, plant, window, daylight and amber lights.
All architecture remains rigid. Maintain natural perspective, coherent contact
shadows and reflections. Stable focus transitions naturally from the opening
subject to the close notebook; no abrupt focus pumping. Restrained motion blur
only, retaining recognisable materials and geometry.

One shot: no cut, dissolve, teleport, scene replacement, furniture sliding,
object scaling, face morphing, extra fingers, duplicated hands or background
reconstruction. No new props, words, logos, watermark, particles or holograms.
No dialogue, music or sound effects. The physical camera path must remain
coherent when played backwards under native website scroll control.
```

## Prompt mobile — 9:16, 8 secondi

```text
Create one continuous 8-second photorealistic cinematic camera shot connecting
the supplied portrait opening and close final images. Dedicated vertical 9:16.
Start exactly on the first image and finish on the final image. The first image
shows Vito Moro behind the counter; the last shows the same notebook close-up,
his white shirt and resting hands, with his face outside the upper camera crop.
Preserve his identity while visible. Do not change or substitute the person.

Create a visibly energetic physical camera move: advance towards the notebook,
descend close to counter level and follow a lateral arc of about 20 degrees.
The composition changes boldly from person and environment to device detail.
Strong parallax separates foreground counter, notebook, torso and distant
architecture. Reach the supplied final perspective through a camera path,
not a digital zoom, stretched image or moving objects.

0–0.5 seconds: short acceleration; the camera starts moving promptly.
0.5–3 seconds: advance and lower the camera. Vito's face naturally exits the
TOP of the frame as the view travels towards the bank; he stays in place.
3–5.5 seconds: continue the decisive approach and lateral arc, bringing the
keyboard, trackpad, hinge and brushed metal into prominent close view. The
screen may leave the left frame edge naturally as in the final reference.
Do not force the whole laptop into the portrait view or warp it to fit.
5.5–7.2 seconds: decelerate smoothly into the exact supplied close composition.
7.2–8 seconds: brief settled end, without a new gesture or extra zoom.

Keep both hands resting in their original physical places. Preserve the white
shirt, folded sleeves and visible fingers. Do not move Vito or the hands to
simulate parallax. The notebook retains its exact geometry, hinge angle,
keyboard, trackpad, unbranded surfaces and warm abstract screen image. The
phone remains where it was and simply leaves the tighter camera view.

Maintain the exact set: counter, wood, shelves, plants and window remain rigid
and stationary. No growing, shortened or rebuilt architecture. Preserve light
direction, amber practical lights, skin colour, cotton texture and metal
reflections. Use natural perspective and a smooth focus adjustment towards
the device detail. No fisheye, heavy motion blur or sudden focus jump.
During this moving passage no website headline occupies the upper area;
do not slow the shot down or retain blank headroom merely to make space for text.

One continuous take, no cut, fade, morph, scene change, face drift, moving props,
extra fingers, duplicate hands, text, logo, watermark, floating UI or particles.
Silent: no speech, music or sound effects. Spatial motion must remain coherent
when scrubbed both forward and backward by website scroll.
```

## Produzione e controllo

Per una prima verifica puoi produrre il desktop prima del mobile: controlliamo
se la camera scende e avanza davvero, anziché ricreare un lento zoom. Questa
verifica non sostituisce quella della variante verticale con i suoi riferimenti.

Alla consegna controllare gli originali, non solo il player: fotogrammi iniziali,
intermedi e finali, continuità della geometria, velocità percepita, dita e luce.
Il modello potrebbe non rispettare esattamente gli endpoint. I frame realmente
raggiunti guideranno il raccordo al capitolo 02.
Il rifiuto mobile sulla persona non è risolto da questi prompt: la composizione
dinamica corregge il movimento e mantiene esplicito il soggetto scelto dall'utente.
