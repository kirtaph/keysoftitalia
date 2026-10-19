<li class="cinematic-shelf-flyer">
 <a class="cinematic-shelf-item" href="<?= $esc($homeFlyerUrl) ?>">
  <span class="cinematic-shelf-cover"><?php if ($homeFlyerCover): ?><img src="<?= $esc($homeFlyerCover) ?>" alt="" width="120" height="168" loading="lazy" decoding="async"><?php else: ?><i class="ri-price-tag-3-line" aria-hidden="true"></i><?php endif; ?></span>
  <span class="cinematic-shelf-details">
   <span class="cinematic-shelf-kind">Volantino · fino al <?= $esc((new DateTimeImmutable($featured_flyer['end_date']))->format('d/m')) ?></span>
   <span class="cinematic-shelf-name"><?= $esc($featured_flyer['title']) ?></span>
   <span class="cinematic-shelf-cta">Sfoglia le offerte <i class="ri-arrow-right-up-line" aria-hidden="true"></i></span>
  </span>
 </a>
</li>
