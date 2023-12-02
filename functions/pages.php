<?php
function chatgpt_plugin_menu()
{
    add_menu_page(
        __('Configurações do ChatGPT Autopost', 'textdomain'),
        'Auto Post',
        'manage_options',
        'chatgpt_plugin',
        'chatgpt_plugin_dashboard_page',
        plugins_url('auto_post_application/images/autopost.png'),
        6
    );
    // Add a sub-menu item

    add_submenu_page(
        'chatgpt_plugin',
        'Dashboard Page',
        'Dashboard',
        'manage_options',
        'chatgpt_dashboard_page',
        'chatgpt_plugin_dashboard_page'
    );
    function chatgpt_plugin_dashboard_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/dashboard.php';
    }
    add_submenu_page(
        'chatgpt_plugin',
        // Parent menu slug
        'Projects Page',
        // Page title
        'Projects',
        // Menu title
        'manage_options',
        // Capability required
        'chatgpt_projects_page',
        // Menu slug
        'chatgpt_plugin_projects_page' // Callback function to display the submenu page
    );
    function chatgpt_plugin_projects_page()
    {
        // Include the projects_page.php template
        include plugin_dir_path(__FILE__) . 'projects_page.php';
    }
    add_submenu_page(
        'chatgpt_plugin',
        // Parent menu slug
        'Submenu Page',
        // Page title
        'Settings',
        // Menu title
        'manage_options',
        // Capability required
        'chatgpt_settings_page',
        // Menu slug
        'chatgpt_plugin_settings_page' // Callback function to display the submenu page
    );
    include plugin_dir_path(__FILE__) . 'settings/fields.php';

    add_submenu_page(
        'chat_gpt_quick_start_part_1',
        // It has no parent because it's hidden
        'Quick Start!',
        'Quick Start!',
        'manage_options',
        'chat_gpt_quick_start_part_1',
        'chat_gpt_quick_start_part_1_page'
    );
    function chat_gpt_quick_start_part_1_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/quick_start/part_1/page.php';
    }

    add_submenu_page(
        'chat_gpt_quick_start_part_2',
        // It has no parent because it's hidden
        'Quick Start!',
        'Quick Start!',
        'manage_options',
        'chat_gpt_quick_start_part_2',
        'chat_gpt_quick_start_part_2_page'
    );
    function chat_gpt_quick_start_part_2_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/dashboard.php';
        include plugin_dir_path(__FILE__) . 'pages/quick_start/part_2/page.php';
    }
    remove_submenu_page('chatgpt_plugin', 'chatgpt_plugin');
}

function openai_api_key_field()
{
    $value = get_option('openai_api_key');
    echo '<input type="text" name="openai_api_key" id="openai_api_key" value="' . esc_attr($value) . '"/>';
}

function load_ico()
{
    include dirname(__FILE__) . '/../images/autopost.png';
}
