# Immagini del Laboratorio — 17 ottobre 2026

Due scene illustrative create con il tool integrato Imagegen. Non sono fotografie del laboratorio o del personale Key Soft Italia. La pagina lo segnala nelle didascalie e nei testi alternativi. Chi siamo e le immagini dei fondatori/team non sono state modificate.

I master PNG sono in `sources/`. I derivati WebP da 1200 e 720 px sono usati rispettivamente per desktop e mobile, qualità 87. L'immagine di apertura è prioritaria; quella del notebook è caricata in modo differito. Le scene non aggiungono animazioni o dipendenze.

## Prompt — banco di riparazione

Photorealistic editorial photograph for an Italian electronics repair workshop website, horizontal 3:2 composition. Close-to-medium view of the hands and forearms of one adult technician carefully servicing an opened modern smartphone on a dark graphite ESD mat. Natural anatomically correct hands, one hand stabilizing the phone chassis and the other holding a small precision screwdriver aligned with an actual screw. Screen removed and resting beside the chassis, realistic battery and shields, tidy precision tools, screw tray, softly out-of-focus inspection microscope and task lamp behind. Honest practical independent repair bench, no server racks, no futuristic machinery. Warm neutral task lighting with a restrained amber highlight, charcoal and graphite environment, realistic skin and metal textures, natural photographic depth of field, believable candid documentary photography. Camera from slightly above at 35 degrees, devices and hands clearly readable in center-right, uncluttered left side. No face or head in frame, no text, no logos, no watermarks, no glowing circuitry, no floating components, no sparks, no cinematic smoke.

## Prompt — notebook

Photorealistic editorial documentary photograph, horizontal 3:2. A real independent electronics repair bench in graphite and warm neutral tones. A powered-off laptop lies upside down with bottom cover neatly removed, exposed realistic motherboard, cooling fan and removable RAM/SSD. An adult technician's two natural hands carefully seat a small M.2 SSD in its proper connector, no face or head visible. Precision screwdriver and removed screws organized in a tray on a dark ESD mat, plain warm task light from upper left, soft background with another closed laptop and ordinary workshop equipment. View from slightly above, framed tightly on laptop and hands. Accurate believable internal hardware scale and structure, realistic photography with crisp foreground and gently defocused background. Practical uncluttered workshop, no server racks, no futuristic props, no text, no logos, no watermarks, no floating parts, no dramatic lights or smoke.

## Integrazione (rivista il 17 ottobre 2026)

`servizi/riparazioni.php` usa solo `assets/css/pages/laboratorio.css`; il vecchio `riparazioni.css` non è più caricato. Le due scene non sono più riquadri: stanno nel grafite sotto la stessa lente del film della home (neri sollevati a #101114, vignetta, grana `img/home/film-grain.png` statica). Il banco apre la pagina a tutta larghezza con il testo sopra una sfumatura laterale; il notebook è una fascia a tutta larghezza da cui emerge il titolo del capitolo computer. Derivati: 1536 px (desktop, qualità 82) e 720 px (mobile). I 1200 px non sono più usati.

Prezzi, servizi, garanzia, passaggi e destinazioni restano invariati; cambiano solo la forma (listino a righe al posto delle card) e l'ordine (smartphone prima del computer, per non accostare le due foto).

## Revisione: ordine e film del processo (17 ottobre 2026, sera)

Ordine della pagina: apertura, «Come funziona», garanzia, riparazioni, contatto. «Come funziona» riusa la sequenza del capitolo 04 della home (`img/home/cinematic-v2/chapter-04/{desktop,mobile}/sequence`, frame 130–768, uno ogni due, AVIF con ripiego WebP): una scena fissata guidata dallo scroll nativo, reversibile, con quattro tratti (rotto e ispezionato, schermo che si stacca, interno aperto, acceso) e una pausa di lettura alla fine di ciascuno. Testi HTML sovrapposti, timeline cliccabile come in home, caricamento limitato a 6 richieste e solo vicino alla sezione. WhatsApp e «torna su» si spostano mentre la scena è fissata. Senza JS o con movimento ridotto resta la lista a quattro colonne. Motore: `assets/js/pages/laboratorio.js`. Le riparazioni non mostrano più prezzi.

Riparazioni ridotte a un indice «Cosa ripariamo»: tre colonne per dispositivo con soli nomi degli interventi, senza introduzioni né descrizioni; sotto, un link WhatsApp per i guasti non elencati. La garanzia mostra solo titolo e quattro dati. Testo nel main: circa 155 parole.
