# Capitolo 01 — Il tuo mondo digitale, in buone mani

Nuova direzione degli asset, 7 ottobre 2026. Produzione dell'utente con GPT Image
per i frame e Veo 3.1 per le clip, ciascuna al massimo di 8 secondi.
Questo è il primo passaggio: produrre e valutare i due frame iniziali.
I filmati attualmente nel sito restano invariati durante questa revisione.

I due primi frame sono stati consegnati. Il desktop è una buona base; il mobile
richiede più spazio sopra al volto e continuità del notebook senza marchio.
Vedi [controllo e prompt di correzione](chapter-01-v2-frame-review.md).

## Riferimento di identità

Protagonista: Vito Moro, front man di Key Soft Italia, indicato dall'utente.
Unico riferimento autorizzato per il volto: la nuova fotografia allegata in chat,
conservata in [references/vito-moro-reference-2026.png](references/vito-moro-reference-2026.png).
Non usare la precedente fotografia del sito. L'immagine comprende altre persone:
servono esclusivamente fisionomia e dettagli del volto dell'uomo al centro in
camicia bianca. Escludere accompagnatori, mani altrui e sfondo della foto.
La posa del nuovo frame è diversa; l'identità deve restare riconoscibile.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| Apertura | Banco con notebook e telefono; Vito dietro al banco in uno spazio contemporaneo con profondità, luce e materiali tangibili. | «Il tuo mondo digitale, in buone mani.» / Riparazioni, dispositivi e soluzioni per la tua attività. |
| Avvicinamento | La camera avanza con un piccolo arco laterale; Vito solleva lo sguardo dal banco, senza cambiare posizione. | Nessuna copia durante il movimento principale. |
| Arrivo | Inquadratura più vicina, espressione accogliente e mani ferme sul banco. | «Ogni soluzione comincia dall'ascolto.» |

Ambiente progettato per il film, senza riprodurre negozio o laboratorio reali.
Una sola persona, un notebook, un telefono. Camicia bianca con colletto aperto
come riferimento di abbigliamento. Grafite, metallo satinato, legno caldo e
piccoli accenti arancioni; luce naturale laterale, niente vuoto nero da studio.
Nessun testo del sito dentro i pixel: titoli, pulsanti e logo principale sono HTML.

## Ordine di lavoro

1. Genera il frame iniziale desktop con il prompt A e la foto aggiornata allegata.
2. Valutiamo volto, ambiente, mani e spazio di lettura.
3. Genera il frame iniziale mobile con il prompt B, allegando sia la foto aggiornata
   sia il frame desktop selezionato. Il desktop guida ambiente e abiti; la foto
   originale resta il riferimento principale per il volto.
4. Dopo i due frame iniziali, prepariamo i prompt dei finali e dei due filmati.
   Non generare ancora le clip: prima fissiamo una direzione convincente.

Se GPT Image offre un selettore delle proporzioni, usa 16:9 e 9:16. Se l'output
nativo disponibile ha un rapporto diverso, conserva l'originale e prevedi spazio
attorno ai soggetti per il ritaglio finale: non deformare l'immagine per adattarla.
Dimensioni finali desiderate: 1920×1080 e 1080×1920, compatibilmente con la fonte.
Non presentare un file ingrandito come una sorgente generata a quella risoluzione.

## Prompt A — frame iniziale desktop, 16:9

Allega la nuova fotografia di Vito. Copia il prompt completo:

```text
Create the opening keyframe of a photorealistic cinematic brand film for Key
Soft Italia, a technology sales, repair and digital-services business. Compose
for landscape 16:9. This is a film frame, with physical depth, natural materials
and a believable environment.

IDENTITY REFERENCE: Use the attached photograph ONLY to reproduce the identity
of the central man wearing the open-collar white shirt, Vito Moro. Preserve his
actual facial proportions, forehead, natural hairline, short dark hair with grey
at the temples, eyebrow shape, eye shape and spacing, nose, ears, cheeks, jaw,
mouth and distinctive smile. Keep his apparent age and real skin texture,
including natural expression lines. Do not beautify, rejuvenate, change his
hairline or substitute a generic model. The other people, their hands, the
background and the tilted selfie pose are not part of the new scene.

SCENE: Vito stands naturally behind a refined graphite service counter in a
designed contemporary technology workspace. His torso is upright, seen in a
comfortable front three-quarter view, and his attention rests gently on the
devices on the counter. His expression is relaxed and approachable, with a
subtle hint of the reference smile. Wear the same type of plain white cotton
shirt with an open collar and naturally folded sleeves. No tie or invented badge.
His two hands rest calmly on the counter, separate and anatomically plausible;
no active repair, tool handling or object exchange in this opening frame.

Place one realistically constructed open graphite notebook and one intact
smartphone on the counter. Both rest on their own contact shadows. The notebook
display shows a restrained abstract warm field, no readable interface. Keep
devices secondary to the person. Include a small, orderly equipment shelf in
the background, warm wood detailing, a brushed metal surface and architectural
depth. The space is a deliberately designed film setting. It is not a copy of
the location in the attached photograph. Avoid a sterile black void.

COMPOSITION: Medium-wide environmental portrait, from approximately waist level
up, including the counter surface and both hands. Position Vito's face around
68 percent of frame width and 38 percent of frame height. Leave the LEFT 35
percent visually quiet: a softly shaded graphite wall with subtle physical
texture and no salient props, for real website text added later. A diagonal
counter edge leads the eye towards Vito on the right. Keep his head and both
hands fully inside frame, with breathing room around them. Natural perspective
similar to a 40mm full-frame lens; no wide-angle facial distortion.

LIGHT: Soft daylight from the right, natural skin tones, restrained warm amber
practical light in the background, gentle shadow falloff and tangible glass
reflections. A moderately shallow depth of field: Vito and the nearby counter
remain clear, the background is gently softened but still identifiable as a
real space. Premium photographic realism, no plastic skin or excessive grading.

No additional people, detached hands, extra fingers, duplicated devices,
floating objects, broken phone close-up, holograms, particles, neon grids,
letters, numbers, logos, text-like glyphs, captions or watermarks. Do not add
the website headline or any button into the image.
```

Salva l'originale come `assets/img/home/cinematic-v2/chapter-01/desktop/first-frame.png`.

## Prompt B — frame iniziale mobile, 9:16

Allega la foto aggiornata e il frame desktop selezionato. Copia il prompt completo:

```text
Create a dedicated portrait opening keyframe for the SAME photorealistic Key
Soft Italia brand film shown in the supplied landscape frame. Compose for
vertical 9:16. Recompose the camera view for a tall screen; do not stretch or
simply crop the landscape image.

IDENTITY: The supplied original photograph is the authoritative identity
reference for Vito Moro, the central man in the open-collar white shirt.
Preserve his exact facial proportions, forehead, natural hairline, short dark
hair with grey temples, eyebrows, eye shape and spacing, nose, ears, cheeks,
jaw, mouth and distinctive smile. Preserve his apparent age, skin texture and
natural expression lines. Do not beautify, rejuvenate or replace his face.
Do not include any companions or their hands from the original photograph.
The landscape frame is the continuity reference for wardrobe, setting,
counter, devices, materials, light direction and colour palette.

Recreate Vito standing naturally behind the SAME graphite service counter in
the SAME contemporary designed workspace, wearing the same plain white cotton
shirt with an open collar and folded sleeves. His torso is upright, in a
comfortable front three-quarter view, looking gently down at the devices with
an approachable expression. Both hands rest calmly and separately on the
counter. One open graphite notebook and one intact smartphone sit on the
counter, with coherent proportions and contact shadows. The notebook screen
has only a quiet abstract warm field, no readable interface.

PORTRAIT COMPOSITION: Place Vito near the horizontal centre. His face is around
48 percent of frame height, fully visible with ample headroom. Keep the upper
28 percent quiet, using the softly shaded graphite architectural background
for website navigation and headline overlays added later. Show both shoulders,
the torso, both hands and enough counter surface in the middle and lower
portion of the image. Keep the hands and important device details above the
bottom 12 percent, reserved for website controls. The notebook may be viewed
at a different angle to suit this tall composition, but must remain the same
physical model. Maintain a naturally proportioned face, with perspective
similar to a 40mm full-frame lens and no wide-angle stretching.

Preserve the landscape frame's soft daylight from the right, restrained amber
background light, warm wood details, brushed metal and softly visible equipment
shelf. Continue the physical setting to every frame edge. Natural skin tones,
real cotton texture and credible metal and glass. The background stays gently
softened and architecturally recognisable; it is not an empty black studio.

No extra people, duplicated body parts, detached hands, extra fingers, changed
wardrobe, extra devices, floating objects, repair action, holograms, particles,
neon grids, words, logos, text-like glyphs, captions or watermarks. Keep all
website text and buttons out of the generated image.
```

Salva l'originale come `assets/img/home/cinematic-v2/chapter-01/mobile/first-frame.png`.

## Cosa valutare prima di continuare

- Somiglianza con la nuova foto: proporzioni, età percepita, capelli, occhi e sorriso.
- Volto naturale anche ingrandito; niente pelle levigata o denti reinventati.
- Una sola persona e due mani plausibili; proporzioni credibili di banco e dispositivi.
- Stesso ambiente, camicia, luce e oggetti nelle due composizioni.
- Volto libero dai titoli; spazio reale per testi sovrapposti e navigazione mobile.
- Profondità dell'ambiente e materiali convincenti, senza decorazioni futuristiche.

La fedeltà non è garantita dal prompt: confrontiamo gli output prima di produrre
frame finali e filmati. Il logo originale potrà essere fornito come riferimento
aggiuntivo se scegliamo un marchio fisico nel set; per questi primi frame non
generiamo loghi o scritte che poi possano deformarsi durante il movimento.
