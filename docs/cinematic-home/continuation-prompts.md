# Un racconto che continua — prompt per la produzione esterna

> Direzione aggiornata: i capitoli 02 e 03 qui sotto sono già stati prodotti.
> Il seguito ora include altri due filmati e una composizione laterale di foto,
> testi e catalogo. Vedi [Capitoli 04–05](next-chapters-prompts.md).
> Il passaggio diretto alle sezioni fotografiche descritto qui era la prima bozza.

La prima sequenza resta: guasto → componenti → cura → telefono riparato.
La continuazione aggiunge due filmati, nello stesso studio e con gli stessi
materiali. Poi il racconto passa alle fotografie reali del negozio e ai contatti.
I testi e i pulsanti saranno HTML, aggiunti nel sito e mai generati nel film.

## Regole comuni

- 16:9, almeno 1920×1080, preferibilmente 24 fps, MP4 originale, senza audio.
- Studio quasi nero, luce arancione tenue, riflessi bianchi morbidi, realismo
  di vetro e metallo. Usare le immagini di riferimento, non soltanto il testo.
- Oggetti senza marchi, scritte, watermark o interfacce leggibili.
- Movimento continuo, controllato e leggibile anche quando lo scroll torna indietro.
- Spazio tranquillo nel 35–40% sinistro per i testi. Tenere il soggetto principale
  intorno al 60% della larghezza, senza spostarlo verso il bordo destro. Per mobile
  valuteremo il ritaglio sul soggetto principale, non l'intero gruppo di dispositivi.
- Non usare transizioni automatiche, dissolvenze, stacchi o zoom improvvisi.

## Capitolo 02 — Scegliere

### Immagine iniziale: già pronta

Usare **assets/img/home/story-continuation/chapter-02-first.png** direttamente
come start frame. È l'ultimo fotogramma del video originale, estratto in Full HD.
Non rigenerarlo: un raccordo tra due immagini simili può comunque essere visibile.

### Immagine finale — generare con lo start frame come riferimento

```text
Create the final keyframe of a premium photorealistic product film, using the
attached image as the authoritative reference for the smartphone and studio.
Landscape 16:9, at least 1920x1080.

Keep exactly the same repaired smartphone: identical proportions, notch,
rounded corners, metal edges, buttons, intact glass, orange abstract screen and
three-quarter front orientation. Do not redesign or replace it.

The camera has slowly pulled back. Reveal a wider composition on the same dark
matte studio floor: the original smartphone at the front and an unbranded
graphite notebook behind it, slightly to its right, open at approximately 105
degrees. The notebook has believable keyboard, hinge and trackpad geometry.
Its screen displays only a soft abstract amber field, without interface or text.
Only these two devices are present. The notebook was outside the original
camera framing; this is a camera reveal, not a magical appearance.

Place both devices within the middle-right portion of the frame. Keep the left
35–40 percent quiet and almost black. The notebook screen centre is around
60 percent of frame width, allowing a later central portrait crop. Maintain
the reference's near-black seamless studio, warm restrained orange rim light,
soft white reflections, physical contact shadows and tangible metal and glass.
Natural product photography, coherent perspective, no levitation.

No text, logos, watermarks, UI, people, hands, tools, floating accessories,
particles, neon grids, holograms, ornamental rings or additional devices.
```

Salvare: **chapter-02-last.png**.

### Filmato — 8 secondi, con entrambe le immagini

```text
Create one continuous 8-second photorealistic product film connecting the
provided first and final keyframes. Begin with the first image exactly and
finish with the final image exactly. Landscape 16:9, minimum 1080p, silent.

The original repaired graphite smartphone remains the exact same physical
object throughout: preserve its notch, buttons, frame, glass and orange screen.
The studio floor, light direction and material identity stay consistent.

0–1 seconds: a very gentle continuation of the original shot, with the phone
stable and intact. No new rotation or sudden camera-speed change.
1–6 seconds: smoothly dolly backwards and slightly sideways, revealing the
open graphite notebook already standing behind the phone, outside the initial
framing. It enters naturally at the frame boundary as the field of view widens.
Both devices remain stationary on the same floor. The camera reveals them;
they do not grow, spawn, morph, slide or assemble. Preserve credible perspective,
hinge geometry, contact shadows and reflections.
6–8 seconds: ease into the supplied final composition and settle calmly.
Keep a useful readable hold near the end. The left side remains dark and quiet
for typography added later by the website.

One controlled camera move, no cuts, no dissolves, no scene changes, no camera
jumps, no focus pumping and no heavy motion blur. Motion must look natural
when scrubbed backwards. Screens show only abstract amber fields. No words,
logos, watermarks, people, hands, particles, holograms or extra devices.
```

Salvare: **chapter-02.mp4**.

Testo previsto nel sito: **«La prossima possibilità è tua.»**
Catalogo e valutazione dell'usato entrano mentre il gruppo di dispositivi si
stabilizza. La vetrina reale resta navigabile: non viene disegnata nel filmato.

## Capitolo 03 — Creare

### Immagine iniziale

Dopo aver controllato chapter-02.mp4, estrarre il suo ultimo fotogramma effettivo
e usarlo come **chapter-03-first.png**. Posso farlo io quando consegni il video.
Per preparare una bozza dell'immagine finale puoi già usare chapter-02-last.png,
ma il video definitivo deve partire dal fotogramma realmente raggiunto.

### Immagine finale — generare con l'immagine del gruppo come riferimento

```text
Create the final keyframe of the next continuous product-film chapter, using
the attached studio composition as the authoritative reference. Landscape
16:9, minimum 1920x1080. Preserve the exact same graphite notebook, keyboard,
hinge, trackpad, studio floor, orange lighting and photographic realism.

The camera has moved closer to the existing open notebook and slightly changed
angle so its screen is clearly readable as a physical surface, with the lower
keyboard and hinge still visible. The original phone is now outside this
tighter crop; it has not transformed or disappeared from the physical scene.
Place the notebook predominantly centre-right, with its screen centre near
60 percent of frame width. Keep the left 35 percent dark and quiet for later
HTML typography. Retain perspective depth and soft white glass reflections.

On the notebook screen show a clean, unbranded abstract website composition:
dark graphite background, one large muted orange rectangular image panel,
two small neutral image panels and a few simple thin grey horizontal rules.
No letters, numbers, text-like glyphs, icons, logos, menus or readable labels.
All shapes are contained inside the screen and follow its perspective. This
is a visual metaphor for web design, not a screenshot of a real project.

No holographic objects, no flying UI, no particles, no neon grid, no people,
hands, watermarks, branding or additional objects. Premium tangible studio
product photography matching the reference, not a futuristic illustration.
```

Salvare: **chapter-03-last.png**.

### Filmato — 8 secondi, con start frame effettivo e immagine finale

```text
Create a continuous 8-second photorealistic product film connecting the supplied
first and final keyframes. Landscape 16:9, minimum 1080p, silent. Start exactly
on the first frame. Maintain the identity of both stationary physical devices,
their materials, floor, shadows and lighting throughout.

0–1 seconds: briefly maintain the wider phone-and-notebook composition.
1–5 seconds: smoothly move the camera closer to the existing open notebook,
with a slight lateral arc that keeps its screen visible. The phone naturally
leaves the image because of the tighter camera crop; it does not dissolve,
transform or vanish. The notebook never changes shape, hinge angle or keyboard.
Do not travel through the screen or create a new environment.
3–6 seconds: only within the physical notebook display, the initial soft amber
field gently settles into the simple dark abstract website layout shown in
the final reference. Use a restrained screen-content transition. No floating
panels, text, glyphs or elements escaping the display.
6–8 seconds: slow down and settle into the exact final frame, leaving a calm
hold for the website's HTML headline and call to action. Keep the left side
dark and uncluttered throughout.

One coherent camera movement, physically plausible perspective, stable
reflections, no cuts, jump zooms, dissolves, geometry morphing, shaky motion,
heavy motion blur, words, logos, watermarks, people or additional devices.
Motion must remain legible when the website scrubs it backwards.
```

Salvare: **chapter-03.mp4**.

Testo previsto nel sito: **«Dalle tue idee, a qualcosa che funziona.»**
Sviluppo web e consulenza IT entrano sulla pausa finale. Da questa scena passiamo
alle fotografie reali di Key Soft Italia: **«Dietro la tecnologia, ci siamo noi.»**
La chiusura contiene indirizzo, orari e contatti. Nessun negozio o team generato.

## Ordine di produzione e consegna

1. Genera chapter-02-last.png usando il frame iniziale estratto come riferimento.
2. Genera chapter-02.mp4 con start e end frame. Consegna immagine e video originali.
3. Controlliamo insieme il raccordo; estraggo il vero ultimo frame per il capitolo 03.
4. Genera chapter-03-last.png e chapter-03.mp4 con i riferimenti approvati.

Cartella di consegna: **assets/img/home/story-continuation/**. Lascia gli asset
del primo filmato invariati. L'ordine evita di spendere sulla seconda sequenza
prima di avere un raccordo utilizzabile. I prompt riducono le incoerenze, ma
non garantiscono che il generatore rispetti ogni dettaglio: valuteremo il risultato.

L'integrazione coordinerà film e testi in uno stesso racconto, con pause di
lettura, salto ai contenuti utili e una versione statica per movimento ridotto.
Non aggiungeremo semplicemente altri due blocchi lunghi quanto il primo:
la distanza di scroll va calibrata sul movimento ottenuto e sul mobile.
