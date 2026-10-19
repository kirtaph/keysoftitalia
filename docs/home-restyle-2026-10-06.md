# Homepage restyle — 6 ottobre 2026

La home usa ora uno showroom scuro con dispositivi 3D WebGL, arancione del
marchio e catalogo chiaro a contrasto. La prima proposta fotografica è stata
sostituita. Il percorso principale porta alla prenotazione di una riparazione;
WhatsApp, catalogo e servizi restano facilmente raggiungibili.

## Revisione con 3D visibile

assets/js/pages/home-spatial.js realizza smartphone e notebook con geometrie
procedurali, superfici riflettenti, fotocamere, tastiera e schermi personalizzati.
Three.js 0.186.1 è incluso localmente in assets/js/lib/three con licenza MIT.
I dispositivi sono illustrativi. Le tre modalità Ripara, Scegli e Crea cambiano
composizione: Ripara separa schermo e batteria dal corpo dello smartphone.
Il movimento continuo si vede senza scroll; mouse e trascinamento cambiano
prospettiva. Su mobile la scena precede il testo per essere visibile in apertura.
assets/css/pages/home-spatial.css estende il restyle a tutte le sezioni.

Verifica specifica: artifacts/check-home-spatial.js e home-spatial-checks.txt.
Il browser ha renderizzato WebGL: 54 nuovi fotogrammi nella misurazione e
immagini del canvas differenti. Pausa, arresto fuori viewport, scena statica
con movimento ridotto e selezione delle tre modalità sono stati verificati.
Il test delle cinque larghezze controlla anche che il titolo non venga tagliato.
Questi controlli non misurano le prestazioni su un telefono fisico.

Registrazione del movimento: artifacts/home-3d-preview.webm.
Il renderer limita DPR a 1,65 e si ferma su tab nascosto e fuori viewport.
Senza WebGL o JavaScript resta la fotografia locale; i controlli 3D si nascondono.
Il video Facebook continua a caricarsi su richiesta. Nessun filmato stock viene
spacciato per il negozio o per un prodotto in vendita.

## Implementazione

- index.php: un solo H1, metadati aggiornati, servizi, prodotti in evidenza,
  video, storia, FAQ, invito al negozio e volantino quando attivo.
- assets/css/pages/home-restyle.css: presentazione circoscritta alla home,
  comprese navigazione e footer. Le altre pagine mantengono i loro stili.
- assets/js/pages/home-restyle.js: ingressi del hero, un passaggio fotografico,
  menu mobile accessibile e player video su richiesta.
- assets/img/home/workbench.webp: fotografia illustrativa generata, 144.666
  byte. Non rappresenta il negozio reale. La fotografia storica viene dal
  progetto; i contenuti del video e della vetrina vengono dal database.
- DESIGN.md: palette, tipografia e regole della nuova home.

Le foto prodotto assenti nella copia locale non vengono richieste e non vengono
sostituite con immagini di modelli diversi. La vetrina mostra nome, prezzo e
disponibilità effettivi con la dicitura “Foto in aggiornamento”.

Il video selezionato è “Microsoft Surface Pro”, pubblicato da Key Soft Italia
su Facebook. Il player esterno viene caricato solo all'apertura del dialogo,
senza autoplay, e rimosso alla chiusura. Il caricamento reale del player è stato
verificato: erano presenti i controlli di riproduzione e la durata di 50 secondi.
La riproduzione e la disponibilità futura dipendono da Facebook. È disponibile
anche un collegamento diretto, utilizzabile senza JavaScript.

## Verifiche

Evidenza completa: artifacts/home-browser-checks.txt.
Procedura browser ripetibile: artifacts/check-home-restyle.js, eseguita tramite
la skill playwright-cli.

- PHP lint, controllo sintattico JavaScript e git diff --check superati.
- Larghezze 320, 390, 768, 1024 e 1440px: nessun overflow orizzontale,
  nessun titolo nascosto.
- Un H1, canonical alla radice e quattro prodotti reali in evidenza.
- Tredici destinazioni principali verificate con GET: tutte HTTP 200.
- Menu mobile: stato ARIA, focus iniziale, contenimento del focus, Escape,
  ritorno al pulsante e sfondo inert verificati.
- FAQ: apertura e chiusura verificate.
- Video: nessun iframe prima del click; apertura, focus, Escape,
  rimozione immediata del player e ritorno del focus verificati.
- Movimento ridotto: zero animazioni attive e tutti i titoli visibili.
- Senza JavaScript: pagina visibile, prodotti presenti, nessun overflow.
- Dopo lo scorrimento dell'intera home: nessuna immagine rotta.
- Nessuna eccezione JavaScript della pagina durante le verifiche.

I controlli browser non costituiscono una certificazione completa WCAG.

Non sono stati inviati moduli né effettuate scritture su record clienti.
I controlli HTTP sui collegamenti non verificano l'invio dei moduli destinazione.

## Revisione delle animazioni

| Prima | Dopo | Motivo |
| --- | --- | --- |
| Carousel con autoplay | Showroom 3D interattivo | Movimento visibile e tre prospettive selezionabili |
| Reveal globale che nascondeva i titoli | Contenuti visibili di default | Lettura affidabile anche fuori viewport e senza JavaScript |
| Drawer animato tramite right | Transform con curva ease-out, 240ms | Movimento fluido senza animare il layout |
| Effetti sovrapposti e inclinazioni | Dispositivi WebGL, stagger di 65ms e una entrata fotografica | Movimento principale concentrato sui dispositivi |
| Movimento indipendente dalle preferenze | Cancellazione su prefers-reduced-motion | Rispetto della preferenza anche quando cambia durante la visita |

Il movimento automatico 3D può essere messo in pausa. L'ingresso del hero
dura 550–700ms come breve momento di presentazione;
le interazioni usano 160–240ms. I contenuti non dipendono dalla fine delle
animazioni. Hover animati limitati a puntatori precisi.

## Revisione design e limiti

Il detector impeccable rileva solo il richiamo generico a Inter. È un falso
positivo contestuale: Inter e Poppins sono la tipografia già adottata dal
marchio. La nuova scala è documentata; nessuna regola è stata soppressa.

La console conserva errori preesistenti di Google Analytics bloccato dalla
Content Security Policy e del suo font incorporato. Non sono errori del restyle;
la configurazione globale di analytics/CSP non è stata modificata.

Anteprime: artifacts/home-after-desktop.png, artifacts/home-mobile-viewport.png
e artifacts/home-after-full.png. La home è modificata in locale; nessun deploy.
