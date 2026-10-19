# Key Soft Italia — Dal guasto, a una nuova possibilità

**Aggiornamento:** gli asset sono stati forniti dall'utente e integrati in
index.php. Il film dura 10 secondi; la timeline è adattata ai suoi 240 frame.
Per stato finale, limiti e verifiche vedere [asset-review.md](asset-review.md).
Le sezioni seguenti conservano il brief di produzione originale.

Direzione proposta per index.php, 6 ottobre 2026. Produzione autorizzata tramite
Higgsfield; primo invio rifiutato prima della creazione del job. Account verificato:
piano free, 0 crediti. La generazione delle immagini richiede il piano indicato
dal servizio. Nessun asset generato e nessun job da recuperare. Si prosegue con
gli asset esterni offerti dall'utente, senza cambiare fornitore in autonomia.

## Identità

Precisa, tangibile, vicina alle persone. Un film di prodotto in macro: vetro,
alluminio e componenti reali, luce arancione del marchio, spazio scuro. Logo
originale preservato. Poppins e Inter mantenuti. Nessun marchio di dispositivo
inventato, nessun negozio sintetico presentato come quello reale.

## Visual Story

| Scene | Visual story | Website copy |
| --- | --- | --- |
| 01 — Il guasto | Un unico smartphone in macro, vetro incrinato. La camera si avvicina; luce radente rivela la frattura. Soggetto nel centro-destra, spazio scuro a sinistra. | **La tua tecnologia merita un'altra possibilità.** Smartphone e computer: riparazioni, assistenza e dispositivi ricondizionati a Ginosa. **Richiedi una riparazione** |
| 02 — Dentro | Lo sfondo lascia il campo. Il medesimo dispositivo ruota e si apre in tre strati leggibili: schermo, componenti, scocca. Nessun altro oggetto appare. | No copy — let the motion lead |
| 03 — La cura | La camera raggiunge i componenti. Una luce arancione ne evidenzia i dettagli; il display integro si avvicina alla scocca. Materiali, orientamento e identità restano coerenti. | **Dentro ogni soluzione, c'è cura.** Diagnosi e assistenza per trovare la strada giusta per il tuo dispositivo. **Scopri il laboratorio** |
| 04 — Riparti | Il telefono si ricompone, il display si accende con un'immagine astratta priva di scritte. La camera arretra, rivelando il dispositivo completo. | **Pronto per quello che viene dopo.** Ripara il tuo dispositivo o scegli il prossimo, nuovo o ricondizionato. **Esplora il catalogo** |

La riparazione è una metafora visiva dei servizi, non un tutorial o una promessa
di durata/esito. Il telefono resta lo stesso in tutto il film. Non compaiono
recensioni, certificazioni o numeri commerciali non verificati.

## Scroll pacing

Un palco visivo alto un viewport resta fissato durante 5,5 viewport di scroll
attivo; l'altezza complessiva della sezione è 6,5 viewport. Scorrimento nativo,
reversibile. Il numero di secondi del video non determina la distanza di scroll.

| Beat | Scroll attivo | Intervallo video | Inquadratura e testo |
| --- | --- | --- | --- |
| Guasto | 0–1,25 viewport | 0–2 s | Avvicinamento lento. Titolo e CTA a sinistra; breve sosta sul primo frame per leggere. |
| Dentro | 1,25–3 viewport | 2–4 s | Il testo esce, il soggetto resta solo e si apre. Nessun capitolo successivo anticipato. |
| Cura | 3–4,5 viewport | 4–8 s | Dettaglio macro e riassemblaggio; testo sul lato libero. Sosta sul dettaglio. |
| Riparti | 4,5–5,5 viewport | 8–12 s | Allontanamento e risultato. Ultimo frame fermo mentre la CTA resta leggibile. |

Il montaggio deve contenere abbastanza movimento nella transizione; allungare
lo scroll non compensa un filmato a scatti o una camera che non isola il soggetto.
I tempi sono iniziali e vanno regolati sul film accettato e sullo scroll reale.

Dopo il palco: servizi in impaginazione aperta, vetrina alimentata dal database,
storia e contatti reali. Ogni sezione conserva una funzione commerciale precisa.
Il resto della home scorre normalmente. Link per saltare al contenuto utile.

## Asset da produrre

1. Frame iniziale 16:9: smartphone integro nella geometria ma con vetro incrinato,
   spazio negativo a sinistra, illuminazione di studio.
2. Frame finale derivato dal frame iniziale: stesso smartphone e set, display
   riparato e acceso; camera un poco più lontana. Usare il primo come riferimento.
3. Un solo video continuo di 12 secondi, 16:9, almeno 1920×1080, senza audio,
   tagli, titoli, logo, interfaccia leggibile, watermark o altri dispositivi.

Con Higgsfield: Kling 3.0, modalità pro, sound off, start_image ed end_image.
Schema live verificato il 6 ottobre: durata 3–15 s, entrambi i ruoli supportati.
Preventivo video 18 crediti; immagine GPT Image 2.5 0,25 crediti per richiesta.
Due immagini + un video: 18,5 crediti indicativi, prima produzione; eventuali
correzioni richiedono un preventivo separato. Un invio del frame iniziale è
stato rifiutato con "Requires basic plan or higher"; nessun job è stato creato.
L'impostazione preventivata delle immagini è quality low, resolution 1k. Per
produzione esterna si richiedono originali almeno Full HD, senza upscaling.

Chi produce fuori dalla chat può fornire i due PNG e l'MP4 originale usando i
prompt in prompts.md. Non comprimere via WhatsApp; conservare gli originali.
La posizione dell'oggetto deve sopravvivere a un crop verticale: centro-destra
moderato, non sul bordo. Su mobile testo sopra/sotto il soggetto, non sopra
componenti importanti. Asset mobile separato solo se il crop risulta insufficiente.

## Integrazione e verifiche

Poster immediato; sequenza WebP estratta solo dal film accettato, manifest con
dimensioni e numero effettivo dei frame. Punto di partenza: 18 fps, larghezza
1280, qualità 78. Misurare dimensione del trasferimento prima di integrarli.
Priorità al frame corrente e ai vicini; richieste limitate, cache di bitmap
limitata e rilascio delle immagini espulse. Non decodificare l'intero film in RAM.
Testare salto rapido, inversione, resize, errori di rete e bordi dei capitoli.
Testi, CTA e navigazione sono HTML, separati dai pixel del film.

Movimento ridotto: poster e capitoli in flusso normale. Fallimento asset:
contenuto leggibile e CTA operative. Il video film non è requisito per capire
l'attività o contattare il negozio. Nessun blocco della rotella o del touch.

La tavola direction-board.html è un campione di stile, non il sito finito.
La foto di laboratorio presente è illustrativa e già disponibile nel progetto;
non è un fotogramma della nuova produzione. index.php resta invariato finché
non sono disponibili asset accettabili per questa direzione.

## Revisione della tavola

Verificata a 390, 768 e 1440px: nessun overflow e nessuna immagine mancante.
I marker 01–04 identificano i beat di uno storyboard di produzione; non sono
decorazione numerata delle sezioni della home finale. Il richiamo generico del
detector a Inter è contestualmente intenzionale: la tipografia del marchio è
preservata. Nessuna regola è stata soppressa.
