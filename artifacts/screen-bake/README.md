# Grafica del metodo incisa nei frame del capitolo 02

**Non in uso dal 10 ottobre 2026:** il display del capitolo 02 è tornato ai frame originali.

La schermata «Prima capire. Poi intervenire.» non è più HTML sovrapposto al video:
è composta dentro i fotogrammi (sostituzione dello schermo), quindi non può
disallinearsi su nessun formato di finestra.

1. `screentrack.js` (desktop) e `screentrack2.js mobile` stimano per ogni frame
   i quattro bordi del display (RANSAC sulla cornice scura, continuità temporale,
   smoothing). Risultato in `screentrack.json` (`desktop`, `mobile2`).
2. `bake.js <desktop|mobile> <da> <a> 1 <cartella>` apre `ui.html` in Chrome
   headless, proietta l'interfaccia sul quadrilatero del frame e salva PNG.
   Desktop: frame locali 34–150. Mobile: 62–112, colonna a sinistra (la camera
   inquadra solo quella metà dello schermo).
3. `encode-screen.js` scrive WebP q80 + AVIF q55 in
   `assets/img/home/cinematic-v2/chapter-02/{desktop,mobile}/sequence-screen/`,
   copiando gli originali per i frame senza grafica. Gli originali in `sequence/`
   restano intatti.
4. Dopo una nuova composizione aggiornare `frameVersion` nel manifest.

Dipendenze: Node con `playwright-core` e `sharp`, Chrome installato. I percorsi
assoluti negli script puntano alla scratchpad della sessione: adattarli.
