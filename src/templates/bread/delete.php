<?php

$this->layout('master');
$this->set('title', 'Are you sure? - ' .  $model->name() . ' delete confirmation');
$this->set('navigation', [
    'links' => [
        'Models' => url('admin/models'),
        $model->name() => url('admin/model/' . $model->name()),
    ],
    'active' => 'Delete'
]);
$this->set('model', $model);
?>
<section class="flex">
    <?= $this->template('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
        <h2 class="text-lg mb-3 md:mb-4 md:text-xl lg:text-2xl font-light"><?= __('Are you sure?') ?></h2>
        <p class="mb-4 md:mb-6"><?= __('Are you sure you want to delete the selected ' . $model->name_plural() . '? All of the following objects, files, and their related items into database will be deleted:') ?></p>
        <?= $this->template('bread/parts/delete-summary', ['objects' => $objects]) ?>
        <?= $this->template('bread/parts/delete-objects', ['objects' => $objects]) ?>
        <?= $this->template('bread/parts/delete-form') ?>
    </div>
</section>