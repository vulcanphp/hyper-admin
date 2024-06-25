<b class="mb-2 block text-lg"><?= __('Summary') ?></b>
<ul class="list-disc text-sm pl-10 mb-4 md:mb-6">
    <li class="capitalize mb-1"><?= str_replace(['_', '-'], ' ', $model->name_plural()) ?>: <?= count($objects) ?></li>
    <?php if (method_exists($model->getModel(), 'getRegisteredOrm')) : ?>
        <?php foreach ($model->getModel()->getRegisteredOrm() as $with => $rel) : ?>
            <?php if (!in_array($rel['has'], ['many', 'many-x'])) {
                continue;
            } ?>
            <?php $totalRelObjs = array_sum(collect($objects)->map(fn ($o) => count($o->{$with}))->all()); ?>
            <?php if ($totalRelObjs > 0) : ?>
                <li class="capitalize mb-1"><?= $rel['has'] == 'many-x' ? str_replace('_', '-', $rel['table']) . ' relationship' : (new ReflectionClass($rel['model']))->getShortName() ?>: <?= $totalRelObjs ?></li>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
</ul>