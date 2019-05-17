<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Class for working with settings
 */ 
class Settings {

    /** 
     * @var string Plugin option name. Option use to save settings
     */
    protected $settings_group     = 'ccf_settings';

    /** 
     * @var string settings page slug
     */
    protected $settings_page_slug = 'content-copy-finder';

    /**
     * add hooks
     *
     * @return void
     */
    public function hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register' ) );

        add_filter( "plugin_action_links_content-copy-finder/{$this->settings_page_slug}.php", array( $this, 'plugin_action_links' ) );
		add_filter( "pre_update_option_{$this->settings_group}", function( $value, $old_value ) {
			delete_transient( 'ccf_balance' ); // сбрасываем кеш баланса
			if ( isset( $value['api_key'] ) ) {
				// если новый api key со звёздочками, то сохраняем старое значение
				$pos = strpos( $value['api_key'], '***' );
				if ( false !== $pos ) {
					$value['api_key'] = isset( $old_value['api_key'] ) ? $old_value['api_key'] : $value['api_key'];
				}
			}
			return $value;
		}, 10, 2);
    }

    /**
     * get plugin option name
     *
     * @return string
     */
    public function get_settings_group() {
        return $this->settings_group;
    }

    /**
     * get settings page slug
     *
     * @return string
     */
    public function get_settings_page_slug() {
        return $this->settings_page_slug;
    }

    /**
     * add settings link to plugin action links
     *
     * @param  array $actions
     *
     * @return array
     */
    public function plugin_action_links( $actions ) {
        $settings_page_url = admin_url( 'options-general.php?page=' . esc_attr( $this->settings_page_slug ) );
        $link_html = '<a href="' . $settings_page_url . '">' . esc_html__( 'Settings', 'content-copy-finder' ) . '</a>';
        return array_merge( array(
            'settings' => $link_html,
        ), $actions );
    }

    /**
     * Add link to WP admin menu and register scripts
     *
     * @return void
     */
    public function admin_menu() {
		$page = add_submenu_page(
			'options-general.php', 
			__( 'Content Copy Finder', 'content-copy-finder' ), 
			__( 'Content Copy Finder', 'content-copy-finder' ), 
			'manage_options', 
			$this->get_settings_page_slug(), 
			array( $this, 'page' ) 
		);
		wp_register_script( 
			'ccf-admin-js', 
			CCF_PLUGIN_URL . 'assets/admin/js/ccf.js', 
			array( 'jquery' ), 
			CCF_PLUGIN_VERSION 
        );
        wp_register_script( 
			'ccf-admin-settings-js', 
			CCF_PLUGIN_URL . 'assets/admin/js/settings.js', 
			array( 'jquery' ), 
			CCF_PLUGIN_VERSION 
        );
        wp_register_style( 
            'ccf-admin-css', 
            CCF_PLUGIN_URL . 'assets/admin/css/ccf.css', 
            array(), 
            CCF_PLUGIN_VERSION 
        );

        add_action( "admin_print_scripts-settings_page_{$this->settings_page_slug}", function() {
			wp_enqueue_script( 'ccf-admin-settings-js' );
		} );

		add_action( 'admin_print_scripts', function() {
			$screen     = get_current_screen();
            $post_types = $this->get_option( 'post_types', array() );
            $post_types = array_keys( $post_types );
			if ( is_object( $screen ) && in_array( $screen->post_type, $post_types ) ) {
                wp_enqueue_script( 'ccf-admin-js' );
                wp_enqueue_style( 'ccf-admin-css' );
			}
		} );
	}
	
	/**
	 * Admin page render template
	 *
	 * @return void
	 */
	public function page() {
        $is_api_key_exists = false;
        if ( $this->get_option( 'api_key', '' ) ) {
            $is_api_key_exists = true;
        }
        View::render( 'settings', array(
            'is_api_key_exists' => $is_api_key_exists,
        ) );
	}

    /**
     * register settings
     *
     * @return void
     */
    public function register() {
        register_setting(
            $this->settings_group,
            $this->settings_group
        );

        // Добавляем секцию
        add_settings_section(
            'ccf_section',
            esc_html__( 'Settings', 'content-copy-finder' ),
            '',
            $this->settings_page_slug
        );

        add_settings_field(
            'api_key',
            __( 'API Key', 'content-copy-finder' ),
            array( $this, 'display_input_field' ),
            $this->settings_page_slug,
            'ccf_section',
            array(
                'type'      => 'text',
                'id'        => 'api_key',
                'required'  => false,
                'desc'      => esc_html__( 'may take on page', 'content-copy-finder' ) . ' <a href="https://content-watch.ru/api/" target="_blank">https://content-watch.ru/api/</a>',
                'label_for' => 'api_key'
            )
        );

        add_settings_field(
            'auto_check',
            esc_html__( 'Auto ckeck', 'content-copy-finder' ),
            array( $this, 'display_input_field' ),
            $this->settings_page_slug,
            'ccf_section',
            array(
                'type' => 'radio',
                'id'   => 'auto_check',
                'vals' => array(
                    'on'  => esc_html__( 'Check posts when adding and editing', 'content-copy-finder' ),
                    'off' => esc_html__( 'Do not automatically check', 'content-copy-finder' ),
                )
            )
        );

        $post_types = get_post_types( array(
            'public'   => true,
            '_builtin' => true
        ), 'objects' );
        $supported_post_types = array();
        if ( is_array( $post_types ) && $post_types ) {
            foreach ( $post_types as $post_type_obj ) {
                if ( 'attachment' == $post_type_obj->name ) {
                    continue;
                }
                $supported_post_types[ $post_type_obj->name ] = $post_type_obj->label;
            }
        }
        
        add_settings_field(
            'post_types',
            esc_html__( 'Post types', 'content-copy-finder' ),
            array( $this, 'display_input_field' ),
            $this->settings_page_slug,
            'ccf_section',
            array(
                'type' => 'checkbox',
                'id'   => 'post_types',
                'vals' => $supported_post_types,
            )
        );
    }
    
    /**
     * get option
     *
     * @param  string $name
     * @param  mixed $default
     *
     * @return mixed
     */
    public function get_option( $name, $default = '' ) {
        $options = get_option( $this->settings_group );

        if ( empty( $options ) || ! is_array( $options ) ) {
            return $default;
        }

        return isset( $options[ $name ] ) ? $options[ $name ] : $default;
    }

    /*
     * Input field display
     * 
     * @param  array $args field params
     * 
     * @return void
     */
    public function display_input_field( $args ) {
        $option = $this->get_option( $args['id'] );
        switch ( $args['type'] ) {
            case 'text':
                $text_value = $option;
                if ( 'api_key' == $args['id'] ) {
                    if ( $text_value ) {
                        $text_value = '*********************'; // скрываем ключ
                    }
                }
                View::render( 'fields/text', array(
                    'field_name'  => $args['id'],
                    'field_value' => $text_value,
                    'group_name'  => $this->settings_group,
                    'description' => $args['desc'],
                ) );
                break;
            case 'checkbox':
                View::render( 'fields/checkbox', array(
                    'field_name' => $args['id'],
                    'values'     => $args['vals'],
                    'option'     => $option,
                    'group_name' => $this->settings_group,
                ) );
                break;
            case 'radio':
                View::render( 'fields/radio', array(
                    'field_name' => $args['id'],
                    'values'     => $args['vals'],
                    'option'     => $option,
                    'group_name' => $this->settings_group,
                ) );
                break;
        }
	}
	
}