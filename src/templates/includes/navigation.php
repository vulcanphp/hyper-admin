<?php
if (!function_exists('__text')) {
    function __text(string $text = ''): string
    {
        return __(ucwords(str_replace(['-', '_'], ' ', $text)));
    }
}

?>
<nav class="bg-teal-900 text-slate-100 text-sm lg:text-base px-4 md:px-6 h-[30px] flex items-center relative z-30">
    <div class="flex w-full items-center gap-2">
        <a class="opacity-75 text-sm hover:opacity-85 transition-all" href="<?= url('admin') ?>"><?= __text('Home') ?></a>
        <?php foreach ($links as $label => $url) : ?>
            <span class="opacity-85">›</span><a class="opacity-75 text-sm hover:opacity-85 transition-all" href="<?= $url ?>"><?= __text($label) ?></a>
        <?php endforeach; ?>
        <span class="opacity-85">›</span><span class="text-teal-50 text-sm opacity-75"><?= __text($active) ?></span>
    </div>
</nav>