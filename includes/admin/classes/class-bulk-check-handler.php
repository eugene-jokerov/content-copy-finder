<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Обработчик массовой проверки на уникальность
 * Используется JWP DH Core
 */
class Bulk_Check_Handler extends DH\Handler {

    /**
     * @var string заголовок обработчика. В шаблоне может не использоваться.
     */
    public $title = 'Обработка записей';
    
    /**
     * @var int кол-во элементов, обрабатываемых за 1 запрос
     */
    public $max_process_elements = 1;
    
    /**
     * Обработка данных
     *
     * @param JWP\CCF\DH\Request $request Объект запроса
     * @param JWP\CCF\DH\Response $response Объект ответа
     * @return void
     */
    public function process( $request, $response ) {
        $post_type = $request->get_custom_data( 'post_type' );
        $post_type = sanitize_text_field( $post_type );
        $posts = get_posts( array(
            'post_type'   => $post_type,
            'numberposts' => $this->max_process_elements,
            'post_status' => 'publish',
            'offset'      => $request->get( 'offset' ),
        ) );
        if ( isset( $posts[0] ) ) {
            $post = $posts[0];
            $post_id = $post->ID;
            $results = Plugin::component( 'single_check' )->check_post( $post_id );
            $results = wp_parse_args( $results, array(
                'post_title' => esc_attr( $post->post_title )
            ) );
            $response->output( $results );
        }
    }
    
    /**
     * Подсчёт общего количества элементов
     *
     * @param  JWP\CCF\DH\Request $request Объект запроса
     *
     * @return int
     */
    public function total( $request ) {
        $post_type = $request->get_custom_data( 'post_type' );
        $post_type = sanitize_text_field( $post_type );
        $posts_args = array( 
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 1,
        );
        $total = new \WP_Query( $posts_args );
        return $total->found_posts;
    }
}