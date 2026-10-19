<section id="home-cinematic" class="cinematic-story cinematic-welcome" aria-label="La tua tecnologia, in buone mani" data-manifest="<?= asset_version('img/home/cinematic-v2/manifest.json') ?>">
 <div class="cinematic-stage">
  <picture class="welcome-poster"><source media="(max-width:767px)" srcset="<?= asset('img/home/cinematic-v2/chapter-01/mobile/sequence/frame-0000.webp') ?>"><img class="cinematic-poster" src="<?= $esc($heroImage) ?>" alt="Vito Moro di Key Soft Italia al banco, con un notebook e uno smartphone (scena ricostruita)" width="1440" height="810" fetchpriority="high"></picture>
  <canvas class="cinematic-canvas" aria-hidden="true"></canvas>
  <div class="cinematic-grade" aria-hidden="true"><i></i></div>
  <div class="cinematic-shade" aria-hidden="true"></div>
  <div class="cinematic-outro" aria-hidden="true"></div>
  <div class="cinematic-content home-shell">
   <div class="cinematic-chapter" data-chapter="opening">
    <div class="welcome-intro">
     <h1 id="home-title">La tua tecnologia,<span>in buone mani.</span></h1>
     <p class="welcome-description">Dal 2004, a Ginosa, ripariamo smartphone e computer. Ti aiutiamo a scegliere il prossimo dispositivo e a realizzare i tuoi progetti digitali.</p>
     <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('preventivo.php') ?>">Richiedi un preventivo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></div>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-computer" data-chapter="computer" data-copy-side="right">
    <picture class="cinematic-chapter-poster"><source media="(max-width:767px)" srcset="<?= asset('img/home/cinematic-v2/chapter-02/mobile/sequence/frame-0191.webp') ?>"><img src="<?= asset('img/home/cinematic-v2/chapter-02/desktop/sequence/frame-0191.webp') ?>" alt="Lo schermo del notebook in primo piano" width="1440" height="810" loading="lazy"></picture>
    <div class="welcome-intro">
     <h2>Il tuo computer,<span>di nuovo al tuo fianco.</span></h2>
     <p class="welcome-description">Individuiamo il problema, ripariamo e configuriamo PC e notebook. Raccontaci cosa non funziona.</p>
     <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prenota-riparazione.php') ?>">Prenota una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></div>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-delivery" data-chapter="delivery" data-copy-side="left">
    <picture class="cinematic-chapter-poster"><source media="(max-width:767px)" srcset="<?= asset('img/home/cinematic-v2/chapter-03/mobile/sequence/frame-0191.webp') ?>"><img src="<?= asset('img/home/cinematic-v2/chapter-03/desktop/sequence/frame-0191.webp') ?>" alt="Una cliente appoggia sul banco uno smartphone con il vetro rotto" width="1440" height="810" loading="lazy"></picture>
    <div class="welcome-intro">
     <h2>Un imprevisto,<span>una soluzione.</span></h2>
     <p class="welcome-description">Vetro rotto, batteria che non dura o un guasto che non capisci. Ecco cosa succede quando lo porti da noi.</p>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-step" data-chapter="repair-1" data-copy-side="left">
    <div class="welcome-intro">
     <h2>Prima<span>capiamo.</span></h2>
     <p class="welcome-description">Apriamo il dispositivo, troviamo la causa e ti diciamo quanto costa prima di intervenire.</p>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-step" data-chapter="repair-2" data-copy-side="left">
    <div class="welcome-intro">
     <h2>Poi<span>ripariamo.</span></h2>
     <p class="welcome-description">Montiamo ricambi di qualità nel nostro laboratorio, qui a Ginosa.</p>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-step" data-chapter="repair-3" data-copy-side="left">
    <picture class="cinematic-chapter-poster"><source media="(max-width:767px)" srcset="<?= asset('img/home/cinematic-v2/chapter-04/mobile/k3-repaired.webp') ?>"><img src="<?= asset('img/home/cinematic-v2/chapter-04/desktop/k3-repaired.webp') ?>" alt="Lo smartphone riparato, con lo schermo nuovo acceso" width="1440" height="810" loading="lazy"></picture>
    <div class="welcome-intro">
     <h2>Pronto a<span>ripartire.</span></h2>
     <p class="welcome-description">Controlliamo che tutto funzioni prima di riconsegnartelo, con garanzia scritta.</p>
     <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prenota-riparazione.php') ?>">Prenota una riparazione <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a></div>
    </div>
   </div>
   <div class="cinematic-chapter cinematic-choosing<?= ($featured_flyer || $homeProducts) ? ' has-shelf' : '' ?>" data-chapter="choosing" data-copy-side="left">
    <picture class="cinematic-chapter-poster"><source media="(max-width:767px)" srcset="<?= asset('img/home/cinematic-v2/chapter-05/mobile/selection-poster.webp') ?>"><img src="<?= asset('img/home/cinematic-v2/chapter-05/desktop/selection-poster.webp') ?>" alt="Smartphone, tablet e notebook su un piano grafite, in una scena illustrativa" width="1440" height="810" loading="lazy"></picture>
    <div class="welcome-intro">
     <div class="cinematic-choosing-copy">
      <h2>Il prossimo<span>è qui.</span></h2>
      <p class="welcome-description">Nuovo o ricondizionato, scegliamolo insieme.</p>
      <div class="home-actions"><a class="home-button home-button-dark" href="<?= url('prodotti.php') ?>">Scopri il catalogo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a><a class="home-button home-button-outline" href="<?= url('valuta-usato.php') ?>">Valuta il tuo usato</a></div>
     </div>
     <?php if ($featured_flyer || $homeProducts): ?>
     <section class="cinematic-shelf" aria-labelledby="home-shelf-title">
      <h3 id="home-shelf-title" class="cinematic-shelf-title">In negozio adesso</h3>
      <ul class="cinematic-shelf-list<?= $featured_flyer ? ' has-flyer' : '' ?>">
       <?php if ($featured_flyer) include __DIR__ . '/home-flyer.php'; ?>
       <?php foreach ($homeProducts as $product):
        $productName = trim((string)$product['title']) ?: trim((string)$product['model']) ?: (string)$product['device'];
        $productImage = $homeMedia($product['image_path']);
        $productGrade = trim((string)$product['grade']);
        if ($productGrade !== '') $productName = trim(preg_replace('/\s*ricondizionato\s+grado\s+\S+\s*$/iu', '', $productName)) ?: $productName;
        $productKind = mb_strtolower($product['device'] . ' ' . $productName);
        $productIcon = match (true) {
            str_contains($productKind, 'smartphone') => 'ri-smartphone-line',
            str_contains($productKind, 'tablet') => 'ri-tablet-line',
            str_contains($productKind, 'notebook') || str_contains($productKind, 'computer') => 'ri-macbook-line',
            str_contains($productKind, 'console') => 'ri-gamepad-line',
            str_contains($productKind, 'proiett') => 'ri-projector-line',
            str_contains($productKind, 'tv') => 'ri-tv-2-line',
            str_contains($productKind, 'power bank') || str_contains($productKind, 'batter') => 'ri-battery-charge-line',
            default => 'ri-shopping-bag-3-line',
        };
       ?>
       <li><a class="cinematic-shelf-item cinematic-shelf-product" href="<?= url('prodotti.php?sku=' . rawurlencode($product['sku'])) ?>">
        <span class="cinematic-shelf-thumb<?= $productImage ? '' : ' is-icon' ?>"><?php if ($productImage): ?><img src="<?= $esc($productImage) ?>" alt="" width="72" height="72" loading="lazy" decoding="async"><?php else: ?><i class="<?= $productIcon ?>" aria-hidden="true"></i><?php endif; ?><?php if ($productGrade !== ''): ?><span class="cinematic-shelf-grade" aria-label="Grado <?= $esc($productGrade) ?>"><?= $esc($productGrade) ?></span><?php endif; ?></span>
        <span class="cinematic-shelf-details">
         <?php if ($productGrade !== ''): ?><span class="cinematic-shelf-kind">Ricondizionato</span><?php endif; ?>
         <span class="cinematic-shelf-name"><?= $esc($productName) ?></span>
         <strong>€ <?= $esc(number_format((float)$product['price_eur'], 2, ',', '.')) ?></strong>
        </span>
       </a></li>
       <?php endforeach; ?>
      </ul>
     </section>
     <?php endif; ?>
    </div>
   </div>
   <div id="home-people" class="cinematic-chapter cinematic-human" data-chapter="people" data-copy-side="left">
    <div class="welcome-intro">
     <div class="cinematic-human-copy" data-motion-side="left">
      <h2>Dietro la tecnologia,<span>ci siamo noi.</span></h2>
      <p class="welcome-description">Siamo partiti in tre, uniti dalla passione per la tecnologia. Da allora ascoltiamo, consigliamo e ripariamo, qui a Ginosa.</p>
      <a class="home-button home-button-dark" href="<?= url('chi-siamo.php#nostra-storia') ?>">Conosci la nostra storia <span aria-hidden="true">↗</span></a>
     </div>
     <figure class="cinematic-human-photo" data-motion-side="right">
      <div class="founders-memory">
       <span class="founders-year" aria-hidden="true">2026</span>
       <div class="founders-portraits">
        <img class="founders-monochrome" src="<?= asset('img/home/founders/founders-2004-cutout.webp') ?>" alt="I tre fondatori di Key Soft Italia nella fotografia dell'apertura del 2004" width="1448" height="1086" loading="lazy" decoding="async">
        <img class="founders-color" src="<?= asset('img/home/founders/founders-2004-cutout.webp') ?>" alt="" aria-hidden="true" width="1448" height="1086" loading="lazy" decoding="async">
        <div class="founders-present"><img src="<?= asset('img/home/founders/founders-now-2026-cutout.webp') ?>" alt="I tre fondatori di Key Soft Italia oggi, nel 2026" width="1536" height="1024" loading="lazy" decoding="async"></div>
       </div>
      </div>
      <figcaption>Dal 2004 al 2026. La stessa passione.</figcaption>
     </figure>
    </div>
   </div>
  </div>
  <nav class="cinematic-footer cinematic-timeline home-shell" aria-label="Capitoli della storia">
   <div class="cinematic-meter" role="progressbar" aria-label="Avanzamento della storia" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"></div>
   <ol class="cinematic-chapters">
    <li><button type="button" data-goto="0.02007843" data-from="0" data-to="0.12047059"><b>01</b> <span>Benvenuto</span></button></li>
    <li><button type="button" data-goto="0.22745098" data-from="0.12047059" data-to="0.25098039"><b>02</b> <span>Il computer</span></button></li>
    <li><button type="button" data-goto="0.37254902" data-from="0.25098039" data-to="0.39215686"><b>03</b> <span>Lo smartphone</span></button></li>
    <li><button type="button" data-goto="0.55686274" data-from="0.39215686" data-to="0.78431373"><b>04</b> <span>La riparazione</span></button></li>
    <li><button type="button" data-goto="0.88039216" data-from="0.78431373" data-to="0.9019607843137255"><b>05</b> <span>Scegliere</span></button></li>
    <li><button type="button" data-goto="0.9382549" data-from="0.9019607843137255" data-to="1.005"><b>06</b> <span>Le persone</span></button></li>
   </ol>
   <button class="cinematic-skip" type="button" aria-label="Vai ai servizi" hidden>Vai ai servizi <span class="cinematic-skip-arrow" aria-hidden="true">↓</span></button>
  </nav>
 </div>
</section>
