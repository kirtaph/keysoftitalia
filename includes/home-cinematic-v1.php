<section id="home-cinematic" class="cinematic-story" aria-label="Dal guasto, alla tua prossima possibilità" data-manifest="<?= asset_version('img/home/story-manifest.json') ?>">
 <div class="cinematic-stage">
  <img class="cinematic-poster" src="<?= $esc($heroImage) ?>" alt="Uno smartphone con vetro incrinato, protagonista di un film illustrativo sui nostri servizi" width="1280" height="720" fetchpriority="high">
  <canvas class="cinematic-canvas" aria-hidden="true"></canvas>
  <div class="cinematic-shade" aria-hidden="true"></div>
  <div class="cinematic-content home-shell">
   <div class="cinematic-chapter" data-chapter="opening">
    <p class="home-location"><span aria-hidden="true"></span> Key Soft Italia · Ginosa, dal 2004</p>
    <h1 id="home-title">La tua tecnologia merita <span>un'altra possibilità.</span></h1>
    <p>Smartphone e computer: riparazioni, assistenza e dispositivi ricondizionati. A Ginosa, vicino a te.</p>
    <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prenota-riparazione.php') ?>">Richiedi una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('prodotti.php') ?>">Esplora i prodotti <i class="ri-arrow-right-line" aria-hidden="true"></i></a></div>
   </div>
   <div class="cinematic-chapter" data-chapter="care">
    <p class="home-location">Il laboratorio</p><h2>Dentro ogni soluzione, <span>c'è cura.</span></h2>
    <p>Diagnosi e assistenza per trovare la strada giusta per il tuo dispositivo. Con competenza e qualcuno che ti ascolta.</p>
    <a class="home-button home-button-dark" href="<?= url('servizi/riparazioni.php') ?>">Scopri il laboratorio <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   </div>
   <div class="cinematic-chapter" data-chapter="restart">
    <p class="home-location">Una nuova prospettiva</p><h2>Pronto per quello <span>che viene dopo.</span></h2>
    <p>Ripara il tuo dispositivo o scegli il prossimo, nuovo o ricondizionato. Ti aiutiamo a trovare quello giusto per te.</p>
    <a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Esplora il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   </div>
   <div class="cinematic-chapter" data-chapter="choose">
    <img class="cinematic-chapter-poster" src="<?= asset('img/home/story-continuation/sequence/desktop/frame-0191.webp') ?>" alt="Immagine illustrativa di uno smartphone e un notebook" width="1280" height="720" loading="lazy" decoding="async">
    <p class="home-location">Scegliere, con noi</p><h2>La prossima possibilità <span>è tua.</span></h2>
    <p>Nuovo o ricondizionato. Il dispositivo giusto per le tue esigenze, con consigli chiari e assistenza anche dopo l'acquisto.</p>
    <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Scopri il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('valuta-usato.php') ?>">Valuta il tuo usato <i class="ri-arrow-right-line" aria-hidden="true"></i></a></div>
   </div>
   <div class="cinematic-chapter" data-chapter="create">
    <img class="cinematic-chapter-poster" src="<?= asset('img/home/story-continuation/sequence-03/desktop/frame-0191.webp') ?>" alt="Notebook con una composizione illustrativa di un sito web sullo schermo" width="1280" height="720" loading="lazy" decoding="async">
    <p class="home-location">Creare, insieme</p><h2>Dalle tue idee, <span>a qualcosa che funziona.</span></h2>
    <p>Siti web, software e consulenza IT. Progettiamo soluzioni digitali a partire da ciò che serve alla tua attività.</p>
    <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('servizi/sviluppo-web.php') ?>">Soluzioni web <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('preventivo.php') ?>">Parliamone <i class="ri-arrow-right-line" aria-hidden="true"></i></a></div>
   </div>
   <div class="cinematic-chapter" data-chapter="perspective" data-copy-side="right">
    <img class="cinematic-chapter-poster" src="<?= asset('img/home/story-continuation/sequence-04/desktop/frame-0191.webp') ?>" alt="Notebook illustrativo sulla sinistra di uno studio scuro" width="1280" height="720" loading="lazy" decoding="async">
    <p class="home-location">Cambiare prospettiva</p><h2>La tecnologia giusta. <span>Per quello che vuoi fare.</span></h2>
    <p><span class="cinematic-copy-full">Nuovo o ricondizionato, scegliamolo insieme. Con consigli chiari e assistenza anche dopo l'acquisto.</span><span class="cinematic-copy-compact">Nuovo o ricondizionato, scegliamolo insieme.</span></p>
    <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('valuta-usato.php') ?>">Valuta l'usato <i class="ri-arrow-right-line" aria-hidden="true"></i></a></div>
   </div>
   <div class="cinematic-chapter cinematic-selection" data-chapter="selection" data-copy-side="right">
    <h2>Il prossimo <span>è qui.</span></h2>
    <div class="cinematic-stock" aria-label="La selezione disponibile in negozio">
    <?php foreach ($homeProducts as $product): $productName = trim((string)$product['title']) ?: trim((string)$product['model']) ?: (string)$product['device']; ?>
     <a class="cinematic-stock-item" href="<?= url('prodotti.php?sku=' . rawurlencode($product['sku'])) ?>"><div><span><?= $esc($product['brand']) ?><?= $product['grade'] ? ' · ' . $esc($product['grade']) : '' ?></span><h3><?= $esc($productName) ?></h3></div><strong>€ <?= $esc(number_format((float)$product['price_eur'], 2, ',', '.')) ?></strong><span aria-hidden="true">↗</span></a>
    <?php endforeach; ?>
    <?php if (!$homeProducts): ?><p>Scopri in catalogo i dispositivi disponibili.</p><?php endif; ?>
    </div>
    <a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Esplora il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
   </div>
   <div id="home-people" class="cinematic-chapter cinematic-people" data-chapter="people">
    <div class="cinematic-people-copy">
     <p class="home-location">Key Soft Italia · Ginosa · Dal 2004</p><h2 id="home-story-title">Dietro la tecnologia, <span>ci siamo noi.</span></h2>
     <p>La tecnologia cambia. Il valore di avere qualcuno a cui affidarsi, resta.</p><p>Ascoltiamo, consigliamo, ripariamo. A Ginosa, vicino alle persone e alle attività.</p>
     <a class="continuum-text-link" href="<?= url('chi-siamo.php') ?>">Conosci la nostra storia ↗</a>
    </div>
    <figure class="cinematic-people-visual" data-motion-side="right"><img src="<?= asset('img/about-story.webp') ?>" alt="Una fotografia dalla storia di Key Soft Italia" width="800" height="600" loading="lazy" decoding="async"><figcaption>Una storia di persone, prima che di dispositivi.</figcaption></figure>
   </div>
   <p class="cinematic-caption">Film illustrativi; la selezione del negozio mostra disponibilità e prezzi reali. La fotografia storica ritrae Key Soft Italia.</p>
  </div>
  <div class="cinematic-footer home-shell"><div class="cinematic-note"><div class="cinematic-meter" role="progressbar" aria-label="Avanzamento del racconto" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div><span>Scorri per una nuova possibilità</span></div><button class="cinematic-skip" type="button" hidden>Vai ai servizi <i class="ri-arrow-down-line" aria-hidden="true"></i></button></div>
 </div>
</section>
