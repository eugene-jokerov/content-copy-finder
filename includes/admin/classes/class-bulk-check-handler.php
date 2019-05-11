<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

class Bulk_Check_Handler extends DH\Handler {
    public $title = 'Обработка записей';
		
    public $max_process_elements = 1;
    
    public function process( $request, $response ) {
        $post_type = $request->get_custom_data( 'post_type' );
        $posts = get_posts( array(
            'post_type'   => $post_type,
            'numberposts' => $this->max_process_elements,
            'post_status' => 'publish',
            'offset'      => $request->get( 'offset' ),
        ) );
        if ( isset( $posts[0] ) ) {
            $post = $posts[0];
            $post_id = $post->ID;
            //$results = Plugin::component( 'single_check' )->check_post( $post_id );
            $results = array(
                'status'    => 'checked',
                'percent'   => rand(1,100),
                'error_msg' => '',
            );
            $results = wp_parse_args( $results, array(
                'post_title' => $post->post_title
            ) );
            sleep(1);
            $response->output( $results );
        }
        return $response;
    }
    
    public function total( $request ) {
        $post_type = $request->get_custom_data( 'post_type' );
        $posts_args = array( 
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 1,
        );
        $total = new \WP_Query( $posts_args );
        return $total->found_posts;
    }
}