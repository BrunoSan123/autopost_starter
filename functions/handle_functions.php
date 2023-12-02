<?php

function chatgpt_enqueue_admin_scripts($hook) {
    if ($hook !== 'edit.php' && $hook !=='post.php' && $hook !=='admin.php') {
        return;
    }
    

    $path=get_site_url() . '/wp-content/plugins/auto_post_application/scripts/chatgpt-admin.js';



    wp_enqueue_script('chatgpt-admin', $path, array('jquery'), '1.0.0', true);

    $ajax_object = array(
        'ajax_nonce' => wp_create_nonce('chatgpt-ajax-nonce'),
        'ajax_url' => admin_url('admin-ajax.php')
    );

    wp_localize_script('chatgpt-admin', 'chatgpt_ajax_object', $ajax_object);
}
