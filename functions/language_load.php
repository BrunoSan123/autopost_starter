<?php 
$current_language = get_option('autopost_language', 'pt_BR');
switch_to_locale($current_language);

function change_wp_language_in_plugin() {
    $current_language = get_option('autopost_language', 'pt_BR');
    switch_to_locale($current_language);
}

// Hook your function to an appropriate action or filter
add_action('init', 'change_wp_language_in_plugin');