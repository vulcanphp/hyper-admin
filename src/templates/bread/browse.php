<?php

$template->layout('master');
$template->set('title', $model->name() . ' - Administration');

// include browse helper functions
require_once __DIR__ . '/parts/browse-functions.php';

/** 
 * Paginator Data
 */
if ($model->hasWith()) {
    $query = $model->getModel()->with($model->getWith());
} else {
    $query = $model->getModel()->get();
}

if ($model->hasWhere()) {
    $query->where($model->getWhere());
}

if (!empty($filters = get_filters())) {
    $query->where($filters);
}

$where = null;
if ($model->hasSearch() && !empty(request()->get('q'))) {
    $where = '(' . collect($model->getSearch())
        ->map(fn($field) => "p.{$field} LIKE '%" . request()->get('q') . "%'")
        ->toString(' OR ') . ')';
    $query->where($where);
}

if (!empty(request()->get('o', ''))) {
    $orders = [];
    foreach (explode('.', request()->get('o', '')) as $order) {
        $orders[] = strpos($order, '-') === 0 ? 'p.' . substr($order, 1) . ' DESC' : "p.{$order} ASC";
    }
    $query->order(implode(', ', $orders));
} elseif ($model->hasOrder()) {
    $query->order($model->getOrder());
}

$paginator = $query->paginate(40);
$hasRealObjs = $paginator->hasData() || !empty(request()->get('q')) || !empty($filters);

$template->set('model', $model);
$template->set('query', $query);
$template->set('filters', $filters);
$template->set('where', $where);
$template->set('paginator', $paginator);
$template->set('hasRealObjs', $hasRealObjs);
?>

<?= $template->include('includes/navigation', [
    'links' => [
        'Models' => admin_url('models'),
    ],
    'active' => $model->name()
]); ?>

<section class="flex">
    <?= $template->include('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full">
        <?= $template->include('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <div class="md:flex items-center justify-between mb-6">
                <h2 class="text-lg mb-2 md:mb-0 md:text-xl lg:text-2xl font-light">
                    <?= __('Select ' . $model->name() . ' to change') ?>
                </h2>
                <a fire href="<?= admin_url('model/' . $model->name() . '/add') ?>"
                    class="inline-flex items-center bg-zinc-800 hover:bg-zinc-700 transition uppercase text-xs px-3 py-1 rounded-full text-slate-50 gap-2">
                    <?= __('Add ' . $model->name()) ?>
                    <svg width="13" height="13" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#ffffff"
                            d="M1600 736v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z" />
                    </svg>
                </a>
            </div>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-full <?= $model->hasFilter() && $hasRealObjs ? 'md:w-8/12 lg:w-9/12 xl:w-10/12' : '' ?>">
                    <?= $template->include('bread/parts/browse-search') ?>
                    <?php if ($paginator->hasData()): ?>
                        <?= $template->include('bread/parts/browse-actions') ?>
                        <?= $template->include('bread/parts/browse-table') ?>
                    <?php endif ?>
                    <?= $template->include('bread/parts/browse-additional-footer') ?>
                    <?php
                    if ($paginator->hasLinks()) {
                        echo $paginator->getLinks(classes: [
                            'ul' => 'mt-6 flex gap-2',
                            'a' => 'px-2 py-1 hover:bg-zinc-700 text-slate-50 bg-zinc-800 rounded-md',
                            'a.current' => 'bg-zinc-700',
                        ]);
                    }
                    ?>
                </div>
                <?= $template->include('bread/parts/browse-filter') ?>
            </div>
        </div>
    </div>
</section>

<?php $template->remove('model', 'query', 'filters', 'where', 'paginator', 'hasRealObjs') ?>