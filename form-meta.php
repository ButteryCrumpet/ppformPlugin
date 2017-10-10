<?php

function ppform_metaboxes() {
    add_meta_box('fields-div', 'Fields', 'render_field_metabox', 'pp-form', 'normal');
    
    //response metabox
    add_meta_box( 'data-div', 'Data', 'render_response_data', 'ppform-response', 'normal' );
}

function render_field($i) {
    $form = '<div>';
    $form .= '<input type="text" name="ppfields['.$i.'][label]">';
    $form .= '<input type="text" name="ppfields['.$i.'][name]">';
    $form .= '<input type="text" name="ppfields['.$i.'][type]">';
    $form .= '<input type="text" name="ppfields['.$i.'][value]">';
    $form .= '<input type="text" name="ppfields['.$i.'][placeholder]">';
    $form .= '</div>';
    echo $form;
}

function render_field_metabox( $post ) {

    $vals = get_post_meta($post->ID, 'ppfields');
    $vals = $vals[0];
    print_r($vals);

    for ($i = 0; $i < 2; $i++) {
        render_field($i);
    }
}

function render_response_data( $post ) {
    $data = get_post_meta( $post->ID, 'data', true );

    foreach ($data as $key => $val) {
        echo '<h4>' . $key . ':　' . $val . '</h4>';
    }
}

function save_field_meta( $post_id, $post ) {

    if ( ! isset( $_POST['ppfields'] ) ) {
        return $post_id;
    }

    $meta_key = 'ppfields';
    $meta_value = get_post_meta( $post_id, $meta_key, true );
    $new_meta_value = ( isset( $_POST[$meta_key] ) ? sanitize_html_class( $_POST[$meta_key] ) : '' );
    //check if time format($new_meta_value);
    if ( $new_meta_value && array() == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );
    // If the new meta value does not match the old value, update it.
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );
    // If there is no new meta value but an old value exists, delete it.
     elseif ( array() == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
}

add_action('add_meta_boxes', 'ppform_metaboxes');
add_action('save_post', 'save_field_meta', 10, 2);
