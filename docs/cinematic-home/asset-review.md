# Asset ricevuti e integrazione — 6 ottobre 2026

## Continuazione documentaria — 7 ottobre 2026

Dopo i tre film entra la fotografia storica reale about-story.webp, mantenuta
intera e accompagnata da «Dietro la tecnologia, ci siamo noi». Immagine e testi
seguono lo scroll durante un breve tratto di lettura; sotto 700px di altezza,
senza JavaScript o con movimento ridotto si usa il flusso ordinario.
Seguono i tre servizi compatti, la vetrina dinamica, il video su richiesta,
FAQ, eventuale volantino e contatti. Rimossa la storia duplicata più in basso.
Non sono state generate persone, negozi o nuove sequenze video.

Verifiche: check-story-continuation.js e check-home-restyle.js; nessun overflow
nei cinque breakpoint, fotografia intera, scroll reversibile, menu e video
operativi, 13 destinazioni HTTP valide, contenuti disponibili senza JavaScript.

## Capitolo 03 — Creare (7 ottobre 2026, integrato)

chapter-03.mp4: H.264, 1920×1080, 24 fps, 8 secondi, 192 frame, 2.670.260 byte.
Derivati senza audio: desktop 4.812.440 byte, mobile 1.753.616 byte. La camera
lascia il telefono e avvicina il notebook; intorno al quinto secondo compare
una composizione web astratta senza testi commerciali incorporati.

Scala e luminosità iniziali differiscono dal finale del capitolo 02: breve
passaggio nel nero controllato dallo scroll. Il manifest v3 coordina 624 frame
con una distanza attiva di 10,3 viewport e conserva i limiti della cache.
L'entrata del notebook guida la scena senza testo; il titolo «Dalle tue idee,
a qualcosa che funziona» e i link web/preventivo entrano nella pausa finale.
Immagine conclusiva estratta: chapter-03-actual-last.png. Originali invariati.

Verifica: artifacts/check-chapter-03.js, cinque larghezze, titoli e pulsanti
mobile sopra il film, percorso inverso, movimento ridotto e manifest assente.
La composizione generata è illustrativa e non viene presentata come un sito
cliente realmente consegnato.

## Capitolo 02 — Scegliere (integrato)

chapter-02.mp4: H.264, 1920×1080, 24 fps, 8 secondi, 192 frame, 2.785.075 byte.
Audio AAC presente nell'originale, escluso dalla sequenza del sito. Derivati:
192 WebP desktop 1280×720 (3.708.386 byte), 192 mobile 640×360 (1.365.400 byte).
Gli originali restano invariati. Il telefono cambia inclinazione all'inizio del
nuovo film: raccordo con breve passaggio nel nero, reversibile e legato allo
scroll. Nessuna pretesa di un'unica ripresa perfettamente continua.

story-manifest.json coordina 432 frame nello stesso canvas: primo film, raccordo,
entrata del notebook senza testo e chiusura «La prossima possibilità è tua».
Cache sempre limitata a 18 bitmap e quattro richieste concorrenti. Distanza
attiva: 7,9 viewport. Movimento ridotto o manifest assente: contenuti leggibili
con immagini statiche. Verificati cinque breakpoint, inversione dello scroll,
raccordo e stati alternativi in artifacts/check-chapter-02.js.

chapter-03-first.png è estratto dall'ultimo frame effettivo del capitolo 02,
in Full HD: usarlo come immagine iniziale per il prossimo filmato.

Gli originali forniti dall'utente sono conservati in assets/img senza modifiche.

| File | Dimensioni | Note |
| --- | --- | --- |
| first_frame.jpg | 2752 × 1536 | Vetro incrinato; spazio scuro a sinistra |
| last_frame.jpg | 2752 × 1536 | Stesso impianto visivo, schermo arancione acceso |
| storyboard.mp4 | 1920 × 1080, 24 fps, 10 s | H.264, 240 frame decodificati, audio AAC presente, 4.410.187 byte |

Il film segue la storia: circa 0–2,5 s guasto, 2,5–4,2 s apertura isolata,
4,2–8 s macro e riassemblaggio, 8–10 s ritorno in uso. Nessun taglio evidente
nella revisione dei fotogrammi; la camera cambia continuamente. La tacca e
alcuni dettagli del dispositivo variano durante la generazione: un'illustrazione
dei servizi, non una riproduzione fedele di un modello o una guida tecnica.
Le scritte microscopiche dei componenti sono parte dell'illustrazione; nessun
titolo, CTA o logo del sito viene incorporato nei pixel.

Il primo frame del video differisce dal JPG per inquadratura e riflessi;
il poster del sito è estratto dal film per garantire continuità all'avvio.
Le immagini JPG hanno rapporto 1,7917; il film è 16:9. L'audio resta
nell'originale ma non è caricato né riprodotto dalla sequenza del sito.

## Produzione web

assets/img/home/cinematic-v1 contiene 240 WebP desktop da 1280×720,
5.926.836 byte complessivi; 240 WebP mobile da 640×360, 2.246.820 byte;
poster e manifest verificati. Nessun upscaling del film. Gli originali e i
JPG non sono necessari al caricamento della home.

Il renderer mantiene massimo 18 bitmap decodificate e quattro richieste
contemporanee. Richiede il frame attuale e i vicini; i salti abbandonano
richieste distanti. Una bitmap espulsa viene chiusa. Dimensioni mobile e
desktop sono selezionate per larghezza della finestra. Poster e copia HTML
restano disponibili se il manifest o il frame iniziale non si caricano.

## Home

Il hero WebGL precedente è sostituito da una sequenza cinematografica.
La navigazione e il resto della pagina mantengono i contenuti del progetto:
prodotti, prezzi, video Facebook e volantino attivo usano il database.
Il palco usa 5,5 viewport di scorrimento attivo, con intervalli distinti
per i beat e pause di lettura. Lo scroll nativo funziona nei due sensi.
La transizione Dentro nasconde copie e link dei capitoli; il macro Cura
ha uno scrim a sinistra per evitare testo su riflessi chiari. Il pulsante
Vai ai servizi salta il racconto e porta il focus al titolo dei servizi.
Movimento ridotto e assenza di JS usano una pagina in flusso normale.

## Verifiche

Script: artifacts/check-home-cinematic.js e artifacts/check-home-restyle.js.
Risultati: artifacts/cinematic-browser-checks.txt e home-browser-checks.txt.
La seconda procedura controlla contenuti, 13 destinazioni HTTP, menu,
dialogo video, FAQ, immagini e assenza di JavaScript. La prima verifica
frame e capitoli nei due sensi, scroll nativo, cinque larghezze, salto,
movimento ridotto e fallimento del manifest. Non certifica prestazioni su
telefono fisico o tutte le reti/browser.

È stato corretto un errore di scrittura temporaneo di index.php durante
l'integrazione. Lint PHP e verifiche della pagina vengono rieseguiti dopo
la correzione. Nessun record clienti modificato e nessun deploy eseguito.

## Continuazione della home

Le sezioni successive al film sono ricostruite in includes/home-continuum.php,
con home-continuum.css e home-continuum.js: servizi con anteprima accessibile,
catalogo scuro, video ampio, fotografia storica, FAQ e contatti. I quattro prodotti
della copia locale non hanno file fotografici disponibili; il fallback è esplicito
e compatto. Due titoli vuoti nel database usano il nome del modello, senza
modificare i record. I controlli browser passano su 320/390/768/1024/1440px;
le tre anteprime vengono verificate anche tramite focus della tastiera.
# Capitolo 04 — integrazione del 7 ottobre 2026

Originali consegnati dall'utente: chapter-04-last.png (1.613.783 byte) e
chapter-04.mp4 (3.600.661 byte). Filmato H.264, 1920×1080, 24 fps, 8 secondi,
192 frame; contiene AAC, omesso nelle sequenze per il browser.
Frame iniziale confrontato con il finale effettivo del 03: stessa inquadratura,
differenza media RGB su campione 320×180 circa 1,81/255, senza salto di posa
visibile nei fotogrammi esaminati. Nessuna dissolvenza nera aggiunta a questo raccordo.
La camera arretra e cambia lato; alla fine il notebook è a sinistra. La posa
finale è una vista diversa dalla prima, coerente con il movimento della camera.

Derivati WebP qualità 76: desktop 1280×720, 192 frame, 4.460.838 byte;
mobile 640×360, 192 frame, 1.565.442 byte. Non sono fonti portrait dedicate.
Il renderer mobile segue il soggetto durante il cambio di lato. Fotogramma
finale effettivo salvato come chapter-04-actual-last.png e chapter-05-first.png.
Originali e keyframe generato preservati. Manifest v4: 816 frame totali,
34 secondi di fonti video; 11,8 altezze schermo di percorso attivo. Distanza
di scroll e durata dei filmati sono grandezze indipendenti.

Controlli eseguiti: tutti i 1.632 percorsi dei frame verificati; sintassi PHP/JS;
scroll nativo e inverso; composizioni a 320, 390, 768, 1024 e 1440 px senza
debordamenti; testi e azioni mobili sopra la zona del film; fallback per manifest
assente e movimento ridotto; regressione sulle entrate dei capitoli 01–03.
La vetrina dinamica è ora nella pausa finale del 04; eliminata la griglia duplicata.

# Capitolo 05 — integrazione del 7 ottobre 2026

Originali dell'utente: chapter-05-last.png, 1.692.124 byte; chapter-05.mp4,
4.248.747 byte. Il film consegnato dura 8 secondi (il prompt proponeva 6),
1920×1080, H.264, 24 fps, 192 frame, traccia AAC omessa nei derivati web.
Il movimento arriva al dettaglio del bordo del notebook; la composizione
finale mantiene metallo e spazio scuro. Nessuna scritta estranea nei campioni.
Primo frame effettivo confrontato con chapter-05-first.png: differenza media
RGB su 320×180 circa 1,56/255; stessa posa e illuminazione, senza salto evidente
nelle immagini esaminate. Non aggiunto un raccordo nero tra 04 e 05.

Derivati WebP: desktop 1280×720, 192 frame, 5.044.468 byte; mobile 640×360,
192 frame, 1.795.868 byte. Fonti originali conservate; ultimo frame effettivo
salvato in chapter-05-actual-last.png. Manifest v5, 1008 frame complessivi,
42 secondi di film sorgenti, 14,5 altezze attive di scroll. Verificati tutti
i 2016 percorsi desktop/mobile. Il ritaglio mobile resta derivato da landscape.

Foto storica integrata nello stesso stage dopo il movimento macro, con
ingresso da destra e testo da sinistra; il film si oscura durante questa
transizione editoriale. Rimosse la sezione fotografica separata e le grandi
sezioni ripetitive dei servizi/video; conservati link, contenuti e dialog video.
Verifiche PHP/JS e browser chapter-05/chapter-04 superate: scroll nativo,
ritorno sui frame, ingressi fotografici reversibili, 320/390/768/1024/1440px,
telefoni alti 667px, desktop 1024×600, nessun debordamento o foto tagliata,
salto ai servizi con focus, apertura/chiusura video, FAQ, movimento ridotto,
contenuti senza JavaScript. Nessuna pubblicazione esterna eseguita.

