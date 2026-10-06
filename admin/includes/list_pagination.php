<?php
$first = $list['total'] ? ($list['page'] - 1) * $list['per_page'] + 1 : 0;
$last = min($list['total'], $list['page'] * $list['per_page']);
$pageLink = static fn($page) => htmlspecialchars(\KeySoftItalia\AdminList::url($list, $page), ENT_QUOTES, 'UTF-8');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
    <p class="mb-0 small" role="status"><?= $first ?>–<?= $last ?> di <?= $list['total'] ?> richieste</p>
    <?php if ($list['pages'] > 1): ?>
    <nav aria-label="Pagine delle richieste">
        <ul class="pagination pagination-sm mb-0 flex-wrap">
            <li class="page-item <?= $list['page'] === 1 ? 'disabled' : '' ?>"><?php if ($list['page'] > 1): ?><a class="page-link" href="<?= $pageLink($list['page'] - 1) ?>">Precedente</a><?php else: ?><span class="page-link">Precedente</span><?php endif; ?></li>
            <?php for ($pageNumber = max(1, $list['page'] - 2); $pageNumber <= min($list['pages'], $list['page'] + 2); ++$pageNumber): ?>
            <li class="page-item <?= $pageNumber === $list['page'] ? 'active' : '' ?>"><a class="page-link" href="<?= $pageLink($pageNumber) ?>" <?= $pageNumber === $list['page'] ? 'aria-current="page"' : '' ?>><?= $pageNumber ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?= $list['page'] === $list['pages'] ? 'disabled' : '' ?>"><?php if ($list['page'] < $list['pages']): ?><a class="page-link" href="<?= $pageLink($list['page'] + 1) ?>">Successiva</a><?php else: ?><span class="page-link">Successiva</span><?php endif; ?></li>
        </ul>
    </nav>
    <?php endif; ?>
</div>
