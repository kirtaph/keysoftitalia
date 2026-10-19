# Laboratorio — il banco esploso

**Abbandonato il 17 ottobre 2026:** dopo il film di «Come funziona» un secondo
smartphone che si apre, poi notebook e console, sarebbe ripetitivo. Le riparazioni
restano immagini e testi curati. Documento conservato come archivio.

17 ottobre 2026. Prompt per le tre scene della sezione riparazioni di
`servizi/riparazioni.php`. Le genera l'utente; il sito le integra dopo.

## Come funzionerà sulla pagina

Per ogni categoria la scena resta fissata mentre scorri. Il dispositivo intero si
apre a strati (prima metà dello scroll), poi resta esploso e, voce dopo voce,
si accende il componente della riparazione: un anello e un'etichetta in HTML
sopra l'immagine, non dentro i pixel. Al passaggio del mouse su una voce
dell'elenco si accende subito il suo componente. A sinistra (su telefono in alto)
c'è l'elenco delle riparazioni.

| Scena | Dal frame iniziale al finale | Componenti che il sito evidenzia |
| --- | --- | --- |
| Smartphone e tablet | Telefono riparato e acceso → esploso in cinque strati | Display · Batteria · Fotocamera · Altoparlante e microfono · Connettore di ricarica · Scheda (danni da liquidi) |
| Computer e notebook | Notebook aperto e acceso → esploso in quattro strati | Schermo (sistema operativo, virus, formattazione) · Tastiera e cerniere · Batteria · SSD (recupero dati, upgrade) · RAM · Scheda madre e ventola |
| Console, TV e micro-saldature | Console chiusa → esplosa in cinque strati | Chip e micro-componenti della scheda · Porta HDMI · Ventola e pasta termica · Lettore · Alimentatore (Smart TV e monitor) |

Per «Smart TV e monitor» l'evidenza cade sull'alimentatore della console: è una
rappresentazione illustrativa del tipo di intervento (alimentazione e schede), non
un televisore. Se preferisci una TV vera, servirebbe una quarta scena.

## Regole comuni (valgono per ogni prompt)

- Fondo uniforme #101114, lo stesso grafite della pagina: niente pavimento,
  orizzonte, bande o sfumature. Il sito applica la stessa lente della home.
- Luce: taglio caldo ambrato dall'alto a destra sul metallo, riempimento freddo
  morbido. Come il telefono del capitolo 04 della home.
- Dispositivi **senza marchio**, che non ricordino prodotti reali riconoscibili.
- Esploso **ordinato**: pochi gruppi paralleli, distanziati in modo regolare
  lungo un solo asse, tutti leggibili. Niente viti volanti, cavi, particelle.
- Nessun testo, logo, filigrana, mano, utensile o etichetta nei pixel.
- Il finale esploso deve essere **fermo e nitido**: ci appoggio sopra gli
  indicatori HTML, quindi ogni componente va riconosciuto a colpo d'occhio.

## Composizione

- **Desktop 16:9:** dispositivo nella metà destra, centro a circa 65% della
  larghezza, ingombro massimo circa 75% dell'altezza. Metà sinistra vuota.
- **Mobile 9:16:** dispositivo nella metà bassa, centrato, ingombro circa 50%
  dell'altezza. Metà alta vuota per l'elenco.

## Produzione, a tappe

Procediamo per risparmiare crediti: prima il **finale smartphone desktop**, me lo
mostri, lo controllo; poi la clip; poi il resto.

1. Immagine (GPT Image 2.5 o equivalente): genera il **finale esploso**. Per
   smartphone si parte da un frame che esiste già; per notebook e console genera
   prima il frame iniziale, poi il finale allegando l'iniziale come riferimento.
2. Video con primo e ultimo frame (Kling O3 first/last frame, o Veo 3.1):
   clip da 8 secondi, iniziale → finale.
3. Ripeti in 9:16 per il mobile, allegando come riferimento il desktop corrispondente.

Consegna in `assets/img/laboratorio/banco/`:

| Scena | Desktop | Mobile |
| --- | --- | --- |
| Smartphone | `smartphone/desktop/start.png`, `end.png`, `explode.mp4` | `smartphone/mobile/start.png`, `end.png`, `explode.mp4` |
| Notebook | `notebook/desktop/start.png`, `end.png`, `explode.mp4` | `notebook/mobile/…` |
| Console | `console/desktop/start.png`, `end.png`, `explode.mp4` | `console/mobile/…` |

Io estraggo le sequenze di frame (AVIF/WebP) e misuro le posizioni dei componenti.

---

## 1 · Smartphone

### Frame iniziale (esiste già, non rigenerare)

- Desktop: `assets/img/home/cinematic-v2/chapter-04/desktop/k3-repaired.png`
- Mobile: `assets/img/home/cinematic-v2/chapter-04/mobile/k3-repaired.webp`

È il telefono riparato e acceso con cui finisce «Come funziona»: la scena delle
riparazioni riprende lo stesso oggetto. Copialo come `smartphone/*/start.png`.

### Finale esploso — desktop 16:9

Allega `k3-repaired.png` desktop.

```text
Use the attached image as the exact product, lighting and background reference:
the same unbranded smartphone with the polished warm gold frame, on the same
seamless #101114 graphite background. Create a photorealistic premium 3D product
render END FRAME, landscape 16:9: an orderly exploded view of this phone.

Turn the phone to a clear three-quarter view, about 30 degrees to the left, and
separate it into FIVE parallel layers spaced evenly along its depth axis, each
layer offset slightly up and to the left of the one behind it so all of them stay
readable, like a technical exploded diagram:
1. front: the display module, glass and panel in one piece, screen OFF, black;
2. the metal mid-frame, with the small bottom loudspeaker grille module and the
   USB-C charging port clearly visible on its lower edge;
3. the flat rectangular battery;
4. the logic board with its chips, and the rear camera module lifted a few
   millimetres from the board so the three lenses are distinct;
5. back: the rear glass panel.

Keep the whole group in the RIGHT HALF of the frame, centred at about 65% of the
width, total height about 75% of the image. The left half stays completely empty
#101114. Warm amber rim light from the upper right on every metal edge, soft cool
fill, sharp focus on all layers, realistic scale and proportions.
No screws, no cables, no particles, no glow halos, no hands, no tools, no text,
no labels, no logos, no watermark.
```

### Finale esploso — mobile 9:16

Allega `k3-repaired.webp` mobile **e** il finale desktop appena accettato.

```text
Same exploded smartphone as the second attached image, identical layers, order,
materials and lighting, on the same seamless #101114 background. Portrait 9:16.
Stack the five layers along a diagonal that rises from lower right to upper left,
evenly spaced and all readable. Place the group in the LOWER HALF of the frame,
horizontally centred, about 50% of the image height. The upper half stays
completely empty #101114. No screws, no cables, no particles, no hands, no text,
no labels, no logos, no watermark.
```

### Clip — desktop e mobile

Primo frame `start.png`, ultimo frame `end.png` della stessa variante.

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. The lit smartphone screen fades
to black, then the phone turns calmly to the three-quarter view while its parts
separate in order, front to back, sliding straight apart along one axis into the
five evenly spaced layers: display, mid-frame, battery, logic board with camera
module, rear glass. Smooth acceleration and braking, static camera, hold the
final exploded pose for the last 1.5 seconds. Only these parts move.
No particles, no screws, no cables, no hands, no text, no logos, no cuts,
no morphing.
```

---

## 2 · Notebook

### Frame iniziale — desktop 16:9

Allega come riferimento di stile `assets/img/home/cinematic-v2/chapter-04/desktop/k3-repaired.png`
(sfondo, luce, schermo ambrato).

```text
Photorealistic premium 3D product render, landscape 16:9, on a seamless empty
#101114 graphite background, matching the lighting and the warm amber dune-like
wallpaper of the attached reference. An unbranded slim laptop in dark
space-grey aluminium, open at about 110 degrees, seen from a high three-quarter
angle (about 30 degrees above, rotated about 35 degrees to the left). The screen
is ON with the same warm amber dune wallpaper as the reference phone.

Place the laptop in the RIGHT HALF of the frame, centred at about 65% of the
width, about 60% of the image height. The left half stays completely empty
#101114. Warm amber rim light from the upper right along the aluminium edges,
soft cool fill. No floor, no shadow plane, no keyboard backlight colours, no
stickers, no text, no logos, no watermark, no hands, no props.
```

### Finale esploso — desktop 16:9

Allega il frame iniziale del notebook appena accettato.

```text
Use the attached image as the exact product, camera angle, lighting and
background reference. Create the END FRAME, landscape 16:9: an orderly exploded
view of the same laptop, as a premium 3D product visualization.

The screen lid stays attached and ON with the same amber wallpaper. The base
separates vertically into FOUR evenly spaced horizontal layers, rising straight
up above each other and all readable from this high three-quarter angle:
1. top: the keyboard deck with keys and trackpad as one piece;
2. the internal layer: the motherboard with a black cooling fan and copper heat
   pipe, two RAM modules in their slots, one M.2 SSD clearly visible, and the flat
   battery pack, arranged realistically as in a real laptop;
3. a thin internal metal frame;
4. bottom: the bottom cover with ventilation slots.

Keep the whole group in the RIGHT HALF of the frame, centred at about 65% of the
width, total height at most 75% of the image. The left half stays completely
empty #101114. Same warm rim light, sharp focus on every layer, believable
scale. No screws, no cables, no particles, no glow halos, no hands, no tools,
no text, no labels, no logos, no watermark.
```

### Mobile 9:16 (iniziale e finale)

Stessi due prompt, allegando ogni volta anche il corrispondente desktop accettato,
e sostituendo la frase di composizione con:

```text
Portrait 9:16. Place the laptop in the LOWER HALF of the frame, horizontally
centred, about 50% of the image height, slightly smaller so the whole exploded
stack fits. The upper half stays completely empty #101114.
```

### Clip — desktop e mobile

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. The open laptop stays still while
its base separates calmly, layer by layer from the top: the keyboard deck lifts
straight up, then the internal board layer with fan, RAM, SSD and battery, then
the thin frame, leaving the bottom cover in place. Even spacing, smooth
acceleration and braking, static camera, the screen stays on throughout, hold
the final exploded pose for the last 1.5 seconds. No particles, no screws, no
cables, no hands, no text, no logos, no cuts, no morphing.
```

---

## 3 · Console

### Frame iniziale — desktop 16:9

Allega lo stesso riferimento di stile (`k3-repaired.png` desktop).

```text
Photorealistic premium 3D product render, landscape 16:9, on a seamless empty
#101114 graphite background, matching the lighting of the attached reference.
An UNBRANDED home video game console with a simple, original design that does
not resemble any real product: a flat rectangular slab in matte graphite with a
thin warm metallic accent line, lying horizontally, seen from a high
three-quarter angle (about 30 degrees above, rotated about 35 degrees to the
left). Front edge with a slim disc slot; on the back, visible ports including one
HDMI port.

Place the console in the RIGHT HALF of the frame, centred at about 65% of the
width, about 40% of the image height. The left half stays completely empty
#101114. Warm amber rim light from the upper right, soft cool fill. No
controller, no cables, no stand, no lights or LEDs, no text, no logos, no
watermark, no hands.
```

### Finale esploso — desktop 16:9

Allega il frame iniziale della console appena accettato.

```text
Use the attached image as the exact product, camera angle, lighting and
background reference. Create the END FRAME, landscape 16:9: an orderly exploded
view of the same unbranded console, as a premium 3D product visualization.

The console separates vertically into FIVE evenly spaced horizontal layers,
rising straight up above each other and all readable:
1. top: the upper outer shell;
2. a large black cooling fan next to a metal heatsink, and directly below it
   the main processor chip with a visible layer of grey thermal paste;
3. the green-black mainboard with fine micro components and chips, the HDMI port
   clearly visible on its rear edge;
4. a compact optical disc drive and, beside it, a rectangular power supply unit
   with its small board;
5. bottom: the lower outer shell.

Keep the whole group in the RIGHT HALF of the frame, centred at about 65% of the
width, total height at most 75% of the image. The left half stays completely
empty #101114. Same warm rim light, sharp focus on every layer, believable
scale. No screws, no cables, no particles, no glow halos, no hands, no tools,
no text, no labels, no logos, no watermark.
```

### Mobile 9:16 (iniziale e finale)

Stessi due prompt, allegando ogni volta anche il desktop accettato, con:

```text
Portrait 9:16. Place the console in the LOWER HALF of the frame, horizontally
centred, about 45% of the image height, so the whole exploded stack fits.
The upper half stays completely empty #101114.
```

### Clip — desktop e mobile

```text
8-second continuous product shot on a seamless #101114 graphite background,
connecting the supplied first and last frame. The closed console stays still
while it opens calmly from the top: the upper shell lifts straight up, then the
fan and heatsink, then the mainboard, then the disc drive and power supply,
leaving the lower shell in place. Even spacing, smooth acceleration and braking,
static camera, hold the final exploded pose for the last 1.5 seconds.
No particles, no screws, no cables, no hands, no text, no logos, no cuts,
no morphing.
```

---

## Cosa controllare prima di accettare un finale

- Tutti gli strati richiesti ci sono, nell'ordine, distanziati, senza pezzi inventati.
- Ogni componente da evidenziare si riconosce (batteria, fotocamera, porta USB-C,
  altoparlante, SSD, RAM, ventola, porta HDMI, chip, alimentatore).
- La metà vuota (sinistra su desktop, alta su mobile) è davvero vuota e #101114.
- Nessun marchio, scritta o forma che ricordi un prodotto reale.
- Il frame iniziale e il finale hanno lo stesso oggetto, colore e luce: altrimenti
  la clip si «trasforma» invece di aprirsi.
