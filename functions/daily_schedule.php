<?php
// Register custom REST API endpoint
function daily_schedule_endpoint() {
    register_rest_route('custom/v1', '/daily-schedule/', array(
        'methods' => 'GET',
        'callback' => 'daily_schedule_callback',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'daily_schedule_endpoint');
function get_daily_schedule_endpoint() {
    register_rest_route('custom/v1', '/daily/', array(
        'methods' => 'GET',
        'callback' => 'get_daily_schedule',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'get_daily_schedule_endpoint');

// Callback function for the custom endpoint
function daily_schedule_callback() {
    ob_start();
    
    // Call the existing function
    daily_schedule();

    $output = ob_get_clean();

    return array('message' => $output);
}


function custom_update_post($data){
        global $wpdb;
    
        $table_posts = $wpdb->prefix . 'posts';
    
        // Dados a serem atualizados
        $dados = array(
            'post_content' => $data['post_content'],
            'post_status' => $data['post_status'],
        );
    
        // Condição para atualizar o post específico
        $where = array('ID' => $data['ID']);
    
        // Formato dos dados
        $formato = array('%s', '%s');
    
        // Executar a atualização
        $wpdb->update($table_posts, $dados, $where, $formato);
    
        // Verificar se ocorreu algum erro durante a atualização
        if ($wpdb->last_error) {
            return false; // Erro na atualização
        } else {
            return true; // Atualização bem-sucedida
        }
    
    
}

function get_daily_schedule(){
    $args = array(
        'post_status' => 'post_queue',
        'post_type' => 'post',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    return $query;
}

function daily_schedule() {
    try {
        $args = array(
            'post_status' => 'post_queue',
            'post_type' => 'post',
            'posts_per_page' => -1,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $start_date = current_time('mysql');

            while ($query->have_posts()) {
                $query->the_post();

                // Get the ID of the current post
                $post_id = get_the_ID();

                // Calculate the future publication date individually for each post
                $start_date = date('Y-m-d H:i:s', strtotime($start_date . ' +1 day'));

                // Update the post status and date using WordPress functions
                // wp_update_post(array(
                //     'ID' => $post_id,
                //     'post_status' => 'future',
                //     'post_date' => $start_date,
                // ));
                $data_to_update = array(
                    'ID' => $post_id,
                    'post_content' => get_the_content(),
                    'post_status' => 'future', // or any other status you want
                );

                // Call custom_update_post method to update the post
                $update_result = custom_update_post($data_to_update);
            }
            wp_reset_postdata();

            echo 'Posts criados e agendados com sucesso!';
        } else {
            echo 'Nenhum post encontrado com o status personalizado.';
        }
    } catch (Exception $e) {
        error_log('Erro capturado: ' . $e->getMessage());
    }
}



