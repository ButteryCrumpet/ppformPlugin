<?php

function ppform_settings_init() {

    add_settings_section('ppform_settings_general', 'General', 'ppform_settings_general_callback', 'ppform_settings');
    add_settings_section('ppform_settings_classes', 'Classes', 'ppform_settings_classes_callback', 'ppform_settings');
    
    //General settings
    add_settings_field( 'ppform_javascript', 'Use Javascript', 'render_ppform_javascript_field', 'ppform_settings', 'ppform_settings_general' );
    add_settings_field( 'ppform_required', 'Use HTML required', 'render_ppform_required_field', 'ppform_settings', 'ppform_settings_general' );

    //Classes settings
    add_settings_field( 'ppform_valid_class', 'Valid Class', 'render_ppform_valid_class_field', 'ppform_settings', 'ppform_settings_classes' );
    add_settings_field('ppform_error_class', 'Error Class', 'render_ppform_error_class_field', 'ppform_settings', 'ppform_settings_classes');

    register_setting( 'ppform_settings', 'ppform_javascript' );
    register_setting( 'ppform_settings', 'ppform_required' );
    register_setting( 'ppform_settings', 'ppform_valid_class' );
    register_setting( 'ppform_settings', 'ppform_error_class' );
}

add_action( 'admin_init', 'ppform_settings_init' );

function ppform_settings_main_callback() {
    echo "<h4>Form Attribute Settings</h4>";
}

function ppform_settings_errors_callback() {
    echo '<h4>Form Classes Settings</h4>';
}

function render_ppform_javascript_field() {
    echo '<input type="checkbox">';
}

function render_ppform_required_field() {
    echo '<input type="checkbox">';
}

function render_ppform_valid_class_field() {
    echo '<input type="text">';
}

function render_ppform_error_class_field() {
    echo '<input type="text">';
}
