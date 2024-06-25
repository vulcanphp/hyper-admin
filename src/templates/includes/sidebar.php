<div class="fixed top-0 left-0 bottom-0 bg-zinc-900">
    <div id="sidebar" class="hidden lg:flex min-w-[20px] overflow-y-auto relative">
        <button class="w-[20px] h-screen bg-zinc-900 hover:bg-zinc-800/50 border-r border-r-zinc-800 text-2xl px-3 text-teal-200 flex justify-center items-center">&raquo;</button>
        <aside class="w-[285px] h-screen overflow-y-auto border-r border-r-zinc-800">
            <input type="text" placeholder="<?= __('Start typing to filter...') ?>" class="bg-zinc-800 focus:outline-none text-slate-50 placeholder:text-zinc-500 px-2 py-[1px] w-full border border-zinc-700 my-2">
            <?= $this->template('includes/tables', ['setup' => array_merge(['change' => false, 'models_limit' => 500, 'menus_limit' => 500], isset($setup) ? $setup : [])]) ?>
        </aside>
    </div>
</div>