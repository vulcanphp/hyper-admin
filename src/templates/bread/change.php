<?php

$template->layout('master');
$template->set('title', $object . ' - Change ' . $model->name() . ' | Model administration');
?>

<?= $template->include('includes/navigation', [
    'links' => [
        'Models' => admin_url('models'),
        $model->name() => admin_url('model/' . $model->name()),
    ],
    'active' => 'Change'
]); ?>

<section class="flex">
    <?= $template->include('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full">
        <?= $template->include('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <h2 class="text-lg md:text-xl lg:text-2xl font-light mb-3 md:mb-4"><?= __('Change ' . $model->name()) ?>
            </h2>
            <p class="font-semibold"><?= $object ?></p>
            <form fire action="<?= request_url() ?>" method="post" enctype="multipart/form-data">
                <?= csrf() ?>
                <?= $template->include('includes/form', ['form' => $form]) ?>
                <div class="flex flex-col md:flex-row gap-2 md:items-center mt-8 bg-zinc-800 px-4 py-3">
                    <input type="hidden" id="formPostAction" name="" value="1">
                    <button type="submit" id="saveObjectForm" style="display: none;"></button>
                    <button type="button"
                        onclick="document.getElementById('formPostAction').setAttribute('name', '_save'), document.getElementById('saveObjectForm').click();"
                        class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-700/75 hover:bg-teal-700 text-teal-100 uppercase"><?= __('Save') ?></button>
                    <button type="button"
                        onclick="document.getElementById('formPostAction').setAttribute('name', '_save_add'), document.getElementById('saveObjectForm').click();"
                        class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-600/75 hover:bg-teal-600 text-teal-100"><?= __('Save and add another') ?></button>
                    <button type="button"
                        onclick="document.getElementById('formPostAction').setAttribute('name', '_save_edit'), document.getElementById('saveObjectForm').click();"
                        class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-600/75 hover:bg-teal-600 text-teal-100"><?= __('Save and continue editing') ?></button>
                    <button onclick="document.getElementById('deleteObjectForm').click();" type="button"
                        class="px-4 md:ml-auto py-2 text-sm rounded bg-red-600/75 hover:bg-red-600 text-red-100"><?= __('Delete') ?></button>
                </div>
            </form>
            <form fire action="<?= admin_url('model/' . $model->name() . '/delete') ?>" method="post">
                <?= csrf() ?>
                <input type="hidden" name="ids" value="<?= $object->id ?>">
                <button type="submit" id="deleteObjectForm" style="display: none;"></button>
            </form>
        </div>
    </div>
</section>