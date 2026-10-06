# Moduli secondari: verifica e correzioni — 5 ottobre 2026

## Problemi risolti

- **Luce/gas:** l'elenco leggeva `operator_name` e `logo_path` dalla tabella offerte, che nel database locale non contiene questi campi. Ora legge nome e logo dalla tabella partner. L'eliminazione dell'offerta non cerca più una colonna logo inesistente.
- **Telefonia:** la creazione dell'offerta ometteva `operator_name`, obbligatorio nello schema. Ora viene salvato il nome del partner e aggiornato quando si cambia operatore. Entrambi i moduli verificano che partner e offerta da modificare esistano e mantengono i centesimi dei prezzi con virgola.
- **Richieste telefonia/utenze:** gli stati consentiti sono quelli del pannello: In attesa, Contattato, Completato, Annullato. Input sconosciuti vengono rifiutati prima della scrittura.
- **Caricamenti:** partner, volantini, video e team condividono controlli su errore upload, dimensione e contenuto immagine. I percorsi sono assoluti rispetto al progetto, indipendenti dalla directory di esecuzione. I file nuovi vengono rimossi se il salvataggio fallisce; quelli sostituiti vengono eliminati solo dopo il commit. Le eliminazioni restano dentro la cartella media prevista. I loghi SVG semplici restano supportati; script, eventi, entità XML, riferimenti esterni e altri contenuti attivi vengono rifiutati.
- **Team:** sostituzione e cancellazione del membro rimuovono anche la foto locale pertinente.
- **Video:** un fallimento durante il salvataggio non azzera più il video precedentemente in evidenza. Gli URL vengono verificati tramite schema HTTPS e dominio Facebook, evitando URL ingannevoli contenenti soltanto la stringa `facebook.com`.
- **Volantini:** date impossibili e scadenze precedenti all'inizio vengono rifiutate; modifiche a record mancanti non proseguono con valori vuoti.
- **Orari:** il database locale aveva 11 fasce settimanali, mentre il pannello invia tutte le 14 fasce. Il salvataggio ora usa la chiave giorno/segmento e crea quelle mancanti invece di aggiornare un ID vuoto. Le fasce chiuse accettano campi orario vuoti; quelle aperte richiedono orari validi e chiusura successiva all'apertura. Vengono validati anche gli array del calendario prima di cancellare/sostituire eccezioni.
- **Festività:** i campi mese/giorno non pertinenti alle regole di Pasqua diventano NULL; le date fisse devono esistere. Il calendario calcola Pasqua anche oltre il limite di `easter_date()` usando giorni e date senza timestamp.

## Verifica

La suite `tests/backend-integration.php --local` include ora **123 test con HTTP e MySQL reali**, 83 in più rispetto alla precedente verifica:

- Elenchi dei cinque moduli, CRUD offerte e partner.
- Stati ed eliminazione delle richieste telefonia/utenze su record di prova.
- Creazione, apertura, modifica ed eliminazione di volantini, video e membri team.
- Caricamenti multipart di immagini, un PDF di prova, SVG semplice e rifiuto di SVG attivo/immagini false.
- Conservazione della vecchia copertina quando il nuovo PDF è invalido e conservazione del video in evidenza dopo un upload fallito.
- CRUD degli orari e delle festività; creazione di fasce mancanti, fasce chiuse, eccezioni con due segmenti e calendario dopo il 2038.

Ogni richiesta viene eseguita nella transazione esterna di test, annullata al termine. I conteggi delle tabelle vengono controllati dopo il rollback, i file di prova vengono rimossi e nessun messaggio viene inviato. MySQL può consumare numeri AUTO_INCREMENT anche quando i record vengono annullati. La suite richiede un host MySQL locale e tabelle InnoDB.

Le altre suite restano verdi: 52 regressioni backend, 11 controlli fondamenta, 7 test API e 1 test archivio credenziali: **194 test complessivi**. Controllata anche la sintassi dei 145 file PHP presenti al momento della verifica.

Nel browser autenticato verificati il caricamento della pagina luce/gas e l'apertura del modulo nuova offerta; nessuna modifica salvata su dati reali dal browser.

## Limiti

I test usano una sessione preparata nel bootstrap e mail intercettate. Non verificano consegna SMTP, rendering completo di ogni modal o tutte le combinazioni dei controlli frontend. PDF controllati tramite estensione e MIME; non viene eseguita una scansione antivirus. Nessuna migrazione o modifica permanente dei dati clienti è stata applicata in questo intervento.

Ricerca e paginazione delle liste restano un intervento successivo. Non sono state aggiunte in questa verifica dei bug.
