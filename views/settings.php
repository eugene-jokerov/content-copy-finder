<div class="wrap ccf-settings-page">
    <h2><?php esc_html_e( 'Content Copy Finder', 'content-copy-finder' ); ?></h2>
    <p><?php esc_html_e( 'The plugin allows you to check the text of your records for uniqueness and find copies on other sites.', 'content-copy-finder' ); ?></p>
    <p><?php esc_html_e( 'For check use API', 'content-copy-finder' ); ?> <a href="https://content-watch.ru/api/" target="_blank">https://content-watch.ru/api/</a></p>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'options-general.php?page=content-copy-finder' ) ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Settings', 'content-copy-finder' ); ?></a>
        <a href="<?php echo admin_url( 'tools.php?page=ccf-bulk-check' ); ?>" class="nav-tab"><?php esc_html_e( 'Bulk check', 'content-copy-finder' ); ?></a>
	</h2>

    <?php if ( $is_api_key_exists ) : ?>
        <h2><?php esc_html_e( 'Account', 'content-copy-finder' ); ?></h2>
        <?php self::render( 'account' ); ?>
    <?php endif; ?>

    <?php self::render( 'form' ); ?>

    <h2><?php esc_html_e( 'Scheme of work', 'content-copy-finder' ); ?></h2>
    <?php self::render( 'steps' ); ?>
</div>
