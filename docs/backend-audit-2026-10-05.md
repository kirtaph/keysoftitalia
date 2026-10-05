# Controllo backend — 5 ottobre 2026

Revisione di autenticazione admin, bootstrap AJAX, preventivi, contatti, gestione prodotti e strumenti database. Le modifiche frontend già presenti sono state conservate.

## Bug corretti

| Area | Problema | Correzione |
| --- | --- | --- |
| Contatti | Include della configurazione da una cartella inesistente: endpoint bloccato prima della validazione. | Percorso corretto rispetto alla root. |
| Contatti | Telefono facoltativo nel form, obbligatorio nel servizio email: invii senza telefono rifiutati. | Opzione specifica per i contatti; gli altri invii continuano a richiederlo. |
| Admin AJAX | Controllo CSRF solo per POST, ma azioni mutative selezionabili via GET, inclusa eliminazione prenotazioni. | GET riservato alle azioni di lettura; altre richieste ricevono 405. Token non scalari ricevono 403. |
| Login | Username inesistenti non incrementavano i tentativi falliti. | Incremento e blocco anche per account non trovati. |
| Preventivi | Salvataggio senza validazione CSRF, privacy o formato email; modalità sconosciute finivano nel salvataggio. | Rifiuto prima dell'INSERT e controllo degli input malformati. |
| Preventivi | Problemi duplicati conteggiati più volte; problemi senza regola ignorati; decimali arrotondati agli euro. | Deduplicazione, importi con centesimi, stima sconosciuta oppure senza limite superiore quando manca un prezzo. |
| Preventivi | ID marca accettato anche se appartenente a un altro dispositivo. | Verifica della relazione marca/dispositivo. |
| Preventivi | Errori SQL restituiti integralmente nella risposta pubblica. | Messaggio generico al client e dettaglio nel log server. |
| Prodotti | ID inesistente restituiva un prodotto artificiale contenente solo immagini. | Errore esplicito di prodotto non trovato. |
| Prodotti | Storage facoltativo vuoto passato come stringa a una colonna numerica, incompatibile con SQL strict. | Stringa vuota convertita in NULL. |
| Immagini prodotti | Percorsi dipendenti dalla directory di esecuzione; nessuna copertina se il primo upload veniva scartato. | Percorsi assoluti e prima immagine valida scelta come copertina. |
| Immagini prodotti | Copertina di un altro prodotto azzerava quella corrente; mancava rollback esplicito. | Controllo dell'appartenenza prima del cambio e rollback sugli errori. |
| Immagini prodotti | Eliminazione file costruita direttamente dal percorso DB. | Risoluzione limitata ai file locali sotto assets/img/recond; URL remoti e traversal esclusi. |
| Strumenti DB | database/migrate.php e script describe accessibili via web senza autenticazione. | Accesso limitato a CLI. Le migrazioni admin continuano tramite il relativo endpoint protetto. |

La logica di stima è stata spostata in `src/QuoteEstimate.php` per provarla senza avviare il database.

## Verifica

- 27 test di regressione in `tests/backend-regressions.php`: logica reale degli endpoint con bootstrap DB sostituito da un PDO controllato, email simulata nei contatti.
- 8 test esistenti su API ricondizionati e credenziali API.
- Controllo sintattico PHP dell'intera codebase e dei file aggiornati.
- Nessuna migrazione, modifica del database locale o email reale eseguita.

## Limiti e punti aperti

- Restano da verificare le operazioni reali su MySQL, gli upload HTTP e la consegna SMTP; i test isolati non certificano questi servizi.
- `config/config.php` contiene credenziali DB/SMTP come fallback. Occorre spostarle nell'ambiente e ruotarle sul server; nessun segreto è riportato qui. Non sono state rimosse durante questa revisione per evitare di interrompere l'ambiente corrente.
- Il blocco login resta legato alla sessione: cambiare cookie aggira il contatore. La correzione sugli username inesistenti non costituisce un limite globale per IP/account.
- La revisione non certifica l'assenza di altri bug in tutti i moduli amministrativi e nei processori dei form.
