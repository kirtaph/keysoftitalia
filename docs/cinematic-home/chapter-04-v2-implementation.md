# Capitolo 04 v2 — la riparazione, integrazione

13 ottobre 2026. Generato con Higgsfield (MCP) seguendo
`chapter-04-v2-rotation-prompts.md`.

## Asset

- Keyframe GPT Image 2.5 (2K): `chapter-04/desktop/k0-cracked.png`, `k1-detach.png`,
  `k2-open.png`, `k3-repaired.png`. Scarti in `candidates/`.
- Clip Kling 3.0 Omni (Frames), pro, 8 s, senza audio: `rot-1.mp4` (K0→K1),
  `rot-2.mp4` (K1→K2), `rot-3.mp4` (K2→K3). Kling 3.0 pro richiede il piano Plus.
  Veo 3.1 non è disponibile su Higgsfield.
- Crediti usati: circa 52 (immagini 22, video 30).

## Sequenza

`artifacts/prepare-chapter-04.js <desktop|mobile>`: 24 frame di dissolvenza dal
finale del capitolo 03 a K0, poi le tre rotazioni (601 frame, globali 624–1224).
Il mobile non richiede generazioni dedicate: lo sfondo è grafite uniforme, quindi
ogni frame desktop viene ritagliato attorno al telefono e posato in basso su una
tela 720×1280 dello stesso grafite; il testo resta in alto.
Desktop 7,9 MB AVIF, mobile 7,0 MB AVIF.

## Pagina

- Testi a sinistra (in alto sul mobile): 01 «Prima capiamo.», 02 «Poi ripariamo.»,
  03 «Pronto a ripartire.» con «Prenota una riparazione».
- Film totale 18,6 altezze di schermo; tempi in `manifest.copy`.
- Barra dei capitoli: «04 La riparazione».
- Il motore accetta per sorgente `desktopFocusX/mobileFocusX`, `*Scale`, `*AlignY`
  (non usati ora, utili per altre clip su fondo uniforme).

## Note sulle clip

- rot-1: riflesso di luce sul vetro nei primi 2 s; rotazione lieve, si legge il distacco.
- rot-2: il display esce a sinistra e il corpo fa un giro completo prima di mostrare l'interno.
- rot-3: il vetro nuovo entra dal basso a sinistra con una traiettoria un po' brusca,
  poi lo schermo si accende con le dune ambrate.

Verificato: storyboard desktop 1440×900 e mobile 390×844, 320 px, movimento ridotto,
nessun errore in console, 17 ms per frame su desktop.

## Raccordo dal banco (13 ottobre 2026)

La dissolvenza è sostituita da `bridge.mp4` (Kling 3.0 Omni, 8 s), uno per variante:
desktop dal finale reale del capitolo 03 a K0; mobile dal finale mobile del 03
a K0 già composto in verticale (`chapter-04/mobile/k0-portrait.png`). Il telefono si
solleva dal banco, si raddrizza e il negozio si spegne nel grafite; a metà c'è un
breve lampo di luce sul vetro. Sequenza: 769 frame (globali 624–1392), film totale
20 altezze di schermo. AVIF desktop 13,7 MB, mobile 12,8 MB. Crediti: +20.
