# Capitolo 05 v2 — Scegliere

15 ottobre 2026. Continuazione del capitolo 04 di Claude, generata direttamente
con Higgsfield su richiesta dell'utente.

## Direzione

Il telefono riparato rimane il raccordo: la camera arretra e rivela una selezione
di dispositivi su un basso piano grafite. Smartphone, tablet e notebook condividono
luce ambrata e fondale del capitolo 04. Il desktop lascia spazio a sinistra per
il testo; il mobile ha una composizione verticale dedicata con i prodotti in basso.
La scena è illustrativa, non rappresenta inventario o prezzi del negozio.

Titolo: «Il prossimo è qui.»
Testo: «Nuovo o ricondizionato, scegliamolo insieme.»
Azioni: «Scopri il catalogo» e «Valuta il tuo usato».
Nessun testo o prezzo viene generato dentro le immagini.

## Generazioni

- Modello keyframe: GPT Image 2.5, 2K, high.
- Desktop end: `ffa69fad-ab8d-4868-b956-c9fa032644db`.
- Mobile end: `d9509723-3131-48e5-84e7-64398e3bf023`.
- Modello raccordi: Kling 3.0 Omni (Frames), pro, 8 secondi, senza audio.
- Desktop video: `987961c5-2d07-4332-820d-e64369f38d53`.
- Mobile video: `4da759f8-c92e-40d7-ac54-066b12c1b2e5`.
- I primi fotogrammi di riferimento sono i frame reali `frame-0768.webp`
  del capitolo 04, desktop e mobile.
- Preventivo video: 10 crediti per variante. Preventivo keyframe desktop: 2,75 crediti.

## File e preparazione

Asset in `assets/img/home/cinematic-v2/chapter-05/{desktop,mobile}/`.
`selection-end.png` conserva il keyframe originale; `selection.mp4` è il raccordo.
`artifacts/prepare-chapter-05-v2.cjs` produce WebP q80 e AVIF q55 a 24 fps,
poster e storyboard. Il primo frame è allineato al finale reale del 04.
`sequence-info.json` registra conteggi, dimensioni e peso delle sequenze.

Il capitolo successivo previsto resta «Le persone», con fotografie autentiche.

## Integrazione e verifica

- Entrambe le clip sono complete: 193 frame a 24 fps, 1440×810 desktop e 720×1280 mobile.
- AVIF: desktop 2.405.877 byte, mobile 1.926.534 byte. WebP disponibili come fallback.
- Il manifest contiene 1.586 frame in cinque sorgenti; verificata l'esistenza di
  tutti i WebP e AVIF, per entrambe le varianti.
- Aggiunte tre altezze di schermo: due per il raccordo e una per la lettura.
  I quattro capitoli precedenti conservano le loro distanze assolute.
- La dissolvenza finale ora è configurabile nel manifest e occupa solo le ultime
  0,35 altezze; la selezione resta visibile durante la pausa di lettura.
- «Scopri il catalogo» e «Valuta il tuo usato» puntano alle pagine esistenti.
  L'inventario e i prezzi rimangono nel catalogo dinamico, non nella scena generata.
- Poster leggibile nel percorso statico esistente; nessuna nuova dipendenza runtime.
- Verificati nel browser desktop, mobile 390×844 e 320×740, barra a cinque tappe,
  assenza di errori nei log durante il controllo; validati sintassi PHP e JavaScript.
- Anteprima: `artifacts/chapter-05-v2-desktop-browser.jpg`.
