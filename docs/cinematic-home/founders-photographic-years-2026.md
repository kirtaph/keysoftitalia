# Capitolo 06: dal 2004 al 2026

La richiesta del 16 ottobre 2026 sostituisce i tentativi video con due fotografie reali. La foto storica resta quella del capitolo originale; la fotografia attuale approvata è conservata in `assets/img/home/founders/founders-now-approved.png`, con derivato WebP da 1200 px per il sito. I volti non vengono ricostruiti o deformati.

Gli anni HTML avanzano da 2004 a 2026 dietro ai fondatori durante lo scroll. La foto storica torna a colori, sfuma nel fondo grafite e lascia emergere quella attuale. La fusione passa brevemente attraverso il fondo per evitare sovrapposizioni dei visi. Scorrendo indietro si ripercorre la stessa sequenza.

`home-founders-blend.js` usa il Three.js già presente nel progetto: un piano, due texture, uno shader con dissolvenza appena irregolare. La foto attuale usa un asset con alpha reale, condiviso da WebGL e fallback CSS. La prima maschera geometrica è stata rimossa perché lasciava aloni neri e spigoli intorno alle teste e alle spalle. Imagegen è stato usato solo per ricavare la maschera alpha; questa è stata applicata ai canali RGB della fotografia originale, senza usare i colori o i volti della sua ricostruzione. Il master è `founders-now-2026-cutout.png`, il derivato web è `founders-now-2026-cutout.webp`. Prompt: rimuovere soltanto lo sfondo nero, con bordi naturali, preservando tutte e tre le persone, capelli, mani e abiti, senza ritocco. Il modulo si carica avvicinandosi al capitolo; disegna solo quando cambiano progressione o dimensioni, con DPR massimo 1.5. Texture e renderer vengono rilasciati all'uscita dalla pagina.

La versione CSS mantiene la dissolvenza se WebGL non è disponibile o perde il contesto. La modalità a movimento ridotto e il layout statico mostrano direttamente la foto attuale con 2026. Le altre scene del film non sono cambiate.

Verifiche: sintassi PHP e JavaScript; navigazione del capitolo nel browser locale; foto 2004 e 2026 su desktop e a 390 × 844; shader Three.js attivo senza errori in console. Anteprime salvate in `artifacts/founders-2026-three-desktop.jpg` e `artifacts/founders-2026-three-mobile.jpg`.
