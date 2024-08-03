<form fire action="<?= request_url() ?>" method="post" class="flex gap-4 items-center mt-6">
    <?= csrf() ?>
    <input type="hidden" name="deleteConfirmed" value="<?= request()->post('ids', '') ?>">
    <button class="px-4 py-2 text-sm rounded bg-red-700 hover:bg-red-800 text-rose-100" type="submit"><?= __('Yes, I\'m Sure') ?></button>
    <a fire class="px-4 py-2 text-sm rounded bg-zinc-700 hover:bg-zinc-600 text-zinc-100" href="<?= admin_url('model/' . $model->name()) ?>"><?= __('No, take me back') ?></a>
</form>