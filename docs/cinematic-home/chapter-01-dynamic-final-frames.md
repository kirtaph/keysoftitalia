# Capitolo 01 — nuovi finali per un movimento deciso

Nuovi finali ricevuti e ispezionati: il cambio di scala è netto in entrambe
le varianti. Si procede alla prova video usando [i nuovi prompt Veo](chapter-01-dynamic-video-prompts.md).
La continuità effettiva di mani, dispositivi e camera resta da verificare nei filmati.

Scelta dell'utente: mantenere Vito nel film e rigenerare i last frame.
La proposta di separare il ritratto dal film non viene adottata.
Produzione esterna: frame GPT Image, poi clip Veo 3.1 da massimo 8 secondi.

## Il nuovo percorso

L'apertura resta la vista ambientata con Vito. La camera avanza, scende verso
il banco e compie un arco laterale di circa 20 gradi. Nel finale il notebook,
le mani e il materiale del banco occupano il campo: il viso esce dall'alto
per effetto dell'inquadratura. Vito resta fisicamente al suo posto.

Il cambio di scala deve essere immediatamente evidente confrontando i frame:
da persona e ambiente a dettaglio del lavoro sul banco, non un ritratto appena
più vicino. Nessun gesto nuovo o passaggio di oggetti richiesto al soggetto.
Gli oggetti non scivolano; il banco e le scaffalature non si trasformano.
La traiettoria passa davanti al notebook, senza attraversare superfici solide.

Per la futura clip: accelerazione breve, movimento principale deciso, frenata
negli ultimi 1–1,5 secondi. Il titolo esce prima del movimento: nel finale non
è necessario conservare la grande zona vuota dei ritratti iniziali. La pausa
di lettura di apertura appartiene allo scroll, non deve consumare la clip.

## Desktop — nuovo last frame, 16:9

Allega `desktop/first-frame.png` come riferimento principale. Puoi aggiungere
la foto aggiornata di Vito come riferimento secondario della persona.
Non usare il vecchio last-frame.png come destinazione: contiene il movimento
troppo lieve che vogliamo sostituire.

```text
Create a substantially different END CAMERA VIEW of the exact same physical
scene shown in the supplied opening frame. Landscape 16:9. This is the endpoint
of a dynamic 8-second cinematic camera move, not a new scene or a small portrait
zoom. Preserve the same man, Vito Moro, white shirt, folded sleeves, hands,
graphite counter, notebook, phone, shelving, materials and lighting.

CAMERA DESTINATION: The camera has travelled roughly 0.8–1.2 metres forward,
descended from the environmental portrait view to a viewpoint about 25–35 cm
above the counter, and followed a lateral arc of about 20 degrees towards the
front corner of the notebook. End in a bold close three-quarter view of the
notebook hinge, keyboard, trackpad and metal edge. Show enough of the display
to retain the same abstract warm screen image. The notebook occupies roughly
65–75 percent of frame width. The counter surface and its near edge lead
strongly through the foreground, with real material texture and reflections.

Vito remains standing in his original physical position with his hands resting
in their original places. His face is now ABOVE the camera crop, not erased,
transformed or moved out of the room. Retain identifiable context from his
white shirt, folded sleeves and resting hands behind or beside the notebook.
Keep visible fingers anatomically credible. Do not move his hands to stage a
new action. If the phone is outside this tighter camera view, it still exists
at its original position; do not relocate it to force it into the image.

Preserve the notebook's exact shape, hinge angle, keyboard and trackpad layout,
materials, unbranded surfaces and screen content. No disassembly or new parts.
The shelves and window remain in their original positions, appearing only where
physically visible in this low, tighter view. Let depth and perspective create
the changed composition. Do not resize the man or slide furniture to match it.
Natural perspective similar to a 35mm lens, no fisheye or optical distortion.

Keep the same soft daylight direction and restrained amber practical lighting.
Sharper foreground material detail, moderately softened background. The final
frame may use the whole image for the visual action: no artificial empty text
panel or requirement to keep the portrait visible.

No new people, props, devices, logos, text, glyphs or watermarks. No floating
objects, extra fingers, duplicate hands, warped laptop geometry or changed room.
Photorealistic tangible materials, no plastic rendering or futuristic effects.
```

Salva: `assets/img/home/cinematic-v2/chapter-01/desktop/last-frame-dynamic.png`.

## Mobile — nuovo last frame, 9:16

Allega `mobile/first-frame-v2.png` come riferimento principale. La foto aggiornata
di Vito è un riferimento secondario opzionale. Il finale desktop può aiutare
a mantenere lo stesso dettaglio visivo, ma non deve sostituire il set portrait.

```text
Create a dedicated vertical 9:16 END CAMERA VIEW of the exact same scene shown
in the supplied portrait opening frame. This is the endpoint of a dynamic
8-second physical camera move. Keep the same Vito Moro, white open-collar shirt,
folded sleeves, resting hands, graphite counter, unbranded notebook, phone,
architecture and warm photographic lighting. Do not crop a landscape picture.

THE CHANGE MUST BE BOLD: Move the camera roughly 0.8–1.2 metres towards the
notebook, lower it to about 25–35 cm above the counter and follow a lateral arc
of approximately 20 degrees. End on a close, dimensional three-quarter view
of the notebook hinge, keyboard, trackpad and brushed metal edge. The device
detail and counter dominate the vertical image. Keep a recognisable part of
the same warm abstract screen content and strong physical depth. The near
counter texture and notebook edge form the foreground; the shirt and resting
hands provide human context in the middle or upper part of the image.

Vito remains standing exactly where he was, with his hands still in their
original positions. His face naturally leaves the TOP of the frame because
the camera has lowered and approached the bank. He does not vanish, shrink,
change identity, sit down or move away. Keep visible portions of his torso,
sleeves and hands consistent with the opening reference. No hand gesture,
object exchange or invented repair action. Do not move the phone to keep it
inside the crop; it may naturally be outside this tighter view.

Preserve the exact notebook geometry, hinge angle, keyboard, trackpad and screen
content. No new device design, logo or interface. Preserve the room as a rigid
physical space; wood slats, shelves, window and plants remain in their original
places, simply leaving view as the camera points closer to the counter.
Natural perspective, no fisheye, stretched face, warped hands or flat-image zoom.

Preserve the same natural daylight, restrained amber light, metal reflections
and graphite surface. The final view fills the portrait frame. There is NO need
to preserve the empty upper 30 percent of the opening: website text will have
left the scene during the camera movement. Reserve only a small bottom margin
around essential detail for website controls, without creating a blank panel.

No extra people, tools, devices, floating UI, text, logos, watermarks, particles
or holograms. No extra fingers or duplicated body parts. Photorealistic material
texture and coherent perspective, substantially closer than the opening view.
```

Salva: `assets/img/home/cinematic-v2/chapter-01/mobile/last-frame-dynamic.png`.

## Controllo prima di produrre di nuovo i filmati

Consegna prima i due nuovi finali. Verificheremo se la camera può effettivamente
collegare apertura e dettaglio senza attraversare il notebook o alterare il set.
Solo allora scriveremo i prompt Veo sul percorso concreto ottenuto.
Conserva i precedenti last-frame.png: restano prove, non endpoint attivi.

Il rifiuto mobile relativo alla persona resta un problema separato del servizio:
questa revisione serve a correggere il movimento, non garantisce che Veo accetti
la generazione del soggetto. Manteniamo esplicita l'identità e i riferimenti forniti.
