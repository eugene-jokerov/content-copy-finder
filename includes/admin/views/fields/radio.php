<fieldset>
    <?php foreach( $values as $key => $value ) : ?>
        <?php
            $checked = ( $option == $key || ! $option ) ? true : false;
        ?>
        <label><input type="radio" name="<?php echo esc_attr( $group_name ); ?>[<?php echo esc_attr( $field_name ); ?>]" value="<?php echo esc_attr( $key ); ?>" <?php checked( true, $checked ); ?> /><?php echo esc_attr( $value ); ?></label><br />

    <?php endforeach; ?>
</fieldset>