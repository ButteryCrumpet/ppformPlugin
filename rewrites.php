<?php

function ppform_rewrites(){
    
    add_rewrite_rule ( 'confirm/([0-9]+)/?$', 'index.php?p=$matches[1]', 'top' );
    add_rewrite_rule ( 'submit/([0-9]+)/?$', 'index.php?p=$matches[1]', 'top' );
    flush_rewrite_rules();
}

add_action('init', 'ppform_rewrites');
