<?php defined('WPINC') || die; ?>

<?php foreach ($addons as $title => $section) : ?>
<div class="glsr-card postbox">
    <div class="glsr-card-header">
        <h2><?= $title; ?></h2>
        <button type="button" class="handlediv" aria-expanded="true">
            <span class="screen-reader-text"><?= esc_html_x('Toggle documentation panel', 'admin-text', 'site-reviews'); ?></span>
            <span class="toggle-indicator" aria-hidden="true"></span>
        </button>
    </div>
    <div class="inside">
        <?= $section; ?>
    </div>
</div>
<?php endforeach; ?>
