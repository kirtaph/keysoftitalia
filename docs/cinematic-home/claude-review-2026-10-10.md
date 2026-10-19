# Revisione e continuazione — 10 ottobre 2026

## Cosa è cambiato

- **Avvio.** Il timeout del sipario conta solo mentre la scheda è visibile:
  aperta in background, la home non ripiega più per sempre sulla versione statica.
- **Frame.** 864 derivati AVIF q55 accanto ai WebP (40 MB → 23 MB). Il motore
  carica per priorità e conserva i frame compressi (max 260) separati dalle
  bitmap decodificate (max 12). Gli strati di sfondo partono solo dopo il primo
  scroll reale; con Risparmio dati o rete 2g/3g restano spenti.
- **Scritture DOM.** Solo quando un valore cambia; `--story-progress` vive sulla
  barra, non sulla sezione; una sola lettura di layout per frame.
- **Uscita dal film.** Dissolvenza nel colore della pagina nell'ultimo tratto di
  lettura; i controlli "Scorri" e "Vai ai servizi" si spengono con essa.
- **Dopo il film.** Contenuto invariato rispetto a Codex (scorciatoie, FAQ,
  volantino, contatti): Scegliere, Creare e Persone diventeranno capitoli del
  film. Cambia solo il motore degli ingressi: una tantum con IntersectionObserver
  invece di letture di layout a ogni scroll.
- **Sito.** Logo WebP (85 KB invece di 1,08 MB) in header, menu e footer;
  Swiper non caricato in home; il service worker non salva più i frame del film
  (cache v6); CSP aperta a `*.google-analytics.com` (GA4 usa region1).
- **Bug preesistente.** Nella versione statica l'immagine del capitolo 02
  usciva a 1440 px fissi: il `<picture>` ora è a blocco.

## Misure (Chrome headless, primo accesso, scroll di una schermata al secondo)

| | Prima | Dopo |
|---|---|---|
| Frame scaricati, film completo, desktop | 32,6 MB | 21,2 MB |
| Caricamento iniziale mobile 4G | 2,3 MB | 1,8 MB |
| Frame in ritardo, mobile 4G CPU 4× | 64 % | 45 % |
| Logo | 1.080 KB | 85 KB |

Il ritardo residuo su mobile dipende dalla rete (servono ~58 frame/s a quella
velocità di scroll). In produzione con HTTP/2 si può alzare `MAX_REQUESTS`.
Restano ~430 ms di calcolo stili iniziale con CPU 4×: dipendono dai CSS globali
(Bootstrap 227 KB e Remixicon 128 KB, usati all'1-6 %).

## Capitolo 03

Per aggiungerlo: derivati WebP + AVIF in `chapter-03/{desktop,mobile}/sequence`,
nuova voce in `manifest.sources`, nuovi `beats` e `activeViewportHeights`.
Il testo del capitolo computer è legato oggi a `position>.80 && drawn>=390`:
con un terzo capitolo quella condizione va resa per-capitolo nel manifest.

## Clarify dei testi — 13 ottobre 2026

Regole adottate per la home:
- Riparazioni (computer e smartphone) → «Prenota una riparazione». Il preventivo resta
  per l'apertura generale e nella risposta della FAQ sul costo.
- Il capitolo 03 pone il problema senza pulsante; l'azione arriva alla fine del 04.
- Barra dei capitoli: Benvenuto · Il computer · Lo smartphone · La riparazione;
  «Vai ai servizi» al posto di «Salta il film»; «storia», non «film», nelle etichette.
- Scorciatoie dopo il film con nomi paralleli (Riparazioni, Nuovo e ricondizionato,
  Siti e progetti web, Telefonia, Luce e gas, Gestionali per negozi, Tutti i servizi).
- FAQ: titolo «Prima di cominciare.», la domanda sul costo risponde «Sì».
- Tolte le etichette decorative sopra FAQ e contatti; restano solo quelle con un
  significato (luogo e anno in apertura, passi numerati della riparazione, volantino).
- Testi alternativi semplici; la scena con Vito resta dichiarata come ricostruita.
