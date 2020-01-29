<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Bulk post check for uniqueness
 */
class Bulk_Check {

	/** 
     * @var string settings page slug
     */
	protected $page_slug = 'ccf-bulk-check';
	
	/**
     * add hooks
     *
     * @return void
     */
    public function hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

	/**
     * Add link to WP admin menu and register scripts
     *
     * @return void
     */
    public function admin_menu() {
		$page = add_submenu_page(
			'tools.php', 
			__( 'Bulk check', 'content-copy-finder' ), 
			__( 'Bulk check', 'content-copy-finder' ), 
			'manage_options', 
			$this->page_slug, 
			array( $this, 'page' ) 
		);

		add_action( "admin_print_scripts-tools_page_{$this->page_slug}", function() {
			wp_enqueue_script( 'jquery-ui-core', false, array( 'jquery' ) );
			wp_enqueue_script( 'jquery-ui-progressbar', false, array( 'jquery' ) );
			wp_enqueue_script( 
				'ccf-bulk-js', 
				CCF_PLUGIN_URL . 'assets/admin/js/bulk.js', 
				array( 'jquery' ), 
				CCF_PLUGIN_VERSION 
			);

			wp_enqueue_style( 'jquery-ui-theme-base', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
			wp_enqueue_style( 
				'ccf-bulk-css', 
				CCF_PLUGIN_URL . 'assets/admin/css/bulk.css', 
				array( 'jquery-ui-theme-base' ), 
				CCF_PLUGIN_VERSION 
			);
		} );

        Plugin::component( 'dh' )->register_work_page( $page );
    }
    
    /**
	 * Admin page render template
	 *
	 * @return void
	 */
	public function page() {
		$class = 'JWP\CCF\Bulk_Check_Handler';
		$params = array(
			'template_file' => CCF_PLUGIN_PATH . '/views/bulk-check.php'
		);
        Plugin::component( 'dh' )->render_handler_page( $class, $params );
	}
}
