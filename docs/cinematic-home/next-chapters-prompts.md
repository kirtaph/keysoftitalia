# Continuazione cinematografica — capitoli 04 e 05

Direzione aggiornata il 7 ottobre 2026 dopo il feedback sulla seconda metà della home.
Questo documento sostituisce la precedente idea di passare direttamente dal
capitolo 03 a normali sezioni fotografiche. Il capitolo 04 è stato consegnato e
integrato; anche il capitolo 05 è ora consegnato e integrato.
Produzione esterna dell'utente, come per i capitoli 02 e 03.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| 01–03 — Riparare, scegliere, creare | Manteniamo i tre filmati già consegnati. Il testo accompagna il movimento, poi lascia libera la scena. | I testi attuali dei tre capitoli; prenotazione, catalogo e sviluppo web. |
| 04 — Cambiare prospettiva | Arretramento e traslazione laterale della camera: notebook dalla destra alla sinistra dell'inquadratura. Il titolo lascia il lato sinistro e il nuovo testo entra da destra. | «La tecnologia giusta. Per quello che vuoi fare.» / «Nuovo o ricondizionato, scegliamolo insieme.» / Catalogo e valutazione dell'usato. |
| Pausa — La selezione reale | Il film si ferma sulla composizione a sinistra. I prodotti disponibili entrano dalla destra in una vetrina HTML, con nome, prezzo e link reali. | «Il prossimo è qui.» / Esplora il catalogo. |
| 05 — Avvicinarsi | La camera si avvicina al bordo del notebook: metallo e riflessi diventano un dettaglio astratto. Il fotogramma finale lascia una grande zona scura. | Nessun testo durante il passaggio ravvicinato. |
| Le persone | Il dettaglio scuro lascia posto alle fotografie vere di Key Soft. Foto e testo arrivano dai lati opposti e si avvicinano durante lo scroll. È un cambio di capitolo editoriale, non un finto viaggio dentro il negozio. | «Dietro la tecnologia, ci siamo noi.» / Conosci la nostra storia. |
| Incontrarsi | La fotografia esce dolcemente; il titolo dei contatti attraversa la scena e si ferma. Indirizzo, orari e azioni restano stabili e leggibili. | «Il prossimo passo? Parliamone.» / WhatsApp, telefono e indicazioni. |

## Cosa succede alla parte oggi sotto i filmati

- La fotografia storica è materiale reale da mantenere; la sua impaginazione è provvisoria.
- La lista Riparare/Scegliere/Creare ripete il racconto iniziale: nella nuova versione
  confluirà nei collegamenti dei capitoli, senza un'altra grande sezione ripetitiva.
- Il catalogo conserva query, disponibilità, prezzi e destinazioni. La griglia attuale
  verrà sostituita dalla vetrina del capitolo 04. Le quattro schede attualmente
  selezionate non hanno foto utilizzabili: occorrono fotografie dei prodotti reali,
  senza sostituirle con dispositivi generati presentati come merce disponibile.
- Il video del negozio sarà un contenuto secondario attivabile nel capitolo persone;
  non allungherà il racconto con un'altra sezione video a tutta pagina.
- Le FAQ diventeranno una pausa compatta vicino ai contatti. Volantini e altri servizi
  restano raggiungibili attraverso collegamenti brevi, e solo quando pertinenti.
- Contatti, orari e footer rimangono contenuti essenziali; cambia la composizione.

## Scroll pacing e composizione

Valori iniziali da calibrare dopo aver visto i filmati, non tempi di animazione:

- 04: circa 1,3 altezze schermo di movimento senza testo, 0,3 di ingresso da destra,
  0,7 di lettura sulla posa finale. Il testo precedente esce prima dell'attraversamento.
- Vetrina: circa 1 altezza schermo; le schede entrano una volta e restano utilizzabili.
  Nessun obbligo di scorrere un lungo carosello per poter raggiungere i contatti.
- 05: circa 1,1 altezze schermo; niente titolo sovrapposto al dettaglio in movimento.
- Persone: circa 1,2 altezze schermo di movimento laterale e lettura, con fotografie
  reali a tutto rapporto originale, senza tagliare i volti.
- Contatti: movimento breve all'ingresso, poi lettura normale e pulsanti stabili.

Ricalibrare anche i primi tre capitoli: non aggiungere semplicemente questi valori
ai 10,3 schermi attuali. Una storia completa deve alternare azione e pause utili.
Movimento nativo reversibile, nessun blocco della rotella. Le entrate laterali non
comportano testi che continuano a muoversi mentre si cerca di leggere o cliccare.
Sul telefono foto e testo si dispongono in verticale; gli spostamenti laterali sono
più corti. Il cambio da destra a sinistra richiede una composizione mobile dedicata:
non presumere che un solo ritaglio centrale del filmato desktop sia sufficiente.
Movimento ridotto e assenza di media devono conservare testi e azioni in flusso.

## Consegna 04 — prima questa

Cartella: `assets/img/home/story-continuation/`.

**Start già pronto:** `chapter-04-first.png`. È una copia dell'ultimo fotogramma
effettivo di chapter-03.mp4. Usalo direttamente, senza rigenerarlo.
Il notebook del riferimento ha ancora la schermata astratta arancione con pannelli
grigi: va preservata, non sostituita con un sito diverso o con testi.

### Genera chapter-04-last.png

Allega chapter-04-first.png come riferimento. Genera un'immagine 16:9, almeno Full HD.

```text
Create the final keyframe of a premium continuous product film. Use the supplied
image as the authoritative identity reference. Landscape 16:9, minimum 1920x1080.

Preserve this exact open graphite notebook: same display, hinge angle, keyboard,
trackpad, ports, materials and proportions. Preserve the abstract orange and
grey panel arrangement inside its screen. No new interface, words or logos.
Preserve the dark studio floor and restrained amber rim lighting.

The camera has pulled back and travelled sideways, reframing the SAME stationary
notebook into the LEFT half of the picture. This is a wider view of the existing
physical studio, not a mirrored image. Keep a front three-quarter view with the
screen clearly visible; do not orbit behind the display. The notebook centre
is at approximately 30 percent of frame width. Its complete silhouette fits
inside the left 58 percent, with room above and below and natural contact shadows.
The RIGHT 40 percent is quiet near-black negative space, without props, highlights
or patterns competing with later website typography. The floor continues naturally
through that space. Keep the camera height and physical perspective plausible.

One notebook only. No phone reintroduced, hands, people, new objects, flying UI,
particles, rings, grids, holograms, words, logos or watermarks. Tangible premium
metal and glass photography with soft reflections. Do not mirror the reference
or distort the notebook to obtain the new composition.
```

### Genera chapter-04.mp4

Allega chapter-04-first.png e la nuova chapter-04-last.png come start e end frame.
Durata 8 secondi, 16:9, minimo 1080p, preferibilmente 24 fps, senza audio.

```text
Create one continuous 8-second photorealistic product-camera shot connecting the
provided first and final frames. Start exactly on the first frame and finish on
the final frame. Landscape 16:9, minimum 1080p, silent.

The same open graphite notebook stays physically stationary on the same dark
studio floor for the entire shot. Preserve its geometry, hinge angle, keyboard,
trackpad, ports, screen layout and amber studio lighting. Never mirror the image.

0–1 seconds: gently continue the close notebook composition of the first frame.
1–6 seconds: smoothly dolly backwards and translate the camera laterally so the
notebook travels from the right side to the left side of the IMAGE. This apparent
movement is caused solely by camera reframing: the notebook does not slide on
the floor, rotate by itself, shrink, morph or levitate. Keep the camera facing
the front of the display. The dark studio extends naturally into the newly
revealed right-hand space. Preserve coherent perspective and contact shadows.
6–8 seconds: decelerate into the supplied final frame and settle. Keep the right
40 percent almost black and uncluttered, ready for HTML text added by the site.

No cut, dissolve, scene replacement, zoom jump, screen transition, new object,
new text, logo, watermark, person, hand, particle or hologram. No extreme blur.
Motion must be legible both forward and backward under scroll scrubbing.
```

Controllo di accettazione: stesso notebook, nessuna inversione dei tasti/porte,
movimento riconoscibile verso sinistra, zona finale libera a destra. Controllare
i primi fotogrammi del video effettivo: un'immagine iniziale allegata non garantisce
che il generatore la rispetti. Conservare immagine e MP4 originali.

## Consegna 05 — dopo il controllo del video 04

Estrarrò il vero ultimo fotogramma del video 04 come `chapter-05-first.png`.
Per questo video non usare automaticamente chapter-04-last.png: il risultato
effettivo del film può essere diverso. Non serve generare subito entrambi i film.

### Genera chapter-05-last.png

Allega chapter-05-first.png come riferimento quando sarà pronto.

```text
Create the final keyframe of a continuous photorealistic macro product shot,
using the supplied notebook and studio as the authoritative reference.
Landscape 16:9, minimum 1920x1080. Keep the exact graphite metal material,
restrained amber light and physical studio identity.

The camera has approached the notebook's front-left metal edge at a low angle.
Frame a tangible close-up of its bevel and a small part of the trackpad surface
in the lower-left portion of the image. A soft amber reflection traces the edge.
The display is outside this tighter camera crop. There are no new physical objects.
The metal detail occupies about the left 40 percent; the right and upper portions
fall into quiet near-black optical background. Believable shallow depth of field,
not excessive blur, no texture warping. This is a macro view of the same object,
not a newly designed abstract sculpture.

No text, logo, watermark, hands, people, store, floating UI, particles or grids.
The dark space will receive real documentary photographs in the website later;
do not generate any photo, collage or extra environment inside this keyframe.
```

### Genera chapter-05.mp4

Start chapter-05-first.png, end chapter-05-last.png. Durata 6 secondi, 16:9,
minimo 1080p, preferibilmente 24 fps, senza audio.

```text
Create one continuous 6-second photorealistic camera shot connecting the provided
first and last frames exactly. Landscape 16:9, minimum 1080p, silent.
Keep the same stationary notebook, physical geometry, dark floor and amber light.

0–1 seconds: continue the final left-side notebook composition of the previous shot.
1–5 seconds: smoothly lower and move the camera towards the notebook's front-left
metal edge. The display and keyboard leave the frame naturally through camera
cropping. Settle focus on the existing bevel and nearby trackpad surface. Keep
the right and upper area quiet and dark. Do not pass through the metal or screen.
5–6 seconds: ease into the exact supplied macro composition, retaining enough
surface detail to recognise a physical object and a calm final hold.

No cuts, dissolves, teleportation, morphing, changing hinge geometry, new objects,
words, logos, watermarks, people, hands or fake store interior. Reflections track
the physical camera movement consistently. Motion stays legible when reversed.
```

Le fotografie delle persone sono contenuti reali già nel progetto: le animeremo
nel sito. Non occorre generare un team o un negozio artificiale. Nuove fotografie
autentiche potranno ampliare questo capitolo; i due keyframe sopra sono le nuove
immagini generate richieste per i nuovi filmati.

## Stato dell'implementazione

- Disponibili e integrati: filmati 01–05; fotografia storica; contenuti del negozio.
- Aggiornati ora: entrate laterali dei testi esistenti e movimento contrapposto
  della fotografia e del testo nel capitolo persone.
- Integrati nel capitolo 04: cambio di lato del testo, movimento senza copia e
  pausa finale con i prodotti reali. Rimossa la vetrina duplicata più in basso.
- Integrato il capitolo 05: raccordo dal 04, movimento ravvicinato senza testi,
  passaggio alla fotografia storica nello stesso stage con ingressi contrapposti.
- Chiusura ricomposta: collegamenti brevi ai servizi, video reale su richiesta,
  FAQ compatte, contatti con ingresso laterale del titolo. Rimosse le grandi
  sezioni servizi/video ripetitive e il precedente capitolo fotografico separato.
- I prompt restano come documentazione degli asset originali. Nessun ulteriore
  filmato desktop è necessario per questa continuazione. La variante mobile usa
  ancora le fonti landscape ricomposte; una fonte portrait dedicata resta distinta.
