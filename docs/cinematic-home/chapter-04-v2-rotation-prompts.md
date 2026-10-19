# Capitolo 04 v2 — la riparazione in tre rotazioni

13 ottobre 2026. Sostituisce `chapter-04-v2-frame-prompts.md` e
`chapter-04-v2-video-prompts.md` (finali sul banco non usati, conservati).

## Visual Story

Dopo la consegna (capitolo 03) lo sfondo si spegne nel grafite della pagina e
resta solo il telefono, in piedi e frontale, a destra. A sinistra il testo HTML
cambia a ogni passaggio. Lo scroll fa ruotare il telefono.

| Passo | Rotazione | Testo HTML a sinistra |
| --- | --- | --- |
| K0 | Telefono frontale, vetro rotto | «Un imprevisto.» |
| K0 → K1 | Ruota di tre quarti verso sinistra; il display rotto si stacca in avanti | 01 «Prima capiamo.» Diagnosi del guasto, preventivo prima di intervenire. |
| K1 → K2 | Il display esce di scena; il telefono ruota dall'altra parte e mostra l'interno ordinato | 02 «Poi ripariamo.» Ricambi di qualità, montati in laboratorio. |
| K2 → K3 | Arriva il display nuovo, il telefono torna frontale e si accende con lo sfondo ambrato | 03 «Pronto a ripartire.» Controllato e garantito. · Prenota una riparazione |

Sfondo: lo stesso grafite della pagina (#101114), quindi il telefono vive dentro
il sito e il testo resta leggibile senza veli. Luce calda di taglio, come nel set.
Il display riparato mostra lo stesso sfondo a dune del notebook: richiamo visivo.

Raccordo dal capitolo 03: dissolvenza guidata dallo scroll dal finale sul banco a
K0 (gestita dal sito). Una clip dedicata si può aggiungere dopo, se serve.

## Produzione

1. GPT Image: genera prima **K0**. Poi K1, K2, K3 allegando sempre K0 come
   riferimento, così il telefono resta identico.
2. Flow/Veo: tre clip da 8 secondi, K0→K1, K1→K2, K2→K3 (primo e ultimo frame).
3. Ripetere per il mobile 9:16.

Cartelle:

- `assets/img/home/cinematic-v2/chapter-04/desktop/k0-cracked.png`, `k1-detach.png`, `k2-open.png`, `k3-repaired.png`
- stessi nomi in `chapter-04/mobile/`
- video: `chapter-04/{desktop,mobile}/rot-1.mp4`, `rot-2.mp4`, `rot-3.mp4`

## Composizione

- Desktop 16:9: telefono verticale nella metà destra, centro a circa 65% della
  larghezza, alto circa il 70% dell'altezza. Metà sinistra vuota per il testo.
- Mobile 9:16: telefono nella metà bassa, alto circa il 50% dell'immagine,
  spazio vuoto nella metà alta per il testo.

## Prompt GPT Image — K0, telefono rotto (desktop 16:9)

Allega `assets/img/home/cinematic-v2/chapter-03/desktop/chapter-03-actual-last.png`
come riferimento del telefono e della crepa.

```text
Create a landscape 16:9 premium product photograph. Use the attached image ONLY
as reference for the smartphone: the same modern unbranded graphite phone with
a dark polished frame and the same pattern of cracked front glass. Ignore the
counter, hands, notebook and room.

The phone stands upright in a clean front view, very slightly turned (about
8 degrees), floating in an empty, seamless deep graphite space with the exact
background colour #101114, no visible floor, no horizon, no gradient bands.
Place the phone in the RIGHT HALF of the frame, centred at about 65% of the
width, its height about 70% of the image. The left half stays completely empty.

The display is powered off, black, with the cracked glass clearly readable:
one impact area and branching fractures, no missing pieces. Warm amber rim light
from the upper right traces the metal frame; a soft cool fill keeps the black
glass readable. Subtle realistic reflections. Sharp focus, true proportions.
No hands, no text, no logos, no watermark, no props, no particles, no glow halo.
```

## K1 — il display si stacca

Allega K0.

```text
Same phone, same lighting and the same #101114 empty graphite background as the
attached image, same position and size in the frame.

The phone has rotated about 35 degrees to the LEFT around its vertical axis,
now in a three-quarter view. The cracked front display module has detached and
moved straight forward towards the camera by a few centimetres, staying exactly
parallel to the phone body and still in one piece with the same cracks.
In the gap between display and body a thin warm amber light reveals a hint of
the neat dark interior. Only two layers. No screws, cables, particles or
exploded parts. No hands, no text, no logos, no watermark.
```

## K2 — il telefono aperto

Allega K0.

```text
Same phone, same lighting and the same #101114 empty graphite background as the
attached image, same position and size in the frame.

The display module has been removed and is no longer in the frame. The phone
body has rotated about 30 degrees to the RIGHT around its vertical axis.
Its open front shows a clean, believable interior: a large battery, a compact
logic board and a few tidy components and connectors, all neatly arranged,
softly lit by warm amber light. The metal frame stays intact.
No tools, no hands, no loose parts, no cables hanging, no particles, no text,
no logos, no watermark.
```

## K3 — riparato

Allega K0.

```text
Same phone, same lighting and the same #101114 empty graphite background as the
attached image, same position and size in the frame.

The phone is fully assembled again and back in the same clean front view as the
reference, very slightly turned. The front glass is now NEW and flawless: no
cracks at all. The display is switched on and shows a warm abstract amber
wallpaper of soft flowing dune-like curves (amber, honey and deep brown), no
icons, no text, no clock, no interface. The light of the screen gently warms the
metal frame. No hands, no text, no logos, no watermark, no particles.
```

## Versione mobile 9:16

Stessi quattro prompt, sostituendo la frase di composizione con:

```text
Portrait 9:16. Place the phone in the LOWER HALF of the frame, horizontally
centred, its height about 50% of the image. The upper half stays completely
empty #101114 graphite for text.
```

Per K0 mobile allegare `chapter-03/mobile/chapter-03-actual-last.png`; per K1–K3
mobile allegare il K0 mobile.

## Prompt Veo — rotazioni (desktop e mobile)

Rot-1: primo frame K0, ultimo frame K1.

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. The smartphone slowly rotates
about 35 degrees to the left around its vertical axis while the cracked front
display module separates and moves straight forward a few centimetres, staying
parallel and in one piece. A thin warm amber light appears in the gap. Smooth
acceleration and braking, hold the final pose for the last second. The camera
is static. Only these two layers move. No particles, no screws, no cables,
no hands, no text, no logos, no cuts, no morphing.
```

Rot-2: primo frame K1, ultimo frame K2.

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. The detached cracked display
slides away forward-left and leaves the frame; the phone body rotates slowly to
the right, about 65 degrees in total, revealing its neat interior with battery
and logic board in warm amber light. Smooth, calm motion, static camera, hold
the final pose for the last second. No particles, no tools, no hands, no loose
parts, no text, no logos, no cuts, no morphing.
```

Rot-3: primo frame K2, ultimo frame K3.

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. A new flawless display module
glides in from the front-left and seats precisely onto the phone body while the
phone rotates back to the clean front view. Once assembled, the screen lights up
softly with the warm amber dune-like wallpaper. Smooth, calm motion, static
camera, hold the final lit pose for the last 1.5 seconds. No cracks on the new
glass, no particles, no tools, no hands, no text, no logos, no cuts, no morphing.
```
