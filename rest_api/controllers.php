<?php
require_once(dirname(__FILE__).'/../functions/settings/settings_backend.php');
function get_gpt_key($request) {
    // Recupere as opções da tabela wp_options
    $options = get_option('chatgpt_api_key');

    // Verifique se as opções existem
    if ($options) {
        // Retorna as opções em formato JSON
        return rest_ensure_response($options);
    } else {
        // Trate o caso em que as opções não foram encontradas
        return new WP_Error('no_options_found', 'Nenhuma opção encontrada', array('status' => 404));
    }
}

function update_token_billing($request){
    $data=$request->get_params();
    if($data){
        insert_gpt_response_data($data);
    }else{
        return new WP_Error('no_data_provided', 'no Data Provided', array('status' => 400));
    }
    
}

function insertUserDataRequest($request){
    $data=$request->get_params();
    $author =retriveUserData($data['author']);
    if(empty($author)){
        $args=array(
            'author_name'=>$data['author'],
            'writen_articles'=>$data['articles'],
            'active'=>$data['active'],
        );
        $result = insertUserData($args);
    }else{
        $args=array(
         'active'=>$data['active']
        );
        $result = updateUserData($author[0]->id,$args);
    }

    return new WP_REST_Response($result, 200);
}


function switch_language($request) {
    $data = $request->get_params();
    if(!isset($data['new_language'])){
        return get_option('autopost_language');
    }
    $new_language = sanitize_text_field($data['new_language']);

    // Update the option 'autopost_language'
    update_option('autopost_language', $new_language);

    return new WP_REST_Response(array('success' => true, 'message' => 'Language option updated successfully.'), 200);
}

function get_all_available_languages() {
    $languages = get_available_languages();
    return new WP_REST_Response($languages, 200);
}

function update_user_request($request){
    $data=$request->get_params();

    $result=edit_author_modal($data['user_id'],$data['user_name'],$data['user_email']);
    return wp_send_json($result);

}

function delete_user_request($request){
    $data=$request->get_params();
    $result=delete_user_settings($data['user_id']);
    return wp_send_json($request);
}

function get_social_toggle_value(){
    $result =get_option('social_toggle');

    return wp_send_json($result);
}

function get_translate_token(){
    $result= get_option('google_translate_key');
    return wp_send_json($result);
}

// Add a REST API endpoint to get available languages
add_action('rest_api_init', function () {
    register_rest_route('your-custom/v1', '/getlanguages/', array(
        'methods' => 'GET',
        'callback' => 'get_all_available_languages',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/change-language', array(
        'methods' => 'POST',
        'callback' => 'switch_language',
        'permission_callback' => '__return_true',
    ));
});


add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/gpt_key', array(
        'methods' => 'GET',
        'callback' => 'get_gpt_key',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/update_token_billing', array(
        'methods' => 'POST',
        'callback' => 'update_token_billing',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/user_status', array(
        'methods' => 'POST',
        'callback' => 'insertUserDataRequest',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/update_user', array(
        'methods' => 'PUT',
        'callback' => 'update_user_request',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/delete_user', array(
        'methods' => 'DELETE',
        'callback' => 'delete_user_request',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/get_toggle', array(
        'methods' => 'GET',
        'callback' => 'get_social_toggle_value',
        'permission_callback' => '__return_true',
    ));
});

add_action('rest_api_init',function(){
    register_rest_route('custom/v1', '/get_translate', array(
        'methods' => 'GET',
        'callback' => 'get_translate_token',
        'permission_callback' => '__return_true',
    ));
});



