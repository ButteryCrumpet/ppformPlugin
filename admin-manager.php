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
    ?>
    <form method="POST" action="options.php">
    <?php settings_fields( 'ppform_settings' );	//pass slug name of page, also referred
                                            //to in Settings API as option group name
    do_settings_sections( 'ppform_settings' ); 	//pass slug name of page
    submit_button();
    ?>
    </form>
    <?php
}

add_action( 'admin_menu', 'ppform_admin_pages' );