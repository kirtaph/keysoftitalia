# Revisione del movimento dei testi — 7 ottobre 2026

Applicate Impeccable (registro brand) e Review Animations. Workflow sorgente:
Barty-Bart/gpt-6-astra-10k-websites; adattamento locale installato come
cinematic-brand-websites, con i due prompt originali come riferimenti.

| Before | After | Why |
| --- | --- | --- |
| Il capitolo cambia classe e il testo compare con una dissolvenza a tempo | Titolo, descrizione e azioni entrano su intervalli di scroll propri | Il testo arriva con il film, senza dipendere da un timer |
| Il blocco entra tutto insieme | Etichetta, titolo, descrizione e azioni hanno uno sfasamento di 0,025 viewport | Gerarchia visibile senza lettere animate o frammentazione del testo |
| Il fondale cambia al confine del capitolo | La sua opacità segue i contenuti | Evita lo stacco improvviso di luminosità |
| L'inversione dello scroll riavvia la transizione CSS | Traslazione e opacità derivano dalla posizione corrente | Inversione interrompibile e deterministica |

Solo transform/opacity sui singoli elementi, senza variabili di movimento
ereditate dal contenitore. Curva condivisa cubic-bezier(.23,1,.32,1), campionata
una volta. Entrata di 0,16 viewport, sfasamento di 0,025, uscita di 0,16. Sono
distanze del racconto, non durate di animazioni UI. Apertura leggibile da subito;
i capitoli successivi entrano gradualmente. Link ancora in arrivo o in uscita
sono inert; non intercettano click. Nessuna animazione avviata da tastiera.

**Verdetto: Approve per il movimento verificato.** Il test check-story-copy.js
misura gli stati intermedi, la posizione finale e la corrispondenza sul percorso
inverso per cura, ripartenza, scelta e creazione. Verifica anche movimento ridotto.
Controllo visuale delle catture desktop e mobile; non è un benchmark su telefono
fisico.

## Continuazione dopo i film

| Before | After | Why |
| --- | --- | --- |
| Servizi estesi e storia distante dal film | Capitolo fotografico reale subito dopo il notebook, poi servizi compatti | Il racconto passa dai dispositivi alle persone |
| Sezioni successive immobili | Entrate native di testi, vetrina e video con transform/opacity | Continuità di movimento anche fuori dal palco del film |
| Nessuna pausa sulla fotografia | Breve tratto di lettura con fotografia intera | La storia reale resta leggibile e non taglia i volti |

**Verdetto: Approve per la continuazione verificata.** check-story-continuation.js
controlla cinque larghezze, foto intera, inversione, movimento ridotto. Le letture
di posizione sono raggruppate prima delle scritture; pose immutate non riscrivono
gli stili. Contenuti e controlli in flusso restano disponibili. Sotto 700px di
altezza il capitolo fotografico usa flusso normale per evitare contenuti tagliati.
# Aggiornamento 7 ottobre — movimento laterale e seguito in produzione

I testi dei capitoli cinematografici entrano dalla zona libera sinistra con
spostamento orizzontale massimo 80 px su desktop e 24 px su telefono, mantenendo
le pause leggibili. Fotografia e testo del capitolo persone hanno direzioni
contrapposte (100 px desktop, 20 px telefono) e una deriva contenuta nella pausa.
Nessun nuovo filmato è stato integrato in questo aggiornamento.

Verifiche eseguite: sintassi dei due script; controlli browser story-copy e
story-continuation superati; scroll inverso deterministico; foto visibile senza
debordamenti a 320, 390, 768, 1024 e 1440 px; movimento ridotto ripristina il flusso
statico. Questi controlli non certificano ancora i futuri capitoli 04–05.
Storyboard, prompt, destinazione dei contenuti esistenti e stato di produzione:
[next-chapters-prompts.md](next-chapters-prompts.md).

# Capitolo 04 — verifiche dopo la consegna

Filmato integrato nella stessa timeline, con intervallo iniziale senza testo,
copy che entra dalla destra e pausa finale per la selezione reale. Entrate e
uscite restano native e reversibili; la vetrina diventa cliccabile dopo l'ingresso.
I primi tre capitoli passano da 10,3 a 8,6 altezze attive, conservando le fasi
separate. Il totale è 11,8, inclusi il movimento del 04 e la selezione.
Verifiche browser chapter-04 e story-copy superate: avanzamento tramite rotella,
salti e ritorno sui frame 623/708/815, 320–1440px, nessun debordamento, azioni
mobili fuori dalla zona del film, fallback del manifest, movimento ridotto,
vetrina disponibile senza JavaScript. Composizione verificata anche a 1024×600.
Limite: sequenza mobile derivata dal film desktop con ritaglio laterale guidato;
resta da produrre la sorgente verticale dedicata. Il capitolo 05 non è integrato.

# Capitolo 05 — passaggio alle persone

La camera macro percorre 1,1 altezze di scroll senza copia sovrapposta. Le
successive 1,6 mantengono l'ultimo frame mentre il film si oscura e fotografia
e testo autentici entrano da lati opposti nello stesso stage. Entrate su 0,45
altezze, scaglionate di 0,025; pause di lettura e uscite separate. Riduzione
del movimento ripristina foto, testo, vetrina e azioni in flusso normale.
La chiusura ordinaria conserva solo collegamenti, video su richiesta, FAQ e
contatti; il titolo di contatto ha un movimento laterale breve e si stabilizza.
Verifiche browser chapter-05 e regressione chapter-04 superate; dimensioni da
320×667 a 1440×1000 e 1024×600; foto intera e testo dentro lo stage; film al
frame 1007 nel capitolo persone; forward/reverse; video Escape e pulizia iframe;
FAQ; skip con focus; noJS e movimento ridotto. Rimane il limite della sorgente
mobile landscape già documentato. Le prove non misurano la fluidità su hardware
telefonico fisico né i tempi di caricamento di una rete mobile reale.

# Scena mobile in sovrapposizione — correzione del 7 ottobre

Il film occupa l'intero stage mobile (cover); testo e azioni sono sullo stesso
fotogramma. Eliminata la divisione verticale al 43%. Gradiente di contrasto
verticale coordinato con la copia, assente nei passaggi senza testo. I controlli
che richiedevano pulsanti sopra la zona del film sono superati da questa scelta.
Check-mobile-overlay superato: limiti reali di drawImage coprono il canvas;
copia nello stage; gradiente attivo durante lettura; 320×667, 390×844, 430×932,
1440×1000; scroll inverso, menu, Escape e movimento ridotto. Regressione
chapter-05 superata. Screenshot ispezionati per apertura e prospettiva.
La fonte mobile rimane landscape: il cover mostra dettagli più ravvicinati.

