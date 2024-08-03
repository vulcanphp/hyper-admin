<?php if ($model->hasFilter() && $hasRealObjs) : ?>
    <div class="w-full md:w-4/12 lg:w-3/12 xl:w-2/12">
        <p class="bg-teal-600/75 text-sm uppercase text-slate-50 px-2 py-1"><?= __('Filter') ?></p>
        <div class="bg-zinc-800">
            <?php if ($showFacts = request()->get('_facts') == 'true') : ?>
                <a fire href="<?= hide_filter_count() ?>" class="flex gap-1 text-sm text-slate-200 hover:text-teal-500 items-center border-b px-2 py-3 border-zinc-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-teal-600/75" width="18" height="18" viewBox="0 0 24 24">
                        <path d="M12 19c.946 0 1.81-.103 2.598-.281l-1.757-1.757c-.273.021-.55.038-.841.038-5.351 0-7.424-3.846-7.926-5a8.642 8.642 0 0 1 1.508-2.297L4.184 8.305c-1.538 1.667-2.121 3.346-2.132 3.379a.994.994 0 0 0 0 .633C2.073 12.383 4.367 19 12 19zm0-14c-1.837 0-3.346.396-4.604.981L3.707 2.293 2.293 3.707l18 18 1.414-1.414-3.319-3.319c2.614-1.951 3.547-4.615 3.561-4.657a.994.994 0 0 0 0-.633C21.927 11.617 19.633 5 12 5zm4.972 10.558-2.28-2.28c.19-.39.308-.819.308-1.278 0-1.641-1.359-3-3-3-.459 0-.888.118-1.277.309L8.915 7.501A9.26 9.26 0 0 1 12 7c5.351 0 7.424 3.846 7.926 5-.302.692-1.166 2.342-2.954 3.558z"></path>
                    </svg>
                    <span><?= __('Hide Counts') ?></span>
                </a>
            <?php else : ?>
                <a fire href="<?= show_filter_count() ?>" class="flex gap-1 text-sm text-slate-200 hover:text-teal-500 items-center border-b px-2 py-3 border-zinc-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-teal-600/75" width="18" height="18" viewBox="0 0 24 24">
                        <path d="M12 9a3.02 3.02 0 0 0-3 3c0 1.642 1.358 3 3 3 1.641 0 3-1.358 3-3 0-1.641-1.359-3-3-3z"></path>
                        <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 12c-5.351 0-7.424-3.846-7.926-5C4.578 10.842 6.652 7 12 7c5.351 0 7.424 3.846 7.926 5-.504 1.158-2.578 5-7.926 5z"></path>
                    </svg>
                    <span><?= __('Show Counts') ?></span>
                </a>
            <?php endif ?>
            <?php foreach ($model->getFilter() as $field => $options) : ?>
                <?php $filter_value = request()->get('filter__' . $field); ?>
                <div toggle="<?= $model->name() . '_filter_' . $field ?>" class="px-3 py-2 text-sm">
                    <button><span class="text-teal-500">&darr;</span> <?= __('By ' . ucwords(str_replace(['_', '-'], ' ', $field))) ?></button>
                    <ul class="mt-2 px-1">
                        <li class="mb-1 text-[90%] hover:text-teal-500 <?= $filter_value === null ? 'text-indigo-400' : 'text-slate-300' ?>"><a fire href="<?= get_filter_url($field, false) ?>"><?= __('All') ?></a></li>
                        <?php foreach ($options as $val => $key) : ?>
                            <li class="mb-1 text-[90%] hover:text-teal-500 <?= $filter_value !== null && $filter_value == $val ? 'text-indigo-400' : 'text-slate-300' ?>"><a fire href="<?= get_filter_url($field, $val) ?>"><?= __($key) ?><?= $showFacts ? ' (' . $model->getModel()->where([$field => $val])->where($where)->count() . ')' : '' ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>