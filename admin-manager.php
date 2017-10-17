<?php

function ppform_admin_pages() {

    add_menu_page( 
        'ppForms',
        'ppForms',
        'manage_options',
        'ppform_menu',
        '',
        '',
        75
    );

    add_submenu_page(
        'ppform_menu',
        'ppform-settings',
        'Settings',
        'manage_options',
        'ppform_settings',
        'render_ppform_settings'
    );
}

function render_ppform_settings() {
    $defaults = array(
        'javascript' => true,
        'complete_message' => 'Thank you for your contribution',
        'invalid_error' => 'は不正です',
        'required_error' => 'を入力してください',
    );

    $options = get_option('ppform', $defaults);
    
    ?>
    <form method="POST" action="options.php">
    <?php settings_fields( 'ppform_settings' ); ?>	
    <table class="form-table">
        <tr valign="top"><th scope="row">Use Javascript:</th>
            <td><input type="checkbox" name="ppform[javascript]"<?php echo ($options['javascript']) ? 'checked' : ''; ?> /></td>
        </tr>
        <tr valign="top"><th scope="row">Completion message:</th>
            <td><input type="text" name="ppform[complete_message]" value="<?php echo $options['complete_message'] ?>" /></td>
        </tr>
        <tr valign="top"><th scope="row">Invalid Error Message:</th>
            <td><input type="text" name="ppform[invalid_error]" value="<?php echo $options['invalid_error'] ?>" /></td>
        </tr>
        <tr valign="top"><th scope="row">Required Error Message:</th>
            <td><input type="text" name="ppform[required_error]" value="<?php echo $options['required_error'] ?>" /></td>
        </tr>
    </table>

    <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
    </form>
    <?php
}

add_action( 'admin_menu', 'ppform_admin_pages' );