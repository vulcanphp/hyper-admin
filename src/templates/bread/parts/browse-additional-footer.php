<div class="border-y border-zinc-800 px-2 py-1 text-xs md:text-sm flex gap-2 items-center">
    <p><?= str_replace('##', $paginator->getTotal(), __('## ' . $model->name_plural())) ?></p>
    <?php if (!empty(request()->get('o'))) : ?>
        <span class="opacity-50">|</span>
        <a href="<?= sort_removed_url() ?>" title="Remove Sorting" class="text-amber-300 text-xs hover:border-b border-dotted flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="fill-current" viewBox="0 0 24 24">
                <path d="M7 20h2V8h3L8 4 4 8h3zm13-4h-3V4h-2v12h-3l4 4z"></path>
            </svg>
            <span class="ml-[2px]"><?= __('Clear Sort') ?></span>
        </a>
    <?php endif ?>
    <?php if (!empty($filters)) : ?>
        <span class="opacity-50">|</span>
        <a href="<?= clear_filtered_url() ?>" title="Remove Filters" class="text-amber-300 text-xs hover:border-b border-dotted flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="fill-current" viewBox="0 0 24 24">
                <path d="M21 3H5a1 1 0 0 0-1 1v2.59c0 .523.213 1.037.583 1.407L10 13.414V21a1.001 1.001 0 0 0 1.447.895l4-2c.339-.17.553-.516.553-.895v-5.586l5.417-5.417c.37-.37.583-.884.583-1.407V4a1 1 0 0 0-1-1zm-6.707 9.293A.996.996 0 0 0 14 13v5.382l-2 1V13a.996.996 0 0 0-.293-.707L6 6.59V5h14.001l.002 1.583-5.71 5.71z"></path>
            </svg>
            <span class="ml-[2px]"><?= __('Clear Filter') ?></span>
        </a>
    <?php endif ?>
</div>