# Fondatori — trasformazione continua, v2

16 ottobre 2026. L'utente ha rifiutato la dissolvenza fra le due fotografie:
vuole vedere i tre fondatori diventare progressivamente le persone di oggi.

Il nuovo asset viene generato come una sola ripresa di 10 secondi con Kling 3.0
Omni (Frames), pro, senza audio. Non si montano insieme riprese dei due periodi.
La preparazione ricompone il riferimento giovane nell'ordine e nell'inquadratura
del ritratto attuale, mantenendo i riferimenti dei volti e dell'abbigliamento.
Questo è un fotogramma ricostruito per guidare il modello, non una nuova foto d'archivio.

Il prompt distribuisce la trasformazione sull'intera ripresa: maturazione del viso,
capelli, occhiali e abiti, con posizione sinistra/centro/destra stabile. La camera
resta ferma e il fondale scuro resta semplice. Vietati nel prompt stacchi,
dissolvenze e sovrapposizioni delle fotografie.

Il modello concentra la trasformazione nella parte centrale. Il mastering estende
la porzione continua 2,6–6,4 secondi su 10 secondi con interpolazione compensata
del movimento, per rendere più leggibili le età intermedie. Aggiunge il ritorno
progressivo della saturazione e l'encoding per il web. Non inserisce altre riprese,
crossfade o trasformazioni 2D fra le fotografie.
Prima della consegna si controllano gli stadi intermedi e la continuità dei volti.
Riferimenti e parametri sono salvati in `founders-aging-v2-generation.json`.
Gli asset precedenti restano conservati come versioni rifiutate.

L'anteprima usa Poppins e Inter, già definiti dal design system del progetto.
I warning overused-font su Inter sono falsi positivi contestuali: qui si conserva
la tipografia del sito, senza introdurre una nuova identità o sopprimere regole.
