<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Class to work with API Content-Watch service
 */ 
class Api {

    /** 
     * @var string Service API URL 
     */
    protected $api_url = 'https://content-watch.ru/public/api/';

    /**
     * Request to API service
     *
     * @param  array $params API params
     *
     * @return bool
     */
    public function request( array $params ) {
        $params = wp_parse_args( $params, array(
            'key'    => Plugin::component( 'settings' )->get_option( 'api_key', '' ),
            'source' => 'ContentCopyFinderWP',
            'test'   => ( defined( 'CCF_PLUGIN_DEBUG' ) && CCF_PLUGIN_DEBUG ) ? 1 : 0
        ) );

        $http_response = wp_remote_post( $this->api_url, array(
            'timeout' => 25,
            'body'    => $params,
        ) );
        if ( is_wp_error( $http_response ) ) {
            // обработать нестандартную ошибку
            return false;
        }

        if ( isset( $http_response['response'] ) && 200 == $http_response['response']['code'] ) {
            $response = json_decode( trim( $http_response['body'] ), true );
            return $response;
        }

        return false;
	}
}