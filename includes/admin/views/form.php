<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
    <?php echo settings_fields( 'ccf_settings' ); ?>
    <?php echo do_settings_sections( 'content-copy-finder' ); ?>
	<?php submit_button(); ?>
</form>

