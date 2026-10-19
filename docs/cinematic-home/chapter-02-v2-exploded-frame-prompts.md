# Capitolo 02 v2 — entrare nel dettaglio

**Superato il 9 ottobre 2026:** l'utente abbandona il disassemblaggio del notebook.
Direzione corrente: [avvicinamento al display](chapter-02-v2-screen-frame-prompts.md).
Questo documento e i relativi asset restano come archivio delle prove.

Direzione approvata dall'utente: dal notebook del capitolo 01 a una vista
esplosa controllata, poi un secondo film ravvicinato fra i componenti.
Produzione esterna con GPT Image per i frame e Veo 3.1 per clip di massimo
8 secondi. Questo documento riguarda soltanto il finale della prima clip.
La home corrente non viene modificata finché non abbiamo verificato gli asset.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| 02A — Entrare nel dettaglio | Dal banco la camera avanza, sale leggermente e rivela tre strati del notebook, in una rappresentazione 3D esplosa. Il banco e il set rimangono continui. | Durante il movimento nessun testo. Alla posa: «Ogni dettaglio conta.» |
| Pausa sulla composizione | La vista esplosa resta leggibile; titolo, descrizione e azione arrivano lateralmente sullo scroll. | «Diagnosi e riparazioni per smartphone e computer. Partiamo dal problema, troviamo la soluzione.» / «Richiedi una riparazione» |
| 02B — Il dettaglio | Seconda clip, da definire dopo il risultato di 02A: passaggio fra gli strati fino a un componente della scheda. | Nessun testo durante il passaggio ravvicinato. |

## Riferimenti e consegna

Non rigenerare l'apertura: il primo frame della clip 02A sarà il finale
effettivo della rispettiva clip 01, senza ritagli o modifiche.

| Variante | Immagine da allegare a GPT Image | Nuovo file da produrre |
| --- | --- | --- |
| Desktop 16:9 | assets/img/home/cinematic-v2/chapter-01/desktop/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/desktop/last-frame-exploded.png |
| Mobile 9:16 | assets/img/home/cinematic-v2/chapter-01/mobile/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/mobile/last-frame-exploded.png |

Massima qualità disponibile. Usare il riferimento della stessa variante.
Non serve allegare il volto o il logo: il finale si concentra sul dispositivo
e non introduce marchi, parole, altre persone o nuovo arredamento.

## Direzione del finale

Tre gruppi visivi, senza esplosione caotica di singoli pezzi:

1. Scocca inferiore appoggiata sul banco, nello stesso punto del notebook.
2. Un livello interno ordinato, poco sopra la scocca: scheda, batteria e
   raffreddamento con dimensioni credibili e disposizione coerente.
3. Tastiera con top case e trackpad, sollevati come un unico gruppo sopra
   gli interni. Tasti e trackpad non si separano dal pannello.

Lo schermo resta collegato al gruppo di base, con angolo di apertura e
contenuto del riferimento. La composizione è una visualizzazione 3D di
prodotto: non una fotografia di un notebook fisicamente sospeso in negozio.
Non aggiungere nuove azioni delle mani o un secondo volto.

## Prompt GPT Image — desktop 16:9

```text
Use the attached image as the exact scene and product reference. Create a
photorealistic premium 3D product-render END FRAME, landscape 16:9, for a
continuous camera shot starting from that image. This is the same silver
notebook on the same counter in the same warm architectural environment.

Move the viewpoint closer to the notebook, raise the camera modestly and
look downward at a readable high three-quarter angle, around 35 degrees
above the counter. Use a small lateral arc, not a new opposite-side view.
Make the internal structure visible with convincing physical perspective.

Show a restrained, precisely aligned exploded view with ONLY THREE main
groups: the lower metal chassis resting firmly on the original counter;
an orderly internal layer separated a few centimetres above it; and the
keyboard top-case with its original keyboard and trackpad lifted together
as one rigid panel above the internals. Keep the separation compact and
clearly readable, approximately 4 cm to the internal layer and 9 cm total
to the keyboard panel. Do not scatter individual keys, screws or chips.

The internal layer has plausible laptop-scale components: a compact dark
motherboard, flat battery modules and a coherent cooling assembly. Do not
fill the entire chassis with an oversized motherboard. Keep component
placement and metal boundaries mechanically credible. Treat the separated
groups as an intentional technical 3D product visualization.

The display remains attached to the base assembly at the original rear
hinge position, with the same opening angle, bezel shape and warm abstract
screen content. The raised keyboard panel clears the display and hinges;
no intersections or bent geometry. Preserve the notebook's proportions,
port positions, rounded corners, key layout, trackpad and silver finish.

Place the exploded notebook predominantly in the left 65 percent of the
landscape composition, leaving the right portion quieter for future HTML
copy: natural counter surface and soft distant environment, not an empty
black studio or a blank graphic panel. The device remains the hero.

The original person is behind the device and naturally outside the tighter,
downward camera crop; do not introduce new visible hands, faces or gestures.
Keep the exact counter material and warm amber light direction. Preserve
credible contact shadows under the base and soft shadows between layers.
Retain continuity with the supplied set wherever it remains visible; the
environment can fall softly out of focus but must not become a different room.

Sharp, tactile brushed metal, dark circuit board, realistic small components,
controlled reflections, clean premium product cinematography. No new logo,
words, labels, website text, watermark, holograms, luminous circuit effects,
particles, smoke, energy beams or props. Do not create a collage or render
multiple camera views. One complete landscape image.
```

## Prompt GPT Image — mobile 9:16

```text
Use the attached portrait image as the exact scene and notebook reference.
Create a photorealistic premium 3D product-render END FRAME in dedicated
vertical 9:16. This is the next camera view of the same silver notebook on
the same counter, not a crop of a landscape design and not a new product.

Advance towards the notebook and lift the camera modestly, looking down at
a high three-quarter angle around 35 degrees above the counter. Keep the
same side of the notebook and a small coherent lateral arc. Reveal the
internal structure through an intentional, compact exploded product view.

Use ONLY THREE precisely aligned main groups: the lower chassis firmly
resting on the same counter; an orderly internal layer approximately 4 cm
above it; and the original keyboard top-case with keyboard and trackpad
lifted together as one rigid panel approximately 9 cm above the base.
Keep the separated groups compact and visually clear. No floating individual
keys, scattered screws or dozens of independently flying components.

The internals use credible laptop-scale parts: a compact dark motherboard,
flat battery modules and a coherent cooling assembly. Keep mechanical
relationships believable. Preserve the original product proportions,
keyboard layout, trackpad, metal edges, port positions and silver finish.
The display stays attached to the original base hinge, at the same opening
angle, with the same bezel and warm abstract screen content. No intersections
between lifted keyboard panel, display, hinge or internal components.

Recompose the device for portrait: centre the important exposed components
and the three layered groups within the lower two-thirds, retaining a
quiet upper portion of natural counter or softly blurred original set
for a future short HTML headline. The counter fills every image edge.
Peripheral display edges may be naturally cropped if needed, but do not
crop away the internal layer or deform the laptop to force it into frame.

The original person naturally leaves the downward, closer camera crop.
No new face, hands, gestures, tools or props. Retain the same warm amber
lighting, counter material and visible architectural cues from the reference.
Do not replace the set with a black void, flat coloured background or a
different laboratory. Maintain realistic contact shadows beneath the base,
soft shadows between separated layers and restrained metal reflections.

Premium tactile product cinematography: sharp brushed metal, dark circuit
board, realistic small components and natural depth of field. This is an
intentional technical 3D visualization, not a physical floating repair.
No text, labels, logo, watermark, holograms, neon circuits, particles, smoke,
light beams or collage. One complete portrait image.
```

## Controllo prima del video

Verificare entrambe le varianti: stesso notebook, base nello stesso punto,
tre gruppi leggibili, tastiera e trackpad integri, display stabile, nessuna
intersezione, niente oggetti o mani comparsi dal nulla. Il finale mobile
deve conservare spazio per testo e navigazione senza rendere minuscolo il
dispositivo. Confrontare angoli di camera e luce con il riferimento: i prompt
sono una direzione, non una garanzia di continuità.

Preparare il prompt Veo della clip 02A solo dopo l'ispezione dei due nuovi
frame. Decidere il dettaglio finale di 02B dal risultato effettivo, evitando
di generare ora una scheda diversa da quella che verrà vista in 02A.
