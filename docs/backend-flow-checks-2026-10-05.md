# Verifica dei flussi backend — 5 ottobre 2026

## Correzioni

- **Form luce/gas:** la query usava `utility_promotions.operator_name`, assente nello schema locale. Ora usa il nome del partner e un valore predefinito quando manca il partner.
- **Import CSV:** prezzi con punto decimale (`199.90`) conservano i centesimi. Sono supportati anche virgola decimale e separatori italiani (`1.200,00`). Importi malformati o negativi vengono rifiutati. Prima della transazione vengono validati elenco, campi, SKU duplicati, memoria, quantità e grado. Marca e modello vengono cercati con uguaglianza, evitando il significato speciale di `%` e `_` in `LIKE`. Rimossi i log CSV scritti in un percorso relativo.
- **Prodotti e immagini:** prima del salvataggio vengono controllati errori di caricamento, dimensione massima di 2 MB e contenuto immagine effettivo. I file non validi vengono segnalati, invece di essere ignorati silenziosamente. Prodotto e immagini vengono salvati nella stessa transazione; in caso di errore vengono rimossi i nuovi file già spostati. I nomi dei file sono generati casualmente con estensione corrispondente al contenuto.
- **Prenotazioni:** escaping dei dati cliente/dispositivo nel modal e gestione degli errori di caricamento.
- **Telefonia, utenze e usato:** importi con virgola correttamente normalizzati; valori negativi o malformati rifiutati. Per telefonia, input non numerici del numero di linee non vengono sostituiti silenziosamente con 1.
- **Contatti:** un errore di invio email restituisce HTTP 500 e un messaggio generico, con dettagli nel log server.

## Verifiche

`tests/backend-integration.php --local` esegue **40 controlli con HTTP e MySQL reali**:

- Apertura dettagli e salvataggio di tutti gli stati non iniziali di preventivi, valutazioni usato e prenotazioni; note e schede di stampa con caratteri HTML.
- Creazione, modifica, eliminazione prodotto, copertina, caricamento multipart di una PNG e rifiuto di file falsi o superiori a 2 MB.
- Anteprima CSV, import effettivo e rifiuto di JSON errato, prezzi negativi e SKU duplicati nel lotto.
- Registrazione dai form pubblici preventivo, usato, prenotazione, demo Liberty, telefonia e luce/gas; verifica dei centesimi e del risparmio calcolato.
- Chiamata alla funzione email per contatti, assistenza, preventivi, usato e prenotazioni; simulazione dell'errore di invio per contatti e assistenza.

La suite avvia un server PHP su `127.0.0.1` e una porta libera, protetto da token casuale; rifiuta database con host diverso da loopback e tabelle non InnoDB. Ogni richiesta ha una transazione esterna annullata al termine. Le transazioni interne degli endpoint usano savepoint durante il test. Vengono controllati i conteggi delle tabelle dopo il rollback e rimossi i file immagine di prova. Gli incrementi dei contatori AUTO_INCREMENT non vengono annullati da MySQL.

Il bootstrap di test prepara la sessione autenticata e i record di prova; gli endpoint di produzione vengono inclusi senza modificarne il codice. **L'invio email è intercettato con una funzione di test:** queste verifiche non dimostrano connessione SMTP, autenticazione, composizione del messaggio nel vero helper o consegna a una casella reale. I controlli non coprono il login tramite browser o ogni combinazione di interazione frontend.

Nel browser locale autenticato sono stati aperti i modal tramite **Gestisci** di prenotazioni/usato e **Dettagli** dei preventivi. Nessun salvataggio su richieste clienti è stato eseguito dal browser.

Le altre suite risultano verdi: 52 regressioni backend, 11 controlli delle fondamenta, 7 test API ricondizionati e 1 test archivio credenziali API: **111 test complessivi**.

## Ripetere in locale

```powershell
& 'C:/laragon/bin/php/php-8.3.16-Win32-vs16-x64/php.exe' tests/backend-integration.php --local
```

Usare un database locale con schema aggiornato e dati di configurazione (dispositivi/modelli). Evitare scritture concorrenti durante la suite, perché la verifica dei conteggi potrebbe rilevarle come differenze. Le credenziali vengono lette dalla configurazione locale senza essere stampate. Non distribuire questa suite come endpoint pubblico.

Restano da verificare separatamente la consegna SMTP reale e i flussi completi dei moduli secondari.
