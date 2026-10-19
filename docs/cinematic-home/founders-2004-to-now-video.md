# Raccordo video dal 2004 a oggi

16 ottobre 2026. Video richiesto dall'utente con Higgsfield, a partire dal
precedente scontorno storico e dalla nuova foto dei tre fondatori oggi.

## Struttura

Due riprese separate da 5 secondi, unite con 1 secondo di dissolvenza: durata
finale 9 secondi, 1920×1080, 24 fps, H.264, senza audio. La prima parte inizia
in bianco e nero e recupera gradualmente i colori tra 0,5 e 3 secondi.
Le persone rimangono sul lato destro, con il lato sinistro disponibile per
il testo HTML. Anni e «ORA» restano sovrapposti dal sito.

Le due composizioni provengono da fotografie differenti: non si chiede al
modello di trasformare o invecchiare i volti. La dissolvenza è montata in modo
deterministico dopo la generazione. La foto attuale mostra tre persone;
per un'ulteriore rivelazione del team servirebbe una fotografia del team.

## Generazione e controllo

Modello Kling 3.0 Omni (Frames), pro, senza audio. Le due foto sono ricomposte
su grafite prima della generazione, senza ritoccare i volti nella preparazione.
Le fotografie originali e le composizioni di riferimento sono conservate.

Primo tentativo: `0ab19942-07a9-48cb-b91d-6f814ab0a825` e
`ed60f16a-8ce7-4e45-8b47-cfca739be079`. Scartato dopo l'ispezione degli
storyboard: comparivano un fondale grigio, una scritta e luce sul bordo inferiore.
I video scartati sono in `assets/img/home/founders/video/candidates/`.

Secondo tentativo: `468ae8a9-1ddd-4085-855b-eef272901a0d` e
`32f364f2-b5b3-46b5-81b6-65bde393f566`. Prompt semplificato, nessun codice
colore nel testo; lo stesso fotogramma guida l'inizio e la fine di ogni ripresa.
Preventivo totale per i quattro job: 25 crediti (6,25 per job).

Montaggio riproducibile: `artifacts/compose-founders-memory.py`.
Esporta anche la variante 720×1280 ricomposta, senza tagliare il gruppo,
poster e storyboard di verifica. Il video generato può introdurre piccoli
movimenti ed espressioni; l'identità va valutata sulle immagini di controllo.

## Anteprima

`artifacts/founders-memory-preview.html` mostra gli anni dal 2004 al 2026 e poi
«ORA», come livelli HTML sincronizzati al video. La pagina è una preview tecnica
locale, non una proposta di nuovo design per il sito: il singolo font Arial evita
dipendenze esterne ed è intenzionale in questo artefatto. I relativi avvisi
overused-font/single-font sono falsi positivi contestuali, senza soppressioni.

## Risultato selezionato

Entrambi i secondi tentativi sono completati. Controllati inizio, metà e fine
delle singole clip e nove campioni del montaggio. I difetti di sfondo e testo
del primo tentativo non compaiono nei campioni selezionati. Il passaggio centrale
è una dissolvenza tra le due fotografie, senza trasformazione dei volti.

Master `assets/img/home/founders/video/founders-2004-to-now.mp4`:
9 secondi, 216 frame, 1920×1080, 24 fps, H.264, senza audio, 1.532.981 byte.
Variante mobile: `founders-2004-to-now-mobile.mp4`, 720×1280.
Il master e l'anteprima sono pronti; la homepage non è stata modificata in questo
passaggio di generazione.
