<div class="wrap ccf-settings-page">
    <h2><?php esc_html_e( 'Content Copy Finder', 'content-copy-finder' ); ?></h2>
    <p><?php esc_html_e( 'The plugin allows you to check the text of your records for uniqueness and find copies on other sites.', 'content-copy-finder' ); ?></p>
    <p><?php esc_html_e( 'For check use API', 'content-copy-finder' ); ?> <a href="https://content-watch.ru/api/" target="_blank">https://content-watch.ru/api/</a></p>

    <?php if ( $is_api_key_exists ) : ?>
        <h2><?php esc_html_e( 'Account', 'content-copy-finder' ); ?></h2>
        <?php include CCF_PLUGIN_PATH . '/includes/admin/views/account.php'; ?>
    <?php endif; ?>

    <?php include CCF_PLUGIN_PATH . '/includes/admin/views/form.php'; ?>

    <h2><?php esc_html_e( 'Scheme of work', 'content-copy-finder' ); ?></h2>
    <?php include CCF_PLUGIN_PATH . '/includes/admin/views/steps.php'; ?>
</div>