<?php if ($model->hasSearch() && $hasRealObjs) : ?>
    <form method="get" class="mb-6 bg-zinc-800 border border-zinc-700/25 px-2 py-[5px] flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="fill-current text-slate-200/25 mr-2" viewBox="0 0 24 24">
            <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
        </svg>
        <?php foreach (request()->queryParams as $k => $v) : ?>
            <?php if ($k == 'q') {
                continue;
            } ?>
            <input type="hidden" name="<?= $k ?>" value="<?= $v ?>">
        <?php endforeach ?>
        <input type="text" name="q" value="<?= request()->get('q', '') ?>" placeholder="<?= __('Start typing to search... ') ?>" class="bg-zinc-900 text-sm focus:outline-none px-3 py-1 rounded-sm border border-zinc-700/75">
        <button type="submit" class="bg-zinc-900 px-3 py-1 border border-zinc-700/75 hover:border-zinc-600 text-sm rounded ml-2"><?= __('Search') ?></button>
        <?php if (!empty(request()->get('q'))) : ?>
            <span class="text-xs ml-2">
                <?= $paginator->getTotal() . ' ' . __('result') ?>
                (<a class="text-teal-400 hover:text-teal-500" href="<?= query_removed_url() ?>"><?= $model->getModel()->count() ?> <?= __('Total') ?></a>)
            </span>
        <?php endif ?>
    </form>
<?php endif ?>