<?php


spl_autoload_register( function( $class ) {
    // project-specific namespace prefix
    $prefix = 'JWP\\CCF\\';

    // does the class use the namespace prefix?
    $len = strlen( $prefix );
    if ( 0 !== strncmp( $prefix, $class, $len ) ) {
        return; // no, move to the next registered autoloader
    }
    
    // base directory for the namespace prefix
    $base_dir = CCF_PLUGIN_PATH . '/includes/classes/';

    // get the relative class name
    $relative_class = substr( $class, $len );

    $relative_class = str_replace( '\\', '/', $relative_class );
    $relative_class = explode( '/', $relative_class );
    if ( is_array( $relative_class ) ) {
        $class_name_index = count( $relative_class ) - 1;
        $relative_clas_name = $relative_class[ $class_name_index ];
        $relative_clas_name = strtolower( $relative_clas_name );
        $relative_clas_name = str_replace( '_', '-', $relative_clas_name );
        $relative_class[ $class_name_index ] = 'class-' . $relative_clas_name;
        $relative_class = join( '/', $relative_class );
    }

    $class_file_path = $base_dir . $relative_class . '.php';

    if ( file_exists( $class_file_path ) ) {
        include $class_file_path;
    }
} );
