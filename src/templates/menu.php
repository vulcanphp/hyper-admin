<?php

$template->layout('master')
    ->set('title', $menu . ' - Administration');
?>

<?= $template->include('includes/navigation', [
    'links' => [
        'Menus' => admin_url('menus'),
    ],
    'active' => $menu
]); ?>

<section class="flex">
    <?= $template->include('includes/sidebar', ['setup' => ['active_menu' => $menu]]) ?>
    <div id="content" class="w-full">
        <?= $template->include('includes/messages') ?>
        <div class="px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
            <h2 class="text-xl lg:text-2xl font-light mb-8 capitalize"><?= __(str_replace(['_', '-'], ' ', $menu)) ?>
            </h2>
            <div class="flex flex-col md:flex-row gap-10">
                <?= $content ?>
            </div>
        </div>
    </div>
</section>