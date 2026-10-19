# Capitolo 02 — controllo dei filmati dello schermo

Controllo del 9 ottobre 2026. Originali conservati; nessuna modifica alla home
in questo passaggio. Analizzati metadati e sequenze campionate ogni mezzo
secondo, con dettagli a piena risoluzione e finali estratti dai filmati.

## Esito

Entrambe le varianti sono utilizzabili per proseguire la produzione. Il
cambio di scala è ampio e leggibile: dal notebook sul banco al dettaglio dello
schermo. Il mobile arriva al contenuto ambrato a pieno campo; il desktop
conserva correttamente la prospettiva obliqua e le cornici laterali.

| Variante | Dimensioni | Durata | Frame | Dimensione originale |
| --- | --- | --- | --- | --- |
| Desktop | 1920 × 1080 | 8 s, 24 fps | 192 | 4.940.606 byte |
| Mobile | 1080 × 1920 | 8 s, 24 fps | 192 | 4.753.630 byte |

I primi frame sono molto vicini ai finali effettivi del capitolo 01; ci sono
piccole differenze di resa e compressione. Non considerarli pixel identici.
La schermata ambrata resta riconoscibile nel percorso, ma il video generativo
non garantisce una proiezione geometricamente esatta di ogni curva del wallpaper.
Nei campioni esaminati non compare una transizione al nero o un cambio di scena.

## Dettagli da tenere presenti nell'integrazione

- I primi circa 0,5 secondi sono quasi fermi in entrambe le versioni. Comprimere
  questa porzione nella mappa dello scroll per evitare una seconda attesa
  dopo il finale del capitolo 01; mantenere il frame iniziale per il raccordo.
- L'avvicinamento continua fino al finale, senza una lunga pausa conclusiva.
  L'eventuale tempo di lettura va costruito nella timeline del sito.
- Sul desktop nasce un riflesso chiaro nella zona superiore del display,
  visibile durante l'avvicinamento. Nella cornice inferiore appare anche un
  piccolo segno simile a un marchio generato, prima che esca dal campo.
  Questi dettagli impediscono di definire il filmato perfetto in ogni punto,
  ma non compromettono il movimento principale.
- Il mobile esclude progressivamente il dispositivo dal campo. Il finale
  deve essere riutilizzato esattamente come immagine di partenza, evitando
  di reinventare la schermata nel capitolo seguente.
- I file contengono una traccia AAC. L'esperienza di scroll usa immagini
  estratte e non riproduce quella traccia.

## Finali effettivi per il capitolo 03

Usare i nuovi file estratti, non i finali richiesti originariamente a GPT Image:

- `assets/img/home/cinematic-v2/chapter-02/desktop/chapter-02-actual-last.png`
- `assets/img/home/cinematic-v2/chapter-02/mobile/chapter-02-actual-last.png`

Il prossimo passaggio parte da queste superfici ambrate. La camera arretra
per ritrovare lo stesso notebook, quindi scorre sul banco verso le mani della
cliente e il telefono con il vetro rotto. Per definire il nuovo frame finale
servono sia il finale effettivo dello schermo sia il riferimento più largo del
set del capitolo 01, così il generatore non inventa un ambiente diverso.

## Materiale di verifica

Script: `artifacts/inspect-chapter-02-screen.py`.
Metadati, misure delle differenze e provini:
`artifacts/chapter-02-screen-review/`.
Le differenze RGB sono misure descrittive, non punteggi di qualità né prove
di continuità fisica. Nessuna misura di velocità di caricamento è stata eseguita.
