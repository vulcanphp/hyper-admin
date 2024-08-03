<b class="mb-2 block text-lg"><?= __('Objects') ?></b>
<ul class="list-disc break-all text-sm pl-10">
    <?php foreach ($objects as $object) : ?>
        <li class="capitalize mb-2">
            <p class="mb-1"><?= str_replace(['_', '-'], ' ', $model->name()) ?>: <a fire class="underline text-teal-500" href="<?= admin_url('model/' . $model->name() . '/' . $object->id . '/change') ?>"><?= $object ?></a></p>
            <?php if (method_exists($model->getModel(), 'getRegisteredOrm')) : ?>
                <?php foreach ($model->getModel()->getRegisteredOrm() as $with => $rel) : ?>
                    <?php if (!in_array($rel['has'], ['many', 'many-x'])) {
                        continue;
                    } ?>
                    <?php if (!empty($relObjs = $object->{$with})) : ?>
                        <ul class="pl-6 list-disc mb-1">
                            <?php foreach ($relObjs as $relObj) : ?>
                                <?php $relModelName = (new ReflectionClass($relObj))->getShortName() ?>
                                <?php if ($rel['has'] == 'many') : ?>
                                    <li class="capitalize mb-1"><?= $relModelName ?>: <a fire class="underline text-teal-500" href="<?= admin_url('model/' . $relModelName . '/' . $relObj->id . '/change') ?>" href=""><?= $relObj ?></a></li>
                                <?php else : ?>
                                    <li class="capitalize mb-1"><?= str_replace('_', '-', $rel['table']) . ' relationship for: ' . '<a fire class="underline text-teal-500" href="' . admin_url('model/' . $relModelName . '/' . $relObj->id . '/change') . '">' . $relObj . '</a>' ?></li>
                                <?php endif ?>
                                <?php if ($rel['has'] == 'many' && method_exists($relObj, 'getRegisteredUploads')) : ?>
                                    <?php foreach ($relObj->getRegisteredUploads() as $u) : ?>
                                        <ul class="pl-6 list-disc mb-1">
                                            <?php foreach ((array) $relObj->{$u['name']} as $f) : ?>
                                                <li class="mb-1"><?= ucwords(str_replace(['_', '-'], ' ', $u['name'])) ?>: <a fire class="text-indigo-300 hover:underline" href="<?= media_url($f) ?>"><?= $f ?></a></li>
                                            <?php endforeach ?>
                                        </ul>
                                    <?php endforeach ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>
            <?php if (method_exists($model->getModel(), 'getRegisteredUploads')) : ?>
                <?php foreach ($model->getModel()->getRegisteredUploads() as $u) : ?>
                    <?php if (!empty($ufs = (array) $object->{$u['name']})) : ?>
                        <ul class="pl-6 list-disc mb-1">
                            <?php foreach ($ufs as $f) : ?>
                                <li class="mb-1"><?= ucwords(str_replace(['_', '-'], ' ', $u['name'])) ?>: <a fire class="text-indigo-300 hover:underline" href="<?= media_url($f) ?>"><?= $f ?></a></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>