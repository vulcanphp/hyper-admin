<?php

$template->layout('master');
$template->set('title', 'Add ' . $model->name() . ' - model administration');
?>
<?= $template->include('includes/navigation', [
    'links' => [
        'Models' => admin_url('models'),
        $model->name() => admin_url('model/' . $model->name()),
    ],
    'active' => 'Add'
]); ?>

<section class="flex">
    <?= $template->include('includes/sidebar', ['setup' => ['active_model' => $model->name()]]) ?>
    <div id="content" class="w-full">
        <?= $template->include('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <h2 class="text-lg mb-3 md:mb-4 md:text-xl lg:text-2xl font-light"><?= __('Add ' . $model->name()) ?></h2>
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
                </div>
            </form>
        </div>
    </div>
</section>