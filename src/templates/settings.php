<?php

$template->layout('master');
$template->set('title', 'Settings - Administration');
?>

<section class="w-full xl:w-8/12 px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
    <h2 class="text-xl lg:text-2xl font-light mb-8"><?= __('Site settings') ?></h2>
    <div class="flex flex-col md:flex-row gap-10">
        <div class="w-full md:w-7/12 lg:w-8/12">
            <?= $template->include('includes/tables', ['setup' => ['include' => ['settings']]]) ?>
        </div>
    </div>
</section>