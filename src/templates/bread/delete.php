<?php

$template->layout('master');
$template->set('title', 'Are you sure? - ' . $model->name() . ' delete confirmation');
$template->set('model', $model);
?>

<?= $template->include('includes/navigation', [
    'links' => [
        'Models' => admin_url('models'),
        $model->name() => admin_url('model/' . $model->name()),
    ],
    'active' => 'Delete'
]); ?>

<section class="flex">
    <?= $template->include('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
        <h2 class="text-lg mb-3 md:mb-4 md:text-xl lg:text-2xl font-light"><?= __('Are you sure?') ?></h2>
        <p class="mb-4 md:mb-6">
            <?= __('Are you sure you want to delete the selected ' . $model->name_plural() . '? All of the following objects, files, and their related items into database will be deleted:') ?>
        </p>
        <?= $template->include('bread/parts/delete-summary', ['objects' => $objects]) ?>
        <?= $template->include('bread/parts/delete-objects', ['objects' => $objects]) ?>
        <?= $template->include('bread/parts/delete-form') ?>
    </div>
</section>

<?php $template->remove('model') ?>