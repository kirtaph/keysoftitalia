# Capitolo 03 v2 — video della consegna

Finali consegnati e controllati il 10 ottobre 2026. Produzione esterna in
Flow / Veo 3.1: due clip indipendenti di 8 secondi, con primo e ultimo frame.

## Valutazione dei finali

Entrambi utilizzabili. Stesso banco in pietra, luce ambrata, scaffali e
notebook con il wallpaper a dune; Vito resta un busto in camicia bianca, senza
volto inventato. Telefono intero e senza marchio, vetro rotto leggibile, nessun
pezzo staccato. Mani della cliente naturali: la consegna è appena avvenuta e la
crepa resta scoperta. Nessun secondo telefono sul banco.

Note non bloccanti: la manica della cliente è una giacca in tweed beige sul
desktop e un maglione chiaro sul mobile (ogni visitatore vede una sola versione).
I finali sono 1672×941 e 941×1672: vanno bene come riferimento, Veo produce 1080p.

Il movimento è grande: dallo schermo che riempie il campo a una vista ampia
del banco. Il notebook nel finale è più piccolo, a sinistra e con un'angolazione
diversa; il prompt chiede un arretramento continuo seguito da uno spostamento
laterale, così il cambio di angolo nasce dal movimento della camera.

## Allegati e destinazioni

Percorsi relativi alla cartella del progetto `D:/webprojects/keysoftitalia`.

| Variante | Primo frame | Ultimo frame | Video da salvare |
| --- | --- | --- | --- |
| Desktop 16:9 | assets/img/home/cinematic-v2/chapter-02/desktop/chapter-02-actual-last.png | assets/img/home/cinematic-v2/chapter-03/desktop/last-frame-delivery.png | assets/img/home/cinematic-v2/chapter-03/desktop/chapter-03-delivery.mp4 |
| Mobile 9:16 | assets/img/home/cinematic-v2/chapter-02/mobile/chapter-02-actual-last.png | assets/img/home/cinematic-v2/chapter-03/mobile/last-frame-delivery.png | assets/img/home/cinematic-v2/chapter-03/mobile/chapter-03-delivery.mp4 |

Selezionare 8 secondi e il rapporto corretto in Flow. Allegare i due frame nei
rispettivi campi iniziale e finale. Non mescolare le varianti.

## Prompt Veo — desktop 16:9

```text
Create an 8-second landscape 16:9 cinematic continuous shot connecting the
supplied FIRST FRAME and LAST FRAME, in one physical environment.

Start exactly from the supplied first image: an extreme close-up of the amber
dune-like wallpaper on the notebook display, with the thin dark bezel at the
edges. Begin moving immediately.

From 0 to 3 seconds: the camera pulls straight back from the display. The same
notebook is revealed whole: bezel, lid, keyboard, then the warm stone counter
and the white-shirted man standing behind it, seen only from the chest down.
The wallpaper stays static and simply shrinks with the distance.

From 3 to 6 seconds: the camera travels laterally to the RIGHT along the
customer side of the same counter and tilts gently down. The notebook drifts
to the left background and becomes smaller, as in the last frame.
At about 4 seconds a customer's two hands with beige sleeves enter from the
right edge holding a dark smartphone; at about 5 seconds they lay it flat on
the counter, screen up, and the hand releases it. The cracked front glass is
already visible as the phone touches the counter. The phone stays intact.

From 6 to 7.6 seconds: decelerate smoothly into the exact supplied final
framing, the fingers resting beside the phone. Hold until 8 seconds.

Keep the SAME notebook, counter material, shelves, plants and warm lighting
throughout; nothing is redesigned. The man stays behind the counter with calm
hands on the counter or notebook; his face does not enter the frame. Exactly
one smartphone and exactly two customer hands, natural five-finger anatomy.
No tools, no dismantling, no detached screen, no floating parts, no text,
no logos, no watermark, no cuts, no morphing, no dissolves.

One continuous camera move, premium tactile product cinematography, smooth
acceleration and braking, realistic parallax between notebook, counter and room.
```

## Prompt Veo — mobile 9:16

```text
Create an 8-second portrait 9:16 cinematic continuous shot connecting the
supplied FIRST FRAME and LAST FRAME, in one physical environment.

Start exactly from the supplied first image: the amber dune-like wallpaper of
the notebook display fills the whole portrait frame. Begin moving immediately.

From 0 to 3 seconds: the camera pulls back. The landscape notebook appears
with its dark bezel and keyboard, then the warm stone counter and the
white-shirted man behind it, chest down only. The notebook keeps its real
landscape proportions; it is never stretched to fit the portrait frame.

From 3 to 6 seconds: the camera moves to the RIGHT along the customer side of
the counter and tilts down. The notebook settles in the upper-left background.
At about 4 seconds two customer hands with light knit sleeves enter from the
right holding a dark smartphone and lay it flat in the centre of the counter,
screen up; the cracked glass is visible and the phone remains intact.

From 6 to 7.6 seconds: decelerate into the exact supplied final framing, with
the phone in the lower-centre and both hands resting to its right. Hold until
8 seconds.

Same notebook, counter, shelves and warm light throughout. The man's face does
not enter the frame. Exactly one smartphone, exactly two customer hands,
natural anatomy. No tools, no dismantling, no detached screen, no floating
parts, no text, no logos, no watermark, no cuts, no morphing, no dissolves.
Premium tactile product cinematography, one continuous camera move.
```

## Cosa controllare nel video

- Il primo secondo deve partire davvero dal finale del capitolo 02, senza salto.
- Un solo telefono, sempre intero; nessuna mano in più o dita fuse.
- Il wallpaper del notebook resta fermo mentre si rimpicciolisce.
- Il volto dell'uomo non entra mai in campo.
- Se il generatore non riesce a tenere arretramento e spostamento in 8 secondi,
  tenere il video più stabile: il raccordo si può aiutare nella timeline del sito.

Dopo la consegna: derivati WebP + AVIF in `chapter-03/{desktop,mobile}/sequence/`,
nuova voce in `manifest.sources`, nuovi `beats` e un capitolo «03 La consegna»
nella barra dei capitoli. Nessun testo durante il movimento, come da piano.
