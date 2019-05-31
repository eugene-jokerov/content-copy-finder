<?php
namespace JWP\CCF;
defined( 'ABSPATH' ) || exit;

/**
 * Single post check for uniqueness
 */
class Single_Check {

	/** 
     * @var string column name
     */
	private $column_name  = 'ccf_column';
	
	/** 
     * @var string nonce
     */
	private $column_nonce = '';

    /**
     * add hooks
     *
     * @return void
     */
    public function hooks() {
		$settings = Plugin::component( 'settings' );
        if ( 'on' == $settings->get_option( 'auto_check', 'off' ) ) {
			add_filter( 'save_post', array( $this, 'save_post' ), 10, 3 );
			add_action( 'ccf_scheduled_check', array( $this, 'check_post' ), 10, 1 );
        }
		$post_types = $settings->get_option( 'post_types', array() );
		$post_types = array_keys( $post_types );
		foreach ( $post_types as $post_type ) {
			add_filter( "manage_edit-{$post_type}_columns", array( $this, 'add_column' ) );
			add_filter( "manage_{$post_type}_posts_custom_column", array( $this, 'show_column_content' ), 5, 2 );
		}

		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
    }

    /**
     * after save post
     */
    public function save_post( $post_ID, $post, $update ) {
		if ( wp_is_post_revision( $post_ID ) || wp_is_post_autosave( $post_ID ) ) {
            return;
        }
		$post_types = Plugin::component( 'settings' )->get_option( 'post_types', array() );
		// пропускаем ненужные типы записей
		if ( ! isset( $post_types[ $post->post_type ] ) || 'on' != $post_types[ $post->post_type ] ) {
			return;
		}
		delete_post_meta( $post_ID, '_ccf_unique_percent' );
		delete_post_meta( $post_ID, '_ccf_matches' );
		delete_post_meta( $post_ID, '_ccf_error_msg' );
		update_post_meta( $post_ID, '_ccf_status', 'processing' );
		wp_schedule_single_event( date( 'U' ) + 2, 'ccf_scheduled_check', array( $post_ID ) );
	}

	/**
     * add column filter
     */
	public function add_column( $columns ) {
        $columns[ $this->column_name ] = esc_html__( 'Content Copy Finder', 'content-copy-finder' );
        return $columns;
	}

	/**
	 * get column name
	 *
	 * @return string
	 */
	public function get_column_name() {
		return $this->column_name;
	}
	
	/**
	 * render column content
	 *
	 * @param  string $column_name
	 * @param  int $post_id
	 *
	 * @return void
	 */
	public function show_column_content( $column_name, $post_id ) {
        if ( $this->column_name != $column_name ) {
            return;
		}
		$status    = get_post_meta( $post_id, '_ccf_status', true );
		$percent   = 0;
		$date      = 0;
		$error_msg = '';
		if ( 'checked' == $status ) {
			$percent = get_post_meta( $post_id, '_ccf_unique_percent', true );
			$date    = get_post_meta( $post_id, '_ccf_last_check_date', true );
		} elseif ( 'error' == $status ) {
			$error_msg = get_post_meta( $post_id, '_ccf_error_msg', true );
		}
		if ( ! $this->column_nonce ) {
			$this->column_nonce = wp_create_nonce( 'ccf_check_post' );
		}
		View::render( 'column', array(
			'post_id'   => $post_id,
			'status'    => $status,
			'percent'   => $percent,
			'date'      => $date,
			'error_msg' => $error_msg,
			'nonce'     => $this->column_nonce
		) );
	}
	
	/**
	 * add metabox
	 *
	 * @return void
	 */
	public function add_metabox() {
		$post_types = Plugin::component( 'settings' )->get_option( 'post_types', array() );
		$screens = array_keys( $post_types );
        foreach ( $screens as $screen ) {
            add_meta_box( 'content_copy_finder', esc_html__( 'Content Copy Finder', 'content-copy-finder' ), array( $this, 'metabox_content' ), $screen, 'normal', 'high' );
        }
    }

    /**
     * render metabox content
     *
     * @param  int $post_id
     *
     * @return void
     */
    public function metabox_content( $post_id = 0 ) {
		if ( ! $post_id ) {
			global $post;
		} else {
			$post = get_post( $post_id );
		}
		if ( ! $post || ! isset( $post->ID ) ) {
			return false;
		}
		
		$status    = get_post_meta( $post->ID, '_ccf_status', true );
		$percent   = 0;
		$matches   = false;
		$highlight = array();
		$text      = '';
		$error_msg = '';
		if ( 'checked' == $status ) {
			$percent   = get_post_meta( $post->ID, '_ccf_unique_percent', true );
			$matches   = get_post_meta( $post->ID, '_ccf_matches', true );
			$highlight = get_post_meta( $post->ID, '_ccf_highlight', true );
			$text      = get_post_meta( $post->ID, '_ccf_text', true );
			if ( ! $matches ) {
				return '';
			}
			$matches = json_decode( $matches, true );
		} elseif ( 'error' == $status ) {
			$error_msg = get_post_meta( $post->ID, '_ccf_error_msg', true );
		}

		View::render( 'metabox', array(
			'post_id'   => $post->ID,
			'status'    => $status,
			'percent'   => $percent,
			'matches'   => $matches,
			'highlight' => $highlight,
			'text'      => $text,
			'error_msg' => $error_msg,
			'nonce'     => wp_create_nonce( 'ccf_check_post' )
		) );
	}
	
	/**
	 * check post for uniqueness and save results
	 *
	 * @param  int $post_id
	 * @param  string $text
	 *
	 * @return bool|array
	 */
	public function check_post( $post_id, $text = null ) {
        $post_id = intval( $post_id );
        if ( is_null( $text ) ) {
			$post = get_post( $post_id );
			if ( ! $post || is_wp_error( $post ) ) {
				return array(
					'status'    => 'error',
					'percent'   => 0,
					'error_msg' => esc_html__( 'Post not found', 'content-copy-finder' )
				);
			}
            $text = $post->post_content;
        }
		
        $responce = Plugin::component( 'api' )->request( array(
			'text'   => $text, 
			'ignore' => get_permalink( $post_id )
		) );
		$matches   = json_encode( array() );
		$status    = 'error';
		$error_msg = false;
		$unique_percent = 0;
        if ( ! isset( $responce['error'] ) ) {
			$error_msg = esc_html__( 'Uniqueness Check Request Error', 'content-copy-finder' );
        } elseif ( ! empty( $responce['error'] ) ) {
			$error_msg = $responce['error'];
        } else {
			$matches = wp_slash( json_encode( $responce['matches'] ) );
			$status  = 'checked';
			$unique_percent = $responce['percent'];
			update_post_meta( $post_id, '_ccf_unique_percent', $unique_percent );
			update_post_meta( $post_id, '_ccf_highlight', $responce['highlight'] );
			update_post_meta( $post_id, '_ccf_text', $responce['text'] );
			delete_post_meta( $post_id, '_ccf_error_msg' );
		}
		if ( $error_msg ) {
			update_post_meta( $post_id, '_ccf_error_msg', $error_msg );
			delete_post_meta( $post_id, '_ccf_text' );
		}
		update_post_meta( $post_id, '_ccf_status', $status );
		update_post_meta( $post_id, '_ccf_matches', $matches );
		update_post_meta( $post_id, '_ccf_last_check_date', date( 'U' ) );

        return array(
			'status'    => $status,
			'percent'   => esc_html( $unique_percent ),
			'error_msg' => esc_html( $error_msg )
		);
    }
}