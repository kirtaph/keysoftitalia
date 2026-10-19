# Capitolo 01 v2 — prima integrazione nella home

8 ottobre 2026. La home mostra il nuovo capitolo con Vito e il notebook.
La sequenza precedente è conservata in `includes/home-cinematic-v1.php` e nei
suoi asset; non viene affiancata al nuovo racconto. Dopo questo primo capitolo
rimangono i percorsi ai servizi, FAQ e contatti esistenti. La nuova storia non
è ancora completa e non è stata pubblicata su un server esterno.

## Composizione

Testo HTML: «Il tuo mondo digitale, in buone mani.» con azione «Parliamone».
Sul desktop la frase di supporto introduce l'ascolto; sul mobile resta
un'apertura breve nello spazio sopra il volto. Colori e dimensioni riprendono
il sistema esistente, eliminando le nuove tinte fuori palette segnalate dal
design hook. L'entrata iniziale muove il contenitore, mentre i singoli blocchi
escono lateralmente secondo lo scroll: le due animazioni non si sovrascrivono.

Timeline nativa, reversibile, di 3,2 altezze di viewport attive:

| Tratto | Scroll | Fotogrammi |
| --- | --- | --- |
| Lettura dell'apertura | 0–16% | 0 |
| Avvicinamento | 16–94% | 0–144 |
| Coda compressa e raccordo futuro | 94–100% | 144–239 |

Lo scroll governa il fotogramma e l'uscita dei testi. Nessun taglio alla
geometria per mascherare il movimento laterale o il gesto mobile della fonte.
La camera resta quella generata, con i limiti già registrati nella verifica.

## Asset e caricamento

Originali MP4 intatti. Derivati WebP a 24 fps: 240 desktop 1440 × 810 e
240 mobile 720 × 1280. Totale su disco rispettivamente 13.666.746 e
11.574.570 byte: non sono misure di velocità di caricamento. Il renderer
richiede soltanto fotogrammi vicini, mantiene fino a 18 bitmap e al massimo
quattro richieste contemporanee, annullando quelle lontane dopo un salto.
Le varianti vengono selezionate dal viewport. Poster dedicati tramite picture
anche senza JavaScript; con movimento ridotto il contenuto resta statico e
leggibile. I controlli che escono dalla scena vengono resi inert.

## Verifica

PHP: sintassi corretta per index e nuovo include. Prova browser a 1440 × 1000,
390 × 844 e 320 × 667: avanzamento, inversione, salto al finale effettivo,
uscita del testo, assenza di overflow orizzontale, azioni nascoste non attive,
salto ai servizi con focus e fallback per movimento ridotto. Prova aggiuntiva:
menu mobile con chiusura Escape, manifest indisponibile e JavaScript disabilitato
con poster verticale e titolo leggibile.
Il browser segnala violazioni CSP preesistenti per font data e raccolta
Analytics su region1.google-analytics.com; non sono errori del nuovo renderer.

Script: `artifacts/prepare-chapter-01-v2.py` e
`artifacts/check-chapter-01-v2.js`. Screenshot in artifacts/chapter-01-v2-*.

## Correzione del nero e del ritaglio desktop

Segnalazione dell'utente riprodotta il 8 ottobre. Durante il restringimento
dell'header, ResizeObserver assegnava continuamente le dimensioni del canvas,
cancellandone il contenuto dopo i callback di animazione. Il ridisegno al
requestAnimationFrame successivo lasciava nero il buffer per la transizione:
misurati 15 campioni consecutivi neri desktop e 14 mobile nel primo test.
Ora le dimensioni vengono assegnate solo quando cambiano e il fotogramma viene
ridisegnato sincronicamente nello stesso callback di ridimensionamento.
Il poster rimane dietro al canvas, che viene nascosto mentre si carica una
nuova variante dopo il cambio di breakpoint.

Sui desktop larghi e bassi il cover centrato tagliava la testa all'apertura.
Il ritaglio verticale parte ora dal bordo superiore e segue progressivamente
il notebook fino al fotogramma 144. Il movimento di camera della fonte resta
visibile; la testa esce successivamente come previsto dalla ripresa.
Il mobile mantiene la centratura della sua composizione verticale dedicata.

Test dedicato `artifacts/inspect-welcome-resize.js`: campionamento del canvas
ad ogni animation frame durante scroll avanti/indietro e transizione dell'header,
con screenshot dell'apertura a 1920×900, 1440×600 e 390×844.

## Ingresso diretto, clarify e colorize

Su richiesta dell'utente, l'accesso nuovo senza ancora entra al 16% della
timeline: il punto in cui comincia l'avvicinamento. La topbar è già fuori dal
viewport e resta visibile la navigazione principale compatta. Il frame iniziale
e il testo sono ancora interi; il primo scroll avanza la ripresa. L'utente
può tornare sopra normalmente. Nessun autoplay né blocco dello scroll.
Reload, cronologia e posizioni di lettura già ripristinate non vengono forzati.
Le ancore vengono riallineate dopo l'espansione dello stage. Movimento ridotto
mantiene il layout statico e non esegue l'ingresso automatico.

Applicate le reference impeccable clarify e colorize al solo capitolo 01:
titolo «La tua tecnologia, in buone mani.»; descrizione concreta di riparazioni,
scelta dei dispositivi e progetti digitali; azione «Richiedi un preventivo»
coerente con la pagina di destinazione; indicazione «Scorri per continuare».
Il luogo diventa «A Ginosa, dal 2004», senza ripetere il marchio della testata.

Strategia colore restrained, con il film come colore dominante: bianco caldo
esistente #fffaf7 per il titolo, dettaglio chiaro del marchio #ffe5db per
descrizione, focus e hover, arancio #ff976f per seconda riga, luogo e progresso.
CTA arancio primario #ff6b35 con testo scuro #17191e. Non alterati i film,
il logo, la navigazione generale o i capitoli successivi. Rifinitura visiva
secondo impeccable polish, con controllo dei viewport bassi.

Test `artifacts/check-welcome-entry.js`: ingresso diretto, topbar fuori vista,
primo scroll in movimento, titolo leggibile e nessun overflow a 1920×900,
1440×600, 390×844 e 320×667; ancore e posizione al reload preservate.
Ripetuta la prova di timeline, inversione, controlli inattivi e movimento ridotto.
Il comando «Vai ai servizi» ha uno sfondo opaco per restare leggibile anche
sulla camicia chiara del film. Durante lo stage il pulsante flottante «Torna su»
è nascosto e inert per evitare la sovrapposizione mobile al comando; torna
disponibile dopo la scena. Verificati salto ai servizi e ripristino del controllo.

## Preparazione dell'ingresso senza sfarfallio

Un piccolo script sincrono nel head attiva la schermata di caricamento prima
che il browser mostri il layout statico. Lo spinner arancio è accompagnato
dal testo «Caricamento…» con role=status. Non viene mostrato senza JavaScript
né con movimento ridotto. Il contenuto principale segnala aria-busy durante
la preparazione.

La schermata resta finché il primo fotogramma è decodificato, i font principali
sono caricati (attesa limitata a 2 secondi), lo stage è dimensionato e lo scroll
d'ingresso si è assestato per due animation frame. Solo allora si scopre la
scena e inizia l'animazione dei testi. Nessun caricamento anticipato dell'intero
film; rimangono cache e richieste limitate del renderer.

Errore di manifest o fotogramma: viene mostrato il layout statico leggibile.
Un watchdog di 8 secondi sblocca la pagina anche se il modulo del renderer
non arriva; una risposta tardiva non riattiva la sequenza dopo questa scadenza.
Test `artifacts/check-welcome-loader.js`: fotogramma iniziale ritardato di
1 secondo su desktop/mobile, spinner durante l'attesa, scena correttamente
posizionata alla rivelazione, frame mancante e modulo JavaScript mancante.
