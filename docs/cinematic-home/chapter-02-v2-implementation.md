# Integrazione dei capitoli 01 e 02

Aggiornamento del 9 ottobre 2026. La home utilizza le clip approvate nello stesso
canvas, con timeline nativa reversibile e testi HTML separati dal filmato.

Manifest attivo: `assets/img/home/cinematic-v2/manifest.json`.
432 frame virtuali: 0–239 del capitolo 01, 240–431 del capitolo 02.
I file originali e il manifest storico del capitolo 01 restano conservati.

| Scroll | Azione |
| --- | --- |
| 0–8% | Apertura leggibile; ingresso automatico fresco all'8%, con topbar fuori campo |
| 8–45% | Movimento del capitolo 01; il testo iniziale esce lateralmente |
| 45–48% | Finale del capitolo 01, comprimendo la coda quasi ferma |
| 48–49% | Raccordo al capitolo 02 e compressione dei suoi primi 12 frame |
| 49–88% | Avvicinamento allo schermo |
| 54–79% circa | Grafica del percorso di assistenza proiettata sul display: diagnosi, intervento e verifica |
| 80–89% circa | Entrata sfalsata da destra del testo sull'assistenza, dopo l'uscita della grafica; sul mobile nella zona superiore |
| 88–100% | Finale reale del capitolo 02 e tempo di lettura prima dei servizi |

L'altezza attiva è 6,4 viewport. L'8% conserva circa la stessa distanza iniziale
della precedente timeline di 3,2 viewport con ingresso al 16%.
La cache rimane limitata a 18 bitmap e 4 richieste contemporanee. Cambiando
breakpoint si annullano le richieste vecchie e si usano gli asset dedicati.
Il desktop prosegue dal ritaglio basso del capitolo 01 verso un centraggio
graduale durante il capitolo 02; il mobile mantiene il proprio centraggio.

Il testo usa la palette ambrata già scelta. Il velo scuro segue il lato della
copia e garantisce leggibilità sul display luminoso; il filmato resta visibile
nella parte libera. Le CTA nascoste sono fuori dall'interazione. La descrizione
breve del capitolo 02 rimane visibile anche sul mobile.

Reduced motion e fallback statico includono entrambi i capitoli, con immagine
finale e messaggio sull'assistenza. Lo spinner iniziale resta limitato al
caricamento del primo frame e all'assestamento della posizione.

Derivati WebP del capitolo 02, qualità 80: desktop 1440×810, 192 frame,
10.133.758 byte; mobile 720×1280, 192 frame, 6.671.602 byte. Sono dimensioni
dell'intera sequenza, non misure di trasferimento iniziale o velocità di rete.

## Verifica

`artifacts/check-chapter-02-integrated.js`: 1920×900, 1440×600, 390×844 e
320×667. Verificati ingresso al primo frame con topbar nascosta, attraversamento
del raccordo, salti nella timeline, ritorno al primo capitolo, finale 431,
testo leggibile e CTA senza sovrapposizione al footer, assenza di overflow,
salto ai servizi con focus e fallback reduced motion. PHP lint e controllo
sintattico JavaScript superati. Screenshot in `artifacts/chapter-02-integrated-*`.

I piccoli difetti generativi del filmato, documentati nella revisione degli
asset, restano nell'originale. Il raccordo è vicino, ma i frame di confine non
sono pixel identici; non è dichiarato un piano sequenza fisicamente perfetto.

Il capitolo 03 è in preparazione esterna secondo
`docs/cinematic-home/chapter-03-v2-delivery-frame-prompts.md`.

Passata Clarify: la CTA del capitolo 02 è «Richiedi un preventivo», perché porta
a `preventivo.php`; la descrizione ora dice «Individuiamo il problema, ripariamo
e configuriamo PC e notebook. Raccontaci cosa non funziona.». Titoli e timeline
restano quelli approvati. La revisione è limitata al capitolo 02; le modifiche
inizialmente estese all'apertura e al seguito sono state ritirate su indicazione
dell'utente.

## Grafica sul display

La grafica HTML `screen-assistance` segue una proiezione a quattro vertici con
punti misurati su fotogrammi desktop e mobile. Interpolazione lineare tra i
campioni del video, poi applicazione dello stesso ritaglio cover del canvas.
È una calibrazione manuale del filmato generativo, non un tracciamento ottico
automatico né una garanzia di coincidenza subpixel su ogni frame.

Il modulo `assets/js/pages/home-screen-assistance.js` usa l'indice effettivamente
disegnato. Attivazione nei frame locali 34–52, percorso delle tre fasi, ritiro
126–150. Nessuna animazione temporale in loop: fermando lo scroll si ferma
anche la grafica, e la sequenza si inverte tornando indietro.
Il finale 191 resta esattamente libero per il raccordo alla clip successiva.
Il testo principale entra oltre l'80% solo se il frame dipinto è almeno 390
(frame locale 150), quando il pannello è già ritirato. Con richieste ritardate
di 900 ms, verificata l'attesa del frame e la successiva disponibilità della CTA.

Il pannello rappresenta «Il nostro metodo», non una diagnosi reale del computer
del visitatore. Non presenta metriche tecniche, percentuali o risultati falsi.
Sul mobile titolo compatto «Capire. Risolvere.» e caratteri più grandi.
Il messaggio essenziale e la CTA restano HTML semantico nel capitolo; il pannello
è illustrativo, `aria-hidden`, inert e senza interazione. Reduced motion,
fallback e assenza di JavaScript non mostrano la grafica.

Verifica dedicata: `artifacts/check-screen-assistance.js`, quattro viewport da
320 a 1920 px, fasi, inversione con la stessa proiezione sullo stesso frame,
assenza di grafica in apertura e sul finale, copia leggibile al 90%, nessun
overflow e fallback reduced motion. Screenshot `artifacts/screen-assistance-*`.

## Grafica sul display — revisione del 10 ottobre 2026

Il pannello scuro all'80% dello schermo è sostituito da un'interfaccia che
occupa il display: barra con marchio, titolo, tre passi con nodo numerato che
diventa ✓, linea di avanzamento e una riga di stato per fase (diagnosi,
intervento, verifica, pronto). Riflesso diagonale del vetro e alone caldo
legano la grafica alla luce della scena. Nessuna metrica o diagnosi inventata.

Angoli da tracciamento per fotogramma (`home-screen-track.js`): sul desktop
bordi stimati su ogni frame dalla superficie ambrata, con RANSAC e smoothing
su cinque frame; sul mobile dove i bordi sono visibili, altrimenti la
calibrazione manuale. Sul telefono l'interfaccia occupa la parte del display
dentro il viewport in quel frame, con bordi sfumati: la camera non mostra mai
lo schermo intero. Script di tracciamento: `screentrack.js` (scratchpad, sharp).

Correzione dello stesso giorno: sui monitor più larghi del 16:9 il ritaglio è
verticale e l'inquadratura fissa portava la parte alta del display fuori vista.
Nel capitolo 02 il ritaglio verticale ora segue il display tracciato (entrata
graduale nei primi 30 frame) e l'interfaccia si restringe alla parte di schermo
dentro il viewport anche su desktop. Verificato con scroll continuo a 1920×855,
1658×840, 1440×900, 1280×720 e 390×844. I moduli importati sono versionati con
una import map in index.php, così un modulo in cache non incontra un motore nuovo.

## Grafica incisa nei frame — 10 ottobre 2026 (sostituisce le revisioni sopra)

La sovrapposizione HTML in prospettiva è stata abbandonata: su viewport diversi e
con un filmato generativo non resta mai solidale allo schermo. Il metodo è ora
composto dentro i fotogrammi del capitolo 02 (`sequence-screen/`), con tracciamento
per frame, riflesso del vetro e la stessa morbidezza del video. Desktop: frame
34–150, titolo e passi nella metà bassa dello schermo per restare visibili anche
nei ritagli dei monitor molto larghi. Mobile: frame 62–112, colonna a sinistra con
titolo compatto. Rimossi `screen-assistance` dal markup, dal CSS e dal motore;
`home-screen-assistance.js` non è più caricato. Procedura: `artifacts/screen-bake/`.
Verificato con scroll continuo a 1920×855, 1440×900, 1280×720 e 390×844.

Aggiornamento finale del 10 ottobre: su richiesta dell'utente il display torna
pulito. Il manifest usa di nuovo i frame originali (`sequence/`), le cartelle
`sequence-screen/` sono state eliminate; nessuna grafica HTML sullo schermo.
Gli script di composizione restano in `artifacts/screen-bake/`, non in uso.
