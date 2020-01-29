<?php
use JWP\CCF\Plugin;
defined( 'ABSPATH' ) || exit;

/**
 * Check balance AJAX action
 */ 
add_action( 'wp_ajax_ccf_check_balance', function() {
    check_ajax_referer( 'ccf_balance' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die();
    }
    $force   = ( isset( $_POST['force'] ) && $_POST['force'] ) ? true : false;
    $balance = get_transient( 'ccf_balance' );
    if ( false === $balance || $force ) {
        $response = Plugin::component( 'api' )->request( array(
            'action' => 'GET_BALANCE',
        ) );

        if ( ! empty( $response['error'] ) ) {
            delete_transient( 'ccf_balance' );
            wp_send_json_error( array(
                'message' => esc_html__( 'API Error:', 'content-copy-finder' ) . ' (' . wp_kses_post( $response['error'] ) . ')'
            ) );
        }

        $balance = array(
            'balance'  => floatval( $response['balance'] ),
            'tarif'    => floatval( $response['tariff'] ),
            'limit'    => 0,
            'currency' => esc_html__( 'rub.', 'content-copy-finder' ),
        );
        if ( $balance['tarif'] <= 0 ) {
            $balance['limit'] = 0;
        } else {
           $balance['limit'] = floor( $balance['balance'] / $balance['tarif'] ); 
        }
        set_transient( 'ccf_balance', $balance, HOUR_IN_SECONDS );
    }

    wp_send_json_success( $balance );
} );

/**
 * Check post AJAX action
 */
add_action( 'wp_ajax_ccf_check_post_by_id', function() {
    check_ajax_referer( 'ccf_check_post' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die();
    }
    $post_id = intval( $_POST['post_id'] );
    $from = isset( $_POST['from'] ) ? sanitize_text_field( $_POST['from'] ) : 'metabox';
    $check = ( isset( $_POST['check'] ) && $_POST['check'] ) ? true : false;
    if ( 'metabox' != $from ) {
        $from = 'column';
    }
    $single_check = Plugin::component( 'single_check' );
    if ( $check ) {
        $single_check->check_post( $post_id );
        delete_transient( 'ccf_balance' ); // сбрасываем кеш баланса
    }

    if ( 'column' == $from ) {
        $column_name = $single_check->get_column_name();
        $single_check->show_column_content( $column_name, $post_id );
    } elseif ( 'metabox' == $from ) {
        $single_check->metabox_content( $post_id );
    }
    wp_die();
} );