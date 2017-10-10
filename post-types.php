<?php 

function ppform_form_init() {
    $ppformlabels = array(
        'name' => 'Form',
    );

    $ppformargs = array(
        'labels' => $ppformlabels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'ppform_menu',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 9,
        'supports' => array(
            'title',
            'editor'
        ),
    );

    register_post_type( 'pp-form', $ppformargs );

    $responselabels = array(
        'name' => 'Response',
    );

    $responeargs = array(
        'labels' => $responselabels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'ppform_menu',
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array(
            null
        ),
    );

    register_post_type( 'ppform-response', $responeargs );
}

add_action( 'init', 'ppform_form_init');
