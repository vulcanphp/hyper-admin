<?php
if (!function_exists('parseBrowseFieldValue')) {
    /**
     * Converts a value to a string that can be displayed in a table.
     * Bool values are translated to "Yes" or "No".
     * Array values are JSON encoded.
     * Other values are left as is.
     *
     * @param mixed $input The value to convert
     * @return string The converted string
     */
    function parseBrowseFieldValue(mixed $input): string
    {
        return is_bool($input) ? __($input ? 'Yes' : 'No') :
            (is_array($input) ? json_encode($input) : $input);
    }
}
?>
<table id="dataTable" class="w-full text-[90%] md:text-[92%]">
    <thead>
        <tr class="bg-zinc-800 border border-zinc-700/50">
            <th class="w-10 text-left px-2 py-1"><input type="checkbox"></th>
            <?php if ($model->hasFields()): ?>
                <?php foreach ($model->getFields() as $field): ?>
                    <?php $orderP = sort_position($field); ?>
                    <?php $canOrder = property_exists($model->getModel(), $field); ?>
                    <th class="text-left font-normal uppercase">
                        <div
                            class="group flex items-center justify-between px-2 py-1 <?= !$orderP ? ($canOrder ? 'hover:bg-zinc-900/35' : '') : 'bg-zinc-900/35' ?>">
                            <?php if ($canOrder): ?>
                                <a fire class="active:underline w-full" href="<?= sort_link($field) ?>">
                                    <?= __(str_replace(['_', '-'], ' ', $field)) ?>
                                </a>
                                <?php if ($orderP): ?>
                                    <span class="flex items-center">
                                        <span class="text-xs opacity-75 mr-1"><?= $orderP ?></span>
                                        <svg class="fill-current opacity-75" width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <?php if (sort_type($field) == 'asc'): ?>
                                                <path d="M11 9h9v2h-9zm0 4h7v2h-7zm0-8h11v2H11zm0 12h5v2h-5zm-6 3h2V8h3L6 4 2 8h3z"></path>
                                            <?php else: ?>
                                                <path d="m6 20 4-4H7V4H5v12H2zm5-12h9v2h-9zm0 4h7v2h-7zm0-8h11v2H11zm0 12h5v2h-5z"></path>
                                            <?php endif ?>
                                        </svg>
                                    </span>
                                <?php endif ?>
                            </div>
                        <?php else: ?>
                            <?= __(ucwords(str_replace(['_', '-'], ' ', $field))) ?>
                        <?php endif ?>
                    </th>
                <?php endforeach ?>
            <?php else: ?>
                <th class="text-left font-normal uppercase px-2 py-1"><?= __($model->name()) ?></th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($paginator->getData() as $key => $row): ?>
            <tr class="<?= $key % 2 != 0 ? 'bg-zinc-800' : '' ?> border border-zinc-700/50">
                <td class="px-2 py-1"><input type="checkbox" value="<?= $row->id ?>"></td>
                <?php if ($model->hasFields()): ?>
                    <?php foreach ($model->getFields() as $key => $field): ?>
                        <?php $fieldValue = parseBrowseFieldValue($row->{$field}) ?>
                        <td class="px-2 py-1">
                            <?php if ($key == 0): ?>
                                <a fire class="text-teal-400 hover:text-teal-500"
                                    href="<?= admin_url('model/' . $model->name() . '/' . $row->id . '/change') ?>"><?= $fieldValue ?></a>
                            <?php else: ?>
                                <?= $fieldValue ?>
                            <?php endif ?>
                        </td>
                    <?php endforeach ?>
                <?php else: ?>
                    <td class="px-2 py-1">
                        <a fire class="text-teal-400 hover:text-teal-500"
                            href="<?= admin_url('model/' . $model->name() . '/' . $row->id . '/change') ?>"><?= $row ?></a>
                    </td>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>