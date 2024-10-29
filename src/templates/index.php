<?php

$template->layout('master')
    ->set('title', 'Welcome to Admin');
?>

<section class="w-full xl:w-8/12 px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
    <h2 class="text-xl lg:text-2xl font-light mb-8"><?= __('Site administration') ?></h2>
    <div class="flex flex-col md:flex-row gap-10">
        <div class="w-full md:w-7/12 lg:w-8/12">
            <?= $template->include('includes/tables', ['setup' => ['models_limit' => 20, 'menus_limit' => 10]]) ?>
        </div>
    </div>
</section>