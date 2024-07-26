<?php

$this->layout('master');
$this->set('title', $object . ' - Change ' . $model->name() . ' | Model administration');
$this->set('navigation', [
    'links' => [
        'Models' => admin_url('models'),
        $model->name() => admin_url('model/' . $model->name()),
    ],
    'active' => 'Change'
]);
?>
<section class="flex">
    <?= $this->template('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full">
        <?= $this->template('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <h2 class="text-lg md:text-xl lg:text-2xl font-light mb-3 md:mb-4"><?= __('Change ' . $model->name())  ?></h2>
            <p class="font-semibold"><?= $object ?></p>
            <form method="post" enctype="multipart/form-data">
                <?= csrf() ?>
                <?= $this->template('includes/form', ['form' => $form]) ?>
                <div class="flex flex-col md:flex-row gap-2 md:items-center mt-8 bg-zinc-800 px-4 py-3">
                    <input type="submit" value="<?= __('Save') ?>" name="_save" class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-700/75 hover:bg-teal-700 text-teal-100 uppercase">
                    <input type="submit" value="<?= __('Save and add another') ?>" name="_save_add" class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-600/75 hover:bg-teal-600 text-teal-100">
                    <input type="submit" value="<?= __('Save and continue editing') ?>" name="_save_edit" class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-600/75 hover:bg-teal-600 text-teal-100">
                    <button onclick="document.getElementById('deleteObjectForm').submit()" type="button" class="px-4 md:ml-auto py-2 text-sm rounded bg-red-600/75 hover:bg-red-600 text-red-100"><?= __('Delete') ?></button>
                </div>
            </form>
            <form id="deleteObjectForm" method="post" action="<?= admin_url('model/' . $model->name() . '/delete') ?>">
                <?= csrf() ?>
                <input type="hidden" name="ids" value="<?= $object->id ?>">
            </form>
        </div>
    </div>
</section>