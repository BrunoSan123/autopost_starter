<?php

function change_post_title(){
    if (!chatgpt_freemius_integration()->can_use_premium_code()) {
        wp_send_json_error(array('message' => 'Acesso negado. Esta funcionalidade é apenas para usuários premium.'));
        return;
    }

    check_ajax_referer('chatgpt-ajax-nonce', 'security');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'Você não tem permissão para editar posts.'));
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $post_title=isset($_POST['title'])?sanitize_text_field($_POST['title']):'';

    if ($post_id <= 0) {
        wp_send_json_error(array('message' => 'ID de post inválido.'));
    }

    if ($post_title == '') {
        wp_send_json_error(array('message' => 'Titulo não providenciado.'));
    }

    $post = get_post($post_id);

    if (!$post) {
        wp_send_json_error(array('message' => 'Post não encontrado.'));
    }

    $data = array(
        'ID' => $post_id,
        'post_title' => $post_title
    );
    
    // Atualize o post usando wp_update_post()
    wp_update_post($data);


    wp_send_json_success();
}

function smart_schedule(){
    register_post_status('post_queue', array(
        'label'                     => _x('Queue', 'post'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Queue (%s)', 'Novo Queue(%s)')
    ));

}

function my_custom_status_add_in_quick_edit() {
    echo "<script>
    jQuery(document).ready( function() {
        jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"post_queue\">Post queue</option>' );      
    }); 
    </script>";
}

function my_custom_status_add_in_post_page() {
    echo "<script>
    jQuery(document).ready( function() {        
        jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"post_queue\">Post queue</option>' );
    });
    </script>";
}

function add_toggle_social_option(){
    $data=$_POST['social'];
    $result=update_option('social_toggle',$data);
    return wp_send_json($result);
}
