<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Component manager provides access to system components
 */ 
class Component_Manager {
    
    /** 
     * @var array system components
     */
    private $components = array();

     /** 
     * @var array components params. Used to load on demand.
     */
    private $_lazy_load = array();

    /**
     * Get component by name
     *
     * @param  string $component_name name of component
     *
     * @return object|null component instance
     */
    public function get( $component_name ) {
        if ( ! isset( $this->components[ $component_name ] ) ) {
            $params = isset( $this->_lazy_load[ $component_name ] ) ? $this->_lazy_load[ $component_name ] : false;
            if ( ! $params ) {
                return null; // заменить на выброс искллючения
            }
            if ( ! isset( $params['class'] ) || empty( $params['class'] ) ) {
                return null; // заменить на выброс искллючения
            }
            if ( ! class_exists( $params['class'] ) ) {
                return null; // заменить на выброс искллючения
            }
			$this->components[ $component_name ] = new $params['class'];
		}
        return $this->components[ $component_name ];
    }

    /**
     * register component
     *
     * @param  string $component_name name of component
     * @param  array|object $params component params or component object
     *
     * @return void
     */
    public function add( $component_name, $params = array() ) {
        if ( is_array( $params ) ) {
            $this->_lazy_load[ $component_name ] = $params;
            $this->components[ $component_name ] = null;
        } elseif ( is_object( $params ) ) {
            $this->components[ $component_name ] = $params;
        }
    }

    /**
     * Registration of several components
     *
     * @param  array $components_data component params
     *
     * @return void
     */
    public function add_components( $components_data ) {
        foreach ( $components_data as $key => $params ) {
            $this->add( $key, $params );
        }
    }
}