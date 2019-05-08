<fieldset>
    <?php foreach( $values as $key => $value ) : ?>
        <?php
            $checked = false;
            if ( isset( $option[ $key ] ) && 'on' == $option[ $key ] ) {
                $checked = true;
            }
        ?>
        <label><input type="checkbox" id="<?php echo esc_attr( $field_name ); ?>" name="<?php echo esc_attr( $group_name ); ?>[<?php echo esc_attr( $field_name ); ?>][<?php echo esc_attr( $key ); ?>]" <?php checked( true, $checked ); ?> /> <?php echo esc_attr( $value ); ?></label></br>
    <?php endforeach; ?>
</fieldset>