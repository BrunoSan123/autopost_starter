<?php 
function insert_gpt_response_data($response_data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';

    $data = array(
        'completion_tokens' => $response_data['usage']['completion_tokens'],
        'prompt_tokens' => $response_data['usage']['prompt_tokens'],
        'total_tokens' => $response_data['usage']['total_tokens'],
        'model' => $response_data['model'],
        'created' => date('Y-m-d H:i:s', $response_data['created'])
    );

    $wpdb->insert($table_name, $data);
}


function get_genreal_tokens_value($model){
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';

    $query = $wpdb->prepare("
    SELECT SUM(completion_tokens) as completion ,SUM(prompt_tokens) as prompts
    FROM $table_name
    WHERE model = %s
    ", $model);

    $billing_values = $wpdb->get_results($query);

        return $billing_values;

}
function get_total_tokens_sum_for_model($model) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';

    // Prepare and execute the SQL query to calculate the sum of total_tokens for the specified model
    $query = $wpdb->prepare("
        SELECT SUM(total_tokens) as total_sum
        FROM $table_name
        WHERE model = %s
    ", $model);

    $total_sum = $wpdb->get_var($query);

    return $total_sum;
}
function getChartData() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';

    $data = $wpdb->get_results("SELECT * FROM $table_name");
    return $data;
}

function getChartDataByDay() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';

    $data = $wpdb->get_results("SELECT DATE(created) AS date, SUM(total_tokens) AS total_tokens
        FROM $table_name
        GROUP BY DATE(created)
        ORDER BY DATE(created)");

    return $data;
}

function saveBasicConfig($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'autopost_config';

    // Check if a record with ID 1 already exists
    $existing_record = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1");

    if ($existing_record) {
        // Update the existing record
        $result = $wpdb->update(
            $table_name,
            array(
                'words_per_article' => $data['words_per_article'],
                'article_type' => $data['article_type'],
                'writen_tone' => $data['writen_tone'],
                'text_style' => $data['text_style'],
                'image_style' => $data['image_style'],
            ),
            array('id' => 1)
        );
    } else {
        // Insert a new record
        $result = $wpdb->insert(
            $table_name,
            array(
                'id' => 1, // Assuming ID 1 is the record you want to update
                'words_per_article' => $data['words_per_article'],
                'article_type' => $data['article_type'],
                'writen_tone' => $data['writen_tone'],
                'text_style' => $data['text_style'],
                'image_style' => $data['image_style'],
            )
        );
    }

    if ($result === false) {
        // Handle the error as before
        $error_message = $wpdb->last_error;
        error_log('Error during database operation: ' . $error_message);
        throw new Exception('Error during database operation: ' . $error_message);
    }

    return $wpdb->insert_id;
}


function updateBasicConfig($data){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_config';
    $result=$wpdb->update($table_name,array(
        'words_per_article'=>$data['words_per_article'],
        'article_type'=>$data['article_type'],
        'writen_tone'=>$data['writen_tone'],
        'text_style'=>$data['text_style'],
        'image_quantity'=>$data['image_quantity'],
        'image_style'=>$data['image_style'],
    ),
     array('id'=>1),
    );

    if ($result === false) {
        // Ocorreu um erro durante a inserção
        $error_message = $wpdb->last_error;
        // Adicione aqui qualquer lógica adicional de tratamento de erro que você precisar

        // Por exemplo, você pode registrar o erro, enviar um e-mail, ou lançar uma exceção
        // Log do erro
        error_log('Erro durante a inserção no banco de dados: ' . $error_message);

        // Você pode lançar uma exceção se quiser tratar o erro em outro lugar
        throw new Exception('Erro durante a inserção no banco de dados: ' . $error_message);
    }

    return $wpdb->insert_id;

}


function retriveAutopostconfig(){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_config';
    $data=$wpdb->get_results("SELECT * FROM $table_name");

    return $data;
}
function retrieveImageQuantity() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'autopost_config';
    $data = $wpdb->get_results("SELECT image_quantity FROM $table_name LIMIT 1");

    if ($data && isset($data[0]->image_quantity)) {
        // Convert image_quantity to an integer and return it
        return intval($data[0]->image_quantity);
    }

    // Default value if image_quantity is not found or not valid
    return 0;
}

function insertUserData($data){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_plugin_users';

    $args=array(
        'author_name'=>$data['author_name'],
        'writen_articles'=>$data['writen_articles'],
        'active'=>$data['active']
    );

    $result=$wpdb->insert($table_name,$args);

    wp_send_json($result);


}

function updateUserData($user_id, $data){
    global $wpdb;
    $table_name = $wpdb->prefix . 'autopost_plugin_users';

    $args = array(
        'active' => $data['active']
    );

    $where = array('ID' => $user_id);

    $result = $wpdb->update($table_name, $args, $where);

    wp_send_json($result);
}


function retriveUserData($author){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_plugin_users';

    $results=$wpdb->get_results("SELECT active FROM $table_name WHERE author_name='$author'");

    return $results;
}

function retrieveUserGeneral(){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_plugin_users';

    $results=$wpdb->get_results("SELECT author_name FROM $table_name WHERE active=1");

    return $results;

}


function update_linkedin_callback() {
    global $wpdb;

    // Retrieve data from the AJAX request
    $linkedin_token = sanitize_text_field($_POST['linkedin_token']);
    $linked_in_active = sanitize_text_field($_POST['linkedin_active']);


    // Update the database with the received LinkedIn data
    $result = updateSocial([
        'likd_in_token' => $linkedin_token,
        'linked_in_active' => $linked_in_active
        // Add other necessary fields here
    ]);

    // Send a response back to the JavaScript
    echo json_encode(['success' => $result]);
    wp_die(); // Always call wp_die() at the end to terminate the script
}

function retriveSocial(){
    global $wpdb;
    $table_name=$wpdb->prefix.'chat_gpt_social';

    $social_query=$wpdb->get_results("SELECT * FROM $table_name");

    return $social_query;
}


?>