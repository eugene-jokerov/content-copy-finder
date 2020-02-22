<?php
/**
* Plugin Name: Content Copy Finder
* Description: Check text for uniqueness and plagiarism search
* Version: 1.2.2
* Author: Eugene Jokerov
* License: GPLv2
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain: content-copy-finder
* Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() && ! ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
	return;
}

add_action( 'init', function() {
	load_plugin_textdomain( 'content-copy-finder', false, basename( dirname( __FILE__ ) ) . '/languages' );
} );

if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
	add_action( 'admin_notices', function() {
		/* translators: %s: PHP version */
		$message = sprintf( esc_html__( 'Content Copy Finder requires PHP version %s+, plugin is currently NOT RUNNING.', 'content-copy-finder' ), '5.6' );
		$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	} );
	return;
}
if ( version_compare( get_bloginfo( 'version' ), '4.9', '<' ) ) {
	add_action( 'admin_notices', function() {
		/* translators: %s: WordPress version */
		$message = sprintf( esc_html__( 'Content Copy Finder requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'content-copy-finder' ), '4.9' );
		$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
		echo wp_kses_post( $html_message );
	} );
	return;
}

if ( ! defined( 'CCF_PLUGIN_VERSION' ) ) {
	define( 'CCF_PLUGIN_VERSION', '1.2.2' );
}

if ( ! defined( 'CCF_PLUGIN_PATH' ) ) {
	 define( 'CCF_PLUGIN_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'CCF_PLUGIN_FILE' ) ) {
	define( 'CCF_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'CCF_PLUGIN_URL' ) ) {
	 define( 'CCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! class_exists( '\JWP\CCF\Plugin' ) ) {
	include_once CCF_PLUGIN_PATH . '/includes/autoload.php';
	\JWP\CCF\Plugin::instance();
}

