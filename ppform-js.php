<?php 

function ppform_enqueue_script() {   
    wp_enqueue_script( 'autozip', plugin_dir_url( __FILE__ ) . 'formJS/jpostal.js', array('jquery') );
    wp_enqueue_script( 'my_utils', plugin_dir_url( __FILE__ ) . 'formJS/pp-utils.js', array('jquery') );
    wp_enqueue_script( 'autovalidation', plugin_dir_url( __FILE__ ) . 'formJS/validation.js', array('jquery') );
    wp_enqueue_script( 'runtimes', plugin_dir_url( __FILE__ ) . 'formJS/runtime.js', array('jquery') );
}

add_action('wp_enqueue_scripts', 'ppform_enqueue_script');