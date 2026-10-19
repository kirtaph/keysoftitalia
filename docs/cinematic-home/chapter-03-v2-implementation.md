# Capitolo 03 v2 — la consegna, integrazione

10 ottobre 2026. Clip consegnate dall'utente con i prompt di
`chapter-03-v2-video-prompts.md`.

## Controllo dei video

| Variante | Formato | Durata | Frame |
| --- | --- | --- | --- |
| Desktop | 1920×1080, H.264 | 8 s, 24 fps | 192 |
| Mobile | 1080×1920, H.264 | 8 s, 24 fps | 192 |

- Raccordo: il primo frame differisce dal finale reale del capitolo 02 di 2,1
  (desktop) e 3,6 (mobile) livelli medi su 255: nessun salto visibile.
- Arretramento dallo schermo, spostamento sul banco, consegna del telefono e
  pausa finale come da prompt. Un solo telefono, sempre intero; mani naturali.
- Mobile: per circa mezzo secondo (frame 466–500) l'arretramento mostra il
  mento con la barba di Vito. Nel sito quella parte cade sotto il menu ed è
  sfocata: accettato. Desktop: il volto non entra mai.
- Traccia audio presente negli originali, non usata.

## Integrazione

- Derivati 1440×810 e 720×1280, WebP q80 + AVIF q55, in `chapter-03/*/sequence/`.
  Desktop 12,3 MB WebP / 7,2 MB AVIF; mobile 10,8 / 6,6 MB.
- Finali reali per il capitolo 04: `chapter-03/{desktop,mobile}/chapter-03-actual-last.png`.
- Manifest: 624 frame su 10 altezze di schermo. I capitoli 01 e 02 mantengono
  le stesse distanze in schermi di prima; si aggiungono 2,6 schermi di consegna
  senza testo e 1 di lettura. Tempi del testo ridefiniti in `manifest.copy`.
- Testo HTML a sinistra nella pausa finale: «Un imprevisto, una soluzione.»,
  descrizione sulle riparazioni smartphone e «Prenota una riparazione».
- Barra dei capitoli: «03 La consegna». Poster statico per movimento ridotto.

Verificato: sequenza completa desktop 1440×900 e mobile 390×844, 320×640,
movimento ridotto, nessun errore in console. Scroll: 17 ms per frame su desktop.
