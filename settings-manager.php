<?php

function ppform_settings_init() {

    register_setting( 'ppform_settings', 'ppform', 'validate_ppform_settings' );
}

add_action( 'admin_init', 'ppform_settings_init' );

function validate_ppform_settings($data) {
    return $data;
}

function init_settings() {

    $defaults = array(
        'javascript' => true,
        'complete_message' => 'Thank you for your contribution',
        'invalid_error' => 'は不正です',
        'required_error' => 'を入力してください',
    );

    update_option('ppform', $defaults);
}