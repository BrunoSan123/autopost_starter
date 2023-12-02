<?php
    add_settings_section('openai_section', 'OpenAI Settings', 'openai_section_callback', 'chatgpt_openai_settings');

    // Register fields for the OpenAI settings
    add_settings_field('chatgpt_api_key', 'OpenAI API Key', 'chatgpt_api_key_field', 'chatgpt_openai_settings', 'openai_section');
    //add_settings_field('midjourney_api_key', 'Midjourney API Key', 'midjourney_api_key_field', 'chatgpt_openai_settings', 'openai_section');
    //add_settings_field('stable_diffusion_api_url', 'Stable Diffusion API URL', 'stable_diffusion_api_url_field', 'chatgpt_openai_settings', 'openai_section');
 
	
    // Register the settings
    register_setting('chatgpt_openai_settings', 'chatgpt_api_key');
    //register_setting('chatgpt_openai_settings', 'midjourney_api_key');
    //register_setting('chatgpt_openai_settings', 'stable_diffusion_api_url');

    
    // Social Media section
    add_settings_section('social_media_settings', 'Social Media Settings', 'chatgpt_social_media_settings_callback', 'chatgpt_social_media_settings');
    
    // Register fields for the Social Media settings
    add_settings_field('facebook_api_key', 'Facebook API Key', 'facebook_api_key_field', 'chatgpt_social_media_settings', 'social_media_settings');
    add_settings_field('facebook_token', 'Facebook Token', 'facebook_token_field', 'chatgpt_social_media_settings', 'social_media_settings');
    add_settings_field('linkedin_api', 'LinkedIn API', 'linkedin_api_field', 'chatgpt_social_media_settings', 'social_media_settings');
    
    // Register the settings
    register_setting('chatgpt_social_media_settings', 'facebook_api_key');
    register_setting('chatgpt_social_media_settings', 'facebook_token');
    register_setting('chatgpt_social_media_settings', 'linkedin_api');
    function openai_section_callback() {
        // Section callback (can be empty)
    }
    function chatgpt_api_key_field() {
        $value = get_option('chatgpt_api_key');
        echo '<input type="text" name="chatgpt_api_key" id="chatgpt_api_key" value="' . esc_attr($value) . '"/>';
        
    }
    // Callback function for the Midjourney API Key field
    function midjourney_api_key_field() {
        $midjourney_api_key = get_option('midjourney_api_key');
        echo '<input type="text" name="midjourney_api_key" value="' . esc_attr($midjourney_api_key) . '" />';
    }

    // Callback function for the Stable Diffusion API URL field
    function stable_diffusion_api_url_field() {
        $stable_diffusion_api_url = get_option('stable_diffusion_api_url');
        echo '<input type="text" name="stable_diffusion_api_url" value="' . esc_attr($stable_diffusion_api_url) . '" />';
    }
	
    // Callback function for the Social Media section
    function chatgpt_social_media_settings_callback() {
        //echo 'Configure your Social Media settings:';
    }

    // Callback functions for the social media fields
    function facebook_api_key_field() {
        $facebook_api_key = get_option('facebook_api_key');
        echo '<input type="text" name="facebook_api_key" value="' . esc_attr($facebook_api_key) . '" />';
    }

    function facebook_token_field() {
        $facebook_token = get_option('facebook_token');
        echo '<input type="text" name="facebook_token" value="' . esc_attr($facebook_token) . '" />';
    }

    function linkedin_api_field() {
        $linkedin_api = get_option('linkedin_api');
        echo '<input type="text" name="linkedin_api" value="' . esc_attr($linkedin_api) . '" />';
    }
