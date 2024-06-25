<?php
$this->layout('auth/layout')
    ->set('title', 'Login - administration');
?>
<section class="flex items-center justify-center min-h-screen w-full">
    <div class="bg-zinc-950 rounded-b">
        <div class="px-4 py-2 md:px-6 md:py-3 xl:px-8 rounded-t text-center text-amber-400 bg-teal-600">
            <h1 class="text-lg md:text-xl lg:text-2xl font-light"><?= __('Administration') ?></h1>
        </div>
        <form action="<?= url('admin/login') ?>" method="post" class="px-8 py-4 text-center">
            <?= csrf() ?>
            <?php if (!empty($error)) : ?>
                <p class="bg-red-100 text-red-600 mb-2 rounded border border-red-200"><?= $error ?></p>
            <?php endif ?>
            <input type="password" name="password" placeholder="<?= __('Password?') ?>" class="w-64 block text-center bg-zinc-900 px-4 py-1 border border-zinc-800 rounded">
            <button type="submit" class="px-4 py-1 text-sm bg-teal-500 hover:bg-teal-600 border border-teal-500/75 mt-4 rounded"><?= __('Login') ?></button>
        </form>
    </div>
</section>