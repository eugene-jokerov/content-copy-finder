<?php
    $checked_text = '';
    if ( $matches && isset( $matches[0]['url'] ) ) {
        $words = explode( ' ', $text );
        if ( $highlight && is_array( $highlight ) ) {
            for ( $i = 0 ; $i < count( $highlight ) ; $i++ ) {
                $words[ $highlight[$i][0] ] = '<span class="ccf-match-word">' . $words[ $highlight[$i][0] ];
                $words[ $highlight[$i][1] ] = $words[ $highlight[$i][1] ] . '</span>';
            }
            $checked_text = join( ' ', $words );
        }
    }
?>
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
                <p>
                    <span class="button ccf-show-matches" data-show="<?php esc_html_e( 'Show matches', 'content-copy-finder' ); ?>" data-hide="<?php esc_html_e( 'Hide matches', 'content-copy-finder' ); ?>"><?php esc_html_e( 'Show matches', 'content-copy-finder' ); ?></span>
                </p>
                <p class="ccf-text-info">* <?php esc_html_e( 'Before checking, the server cleared the text from markup and formatting. Therefore, the result looks monotonous. This is necessary for greater accuracy in finding plagiarism. Non-unique text fragments are highlighted.', 'content-copy-finder' ); ?></p>
                <p class="ccf-text-matches">
                    <?php echo wp_kses_post( $checked_text ); ?>
                </p>
            <?php endif; ?>
        </div>
    <?php elseif ( 'error' == $status ) : ?>
        <div class="ccf_column_value">
            <p class="ccf_result"> <?php esc_html_e( 'Check error', 'content-copy-finder' ); ?>: <?php echo esc_attr( $error_msg ); ?></p>
        </div>
    <?php endif; ?>
<?php endif; ?>