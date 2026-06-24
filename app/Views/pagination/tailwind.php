<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<nav aria-label="Page navigation" class="flex justify-center">
    <ul class="inline-flex items-center gap-1.5">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" class="px-3 py-2 text-sm font-medium text-slate-500 bg-white rounded-lg ring-1 ring-slate-200 hover:bg-orange-50 hover:text-orange-600 hover:ring-orange-200 transition-all duration-200">
                    &laquo;
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="px-3 py-2 text-sm font-medium text-slate-500 bg-white rounded-lg ring-1 ring-slate-200 hover:bg-orange-50 hover:text-orange-600 hover:ring-orange-200 transition-all duration-200">
                    &lsaquo;
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="px-3.5 py-2 text-sm font-medium rounded-lg ring-1 transition-all duration-200 <?= $link['active'] ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white ring-orange-500 shadow-sm hover:shadow-md' : 'text-slate-500 bg-white ring-slate-200 hover:bg-orange-50 hover:text-orange-600 hover:ring-orange-200' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="px-3 py-2 text-sm font-medium text-slate-500 bg-white rounded-lg ring-1 ring-slate-200 hover:bg-orange-50 hover:text-orange-600 hover:ring-orange-200 transition-all duration-200">
                    &rsaquo;
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" class="px-3 py-2 text-sm font-medium text-slate-500 bg-white rounded-lg ring-1 ring-slate-200 hover:bg-orange-50 hover:text-orange-600 hover:ring-orange-200 transition-all duration-200">
                    &raquo;
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>
