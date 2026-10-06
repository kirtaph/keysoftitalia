# Miglioramento backend — 5 ottobre 2026

Intervento sulla struttura PHP esistente, con priorità ad affidabilità, validazione e protezione dei dati. Nessuna riscrittura del framework o modifica grafica.

## Modifiche

- `BackendHttp`: risposte JSON esplicite, gestione UTF-8, cache disabilitata per dati dinamici e sessioni con cookie HttpOnly/SameSite.
- Bootstrap admin condiviso: errori applicativi 500, validazioni 422, rollback delle transazioni e rifiuto degli input con struttura errata. I form conservano il proprio formato di risposta.
- `BackendValidation`: identificativi positivi, importi con centesimi e limiti DECIMAL, stati consentiti, campi obbligatori per prodotti, utenti, prenotazioni, preventivi, valutazioni usato e regole prezzo. I campi prezzo facoltativi vuoti diventano NULL.
- `PublicRequest`: controllo POST, CSRF, consenso privacy e input scalari per gli otto processori pubblici. Le anteprime preventivo restano disponibili senza dati cliente o consenso.
- `AjaxRequest`: controllo GET e input scalari per tutti e sette gli endpoint pubblici di lettura. Gli errori DB restituiscono JSON 503 anziché testo non decodificabile.
- Prenotazioni: date impossibili o precedenti a oggi e fasce orarie sconosciute vengono rifiutate; la risposta admin esclude l'IP binario.
- Regole da preventivo: operazione transazionale, ricerca marca/modello coerente con il dispositivo. Un totale con più problematiche non viene più assegnato per intero a ciascuna singola riparazione.
- Login: contatore persistente su file con lock, indipendente dalla sessione. Cinque fallimenti per coppia IP/username o trenta per IP nella finestra di quindici minuti. Il successo azzera la coppia, non il contatore generale IP.
- Password di configurazione rimosse dal file versionato. In locale sono conservate, senza cambiarne il valore, in `config/local.php`, escluso da Git. Le variabili d'ambiente hanno precedenza.
- Apache blocca l'accesso HTTP a configurazione, runtime, sorgenti interni, test, dump e directory degli agenti.
- Migrazioni admin: il controllo GET non crea tabelle; esecuzione con lock e controllo preventivo delle cancellazioni di dati. I vecchi dump distruttivi richiedono gestione manuale.
- Nuovo comando CLI per validare o applicare una singola migrazione senza avviare tutto lo storico.
- Migrazione degli stati `accepted`/`rejected` dell'usato applicata in locale e registrata nella tabella delle migrazioni. Nessuna valutazione esistente modificata o eliminata.

## Verifica

- 52 test endpoint e regressioni, con database controllato e invio email simulato.
- 9 test delle nuove funzioni comuni, login persistente e protezione migrazioni.
- 8 test esistenti API KeyOS e credenziali.
- Controllo sintattico PHP dell'intera codebase.
- Apertura reale dei dettagli preventivo nel browser locale dopo le modifiche; pagina utenti caricata correttamente.
- Migrazione locale verificata anche nella seconda esecuzione: viene riconosciuta come già applicata.

Le operazioni CRUD valide sono verificate principalmente con PDO controllato: questo non sostituisce una verifica completa di tutti gli upload HTTP, delle email SMTP o dei servizi esterni. Non sono stati inviati messaggi o modificati gli stati delle richieste dei clienti durante le prove.

## Trasferimento sul server

1. Configurare `DB_PASS` e `SMTP_PASS` nell'ambiente del server oppure in un `config/local.php` privato. Il file locale non viene trasferito da Git. Le credenziali già esposte nella cronologia Git vanno comunque ruotate sull'ambiente di destinazione.
2. Garantire i permessi di scrittura PHP su `config/runtime/login`; il login segnala errore se non può persistere il contatore.
3. Applicare solo la migrazione degli stati:

   `php scripts/apply-safe-migration.php --apply 2026_10_05_001_extend_used_quote_statuses.sql`

   Senza `--apply` il comando valida il file senza modificare il DB. Non lanciare indiscriminatamente tutte le vecchie migrazioni: quella del novembre 2025 contiene DROP TABLE.
4. Verificare le regole Apache sull'hosting effettivo; la protezione `.htaccess` richiede mod_rewrite e AllowOverride.
5. Provare i flussi CRUD, upload e SMTP sull'ambiente di destinazione.

## Ambito ancora distinto

Permessi admin per ruoli, paginazione delle liste, registro completo delle modifiche e refactoring dei singoli moduli secondari non sono inclusi in questo intervento. Richiedono scelte di prodotto o verifiche dedicate. L'intervento rafforza le basi condivise e i flussi principali; non certifica l'assenza di ogni possibile bug.
