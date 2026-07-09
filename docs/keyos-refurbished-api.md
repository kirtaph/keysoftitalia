# API ricondizionati KeyOS

Base URL di produzione: `https://www.keysoftitalia.it/api/v1/refurbished`

L'API usa le entità del catalogo esistente (`products`, `models`, `brands`,
`devices` e `product_images`). `external_ref` corrisponde allo SKU univoco del
prodotto. Tutti gli endpoint, incluso `ping`, richiedono autenticazione.

## Configurazione dal pannello amministrativo

Aprire **Admin → Impostazioni Sistema → API KeyOS**, premere **Genera
credenziali**, confermare scrivendo `RUOTA` e copiare subito API key e secret
in KeyOS. Il secret viene mostrato una sola volta.

Le credenziali sono attive immediatamente: non servono terminale né riavvio
del server. Una nuova generazione invalida subito la coppia precedente.

Il pannello salva la coppia in `config/runtime/keyos-api.php`, escluso da Git.
La directory deve essere scrivibile dall'utente PHP.

## Configurazione alternativa tramite ambiente

Per installazioni gestite via infrastruttura si possono impostare:

```text
KSI_API_KEY=<identificativo casuale>
KSI_API_SECRET=<segreto casuale>
KSI_API_RATE_LIMIT=60
KSI_API_LOG_PATH=/percorso/non/pubblico/refurbished-api.log
KSI_API_RUNTIME_PATH=/percorso/scrivibile/refurbished-api
```

Le credenziali generate dal pannello hanno precedenza sulle variabili
d'ambiente. Generazione da terminale, solo come alternativa:

```bash
php -r "echo 'KSI_API_KEY=', bin2hex(random_bytes(16)), PHP_EOL; echo 'KSI_API_SECRET=', bin2hex(random_bytes(32)), PHP_EOL;"
```

Questa versione accetta una sola coppia. Non scrivere mai i valori nei log o
nel repository.

Applicare la migration:

```bash
php database/migrate.php
```

`KSI_API_RUNTIME_PATH` contiene contatori rate-limit e impronte anti-replay.
`KSI_API_LOG_PATH` è un log JSON Lines con timestamp, riferimento, esito e IP.
Entrambi devono essere scrivibili dall'utente del web server e non pubblici.

## Firma

Per ogni richiesta:

1. serializzare il JSON una volta e conservarne i byte esatti;
2. calcolare `HMAC-SHA256(body_raw, KSI_API_SECRET)` in esadecimale minuscolo;
3. inviare `X-KSI-Key`, `X-KSI-Signature` e `X-KSI-Timestamp` Unix.

Il timestamp deve essere entro ±300 secondi. La stessa combinazione
timestamp/firma non può essere riutilizzata nella finestra.

Esempio shell:

```bash
API_KEY='...'
API_SECRET='...'
BASE='https://www.keysoftitalia.it/api/v1/refurbished'
BODY='{"external_ref":"RIC0227","status":"publish","identity":{"imei":"353194561910245","serial":"CND51255GZ"},"device":{"category":"Smartphone","brand":"Apple","model":"iPhone 11 Pro","title":"Apple iPhone 11 Pro 64GB Ricondizionato Grado A","description":"Testo descrittivo pronto per la pubblicazione...","specs":{"Colore":"Verde notte","Memoria":"64GB","RAM":"4GB","Display":"5.8\" OLED"}},"condition":{"grade":"A","battery_pct":87,"notes":"Lievi segni sul frame","accessories":["Cavo di ricarica","Scatola"]},"commercial":{"price_eur":399.00,"warranty_months":12},"photos":[{"url":"https://cdn.example.test/ric0227_1.jpg","position":1}],"source":{"system":"keyos","device_id":227,"pushed_at":"2026-07-08T18:30:00+02:00"}}'
TS="$(date +%s)"
SIG="$(printf '%s' "$BODY" | openssl dgst -sha256 -hmac "$API_SECRET" -hex | sed 's/^.* //')"
curl -i -X POST "$BASE/upsert" \
  -H 'Content-Type: application/json' \
  -H "X-KSI-Key: $API_KEY" \
  -H "X-KSI-Timestamp: $TS" \
  -H "X-KSI-Signature: $SIG" \
  --data-binary "$BODY"
```

Creazione risponde `201`; aggiornamento `200`. Ripetere con lo stesso
`external_ref` aggiorna la stessa riga e non duplica prodotto o foto.

### Cambio stato

```bash
BODY='{"status":"sold"}'
TS="$(date +%s)"
SIG="$(printf '%s' "$BODY" | openssl dgst -sha256 -hmac "$API_SECRET" -hex | sed 's/^.* //')"
curl -i -X POST "$BASE/RIC0227/status" \
  -H 'Content-Type: application/json' \
  -H "X-KSI-Key: $API_KEY" \
  -H "X-KSI-Timestamp: $TS" \
  -H "X-KSI-Signature: $SIG" \
  --data-binary "$BODY"
```

Gli stati ammessi sono `sold`, `hidden` e `publish`. `sold` e `hidden`
tolgono il prodotto dalla disponibilità senza cancellarlo.

### Ping

Il body è vuoto, quindi la firma è l'HMAC della stringa vuota.

```bash
TS="$(date +%s)"
SIG="$(printf '' | openssl dgst -sha256 -hmac "$API_SECRET" -hex | sed 's/^.* //')"
curl -i "$BASE/ping" \
  -H "X-KSI-Key: $API_KEY" \
  -H "X-KSI-Timestamp: $TS" \
  -H "X-KSI-Signature: $SIG"
```

Risposta: `{"ok":true,"version":"1.0"}`.

## Errori

- `401`: key/firma non valida, timestamp scaduto o replay;
- `404`: endpoint o riferimento inesistente;
- `422`: validazione, con mappa `errors` per campo;
- `429`: limite richieste;
- `500`: errore interno, senza dettagli sensibili.

## Verifica automatica

Con ambiente configurato e sito raggiungibile:

```bash
php scripts/verify-refurbished-api.php https://www.keysoftitalia.it
```

Lo script verifica ping, firma errata, timestamp scaduto, validazione di
prezzo e identità, creazione, aggiornamento idempotente e cambio stato.
