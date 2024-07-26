<?php

$this->layout('master');
$this->set('title', 'Change ' .  $setting . ' settings - Administration');
$this->set('navigation', [
    'links' => [
        'Settings' => admin_url('settings'),
    ],
    'active' => $setting
]);
?>
<section class="flex">
    <?= $this->template('includes/sidebar', ['setup' => ['active_setting' => $setting, 'include' => ['settings']]]) ?>
    <div id="content" class="w-full">
        <?= $this->template('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <h2 class="text-lg mb-3 md:mb-4 md:text-xl lg:text-2xl font-light"><?= __('Change ' . $setting . ' settings') ?></h2>
            <form method="post" enctype="multipart/form-data">
                <?= csrf() ?>
                <?= $this->template('includes/form', ['form' => $form]) ?>
                <div class="flex flex-col md:flex-row gap-2 md:items-center mt-8 bg-zinc-800 px-4 py-3">
                    <input type="submit" value="<?= __('Save') ?>" name="_save" class="cursor-pointer px-4 py-2 text-sm rounded bg-teal-700/75 hover:bg-teal-700 text-teal-100 uppercase">
                </div>
            </form>
        </div>
    </div>
</section>