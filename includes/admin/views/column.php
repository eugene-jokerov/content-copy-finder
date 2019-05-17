<span class="ccf-column-content" data-progress-text="<?php _e( 'Uniqueness check in progress', 'content-copy-finder' ); ?>">
    <?php
        if ( 'checked' == $status ) {
            echo esc_html__( 'Uniqueness', 'content-copy-finder' )  . ': ' . $percent . '%<br/>' . esc_html__( 'text checked', 'content-copy-finder' ) . ' <abbr title="' . date( "d.m.Y H:i:s", $date ) . '">' . date( "d.m.Y", $date ) . '</abbr>';
        } elseif ( 'processing' == $status ) {
            echo esc_html__( 'Uniqueness check in progress', 'content-copy-finder' );
        } elseif ( 'error' == $status ) {
            echo esc_html__( 'Check error', 'content-copy-finder' ) . ':</br>';
            echo esc_attr( $error_msg );
        } else {
            echo esc_html__( 'Not yet verified', 'content-copy-finder' );
        }
    ?>
	<br/><a href="#" class="ccf-check-btn" data-check="1" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-id="<?php echo intval( $post_id ); ?>"><?php esc_html_e( 'Check', 'content-copy-finder' ); ?></a>
</span>