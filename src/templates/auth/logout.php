<?php
$this->layout('auth/layout')
    ->set('title', 'Logged Out - administration');;
?>
<section class="px-4 md:px-8 lg:px-10 xl:px-20 py-3 md:py-4 lg:py-6 xl:py-10">
    <h3 class="text-lg md:text-xl lg:text-2xl font-light mb-4 md:mb-5"><?= __('Logged out') ?></h3>
    <p class="text-sm font-semibold mb-3 md:mb-4"><?= __('Thank you for spending time with us today! We hope you had a great experience and look forward to your next visit.') ?></p>
    <a class="text-teal-500 hover:text-teal-600 hover:border-b border-dotted border-teal-400" href="<?= admin_url('login') ?>"><?= __('Login again') ?></a>
</section>