# Capitolo 02A v2 — dal notebook alla vista esplosa

**Superato il 9 ottobre 2026:** non proseguire questa produzione.
Nuova direzione approvata: [notebook integro e avvicinamento al display](chapter-02-v2-screen-frame-prompts.md).
Originali e prove precedenti vengono conservati.

Frame ricevuti e ispezionati l'8 ottobre 2026. Produzione esterna dell'utente
con Veo 3.1: due video dedicati, ciascuno di massimo 8 secondi.
Non generare ancora la clip 02B.

## Verifica dei nuovi finali

Tre gruppi ben distinguibili: scocca sul banco, livello interno con batteria,
scheda e ventola, pannello tastiera/trackpad sopra. Materiali, illuminazione
ambra e schermata astratta seguono la direzione del capitolo 01.
Desktop: prodotto a sinistra e banco più tranquillo a destra per il testo.
Mobile: composizione verticale con spazio sopra; il notebook è più intero
e meno ravvicinato rispetto all'apertura, quindi la camera deve aprire
leggermente l'inquadratura, salire e compiere un arco breve. Non imporre un
avvicinamento continuo che contraddirebbe il finale consegnato.

Sono finali adatti alla prova video, non una certificazione della continuità.
La persona è assente nei nuovi finali: la ripresa deve portarla fuori campo
per inquadratura, senza dissolverla o trasformarla nello sfondo. La relazione
fra cerniera, display e strati è illustrativa: controllare la stabilità del
gruppo posteriore nel film e rifiutare pieghe o intersezioni introdotte dal modello.

## File da caricare nel generatore

Usare l'apertura come first frame e la vista esplosa come last frame.
Non rigenerare né ritagliare l'apertura. Non mescolare le due varianti.

| Variante | First frame | Last frame | MP4 da consegnare |
| --- | --- | --- | --- |
| Desktop 16:9 | assets/img/home/cinematic-v2/chapter-01/desktop/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/desktop/last-frame-exploded.png | assets/img/home/cinematic-v2/chapter-02/desktop/chapter-02a-exploded.mp4 |
| Mobile 9:16 | assets/img/home/cinematic-v2/chapter-01/mobile/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/mobile/last-frame-exploded.png | assets/img/home/cinematic-v2/chapter-02/mobile/chapter-02a-exploded.mp4 |

Impostare 8 secondi, qualità massima disponibile, nessun audio richiesto.
Nella home il testo resta HTML e non deve essere impresso nel filmato.

## Prompt Veo — desktop 16:9

```text
Create one continuous 8-second cinematic 3D product visualization connecting
the supplied FIRST FRAME and LAST FRAME. Landscape 16:9. Begin on the exact
assembled silver notebook on the counter and finish on the supplied compact
three-layer exploded view. Preserve the warm amber environment, counter
material, notebook identity and lighting. No cut or fade between scenes.

The main action is a decisive, controlled separation of the notebook's
structural groups, accompanied by a short smooth camera rise and lateral arc.
This must be visibly animated mechanical product choreography, not a slow
zoom on a still image. The base stays planted in its original location.
Use rigid-body motion: no stretching, dissolving or rebuilding parts.

0–1.5 seconds: begin moving promptly with a short acceleration. Raise the
camera modestly and arc towards the notebook so the original person moves
naturally outside the upper/right crop. He remains stationary behind the
counter; his white shirt and hands must not fade, deform, retract or become
the background. Choose framing and a coherent camera path to reach the
supplied final view. Do not add a gesture or a new person.

1.5–4 seconds: the keyboard top-case and trackpad rise together as ONE rigid
panel, exposing the internals. Keep the entire keyboard layout fixed to
the panel. Individual keys do not fly or spread. The camera continues its
short arc with clear foreground/background parallax.

3–6 seconds: the orderly internal assembly separates slightly above the
lower chassis, settling into the three grouped levels shown in the final
image. Battery modules, motherboard and cooling components retain their
shape, scale and relative arrangement. Reveal them from beneath the keyboard,
not by materializing them out of thin air. No scattering of chips or screws.

6–7.6 seconds: brake the camera and the two raised groups smoothly into the
exact supplied final composition. Preserve the screen as a rigid rear display
assembly with its hinge geometry and warm abstract image; no bending screen,
changing lid angle, wobbling hinges or collision with the keyboard panel.
Use the final reference to resolve the rear assembly's position relative to
the separated groups. Leave the device predominantly on the left.

7.6–8 seconds: a brief stable ending. No long idle tail, extra object motion
or additional zoom. No floating bobbing after the layers settle.

Maintain brushed silver metal, fixed key shapes, trackpad, chassis edges
and coherent contact shadows. The base never slides on the counter. Preserve
the reference architecture, window and warm practical lights as rigid parts
of the same environment; camera movement may reveal or crop them but must not
rebuild the room. Focus on the product with natural depth of field, not heavy
blur that hides geometry. No visible component intersections or teleportation.

One continuous take; no cut, crossfade, black transition, new scene, words,
logos, labels, watermark, holograms, neon circuitry, particles, smoke, beams,
additional props or tools. No speech, music or sound effects. The coordinated
camera and rigid assembly movements must remain coherent when scrubbed
backward by the website's native scroll.
```

## Prompt Veo — mobile 9:16

```text
Create one continuous 8-second cinematic 3D product visualization in dedicated
vertical 9:16, using the supplied portrait FIRST FRAME and LAST FRAME.
Begin exactly on the assembled notebook and white-shirted person's cropped
torso and hands. Finish exactly on the compact three-layer exploded notebook
in the supplied portrait composition. Same notebook, counter, amber light
and architectural environment throughout. No cut or fade.

The final reference opens the framing slightly and shows the whole layered
device lower in the portrait image. Achieve this with a short camera rise,
modest pullback and coherent lateral arc towards the notebook. Do not force
a forward zoom that would contradict that ending. Keep the product prominent.
The primary visible action is the controlled mechanical separation of its
groups, not a barely moving portrait or background animation.

0–1.5 seconds: start the camera promptly, raising it modestly and opening
the framing with a small lateral arc. Naturally crop the stationary original
person out through the upper/right edge. He must not vanish, dissolve, slide
away, change his hands or turn into the room. No new person or gesture.

1.5–4 seconds: lift the keyboard top-case and trackpad together as ONE rigid
panel. Keep every key fixed in that panel. Expose the internal assembly
beneath it while retaining the lower chassis on the same spot of the counter.
Make the separation energetic and clearly visible, with a short smooth
acceleration rather than several seconds of inactivity.

3–6 seconds: lift the orderly internal assembly slightly above the lower
chassis and separate the three grouped levels to match the final reference.
Motherboard, battery modules and cooling assembly keep their geometry and
relative placement. No individual flying chips, loose screws or scattered
keys. Reveal components as the top-case clears them; do not fade them into
existence. Maintain realistic soft shadows between groups.

6–7.6 seconds: decelerate into the exact supplied final portrait. The exposed
components remain readable in the lower two-thirds, with quieter upper space
as shown in the final reference. Preserve the rigid rear screen and hinge
assembly, its opening angle and warm screen content. Do not warp or shrink
the laptop to fit the portrait or collide the keyboard panel with the screen.
Resolve the rear assembly's final position from the supplied end image.

7.6–8 seconds: brief settled end, with no extra zoom, bobbing or long static
tail. The two raised groups remain still above the grounded lower chassis.

Preserve the original silver finish, keyboard layout, trackpad, chassis
boundaries, rigid components and coherent contact shadows. The counter,
shelves, plants, window and amber practical lights remain the same fixed
environment. Use natural perspective and focus; no fisheye, abrupt focus
pumping or heavy blur to conceal morphing. Fill every edge with the scene.

One shot, no cut, crossfade, black transition, background replacement,
teleportation, geometry morphing, labels, words, logo, watermark, holograms,
neon circuits, particles, smoke, light beams, new props or tools. Silent:
no speech, music or sound effects. Camera and rigid group motion must remain
spatially coherent when the website scrubs this clip both forward and backward.
```

## Verifica alla consegna

Controllare durata effettiva e dimensioni degli originali; il precedente
generatore ha prodotto clip di 10 secondi nonostante il brief da 8.
Ispezionare primo, intermedi e ultimo frame, soprattutto: uscita della persona,
scocca ferma, gruppi rigidi, cerniere stabili e nessuna apparizione improvvisa.
Se il raccordo iniziale cambia posa o luce, segnalarlo anziché chiamarlo
continuo. Estrarre i finali realmente raggiunti per il primo frame della clip 02B.
Non produrre 02B prima di questa verifica.
