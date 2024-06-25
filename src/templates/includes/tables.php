<?php

use admin\admin;

$setup = array_merge([
    'add' => true,
    'change' => true,
    'models_limit' => 10,
    'menus_limit' => 5,
    'active_model' => null,
    'active_menu' => null,
    'include' => ['models', 'menus']
], $setup ?? []);

if (!function_exists('__text')) {
    function __text(string $text = ''): string
    {
        return __(ucwords(str_replace(['-', '_'], ' ', $text)));
    }
}

?>
<?php if (in_array('models', $setup['include']) && !empty($models = admin::$instance->getModels())) : ?>
    <table class="w-full mb-8">
        <caption>
            <a href="<?= url('admin/models') ?>" class="bg-teal-600/75 block text-sm uppercase text-slate-50 px-2 py-1"><?= __('Models') ?></a>
        </caption>
        <tbody>
            <?php foreach ($models as $key => $model) : ?>
                <?php $bg = $key % 2 != 0 ? 'bg-zinc-800' : '' ?>
                <?php if ($key >= $setup['models_limit']) : ?>
                    <tr class="border-b <?= $bg ?> border-zinc-800">
                        <th scope="row" class="text-left"><a class="text-teal-200 hover:text-teal-300 px-2 font-normal py-1 inline-block text-sm" href="<?= url('admin/models') ?>"><?= '...' . __('View More') . ' (' . count($models) - $setup['models_limit'] . ')' ?></a></th>
                    </tr>
                    <?php break; ?>
                <?php else : ?>
                    <tr class="border-b <?= $setup['active_model'] == $model->name() ? 'bg-teal-500/25' : $bg ?> border-zinc-800">
                        <th scope="row" class="text-left">
                            <a class="text-teal-400 hover:text-teal-500 px-2 font-normal text-sm md:text-base py-1 inline-block" href="<?= url('admin/model/' . $model->name()) ?>"><?= __text($model->name()) ?></a>
                        </th>
                        <td class="flex justify-end gap-2">
                            <?php if ($setup['add']) : ?>
                                <a href="<?= url('admin/model/' . $model->name() . '/add') ?>" class="flex items-center justify-end gap-1 font-normal text-xs md:text-sm text-teal-400 hover:text-teal-500 px-2 py-1">
                                    <svg width="13" height="13" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#70bf2b" d="M1600 796v192q0 40-28 68t-68 28h-416v416q0 40-28 68t-68 28h-192q-40 0-68-28t-28-68v-416h-416q-40 0-68-28t-28-68v-192q0-40 28-68t68-28h416v-416q0-40 28-68t68-28h192q40 0 68 28t28 68v416h416q40 0 68 28t28 68z" />
                                    </svg>
                                    <?= __('Add') ?>
                                </a>
                            <?php endif ?>
                            <?php if ($setup['change']) : ?>
                                <a href="<?= url('admin/model/' . $model->name()) ?>" class="flex items-center justify-end gap-1 font-normal text-xs md:text-sm text-teal-400 hover:text-teal-500 px-2 py-1">
                                    <svg width="13" height="13" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="#efb80b" d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z" />
                                    </svg>
                                    <?= __('Change') ?>
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endif ?>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php if (in_array('menus', $setup['include']) && !empty($menus = admin::$instance->getSetup('menus', []))) : ?>
    <table class="w-full">
        <caption>
            <a href="<?= url('admin/menus') ?>" class="bg-teal-600/75 block text-sm uppercase text-slate-50 px-2 py-1"><?= __('Menus') ?></a>
        </caption>
        <tbody>
            <?php foreach (array_keys($menus) as $key => $menu) : ?>
                <?php $bg = $key % 2 != 0 ? 'bg-zinc-800' : '' ?>
                <?php if ($key >= $setup['menus_limit']) : ?>
                    <tr class="border-b <?= $bg ?> border-zinc-800">
                        <th scope="row" class="text-left"><a class="text-teal-200 hover:text-teal-300 px-2 font-normal py-1 inline-block text-sm" href="<?= url('admin/menus') ?>"><?= '...' . __('View More') . ' (' . count($tools) - $setup['tools_limit'] . ')' ?></a></th>
                    </tr>
                    <?php break; ?>
                <?php else : ?>
                    <tr class="border-b <?= $setup['active_menu'] == $menu ? 'bg-teal-500/25' : $bg ?> border-zinc-800">
                        <th scope="row" class="text-left"><a class="text-teal-400 hover:text-teal-500 px-2 font-normal text-sm md:text-base py-1 inline-block" href="<?= url('admin/menu/' . $menu) ?>"><?= __text($menu) ?></a></th>
                    </tr>
                <?php endif ?>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
