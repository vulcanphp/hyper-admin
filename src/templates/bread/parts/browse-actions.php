<form id="bulkActionForm" method="post" class="mb-3 flex items-center text-sm">
    <?= csrf() ?>
    <label for="bulkAction" class="mr-2"><?= __('Action') ?>:</label>
    <select id="bulkAction" class="bg-zinc-900 px-2 text-slate-100 border border-zinc-600 rounded-sm">
        <option value="">---------</option>
        <option value="<?= admin_url('model/' . $model->name() . '/delete') ?>"><?= __('Delete selected ' . $model->name_plural()) ?></option>
        <?php if ($model->hasActions()) : ?>
            <?php foreach ($model->getActions() as $action => $url) : ?>
                <option value="<?= $url ?>"><?= __($action) ?></option>
            <?php endforeach ?>
        <?php endif ?>
    </select>
    <button type="submit" class="bg-zinc-900 px-2 border border-zinc-700/75 hover:border-zinc-600 rounded ml-2"><?= __('Go') ?></button>
    <span class="ml-2"><span id="totalSelected">0</span> <?= __('of') ?> <?= count($paginator->getData()) ?> <?= __($model->name() . ' selected') ?></span>
</form>