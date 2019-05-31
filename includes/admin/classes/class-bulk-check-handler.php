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
        $post_type  = $request->get_custom_data( 'post_type' );
        $check_type = $request->get_custom_data( 'check_type' );
        $post_type  = sanitize_text_field( $post_type );
        $check_type = sanitize_text_field( $check_type );
        $posts_args = array(
            'post_type'   => $post_type,
            'numberposts' => $this->max_process_elements,
            'post_status' => $this->post_statuses( $request ),
            'offset'      => $request->get( 'offset' ),
        );
        $posts_args = $this->check_type_filter( $check_type, $posts_args );
        $posts = get_posts( $posts_args );
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
        $post_type  = $request->get_custom_data( 'post_type' );
        $check_type = $request->get_custom_data( 'check_type' );
        $post_type  = sanitize_text_field( $post_type );
        $check_type = sanitize_text_field( $check_type );
        $posts_args = array( 
            'post_type'      => $post_type,
            'post_status'    => $this->post_statuses( $request ),
            'posts_per_page' => 1,
        );
        $posts_args = $this->check_type_filter( $check_type, $posts_args );
        $total = new \WP_Query( $posts_args );
        return $total->found_posts;
    }

    /**
     * Возвращает выбранные статусы записи
     *
     * @param  JWP\CCF\DH\Request $request Объект запроса
     *
     * @return array
     */
    protected function post_statuses( $request ) {
        $checked_post_statuses = $request->get_custom_data( 'post_statuses' );
        if ( ! $checked_post_statuses ) {
            return array();
        }
        $post_statuses = get_post_statuses();
        $real_post_statuses = array();
        foreach ( $post_statuses as $key => $value ) {
            if ( in_array( $key, $checked_post_statuses ) ) {
                $real_post_statuses[] = $key;
            }
        }
        return $real_post_statuses;
    }

    /**
     * Добавляет тип проверки
     *
     * @param  string $check_type тип проверки
     * @param  array $args параметры запроса
     *
     * @return array
     */
    protected function check_type_filter( $check_type, $args ) {
        if ( ! $check_type || 'all' == $check_type ) {
            return $args;
        }
        $meta_query = false;
        if ( 'unchecked' == $check_type ) {
            $meta_query = array(
                array(
                    'key'     => '_ccf_status',
                    'compare' => 'NOT EXISTS'
                )
            );
        } elseif ( 'checked' == $check_type ) {
            $meta_query = array(
                array(
                    'key'   => '_ccf_status',
                    'value' => 'checked'
                )
            );
        } elseif ( 'error' == $check_type ) {
            $meta_query = array(
                array(
                    'key'   => '_ccf_status',
                    'value' => 'error'
                )
            );
        }

        if ( $meta_query ) {
            $args['meta_query'] = $meta_query;
        }

        return $args;
    }
}