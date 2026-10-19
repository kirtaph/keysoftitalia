# Servizi — immagini di anteprima

19 ottobre 2026. Sei immagini nuove, una per servizio, per l'indice di `servizi.php`
(anteprima grande su desktop, miniatura su mobile). L'apertura resta il bancone della
home, per continuità con il film.

## Formato e consegna

- GPT Image 2.5 (Higgsfield `gpt_image_2_5`), qualità alta, risoluzione 2k, **4:5 verticale**.
- Il soggetto sta al centro, con margine su ogni lato: su mobile la miniatura è quadrata.
- File in `assets/img/servizi/` con questi nomi: `riparazioni`, `vendita`, `consulenza-it`,
  `sviluppo-web`, `gestionali`, `telefonia` (`.png` originale; io preparo i `.webp`).

## Stile comune (incluso in ogni prompt)

```text
Photorealistic premium editorial photograph, vertical 4:5. A calm, dark graphite
world close to #101114, lit by a single warm amber key light with a soft cool fill,
like a high-end product campaign shot in a real independent tech shop. Shallow depth
of field, realistic materials and scale, subject centred with breathing room on every
side. All devices unbranded. No readable text, no logos, no watermark, no UI with
words, no neon, no glowing circuitry, no floating particles, no smoke.
```

## 1 · Riparazioni

```text
[stile comune] Close view from slightly above of an independent repair bench: an
adult technician's two natural hands (no face) use a fine precision screwdriver on an
opened modern smartphone lying on a dark ESD mat; the display module rests beside it,
a small magnetic screw tray in soft focus. Warm task light rakes across the metal frame.
```

## 2 · Vendita e ricondizionati

```text
[stile comune] On a dark stone shop counter, a customer's hand receives a gleaming
refurbished smartphone in warm gold finish from the shop's hand across the counter; a
simple unprinted dark gift box lies open beside it. Behind, out of focus, a graphite
display shelf with a few tablets and laptops catching amber light. No faces.
```

## 3 · Consulenza IT e reti

```text
[stile comune] A tidy small-business network corner: a compact wall-mounted rack with a
dark network switch and neatly bundled ethernet cables in graphite and one warm accent
colour, tiny amber status LEDs; in the foreground a small unbranded white dome security
camera on a matte dark wall. Clean, orderly, professional. No people.
```

## 4 · Siti web

```text
[stile comune] A slim space-grey laptop open on a dark wooden-and-graphite desk, its
screen showing an elegant abstract website layout made only of shapes: a large warm amber
gradient hero area, blocks and image placeholders, no readable words. Beside it a
smartphone shows the same layout in mobile form. Warm desk lamp light from the side.
```

## 5 · Gestionali e web app

```text
[stile comune] A modern shop checkout counter in dark stone: an unbranded tablet on a slim
stand shows an abstract management dashboard with amber charts and tiles (no readable
text), next to a small receipt printer and a card terminal. Soft warm light, blurred
shelves behind. No people.
```

## 6 · Telefonia e casa

```text
[stile comune] Close-up of a hand inserting a SIM tray into a dark smartphone above a
graphite table; in soft focus behind, an unbranded white home fibre router with a few
small warm status lights, and a cosy dim living-room glow. No faces, no carrier logos.
```

## Controllo prima di accettare

- Nessuna scritta leggibile, logo o marchio riconoscibile.
- Mani anatomicamente corrette, nessun volto.
- Toni grafite e ambra coerenti tra le sei immagini; il soggetto si legge anche in miniatura quadrata.

## Aggiornamenti del 19 ottobre

### 1 · Riparazioni (rigenerata: le mani della prima versione sembravano in posa)

```text
Photorealistic premium editorial photograph, vertical 4:5, candid documentary moment captured
mid-action. A calm, dark graphite workshop close to #101114, lit by a single warm amber task lamp
with a soft cool fill. [...] Tight close-up from a low three-quarter angle at bench height of a
repair technician actually working on an opened smartphone held in a small black repair clamp on a
dark ESD mat. The right hand grips a slim precision screwdriver like a pen, the side of the hand and
wrist resting on the mat; the tip is seated inside a tiny screw on the internal metal shield. The
left hand steadies the phone frame with thumb and index fingertips pressing on its edge. Forearms
and sleeves enter from the bottom of the frame, no face. The removed display rests face down beside
the clamp.
```

Scelta la prima di due varianti; l'altra è in `sources/riparazioni-v2b.png`.

### 7 · Luce e gas (`forniture`)

```text
[stile comune] Evening in a cosy dim living room: an adult hand switches on a warm designer table
lamp on a dark wooden sideboard; beside it a small unbranded smart energy display shows only a soft
amber curve and a few dots, no numbers. In soft focus behind, a gas hob's faint flame glow from an
open kitchen. No faces.
```

## Versioni desktop e mobile (19 ottobre)

Ogni immagine ora esiste in due composizioni, generate con la stessa scena approvata come riferimento (`image_references` = job della versione 4:5):

- **Desktop 5:4** (`{nome}-desktop.webp`, 1500 px): soggetto nel 60% destro, 40% sinistro calmo e scuro, perché la scena del rullo copre il 64% destro dello schermo e la lente dissolve il lato sinistro. «Vendita» desktop è specchiata in orizzontale (soggetto generato a sinistra).
- **Mobile 9:16** (`{nome}-mobile.webp`, 900 px): soggetto nel 55% alto, 45% basso scuro per il rullo e il testo.
- **Apertura**: `negozio-desktop` (16:9, metà sinistra libera per il titolo) e `negozio-mobile` (9:16, parte bassa libera). Negozio la sera, senza persone: sostituisce il fotogramma della home.

Prompt di ricomposizione: «Use the attached image as the exact scene reference: same [soggetto]… Recompose it as a landscape 5:4 photograph: [soggetto] sit in the RIGHT 60% of the frame; the left 40% is calm, dark … with no important detail» / «… portrait 9:16 photograph: [soggetto] fill the UPPER 55% of the frame, fully visible and centred; the lower 45% … calm and empty, for text overlay». Originali PNG in `sources/`.

## Vendita (19 ottobre)

Apertura `vendita-apertura-*` (16:9 / 9:16): banco espositivo la sera con notebook, smartphone su supporto, tablet e auricolari; soggetto nel 55% destro. Categorie `vendita-cat-*` (5:4 / 9:16, soggetto nel 60% destro): PC desktop con monitor; tre smartphone su supporti e tablet; smart TV con soundbar; impastatrice, bollitore, phon e rasoio; telecamere IP e registratore; router, SSD, chiavetta e schede. Stile comune come sopra, in versione orizzontale.

MyShape `vendita-myshape-*`, seconda versione (la prima, con una striscia di pellicola che usciva dal plotter, non era realistica; ora in `sources/vendita-myshape-v1-*`). Il processo reale: plotter compatto da banco con fessura frontale e piccolo display, foglio di pellicola idrogel/TPU su supporto, taglio «kiss-cut» della sagoma senza tagliare il supporto, sagoma staccata dal foglio con la linguetta, poi allineamento e spatolina.

```text
[stile comune, 16:9, candid] Scene, physically accurate: on a dark counter lies a flat rectangular sheet
of thin, perfectly clear hydrogel screen-protector film on a slightly frosted backing liner, about the
size of a postcard. The cutting machine has kiss-cut a smartphone-shaped outline into it with rounded
corners and a small camera notch. A technician's two natural hands are peeling the freshly cut
phone-shaped protector up from the sheet by one corner; a small coloured pull tab sits on one short
edge. Beside the sheet, a smartphone lies face up. Behind, in soft focus, a compact shoebox-sized
desktop film cutter with a narrow front feed slot and a tiny dark touchscreen, a small stack of blank
film sheets. Subject in the LEFT 55%, right 45% calm and dark.
```
