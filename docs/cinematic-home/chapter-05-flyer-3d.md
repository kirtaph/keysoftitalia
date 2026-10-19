# Il volantino nello Step 5

Il volantino attivo con `show_home = 1`, `status = 1` e date di validità inclusive viene presentato insieme ai prodotti in vetrina. In caso di più volantini validi viene scelto il più recente, con ID come spareggio. Titolo, scadenza e URL sono letti dal database: nessuna promozione è codificata nel componente.

La copertina caricata dal pannello diventa una texture su un foglio Three.js leggermente curvo, con retro chiaro, bordo sottile e inclinazione legata allo scroll. Il mouse modifica delicatamente l'orientamento. Il rendering avviene soltanto quando cambiano scroll, puntatore o dimensioni: nessun ciclo permanente. Il modulo viene importato solo vicino allo Step 5 su desktop. Geometrie, materiali, texture, observer ed eventi vengono rilasciati al cambio pagina o all'attivazione di movimento ridotto.

Mobile, movimento ridotto e WebGL non disponibile conservano la copertina HTML. Una copertina esterna viene lasciata statica per evitare problemi CORS. Senza copertina resta il richiamo testuale; senza volantino valido il capitolo conserva la sola selezione prodotti. La precedente riga volantino sotto le FAQ è stata spostata in questo capitolo.

Copertina e pulsante aprono il PDF presente nel record; se il file non è disponibile rimandano alla pagina dei volantini. I collegamenti restano HTML accessibili anche senza Three.js.

Durante l'integrazione sono stati ripristinati due file locali mancanti ai percorsi del database, copiando i corrispondenti originali già presenti in `uploads/flyers` con prefisso di caricamento. Non sono state modificate date, flag o righe del database. Il PDF risponde con HTTP 200, `application/pdf`.

Verifica: lint PHP e JavaScript; browser desktop con renderer Three.js attivo; mobile 390 × 844 con sola immagine; navigazione del collegamento; anteprime in `artifacts/step-05-flyer-three-desktop.jpg` e `artifacts/step-05-flyer-mobile.jpg`. La pagina preesistente Volantini mostra un errore Swiper nel browser locale: il nuovo pulsante apre direttamente il PDF e non dipende da quel lettore.
