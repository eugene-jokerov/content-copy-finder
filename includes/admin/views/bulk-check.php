<div class="wrap ccf-bulk-page">
    <h2><?php esc_html_e( 'Bulk check', 'content-copy-finder' ); ?></h2>
    <p><?php esc_html_e( 'The plugin allows you to check the text of your records for uniqueness and find copies on other sites.', 'content-copy-finder' ); ?></p>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'options-general.php?page=content-copy-finder' ) ?>" class="nav-tab"><?php esc_html_e( 'Settings', 'content-copy-finder' ); ?></a>
        <a href="<?php echo admin_url( 'tools.php?page=ccf-bulk-check' ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'Bulk check', 'content-copy-finder' ); ?></a>
	</h2>

    <table class="form-table ccf-bulk-check-settings">
        <tbody>
        <tr>
            <th scope="row" valign="top">
                <label for="cron_internal_name"><?php esc_html_e( 'Select post type', 'content-copy-finder' ); ?></label>
            </th>
            <td>
                <select class="ccf-post-type">
                    <?php
                        $post_types = get_post_types( array(
                            'public'   => true,
                            '_builtin' => true
                        ), 'objects' );
                        if ( is_array( $post_types ) && $post_types ) {
                            foreach ( $post_types as $post_type_obj ) {
                                if ( 'attachment' == $post_type_obj->name ) {
                                    continue;
                                }
                                echo '<option value="' . esc_attr( $post_type_obj->name ) . '">' . esc_attr( $post_type_obj->label ) . '</option>';
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="ccf-results-container" style="display:none;">
        <h3><?php esc_html_e( 'Mass check in progress', 'content-copy-finder' ); ?>...</h3>
        <p>
            <div id="progressbar"><div class="progress-label"><?php esc_html_e( 'Processed', 'content-copy-finder' ); ?>: <span class="jwp-dh-offset">0</span> <?php esc_html_e( 'of', 'content-copy-finder' ); ?> <span class="jwp-dh-total">?</span></div></div>
        </p>
        <table class="ccf-results-table widefat striped">
            <thead>
                <tr>
                    <th scope="col"><?php esc_html_e( 'Post title', 'content-copy-finder' ); ?></th>
                    <th scope="col"><?php esc_html_e( 'Checking results', 'content-copy-finder' ); ?></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

    <p>
        <input type="button" value="<?php esc_attr_e( 'Start checking', 'content-copy-finder' ); ?>" data-finish="<?php esc_attr_e( 'Processing completed', 'content-copy-finder' ); ?>" data-stop="<?php esc_attr_e( 'Stop', 'content-copy-finder' ); ?>" data-continue="<?php esc_attr_e( 'Continue', 'content-copy-finder' ); ?>" class="button button-primary jwp-dh-start" <?php echo $this->data_atts(); ?>>
    </p>
</div>