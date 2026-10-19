# Capitolo 01 v2 — controllo dei primi frame

Asset consegnati dall'utente il 7 ottobre 2026, prodotti esternamente con GPT Image.

- Desktop: assets/img/home/cinematic-v2/chapter-01/desktop/first-frame.png,
  1672×941, 1.791.318 byte; proporzioni sostanzialmente 16:9.
- Mobile: assets/img/home/cinematic-v2/chapter-01/mobile/first-frame.png,
  941×1672, 1.848.979 byte; proporzioni sostanzialmente 9:16.

Originali conservati, nessuna modifica alla home o alle immagini consegnate.

## Esito della correzione mobile

Ricevuto mobile/first-frame-v2.png (1.858.349 byte). Il nuovo frame sposta il
volto sotto una zona superiore libera, mostra il notebook senza logo con lo
schermo verso il visitatore e rende visibili entrambe le mani. La composizione
è selezionata per il passaggio successivo, insieme al desktop originale.
Il primo tentativo mobile e la diagnosi sotto restano documentati come storico.
Prompt successivi: [frame finali desktop e mobile](chapter-01-v2-final-frames.md).

## Valutazione visiva

Il desktop è una buona base per la composizione: parete tranquilla a sinistra,
Vito a destra, banco in diagonale, sfondo con scaffali, luce calda e luce naturale.
La somiglianza alla foto aggiornata è convincente al primo esame, ma questo non
certifica una riproduzione esatta di ogni dettaglio del volto. Il volto resta
da confermare da parte di chi conosce Vito prima di spendere sulla produzione video.
Camicia e proporzioni sono credibili; entrambi i dispositivi sono appoggiati al
banco e le mani sono visibili. L'ambiente racconta più del precedente studio vuoto.

Nel mobile luce, materiali e abbigliamento seguono la direzione del desktop.
Tuttavia il volto occupa la fascia alta (circa il primo terzo), invece della zona
centrale prevista. Titolo e azione sovrapposti rischierebbero di coprire il viso.
Il notebook è orientato diversamente, con il retro e un logo Apple ben visibile,
mentre il desktop mostra un dispositivo senza marchio con schermo rivolto al
visitatore. Una mano è in parte nascosta dal notebook. Per una composizione
intenzionale mobile chiediamo una correzione prima di preparare i frame finali.

Entrambi i frame mostrano già Vito rivolto verso la camera con un sorriso:
la futura clip deve partire da questa posa effettiva. Non chiedere a Veo di
iniziare con lo sguardo abbassato, perché sarebbe diverso dallo start frame.
Il movimento principale potrà essere un avvicinamento morbido della camera,
con piccoli movimenti naturali del soggetto e nessuna nuova azione complessa.

## Correzione del frame mobile — GPT Image

Allega, nell'ordine:

1. Foto aggiornata originale di Vito: riferimento esclusivo dell'identità.
2. first-frame.png desktop: riferimento dell'ambiente, dei dispositivi e degli abiti.
3. first-frame.png mobile: composizione da correggere.

```text
Revise the supplied portrait cinematic frame for a mobile website opening.
Output a dedicated vertical 9:16 composition. Image 1 is the authoritative
identity reference for Vito Moro, the central man in the white shirt. Image 2
is the selected landscape reference for the physical set, clothing, devices
and lighting. Image 3 is the portrait frame to correct.

Preserve Vito's actual facial identity from image 1: forehead, natural hairline,
short dark hair with grey temples, eyebrows, eyes and their spacing, nose,
ears, cheeks, jaw, mouth and distinctive smile. Keep his apparent age and real
skin texture. Do not rejuvenate, beautify or replace him with another person.
Exclude the other people and their hands from image 1.

Keep the appealing graphite-and-warm-wood workspace of images 2 and 3, the same
white open-collar shirt, restrained amber shelf lighting, daylight direction
and natural photographic colour. Vito remains facing the visitor with a relaxed
smile, standing upright behind the same counter. Do not invent a repair action.

CHANGE THE PORTRAIT FRAMING: Pull the camera back enough to produce an
environmental portrait with deliberate headroom. The upper 30 percent must
contain only quiet, softly shaded architecture, reserved for website navigation,
a short headline and one action. No face, hair, plant, shelf highlight or device
may occupy that upper reserved zone. Place the top of Vito's head below roughly
34 percent of image height, with his eyes around 43–46 percent. Keep his face
naturally proportioned and in clear focus. Do not move his body unnaturally or
stretch the image to obtain this layout. Recompose the physical camera view.

Show both shoulders, torso and BOTH complete hands resting calmly on the
counter, without the laptop hiding a hand. Keep the hands and recognisable
device details above the bottom 10 percent, which is reserved for website
controls. Use one graphite notebook and one intact phone. Match the unbranded
notebook in image 2: screen visible to the visitor, open at the same hinge angle,
with its restrained abstract warm screen content. Keep coherent keyboard,
trackpad and metal edges. Remove the Apple mark from the portrait composition;
do not replace it with another logo. Match the device identity and phone
placement from the landscape reference as closely as the portrait view allows.

Maintain real contact shadows, reflections, cotton texture, facial detail and
architectural depth. Natural perspective, no wide-angle facial distortion.
Do not place Vito inside a plain black void. The environment fills every edge.

No words, glyphs, signage, logos, captions, UI or watermark. No extra people,
extra devices, detached hands, extra fingers or duplicated body parts. Website
text will be added as editable HTML and must not be generated into the image.
```

Salva il nuovo originale in:
`assets/img/home/cinematic-v2/chapter-01/mobile/first-frame-v2.png`.
Conserva il primo tentativo per il confronto.

## Passaggio successivo

Controllare il frame mobile corretto insieme al desktop. Poi definire e produrre
due frame finali con le rispettive inquadrature come riferimento, conservando
identità e disposizione del set. Le clip Veo 3.1, massimo 8 secondi ciascuna,
verranno preparate dopo questo controllo. Non usare lo stesso film landscape
ritagliato per sostituire la composizione portrait dedicata.
