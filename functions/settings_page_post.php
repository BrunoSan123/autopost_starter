<?php
// settings_page_post.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the request method is POST

    if (isset($_POST['save_general_settings'])) {
        // Handle General settings form submission
        $general_settings = $_POST['general_settings'];
        // Update and save the settings as needed
        // ...

    } elseif (isset($_POST['save_openai_settings'])) {
        // Handle OpenAI settings form submission
        $openai_settings = $_POST['openai_settings'];
        // Update and save the OpenAI settings as needed
        // ...

    } elseif (isset($_POST['save_midjourney_settings'])) {
        // Handle Midjourney settings form submission
        $midjourney_settings = $_POST['midjourney_settings'];

        // Update and save the Midjourney settings as needed
        // ...

    } elseif (isset($_POST['save_stablediffusion_settings'])) {
        // Handle Stable Diffusion settings form submission
        $stablediffusion_settings = $_POST['stablediffusion_settings'];

        // Update and save the Stable Diffusion settings as needed
        // ...
    }

    // Redirect back to the settings page
    wp_redirect(admin_url('admin.php?page=chatgpt_plugin'));
    exit;
}
