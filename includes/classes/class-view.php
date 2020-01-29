<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Class to work with templates
 */ 
class View {
    
    /**
     * Render template file
     *
     * @param  string $template_name Name of template
     * @param  array $params varibles
     *
     * @return void
     */
    public static function render( $template_name, $params = array() ) {
        $template_name = str_replace( '..', '.', $template_name );
        $template_file = CCF_PLUGIN_PATH . '/views/' . $template_name . '.php';
		if ( ! file_exists( $template_file ) ) {
			wp_die( 'template file not found' );
        }
        if ( $params && is_array( $params ) ) {
            extract( $params );
        }
        include $template_file;
    }
}
