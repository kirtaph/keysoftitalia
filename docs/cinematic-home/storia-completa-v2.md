# La storia completa v2 — piano di lavoro

10 ottobre 2026. Raccoglie in un solo posto il piano v2 di Codex
(capitoli 01–05, in `chapter-02-v2-screen-frame-prompts.md`) e le idee della
continuazione v1 (`next-chapters-prompts.md`: vetrina, persone, contatti),
adattate al set con Vito. Produzione dell'utente: GPT Image per i finali,
Flow/Veo 3.1 per clip di massimo 8 secondi. Testi e pulsanti restano HTML.

La parte sotto il film resta volutamente minima (scorciatoie, FAQ, volantino,
contatti) finché Scegliere e Persone non diventano capitoli del racconto.

## Visual Story

| # | Stato | Visual story | Website copy |
| --- | --- | --- | --- |
| 01 — Benvenuto | Integrato | Vito dietro al banco; la camera scende verso notebook e mani. | «La tua tecnologia, in buone mani.» / Richiedi un preventivo |
| 02 — Il computer | Integrato | Avvicinamento allo schermo; sul display il metodo Diagnosi · Intervento · Verifica (HTML proiettato). | «Il tuo computer, di nuovo al tuo fianco.» / Richiedi un preventivo |
| 03 — La consegna | **Prossimo da produrre** | Dal display pieno la camera arretra e scorre sul banco: le mani di una cliente appoggiano un telefono con il vetro rotto. | Nessun testo durante il movimento. |
| 04 — Il display | Da produrre | Ritirate le mani, il solo modulo display si separa dal telefono (una clip dedicata, una sola azione). | «Un imprevisto. Una soluzione.» / Diagnosi e riparazioni smartphone · Prenota una riparazione |
| 05 — Ripartire | Da produrre | Il ricambio si posa, il telefono si accende con la schermata ambrata del set. | «Pronto a ripartire.» |
| 06 — Scegliere | Proposta | La camera sale dal telefono acceso agli scaffali caldi già presenti nel set e si ferma su una composizione morbida. Nella pausa entra dal lato libero la vetrina HTML con prodotti, gradi e prezzi reali del database. | «Il prossimo è qui.» / Nuovo o ricondizionato, scegliamolo insieme · Catalogo · Valuta il tuo usato |
| 07 — Le persone | Proposta | Fine del film generato: dissolvenza nel grigio e fotografie vere. La foto d'archivio del 2004 incontra il presente; foto e testo arrivano da lati opposti. Cambio editoriale, non un finto viaggio nel negozio. | «Dietro la tecnologia, ci siamo noi.» / Dal 2004 a Ginosa · Conosci la nostra storia |
| 08 — Incontrarsi | Esistente, da ricomporre | Il titolo dei contatti attraversa la scena e si ferma; indirizzo, orari e azioni restano stabili. | «Il prossimo passo? Parliamone.» / WhatsApp, telefono, indicazioni |

«Creare» (siti e gestionali) resta tra le scorciatoie: un capitolo dedicato
allungherebbe troppo il racconto. Si può riconsiderare dopo il 06.

## Ritmo dello scroll

Oggi 432 frame su 6,4 altezze di schermo. Con 03–05 si arriva a circa 1.000
frame. Non sommare le distanze: comprimere le code quasi ferme di ogni clip
(come già fatto per 01 e 02) e alternare movimento e pause di lettura.
Obiettivo indicativo: 12–13 altezze di schermo fino al 05, più ~1 per la
vetrina. Da calibrare sui filmati veri.

## Prima di generare il capitolo 03

Prompt pronti in `chapter-03-v2-delivery-frame-prompts.md`, ancora validi.
Allegare due immagini per variante (finale reale del 02 + finale del 01).
Nota di continuità: all'apertura del 01 c'è uno smartphone scuro sul banco,
a destra delle mani di Vito, che esce di scena entro il frame ~110. Il finale
reale del 01 allegato ai prompt non lo mostra, quindi i prompt restano validi;
nel controllo del finale del 03 basta verificare che il telefono della cliente
non sembri lo stesso oggetto rimasto sul banco.

Salvare i finali in:

- `assets/img/home/cinematic-v2/chapter-03/desktop/last-frame-delivery.png`
- `assets/img/home/cinematic-v2/chapter-03/mobile/last-frame-delivery.png`

Dopo il controllo dei finali preparo i prompt Veo del 03.

## Integrazione tecnica

Il motore è pronto per altri capitoli senza modifiche al codice:

1. derivati WebP + AVIF in `chapter-0N/{desktop,mobile}/sequence/`
   (`artifacts/prepare-*.py` per i WebP, sharp q55 per gli AVIF);
2. nuova voce in `manifest.sources` e nuovi `beats`;
3. nuovo blocco `<div class="cinematic-chapter" data-chapter="…">` nel markup;
4. tempi del testo in `manifest.copy` (`visible`, `minFrame`, `side`, `enter`, `exit`);
5. `activeViewportHeights` ricalibrato.

Restano specifici del capitolo 02 il percorso proiettato sullo schermo
(`home-screen-assistance.js`, frame 240–431) e l'inquadratura verticale
desktop in `draw()`; dal frame 432 in poi l'immagine resta centrata.
