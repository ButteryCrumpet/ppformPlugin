<?php

function ppform_metaboxes() {

    //form metaboxes
    add_meta_box('fields-div', 'Fields', 'render_field_metabox', 'pp-form', 'normal');
    add_meta_box('formactions-div', 'Actions', 'render_actions_metabox', 'pp-form', 'side');
    add_meta_box('emailopts-div', 'Email Options', 'render_email_opts_metabox', 'pp-form', 'normal');
    
    //response metabox
    add_meta_box( 'data-div', 'Data', 'render_response_data', 'ppform-response', 'normal' );
}

function render_actions_metabox( $post ) {

    $vals = get_post_meta( $post->ID, 'form-action', true );
    ?>
    <div class="ppadmininput">
        <label>Save as Response</label>
        <input type="checkbox" name="form-action[save-response]" <?php echo ($vals['save-response']) ? 'checked' : '' ?>>
        <label>Send Email</label>
        <input type="checkbox" name="form-action[send-email]" <?php echo ($vals['send-email']) ? 'checked' : '' ?>>
    </div>
    <?php
}

function render_field_metabox( $post ) {
    $prev = get_post_meta( $post->ID, 'form-data', true );
 
    $html = '<div id=formBuilder ><field-form ';
    $html .= "initfields='" . $prev . "' >";
    $html .= '</field-form></div>';

    echo $html;
}

function render_email_opts_metabox( $post ) {
    $vals = get_post_meta( $post->ID, 'emailopt', true );
    ?>

    <style>
        .ppadmininput > label {
            display: inline-block;
            width: 200px;
        }
    </style>

    <div class="ppadmininput">
        <label>Send To</label>
        <input type="text" name="emailopt[send-to]" value="<?php echo $vals['send-to'] ?>"><br>
        <label>BCC</label>
        <input type="text" name="emailopt[bcc]" value="<?php echo $vals['bcc'] ?>"><br>
        <label>CC</label>
        <input type="text" name="emailopt[cc]" value="<?php echo $vals['cc'] ?>"><br>
        <label>Sent From</label>
        <input type="text" name="emailopt[sent-from]" value="<?php echo $vals['sent-from'] ?>"><br>
    </div>

    <?php
}

function render_response_data( $post ) {
    $data = get_post_meta( $post->ID, 'data', true );

    foreach ($data as $key => $val) {
        echo '<h4>' . $key . ':　' . $val . '</h4>';
    }
}

function save_field_meta( $post_id, $post ) {

    $meta_key = 'form-data';
    if ( ! isset( $_POST['form-data'] ) ) {
        return $post_id;
    }

    $meta_value = get_post_meta( $post_id, $meta_key, true );

    //parse post array
    //sanitize-validate!!!
    $new_meta_value = $_POST[$meta_key];
    //check if time format($new_meta_value);
    if ( $new_meta_value && array() == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    // If the new meta value does not match the old value, update it.
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );
    // If there is no new meta value but an old value exists, delete it.
     elseif ( array() == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );

    return true;
}

function save_email_opt_meta( $post_id, $post ) {

    if ( ! isset( $_POST['emailopt'] ) ) {
        return $post_id;
    }

    $data = array();

    foreach ( $_POST['emailopt'] as $key => $val) {
        $data[$key] = sanitize_text_field($val);
    }

    if ( ! add_post_meta( $post_id, 'emailopt', $data, true ) ) {
        update_post_meta( $post_id, 'emailopt', $data );
    }

    return true;
}

function save_form_action_meta( $post_id, $post ) {

    if ( ! isset( $_POST['form-action'] ) ) {
        return new WP_Error('error', 'At least one action is required');
    }


    if ( ! add_post_meta( $post_id, 'form-action', $_POST['form-action'], true ) ) {
        update_post_meta( $post_id, 'form-action', $_POST['form-action'] );
    }

    return true;

}

function save_form_post( $post_id, $post ) {
//only on correct post
    $user_id = get_current_user_id();
    $results = array();
    $errors = array();
    $errorExists = false;

    $results[] = save_form_action_meta( $post_id, $post );
    $results[] = save_email_opt_meta( $post_id, $post );
    $results[] = save_field_meta( $post_id, $post );

    foreach ($results as $result) {
        if ( is_a($result, 'WP_Error') ) {
            $errors[] = $result;
            $errorExists = true;
        }
    }

    if ($errorExists) {
        //set_transient("my_save_post_errors_{$post_id}_{$user_id}", $errors, 45);
    }

    return true;
}

function form_error_message() {

    $post_id = get_the_ID();
    $user_id = get_current_user_id();

    if ( $errors = get_transient( "my_save_post_errors_{$post_id}_{$user_id}" ) ) { 
        foreach($errors as $error) {
        ?>
        <div class="error">
            <p><?php echo $error->get_error_message(); ?></p>
        </div>
        <?php
        }
        delete_transient("my_save_post_errors_{$post_id}_{$user_id}");
    }
}

add_action( 'admin_notices', 'form_error_message');

add_action('add_meta_boxes', 'ppform_metaboxes');
add_action('save_post', 'save_form_post', 10, 2);

