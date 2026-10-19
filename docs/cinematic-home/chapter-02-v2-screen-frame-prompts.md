# Capitolo 02 v2 — assistenza informatica, avvicinamento al display

Direzione aggiornata il 9 ottobre 2026, su richiesta dell'utente dopo le
difficoltà di Flow con il disassemblaggio del notebook. Questo documento
sostituisce per la produzione corrente i prompt della vista esplosa.
Originali e prove precedenti restano conservati. La home non viene modificata
prima della consegna e verifica dei nuovi filmati.

Produzione esterna: GPT Image per i finali, Flow/Veo 3.1 per clip di massimo
8 secondi. In questa fase produrre soltanto due immagini finali.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| 02 — Assistenza informatica | Dal notebook integro del finale 01, la camera avanza e si orienta verso lo schermo. Il display arriva a riempire il campo. Una sola azione: movimento di camera, senza smontaggio né cambio di software. | «Il tuo computer, di nuovo al tuo fianco.» / «Diagnosi, riparazioni e configurazione di PC e notebook. A Ginosa, con qualcuno che ti ascolta.» / «Richiedi una riparazione» |
| 03 — La consegna | Dal display pieno, la camera arretra e scorre sul banco. Le mani di una cliente appoggiano un telefono con il vetro rotto. | Passaggio libero dal testo durante il movimento. |
| 04 — Il display | Dopo il ritiro delle mani, in una clip dedicata il solo modulo display si separa dal telefono. Se il movimento non risulta stabile, pianificare un raccordo di camera tra due pose. | «Un imprevisto. Una soluzione.» / Diagnosi e riparazioni smartphone. |
| 05 — Ripartire | Un'altra clip mostra il ricambio posarsi e il telefono accendersi. Seguito da decidere dal finale effettivo. | «Pronto a ripartire.» |

## Scelta del contenuto dello schermo

Mantenere la schermata astratta ambrata già visibile nel capitolo 01.
Il messaggio sull'assistenza sarà testo HTML animato nel sito. Non chiedere
al generatore anche una trasformazione in un programma di diagnostica: per
questa clip rendiamo semplice l'azione e preserviamo il raccordo visivo.
Non incidere parole o interfacce nel video. La schermata astratta diventa
la superficie visiva del passaggio al capitolo successivo.

## Immagini da allegare e file da salvare

Il primo frame è già pronto: usare il finale realmente estratto dal capitolo 01.
Non usare né le viste esplose né il loro eventuale video come riferimento.

| Variante | Riferimento da allegare a GPT Image | Nuovo finale da salvare |
| --- | --- | --- |
| Desktop 16:9 | assets/img/home/cinematic-v2/chapter-01/desktop/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/desktop/last-frame-screen.png |
| Mobile 9:16 | assets/img/home/cinematic-v2/chapter-01/mobile/chapter-01-actual-last.png | assets/img/home/cinematic-v2/chapter-02/mobile/last-frame-screen.png |

Usare la qualità massima disponibile. Non rigenerare il first frame.
Non occorre aggiungere una nuova foto del volto o un logo: il finale inquadra
lo schermo. La persona resta nel set, fuori campo nel dettaglio ravvicinato.

## Prompt GPT Image — desktop 16:9

```text
Use the attached image as the exact reference for the notebook, its screen
and the warm scene. Create a landscape 16:9 END CAMERA FRAME of one continuous
cinematic approach to the SAME notebook display. The laptop remains completely
assembled and stationary on the original counter. Nothing is dismantled.

Move the camera physically towards the display and gently align the viewpoint
with the screen plane, approaching its central region without passing through
the glass. The screen surface now fills almost the entire landscape image.
Only a very narrow, physically plausible strip of the original bezel may
remain at the outer edges. Do not show a small laptop surrounded by the room.
This is a bold close-up, a major scale change from the supplied starting view.

Preserve the SAME amber abstract dune-like screen image from the reference:
same visual forms, warm golden palette and soft tonal gradients, now seen
at close range. Do not replace it with another wallpaper, diagnostics UI,
software dashboard or imaginary operating system. The camera framing may
crop the original screen content naturally; it must not redesign the content.
Maintain a subtle believable glass surface and restrained warm reflections,
without strong glare or blur obscuring the screen.

Keep the actual notebook bezel, screen proportions and metal finish wherever
their edges remain visible. The keyboard, counter, original person and room
leave the camera crop because of the close approach; do not insert them as
new objects or reflected faces inside the display. Retain the lighting logic
of the reference. No black void, cut, portal or unrelated scene.

Premium tactile product cinematography, natural perspective, clean optical
detail. No text, labels, logo, watermark, arrows, floating interfaces, neon
effects, particles or additional props. One complete 16:9 image, not a collage.
```

## Prompt GPT Image — mobile 9:16

```text
Use the attached portrait image as the exact scene and notebook reference.
Create a dedicated vertical 9:16 END CAMERA FRAME of a continuous approach
to the SAME laptop display. Keep the notebook intact, assembled and stationary
on its original counter. No dismantling or transformation of the device.

The camera physically approaches the central region of the original display
and gently aligns with its surface. Finish extremely close to the screen,
so the original amber screen content fills the ENTIRE portrait field of view.
Because this is a landscape laptop screen, the portrait camera naturally
crops its left and right regions. Do not turn the laptop screen vertical,
stretch the device, narrow its bezel or invent a portrait display. It is fine
for every bezel edge to be outside this tight camera crop. Do not try to fit
the full notebook or the whole landscape screen into the portrait frame.

Preserve the SAME warm abstract dune-like screen image from the starting
reference, enlarged by the optical approach and naturally cropped. Keep the
original amber shapes and tonal relationships. No new wallpaper, diagnostic
program, operating-system UI, words or graphics. The future website headline
will be real HTML; do not render any text into this image.

Use a subtle, believable glass surface and controlled warm reflections.
Avoid strong glare, moire, visible pixel grids or blur that makes the final
frame unreadable. The counter, keyboard and original person are naturally
outside the closer camera crop. No person fading into the screen and no new
faces or hands reflected in the glass. Stay in front of the display, never
travel through its solid surface or change the surrounding scene.

One sharp premium cinematic product close-up, vertical 9:16, filling all edges.
No black bars, empty studio background, collage, text, logo, watermark,
hologram, particles, neon effects, portal or new props.
```

## Verifica prima del video

- Confrontare la schermata con quella del first frame: stessa immagine e luce.
- Il finale deve essere molto più ravvicinato, non un notebook appena più grande.
- Sul mobile si ritaglia lo schermo orizzontale: non si altera la forma del PC.
- Nessun volto riflesso, nuova interfaccia o testo generato.
- Nessuna disassemblatura. Preparare i prompt Veo dopo aver visto i due finali.

Al prossimo passaggio i video saranno salvati come chapter-02-screen.mp4 nelle
rispettive cartelle desktop/mobile. Per il capitolo 03 useremo i finali effettivi
di questi video, non soltanto le immagini richieste al generatore.
