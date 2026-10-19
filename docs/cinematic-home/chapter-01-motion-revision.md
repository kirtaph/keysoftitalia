# Capitolo 01 — revisione del movimento

> Scelta successiva dell'utente: mantenere Vito nel film e rigenerare i finali.
> La proposta senza persone qui sotto resta un'alternativa non scelta.
> Prompt attivi: [Nuovi finali dinamici](chapter-01-dynamic-final-frames.md).

Feedback dell'utente: movimento delle clip troppo debole; Veo rifiuta la
generazione mobile classificando il soggetto come persona nota.

## Diagnosi e direzione proposta

Il percorso precedente chiedeva solo un avvicinamento del 10–20 percento in
8 secondi. I finali sono troppo simili alle aperture per un movimento incisivo.
Scrivere "fast" sullo stesso percorso non crea una nuova traiettoria.
I vecchi prompt video sono quindi superati per questa nuova direzione.

Per restare con Veo, proposta: Vito resta nell'apertura fotografica del sito,
animata attraverso lo scroll; il film mostra un ambiente realmente privo di
persone. La fotografia esce e lascia entrare la camera sul banco. Questo è un
passaggio editoriale composto nel sito, non un video generato del volto.
Non provare a far accettare lo stesso volto cambiandone nome o nascondendone
l'identità nel prompt. Se si desidera Vito nel film, occorre risolvere il rifiuto
attraverso l'assistenza del servizio o usare un servizio che accetti quel materiale.
Non abbiamo verificato il motivo del rifiuto né garantiamo l'esito del supporto.

Questa proposta cambia il trattamento di Vito e richiede la scelta dell'utente
prima di produrre altri asset. Non modifica la home né gli originali ricevuti.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| Accoglienza | Il ritratto di Vito è una fotografia HTML nella scena, con ingresso e uscita guidati dallo scroll. | «Il tuo mondo digitale, in buone mani.» |
| Entrare nel lavoro | La fotografia lascia spazio al film: la camera scende e avanza rapidamente lungo il banco; bordo vicino, notebook e scaffali mostrano parallasse evidente. | Nessuna copia: il movimento occupa la scena. |
| Arrivo al dettaglio | La camera frena vicino alla cerniera e al piano della tastiera; il dettaglio dà un punto di partenza al capitolo successivo. | «Ogni soluzione comincia dall'ascolto.» sulla pausa del sito, se utile alla lettura. |

La lettura e il ritratto hanno un intervallo di scroll proprio. Non consumare
cinque secondi del film per un sorriso quasi fermo. Il contenuto del capitolo 02
sarà progettato sul finale effettivo della clip, conservando l'ambiente.

## Percorso di camera — una clip da 8 secondi

- 0–0,7 s: avvio breve; il banco e la stanza sono leggibili.
- 0,7–3,5 s: discesa dalla vista ambientata a una quota vicina al banco, avanzando.
- 3,5–6,5 s: movimento lungo il banco e arco di circa 20–25 gradi verso la
  cerniera del notebook; primo piano e sfondo cambiano prospettiva in modo netto.
- 6,5–8 s: frenata controllata verso un dettaglio ravvicinato, senza stop brusco.

Escursione indicativa: circa un metro nel set, dal campo ambientato al dettaglio.
Un vero cambio di scala, non un ingrandimento minimo. Il banco resta fermo.
La traiettoria passa accanto agli oggetti; non attraversa vetro, schermo o metallo.
Nessun whip-pan che renda la scena incomprensibile, nessun tremolio o forte blur.
Il soggetto e lo spazio riservato alla copia vanno riprogettati per 16:9 e 9:16.

## Prompt del nuovo frame iniziale — dopo la scelta della direzione

Per ciascuna variante allegare il suo frame iniziale selezionato, come riferimento
del set. Non allegare a Veo fotografie di persone: il materiale del film deve
essere una scena senza persone, non lo stesso materiale rinominato.

GPT Image, da usare con la proporzione della variante:

```text
Create a new opening keyframe for a dynamic cinematic camera shot in the exact
technology workspace shown in the supplied reference. Preserve the graphite
counter, warm wood, illuminated shelving, plants, daylight direction, materials,
notebook and phone. Landscape 16:9 for desktop OR dedicated portrait 9:16 for
mobile, according to the selected output format.

The workspace is empty. No person, face, hands, human silhouette, portrait,
photo of a person or human reflection is present anywhere. The supplied image
is a reference for architecture and objects only. Reconstruct the space behind
the counter naturally; do not leave a person-shaped blur or cutout.

Frame a wider architectural three-quarter view from just above counter height.
The near counter edge enters diagonally through the foreground and leads to
the same open unbranded graphite notebook. The notebook and phone remain
stationary on the counter, with plausible contact shadows and reflections.
Include enough shelving and window detail to make the room feel dimensional.
Keep the notebook around the central area so the camera can approach it.
Its screen contains the same restrained abstract warm image, without interface.

Use believable natural perspective and premium photographic realism. Preserve
the reference's warm lighting and graphite-and-wood materials. A physical
workspace with depth, not an isolated device on black. No extra devices,
invented signage, logos, words, floating objects, particles or watermark.
```

Nome proposto: `desktop/motion-first.png` e `mobile/motion-first.png`, nella
cartella del capitolo 01 v2. Questi frame vanno ispezionati prima dei finali.

## Direzione del nuovo finale — GPT Image

Da concretizzare sul nuovo motion-first selezionato, non sugli attuali ritratti:

```text
Using the supplied empty-workspace opening frame as the authoritative reference,
create the end keyframe of a dynamic physical camera move in the SAME room.
Keep the chosen aspect ratio. Preserve the exact notebook, hinge angle, keyboard,
counter, lighting and architectural identity. All objects remain stationary.

The camera has travelled roughly one metre towards the notebook, descended
to a low viewpoint close to the counter surface and followed a lateral arc
of about 20–25 degrees. Frame a bold close view of the notebook hinge, adjacent
keyboard and metal edge, with the perspective of the counter leading through
the foreground. The notebook detail dominates the image; shelving and window
are now naturally outside the tighter crop or softly visible in the background.
Use a real change of camera position, not a flat-image enlargement or a new
object. Keep physical depth, coherent reflections and recognisable geometry.

No people, faces, hands, silhouettes, human reflections, portraits, words,
logos, floating UI, holograms, extra devices or watermarks. Preserve the opening
frame's photographic materials and warm light. Leave enough framing margin
around the selected detail for the final website composition.
```

NomI proposti: `desktop/motion-last.png` e `mobile/motion-last.png`.
Il prompt Veo definitivo seguirà il confronto dei nuovi endpoint. I vecchi
frame di Vito saranno preservati come asset dell'apertura fotografica, se
questa direzione viene scelta; non sono scartati né sostituiti automaticamente.
