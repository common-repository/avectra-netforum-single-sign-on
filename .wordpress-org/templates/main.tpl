<div class="wrap">
    <?= do_action('nf_logo'); ?>

    <h2><?= do_action('nf_head'); ?></h2>

    <h2 class='nav-tab-wrapper'>
        <?= do_action('nf_tabs'); ?>
    </h2>

    <form method="post" action="">
        <?= do_action('nf_body'); ?>
    </form>

    <?= do_action('nf_foot'); ?>

</div>