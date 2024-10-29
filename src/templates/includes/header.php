<header class="bg-teal-600 px-4 md:px-6 h-[90px] md:h-[50px] flex items-center relative z-30">
    <div class="w-full text-center md:flex justify-between items-center">
        <a fire href="<?= admin_url() ?>"
            class="text-xl md:text-2xl lg:text-3xl text-amber-200 font-light"><?= __('Administration') ?></a>
        <p class="text-amber-200 mt-2 md:mt-0 font-light text-xs lg:text-sm uppercase"><?= __('Welcome') ?>,
            <b><?= user('name') ?></b>. <a href="<?= url() ?>"
                class="text-gray-100 border-b border-dotted hover:border-none"><?= __('View Site') ?></a> <span
                class="opacity-75">/</span> <a fire href="<?= admin_url('settings') ?>"
                class="text-gray-100 border-b border-dotted hover:border-none"><?= __('Settings') ?></a> <span
                class="opacity-75">/</span> <button onclick="document.getElementById('logoutForm').submit()"
                class="text-gray-100 border-b border-dotted hover:border-none uppercase"><?= __('Logout') ?></button>
        </p>
    </div>
</header>
<form action="<?= admin_url('logout') ?>" id="logoutForm" style="display: none;" method="post">
    <?= csrf() ?>
</form>