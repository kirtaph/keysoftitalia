# Public site dependencies

Local copies of the libraries previously loaded from jsDelivr, keeping the same major versions used by the site:

- Bootstrap 5.3.2: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/` (MIT).
- Swiper 11.2.10: `https://cdn.jsdelivr.net/npm/swiper@11.2.10/` (MIT).
- Remix Icon 4.0.0: `https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/` (Apache 2.0).
- Inter (300–700) and Poppins (400–700): font files served by Google Fonts, downloaded for local use (SIL Open Font License).

License files are retained with each dependency. The shared head/footer reference these local files. Bootstrap grids and modal behavior, icon glyphs and existing Swiper components now work without a third-party CDN connection. This directory does not contain application-specific design rules; those live in `assets/css/site-alignment.css` and the existing homepage stylesheets.
