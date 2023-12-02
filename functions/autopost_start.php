<?php

function chatgpt_plugin_save_model()
{
    // Verifique se o modelo foi passado
    if (isset($_POST['model'])) {
        $model = sanitize_text_field($_POST['model']);

        // Aqui você pode salvar o modelo no banco de dados WordPress
        // Por exemplo, usando a função update_option:
        update_option('chatgpt_plugin_chatgpt_model', $model);

        // Envie uma resposta de sucesso
        wp_send_json_success();
    } else {
        // Se o modelo não foi passado, envie uma resposta de erro
        wp_send_json_error('No model provided');
    }

    // Certifique-se de sempre morrer em funções AJAX, ou mais saída pode ser adicionada à resposta
    wp_die();
}


function quick_start_part_1_assets()
{
    if (isset($_GET['page'])) {
        if (is_admin() && $_GET['page'] == 'chat_gpt_quick_start_part_1') {
            wp_enqueue_style(
                'quick_start_part_1_style',
                plugin_dir_url(__FILE__) . '/pages/quick_start/part_1/assets/css/style.css',
                [],
                time()
            );
            wp_enqueue_script(
                'quick_start_script',
                plugin_dir_url(__FILE__) . '/pages/quick_start/assets/js/script.js',
                array('jquery'),
                time()
            );
            wp_enqueue_script(
                'quick_start_part_1_script',
                plugin_dir_url(__FILE__) . '/pages/quick_start/part_1/assets/js/script.js',
                array('jquery'),
                time()
            );
            $plugin_dir_url = plugin_dir_url(__FILE__);
            $array = array('plugin_dir_url' => $plugin_dir_url); 
            wp_localize_script('quick_start_part_1_script', 'php_vars', $array);
        }
    }
}
add_action('admin_enqueue_scripts', 'quick_start_part_1_assets');


function quick_start_part_2_assets()
{
    if (isset($_GET['page'])) {
        if (is_admin() && $_GET['page'] == 'chat_gpt_quick_start_part_2') {
            wp_enqueue_style(
                'quick_start_part_2_style',
                plugin_dir_url(__FILE__) . '/pages/quick_start/part_2/assets/css/style.css',
                [],
                time()
            );
            wp_enqueue_script(
                'quick_start_script',
                plugin_dir_url(__FILE__) . '/pages/quick_start/assets/js/script.js',
                array('jquery'),
                time()
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'quick_start_part_2_assets');
