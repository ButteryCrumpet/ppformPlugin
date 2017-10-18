<?php 

function ppform_enqueue_script() { 
    wp_enqueue_style( 'ppform', plugin_dir_url( __FILE__ ) . '/style.css');
    
    wp_enqueue_script( 'autozip', plugin_dir_url( __FILE__ ) . 'formJS/jpostal.js', array('jquery') );
    wp_enqueue_script( 'my_utils', plugin_dir_url( __FILE__ ) . 'formJS/pp-utils.js', array('jquery') );
    wp_enqueue_script( 'autovalidation', plugin_dir_url( __FILE__ ) . 'formJS/validation.js', array('jquery') );
    wp_enqueue_script( 'runtimes', plugin_dir_url( __FILE__ ) . 'formJS/runtime.js', array('jquery') );
}

function ppform_enqueue_admin_scripts() {
    wp_enqueue_style( 'vuFBuildStyle', plugin_dir_url( __FILE__ ) . 'css/app.c52ea565b88f7b6894caeb49b42bba15.css');

    wp_enqueue_script( 'manifest', plugin_dir_url( __FILE__ ) . 'formJS/vuFB/manifest.6a5be3d265d9fdfded70.js', null, false, true);
    wp_enqueue_script( 'vuFBuild', plugin_dir_url( __FILE__ ) . 'formJS/vuFB/vendor.42452ceccabd6fede381.js', null, false, true);
    wp_enqueue_script( 'vuebuild', plugin_dir_url( __FILE__ ) . 'formJS/vuFB/app.6f48d5e94bb732ac73de.js', null, false, true);
    
}

add_action( 'admin_enqueue_scripts', 'ppform_enqueue_admin_scripts' );
add_action('wp_enqueue_scripts', 'ppform_enqueue_script');