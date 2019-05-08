<input type="hidden" id="ccf-progress-text" value="<?php esc_html_e( 'Uniqueness check in progress', 'content-copy-finder' ); ?>">
<?php if ( 'processing' == $status ) : ?>
    <div class="ccf_for_check">
        <?php esc_html_e( 'Uniqueness check in progress', 'content-copy-finder' ); ?><br/>
        <span class="button ccf-check-post-btn" data-check="0" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-id="<?php echo intval( $post_id ); ?>"><?php esc_html_e( 'Get results without page reload', 'content-copy-finder' ); ?></span>
    </div>
    <div id="ccf_result"></div>
<?php else : ?>
    <span class="button ccf-check-post-btn" data-check="1" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-id="<?php echo intval( $post_id ); ?>"><?php esc_html_e( 'Check text', 'content-copy-finder' ); ?></span>
    <?php if ( 'checked' == $status ) : ?>
        <div class="ccf_column_value">
            <p class="ccf_result"> <?php esc_html_e( 'Uniqueness', 'content-copy-finder' ); ?>: <?php echo esc_attr( $percent ); ?> %</p>
            <?php if ( $matches && isset( $matches[0]['url'] ) ) : ?>
                <table class='cw_results_table'>
                    <tr>
                        <th><?php esc_html_e( 'Page URL', 'content-copy-finder' ); ?></th>
                        <th><?php esc_html_e( 'Matches', 'content-copy-finder' ); ?></th>
                    </tr>
                    <?php foreach( $matches as $match ) : ?>
                        <tr>
                            <td><a href="<?php echo esc_attr( $match['url'] ); ?>" target="_blank"><?php echo esc_attr( urldecode( $match['url'] ) ); ?></a></td>
                            <td><?php echo esc_attr( $match['percent'] ) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    <?php elseif ( 'error' == $status ) : ?>
        <div class="ccf_column_value">
            <p class="ccf_result"> <?php esc_html_e( 'Check error', 'content-copy-finder' ); ?>: <?php echo esc_attr( $error_msg ); ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>