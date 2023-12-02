<?php
/*
Plugin Name: ChatGPT Autopost starter
Description: Um plugin para criar e postar automaticamente textos gerados pelo ChatGPT através palavras-chave e prompts personalizados.
Text Domain: autopost
Domain Path: /languages
Version: 3.1.1
Author: <a href="https://visaopontocom.com">VPC Digital</a>
*/

//integração com Freemius



if (!defined('ABSPATH')) {
    exit;
}

set_time_limit(0);
//import de funções
//require 'vendor/autoload.php'; 
require_once(dirname(__FILE__) . '/functions/language_load.php');

require_once(dirname(__FILE__) . '/functions/stable_diffusion_functions.php');
require_once(dirname(__FILE__) . '/functions/openai_functions.php');

require_once(dirname(__FILE__) . '/functions/actions_functions.php');
require_once(dirname(__FILE__) . '/functions/handle_functions.php');
require_once(dirname(__FILE__) . '/functions/ia_image_generation_functions.php');
require_once(dirname(__FILE__) . '/functions/pages_functions.php');
require_once(dirname(__FILE__) . '/functions/pages.php');
require_once(dirname(__FILE__) . '/functions/settings_page.php');
require_once(dirname(__FILE__) . '/functions/custom_actions.php');
require_once(dirname(__FILE__) . '/functions/auto_link.php');
require_once(dirname(__FILE__) . '/rest_api/projectform.php');
require_once(dirname(__FILE__) . '/rest_api/controllers.php');
require_once(dirname(__FILE__) . '/functions/menu_metaboxes.php');
require_once(dirname(__FILE__) . '/functions/database_func.php');
require_once(dirname(__FILE__) . '/templates/quick-start/create-pages.php');
require_once(dirname(__FILE__) . '/functions/quickstart_endpoint.php');
require_once(dirname(__FILE__) . '/helpers/helpers.php');
require_once(dirname(__FILE__) . '/functions/autopost_start.php');

function enqueue_script_style()
{
    wp_enqueue_style('gpt_plugin_style', plugin_dir_url(__FILE__) . 'style.css');
}

add_action('init', 'enqueue_script_style');

if (function_exists('chatgpt_freemius_integration')) {
    chatgpt_freemius_integration()->set_basename(true, __FILE__);
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if (!function_exists('chatgpt_freemius_integration')) {
        // Create a helper function for easy SDK access.
        function chatgpt_freemius_integration()
        {
            global $chatgpt_freemius_integration;

            if (!isset($chatgpt_freemius_integration)) {
                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/freemius/start.php';

                $chatgpt_freemius_integration = fs_dynamic_init(
                    array(
                        'id' => '12370',
                        'slug' => 'autopost-chatgpt-wordpress',
                        'type' => 'plugin',
                        'public_key' => 'pk_b17266a611535aa5e68c49c007401',
                        'is_premium' => false,
                        // If your plugin is a serviceware, set this option to false.
                        'has_premium_version' => false,
                        'has_addons' => false,
                        'has_paid_plans' => false,
                        'is_org_compliant' => false,
                    )
                );
            }

            return $chatgpt_freemius_integration;
        }

        // Init Freemius.
        chatgpt_freemius_integration();
        // Signal that SDK was initiated.
        do_action('chatgpt_freemius_integration_loaded');
    }


    //acessando URL AJAX para o seu script JavaScript
    function chatgpt_plugin_enqueue_scripts($hook)
    {
        // Você pode querer verificar aqui se você está na página correta antes de enfileirar o script
        // Se o script deve ser enfileirado em todas as páginas do admin, você pode remover essa verificação


        wp_enqueue_script('chatgpt-plugin-ajax', plugin_dir_url(__FILE__) . 'modelo-chatgpt.js', array('jquery'), '1.0', true);
        wp_localize_script(
            'chatgpt-plugin-ajax',
            'chatgpt_plugin_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php')
            )
        );
        wp_enqueue_script('gpt_ligt_n_dark', plugin_dir_url(__FILE__) . 'scripts/elements.js', array(), '1.0.0', true);
        wp_enqueue_script('gpt_text_request', plugin_dir_url(__FILE__) . 'scripts/gpt_query.js', array(), '1.0.0', true);

    }
    add_action('admin_enqueue_scripts', 'chatgpt_plugin_enqueue_scripts');


    // Adicione uma nova opção à lista de ações em massa: (PREMIUM)



    //função que registra o Javascript que gera o Post de acordo o Status: Publicado, Rascunho ou Agendar Post
    function chatgpt_enqueue_scripts()
    {
        wp_enqueue_script('chatgpt-post-status', plugin_dir_url(__FILE__) . 'chatgpt-post-status.js', array('jquery'), '1.0.0', true);
    }
    add_action('admin_enqueue_scripts', 'chatgpt_enqueue_scripts');


    add_action('admin_enqueue_scripts', 'chatgpt_enqueue_admin_scripts');


    function chatgpt_register_prompt_settings()
    {
        register_setting('chatgpt-settings-group', 'chatgpt_saved_prompt');
    }
    add_action('admin_init', 'chatgpt_register_prompt_settings');



    //Registra a opção chatgpt_api_key ao ativar o plugin (Liberar na versão FREE)


    function autopost_install()
    {
        add_option('chatgpt_api_key', '');
        require_once(dirname(__FILE__) . '/functions/database.php');
        activate_my_database_plugin();
        // Create a category with the slug 'onqueue'
        $category_id = wp_create_category('On Queue', 0);
        if (is_wp_error($category_id)) {
            // Handle error if category creation fails
            error_log('Error creating category: ' . $category_id->get_error_message());
        } else {
            // Optionally, you can do something with the created category ID
            // For example, save it as an option or use it in your plugin logic
            update_option('onqueue_category_id', $category_id);
        }
    }
    register_activation_hook(__FILE__, 'autopost_install');

    //função que chama o arquivo chatgpt-post-status.js
    function chatgpt_post_status_enqueue_scripts($hook)
    {
        // Verifique se está na página de opções do plugin
        if ($hook != 'settings_page_chatgpt_plugin') {
            return;
        }

        // Registre e adicione o arquivo JavaScript
        wp_register_script('chatgpt_post_status', plugin_dir_url(__FILE__) . 'chatgpt-post-status.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('chatgpt_post_status');
    }

    //Vincula a açao da chamada do Javascript chatgpt-post-status.js
    add_action('admin_enqueue_scripts', 'chatgpt_post_status_enqueue_scripts');


    add_action('admin_menu', 'chatgpt_plugin_menu');




}



// Mostrar mensagem de sucesso quando os textos são criados e publicados com sucesso (FREE)
function chatgpt_show_success_message()
{
    add_settings_error('chatgpt_messages', 'chatgpt_message', 'Textos criados e publicados com sucesso!', 'updated');
}


function load_my_plugin_textdomain()
{
    load_plugin_textdomain('autopost', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'load_my_plugin_textdomain');

//
add_action('init', 'smart_schedule');
add_action('admin_footer-edit.php', 'my_custom_status_add_in_quick_edit');
add_action('admin_footer-post.php', 'my_custom_status_add_in_post_page');
add_action('admin_footer-post-new.php', 'my_custom_status_add_in_post_page');


function logging($string)
{
    // Get the root directory of your plugin
    $pluginRoot = plugin_dir_path(__FILE__);

    // Define the log file path relative to the plugin root
    $logFilePath = $pluginRoot . 'log.txt';

    // Open or create the log file in append mode
    $file = fopen($logFilePath, 'a');

    if ($file) {
        // Add a timestamp to the log entry
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $string" . PHP_EOL;

        // Write the log entry to the file
        fwrite($file, $logEntry);

        // Close the file
        fclose($file);
        return true;
    } else {
        // Handle the case where the file couldn't be opened
        echo "Failed to open the log file for writing.";
    }
}


?>