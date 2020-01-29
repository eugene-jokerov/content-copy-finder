<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Plugin main class
 */
final class Plugin {
	
	/**
	 * @var object singleton instance
	 */
	private static $_instance = null;

	/**
	 * @var JWP\CCF\Component_Manager component manages
	 */
	private $component_manager = null;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 */
	protected function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'content-copy-finder' ), '1.0.0' );
	}
	
	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 */
	protected function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Something went wrong.', 'content-copy-finder' ), '1.0.0' );
	}
	
	/**
	 * Singleton instance
	 *
	 * @return JWP\CCF\Plugin
	 */
	static public function instance() {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Quick access to system components
	 *
	 * @param  string $component_name component name
	 * 
	 * @example Plugin::component( $component_name );
	 *
	 * @return object component instance
	 */
	static public function component( $component_name ) {
		$plugin = self::instance();
		$component = $plugin->component_manager()->get( $component_name );
		return $component;
	}
	
	/**
	 * Load scripts and add actions
	 * 
	 * @return void
	 */
	private function __construct() {
		$this->include_scripts();
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Plugin init
	 *
	 * @return void
	 */
	public function init() {
		if ( ! wp_doing_cron() && ! current_user_can( 'manage_options' ) ) {
			return;
		}
		register_deactivation_hook( CCF_PLUGIN_FILE, function() {
			wp_unschedule_hook( 'ccf_scheduled_check' );
		} );
		$this->register_components();
		Plugin::component( 'settings' )->hooks();
		Plugin::component( 'single_check' )->hooks();
		Plugin::component( 'bulk_check' )->hooks();
	}

	/**
	 * register components
	 *
	 * @return void
	 */
	protected function register_components() {
		$this->component_manager()->add_components( array(
			'settings' => array(
				'class' => 'JWP\CCF\Settings'
			),
			'api' => array(
				'class' => 'JWP\CCF\Api'
			),
			'single_check' => array(
				'class' => 'JWP\CCF\Single_Check',
			),
			'bulk_check' => array(
				'class' => 'JWP\CCF\Bulk_Check',
			)
		) );
		$dh = DH\Core::instance(); // JWP data handler
		$this->component_manager()->add( 'dh', $dh );
	}

	/**
	 * component manager
	 *
	 * @return JWP\CCF\Component_Manager
	 */
	public function component_manager() {
		if( is_null( $this->component_manager ) ) {
			$this->component_manager = new Component_Manager;
		}
		return $this->component_manager;
	}

	/**
	 * load classes and scripts
	 */
	protected function include_scripts() {
		$dh_path = CCF_PLUGIN_PATH . '/libs/jwp-dh-core/jwp-dh.php';
		if ( file_exists( $dh_path ) ) {
			include_once $dh_path;
		}
		
		if ( wp_doing_ajax() ) {
			include_once CCF_PLUGIN_PATH . '/includes/ajax/actions.php';
		}
	}
	
}
