<?php

$this->layout('master');
$this->set('title', 'Models - Administration');
?>

<?= $this->template('includes/navigation', [
    'links' => [],
    'active' => 'Models'
]); ?>

<section class="w-full xl:w-8/12 px-4 md:px-8 lg:px-10 py-3 md:py-4 lg:py-6">
    <h2 class="text-xl lg:text-2xl font-light mb-8"><?= __('Model administration') ?></h2>
    <div class="flex flex-col md:flex-row gap-10">
        <div class="w-full md:w-7/12 lg:w-8/12">
            <?= $this->template('includes/tables', [
                'setup' => [
                    'include' => ['models'],
                    'models_limit' => 500
                ]
            ]) ?>
        </div>
    </div>
</section>