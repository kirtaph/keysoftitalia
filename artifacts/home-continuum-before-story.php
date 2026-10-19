<div class="home-continuum">
<section id="home-services" class="continuum-services home-shell" aria-labelledby="home-services-title">
 <div class="continuum-heading"><p class="continuum-label">Il tuo mondo tech, con noi</p><h2 id="home-services-title">Qualunque sia<br>il punto di <span>partenza.</span></h2><p>Una riparazione, il prossimo dispositivo, un progetto per la tua attività. Partiamo da quello che ti serve.</p></div>
 <div class="continuum-service-layout">
  <div class="continuum-service-list">
   <a class="continuum-service is-selected" href="<?= url('servizi/riparazioni.php') ?>" data-service-image="<?= asset('img/home/cinematic-v1/desktop/frame-0120.webp') ?>" data-service-alt="Dettaglio illustrativo dei componenti di uno smartphone"><div><h3>Riparare.</h3><p>Smartphone, tablet e computer. Diagnosi e assistenza, con qualcuno che ti ascolta.</p></div><i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   <a class="continuum-service" href="<?= url('prodotti.php') ?>" data-service-image="<?= asset('img/hero-ricondizionati.webp') ?>" data-service-alt="Immagine illustrativa di smartphone e notebook"><div><h3>Scegliere.</h3><p>Nuovo o ricondizionato. Il dispositivo giusto per le tue esigenze, con assistenza anche dopo l'acquisto.</p></div><i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   <a class="continuum-service" href="<?= url('servizi/sviluppo-web.php') ?>" data-service-image="<?= asset('img/hero-it.webp') ?>" data-service-alt="Immagine illustrativa di soluzioni digitali"><div><h3>Creare.</h3><p>Siti web, software e consulenza IT. Tecnologia che lavora insieme alla tua attività.</p></div><i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
  </div>
  <figure class="continuum-service-visual"><img id="continuum-service-image" src="<?= asset('img/home/cinematic-v1/desktop/frame-0120.webp') ?>" alt="Dettaglio illustrativo dei componenti di uno smartphone" width="1280" height="720" loading="lazy" decoding="async"><figcaption>Competenza, dentro ogni dettaglio.</figcaption></figure>
 </div>
 <nav class="continuum-more" aria-label="Altri servizi"><span>E c'è molto altro.</span><a href="<?= url('servizi/telefonia.php') ?>">Telefonia ↗</a><a href="<?= url('servizi/forniture.php') ?>">Luce e gas ↗</a><a href="<?= url('servizi/liberty-commerce.php') ?>">Gestionali ↗</a><a href="<?= url('servizi.php') ?>">Tutti i servizi ↗</a></nav>
</section>
<section class="continuum-catalogue" aria-labelledby="home-catalogue-title"><div class="home-shell">
 <div class="continuum-catalogue-heading"><div><p class="continuum-label">La selezione del negozio</p><h2 id="home-catalogue-title">Il prossimo<br>è <span>qui.</span></h2></div><div><p>Dispositivi nuovi e ricondizionati. Consigli chiari, la possibilità di scegliere bene e di usare a lungo.</p><a class="continuum-text-link" href="<?= url('prodotti.php') ?>">Esplora tutto il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></div></div>
 <div class="home-products continuum-products">
 <?php foreach ($homeProducts as $product): $photo = $homeMedia($product['image_path']);
 $productName = trim((string)$product['title']) ?: trim((string)$product['model']) ?: (string)$product['device'];
 $category = mb_strtolower((string)$product['device']);
 $icon = str_contains($category, 'computer') || str_contains($category, 'notebook') || str_contains($category, 'pc') ? 'ri-macbook-line' : (str_contains($category, 'phone') || str_contains($category, 'telefon') ? 'ri-smartphone-line' : 'ri-device-line'); ?>
  <a class="home-product continuum-product<?= !$photo ? ' is-without-photo' : '' ?>" href="<?= url('prodotti.php?sku=' . rawurlencode($product['sku'])) ?>">
   <div class="continuum-product-image"><?php if ($photo): ?><img src="<?= $esc($photo) ?>" alt="<?= $esc($productName) ?>" width="480" height="480" loading="lazy" decoding="async"><?php else: ?><div class="continuum-product-placeholder"><i class="<?= $icon ?>" aria-hidden="true"></i><span>Foto in aggiornamento</span></div><?php endif; ?><?php if ($product['grade']): ?><span class="continuum-grade"><?= $esc($product['grade']) ?></span><?php endif; ?><span class="continuum-product-arrow" aria-hidden="true">↗</span></div>
   <div class="continuum-product-info"><p><?= $esc($product['brand']) ?></p><h3><?= $esc($productName) ?></h3><strong>€ <?= $esc(number_format((float)$product['price_eur'], 2, ',', '.')) ?></strong></div>
  </a>
 <?php endforeach; ?>
 </div>
 <?php if (!$homeProducts): ?><p>Consulta il <a href="<?= url('prodotti.php') ?>">catalogo</a> per i dispositivi disponibili.</p><?php endif; ?>
 <a class="continuum-trade" href="<?= url('valuta-usato.php') ?>"><div><span>Hai già un dispositivo?</span><h3>Facciamo il punto sul tuo usato.</h3></div><span>Richiedi una valutazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></span></a>
</div></section>
<?php if ($homeVideo && $videoPlayable): ?>
<section class="continuum-video home-shell" aria-labelledby="home-video-title">
 <div class="continuum-video-heading"><div><p class="continuum-label">Dal nostro mondo</p><h2 id="home-video-title">Guardiamola<br><span>da vicino.</span></h2></div><a class="continuum-text-link" href="<?= url('video.php') ?>">Tutti i nostri video ↗</a></div>
 <button class="home-video-poster continuum-video-poster" type="button" data-home-video aria-label="Guarda il video: <?= $esc($homeVideo['title']) ?>"><img src="<?= $esc($videoCover) ?>" alt="" width="1024" height="541" loading="lazy" decoding="async"><span class="continuum-video-overlay"><span class="continuum-play" aria-hidden="true"><i class="ri-play-fill"></i></span><span>Guarda il video</span></span></button>
 <div class="continuum-video-caption"><h3><?= $esc($homeVideo['title']) ?></h3><span>Novità e consigli, raccontati da noi.</span></div><noscript><a href="<?= $esc($videoUrl) ?>" target="_blank" rel="noopener">Guarda su Facebook</a></noscript>
</section>
<?php endif; ?>
<section class="continuum-story" aria-labelledby="home-story-title"><div class="home-shell continuum-story-layout">
 <div><p class="continuum-label">Key Soft Italia · Ginosa</p><h2 id="home-story-title">Qui, dal<br><span>2004.</span></h2><p>La tecnologia cambia. Il valore di avere qualcuno a cui affidarsi, resta.</p><p>A Ginosa aiutiamo persone e attività a vivere meglio la tecnologia. Ascoltiamo, consigliamo, ripariamo. Dietro il nostro banco ci siamo noi.</p><a class="continuum-text-link" href="<?= url('chi-siamo.php') ?>">Conosci la nostra storia ↗</a></div>
 <figure><img src="<?= asset('img/about-story.webp') ?>" alt="Una fotografia dalla storia di Key Soft Italia" width="800" height="600" loading="lazy" decoding="async"><figcaption>Una storia di persone, prima che di dispositivi.</figcaption></figure>
</div></section>
<section class="home-faq continuum-faq home-shell" aria-labelledby="home-faq-title"><div><p class="continuum-label">Prima di cominciare</p><h2 id="home-faq-title">Idee<br><span>chiare.</span></h2></div><div class="home-faq-list">
 <details><summary>Come richiedo una riparazione?</summary><p>Puoi <a href="<?= url('prenota-riparazione.php') ?>">prenotare online</a> oppure passare in negozio in Via Diaz 46, a Ginosa.</p></details>
 <details><summary>Posso conoscere il costo prima di procedere?</summary><p><a href="<?= url('preventivo.php') ?>">Richiedi un preventivo</a>. Valutiamo il dispositivo e ti aiutiamo a capire come procedere.</p></details>
 <details><summary>Posso far valutare il mio usato?</summary><p>Sì. Parti dalla <a href="<?= url('valuta-usato.php') ?>">valutazione dell'usato</a> oppure contattaci per parlarne insieme.</p></details>
</div></section>
<?php if ($featured_flyer): ?><section class="continuum-flyer home-shell"><div><p class="continuum-label">Le offerte del momento</p><h2><?= $esc($featured_flyer['title']) ?></h2></div><a class="home-button home-button-dark" href="<?= url('volantini.php?flyer=' . rawurlencode($featured_flyer['slug'])) ?>">Sfoglia il volantino ↗</a></section><?php endif; ?>
<section class="continuum-contact" aria-labelledby="home-contact-title"><div class="home-shell">
 <p class="continuum-label">Dal sito, alle persone.</p><h2 id="home-contact-title">Il prossimo passo?<br><span>Parliamone.</span></h2>
 <div class="continuum-contact-bottom"><div><p>Passa a trovarci.</p><a class="continuum-address" href="https://maps.google.com/maps?q=Via+Diaz+46+Ginosa+TA" target="_blank" rel="noopener">Via Diaz, 46<br>Ginosa <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></div><div class="continuum-contact-actions"><a class="home-button home-button-dark" href="<?= $esc(whatsapp_link('Ciao Key Soft Italia! Vorrei parlare con voi.', ['utm_campaign' => 'home-contact'])) ?>" target="_blank" rel="noopener">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a><a class="continuum-text-link" href="tel:0998293794">Oppure chiamaci: 099 829 3794 ↗</a><a class="continuum-text-link" href="<?= url('contatti.php') ?>">Contatti e indicazioni ↗</a></div></div>
</div></section>
</div>
