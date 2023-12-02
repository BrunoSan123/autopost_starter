<?php


function is_author_user(){
    $current_user=wp_get_current_user();
    if(in_array('Admnistrator',$current_user->roles)){
        return true;
    }else{
        return print_r('Sem previlégios');
    }
} 

function delete_user($user_id){
    global $wpdb;
    $user =get_user_by('id',$user_id)->user_login;
    $id=get_user_by('id',$user_id)->ID;
    if (username_exists($user)) {
        $result =$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->users WHERE ID = %d", $id));
        $meta=$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id = %d", $id));
        wp_send_json($result);
    } else {
        return 'User does not exists';
    }
}

function create_new_user($user,$password,$email){
    if(is_author_user()){
        if(!username_exists($user) && !email_exists($email)){
            $user_id= wp_create_user($user,$password,$email);
            $user = new WP_User($user_id);
            $user->add_role('author');
        }
    }else{
        return "Usuário ou endereço de email já existentes";
    }
}

function update_user($user_id,$user_name,$user_email){
    try{
        $args=array(
            'ID'=>$user_id,
            'user_email'=>$user_email,
            'display_name'=>$user_name
        );
        $result=wp_update_user($args);
        return $result;
    }catch(Exception $e){
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

function create_new_category($name){
    
         $result=wp_create_category($name);
    
        if(!is_wp_error($result)){
            return 'categoria criada com sucesso';
        }else{
            return 'falha em criar categoria';
        }
}

function add_new_author() {
    $author_name = sanitize_text_field($_POST['author_name']);
    $author_email = sanitize_email($_POST['author_email']);

    // Chama a função create_new_user
    $result = create_new_user($author_name, 'password', $author_email);

    echo $result;

    wp_die();
}

function update_openai_token_callback() {
    // Retrieve data from the AJAX request
    $openai_token = sanitize_text_field($_POST['openai_token']);

    // Update the OpenAI token option
    update_option('chatgpt_api_key', $openai_token);

    // Send a response back to the JavaScript
    echo json_encode(['success' => true]);
    wp_die(); // Always call wp_die() at the end to terminate the script
}

function delete_token_callback(){
    delete_option('chatgpt_api_key');
    echo json_encode(['success' => true]);
    wp_die();
}


function update_google_search_key_callback() {
    // Retrieve data from the AJAX request
    $google_search_key = sanitize_text_field($_POST['google_search_key']);

    // Update the Google Search Key option
    update_option('google_search_key', $google_search_key);

    // Send a response back to the JavaScript
    echo json_encode(['success' => true]);
    wp_die(); // Always call wp_die() at the end to terminate the script
}



function update_google_search_id_callback() {
    // Retrieve data from the AJAX request
    $google_search_id = sanitize_text_field($_POST['google_search_id']);

    // Update the Google Search ID option
    update_option('google_search_id', $google_search_id);

    // Send a response back to the JavaScript
    echo json_encode(['success' => true]);
    wp_die(); // Always call wp_die() at the end to terminate the script
}

function update_google_translate_api(){
    $google_translate_api = sanitize_text_field($_POST['translate_api']);

    update_option('google_translate_key', $google_translate_api);
    echo json_encode(['success' => true]);
    wp_die(); 
}

function delete_translate_token(){
    delete_option('google_translate_key');
    echo json_encode(['success' => true]);
    wp_die();
}

add_action('wp_ajax_add_new_author', 'add_new_author');
add_action('wp_ajax_update_google_search_key', 'update_google_search_key_callback');
add_action('wp_ajax_update_google_search_id', 'update_google_search_id_callback');
add_action('wp_ajax_update_google_tanslate', 'update_google_translate_api');
add_action('wp_ajax_delete_google_tanslate','delete_translate_token');
add_action('wp_ajax_update_openai_token', 'update_openai_token_callback');
add_action('wp_ajax_delete_openai_token','delete_token_callback');

