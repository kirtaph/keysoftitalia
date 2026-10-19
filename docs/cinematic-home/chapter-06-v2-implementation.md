# Capitolo 06 — Un ricordo del 2004

15 ottobre 2026. Dopo «Scegliere», i dispositivi sfumano nel grafite e il racconto
passa ai fondatori. L'utente ha fornito una fotografia migliore e richiesto uno
scontorno, un trattamento da ricordo e un passaggio dal bianco e nero al colore.

## Fotografia

- Originale fornito dall'utente conservato in
  `assets/img/home/founders/founders-2004-original.png`.
- Scontorno trasparente realizzato con Imagegen; originale e risultato PNG
  conservati separatamente. Derivato WebP con alpha per il sito.
- Nessun video generato dei fondatori. La fotografia è trattata come un gruppo
  unico, senza animazioni facciali o movimenti inventati delle persone.

## Composizione e movimento

- Il 2004 è un elemento cronologico dietro al gruppo, non un titolo aggiuntivo.
  La sua scala decorativa (96–240 px desktop, 96–160 px mobile) è intenzionale
  per la richiesta dell'utente; i titoli restano nella scala del sito.
- La data di apertura è il 4 dicembre 2004, già documentata in `chi-siamo.php`.
- Foto e testo arrivano da lati opposti con 64 px di corsa desktop e 24 px mobile.
- La foto si assesta con un lieve cambiamento di scala (1,025→1) e 12 px di deriva;
  l'anno scorre di 12 px nella direzione opposta.
- Il ritorno del colore avviene tramite opacità di due copie della stessa immagine,
  evitando di animare il filtro su ogni frame. Il browser riutilizza lo stesso URL.
- Il nero nella `mask-image` serve solo come alpha della dissolvenza inferiore:
  è un falso positivo della regola design-system-color, non un nuovo colore di UI.
- Movimento e colore sono reversibili e dipendono dallo scroll, senza timer o loop.
- Senza enhancement o con movimento ridotto: composizione statica a colori.

## Integrazione

Il capitolo riutilizza l'ultimo frame del 05; nessuna nuova sequenza da scaricare.
Il film dissolve prima del capitolo fotografico, mentre la barra rimane disponibile.
Il motore mantiene separati dissolvenza del film e uscita dei controlli.
Il link «Conosci la nostra storia» porta a `chi-siamo.php#nostra-storia`.
Le 2,5 altezze aggiuntive lasciano invariate le distanze dei capitoli precedenti.

## Verifica

- WebP trasparente: 154.820 byte; due livelli usano lo stesso file.
- Verificati nel browser l'ingresso quasi monocromatico (colore 0,013), il ritorno
  completo al colore e la reversibilità con lo scroll.
- Layout desktop, 390×844 e 320×740: foto, testo e barra rimangono separati.
  A 320 px la foto termina a 604 px, con la barra a partire da 672 px.
- Sintassi JavaScript e PHP valida. Originale conservato senza sovrascritture.
- Anteprime in `artifacts/chapter-06-memory-{monochrome,color,mobile}.jpg`.
