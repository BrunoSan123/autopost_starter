<?php
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/quickstart/', array(
        'methods' => 'POST',
        'callback' => 'process_data_callback',
        'permission_callback' => '__return_true',
    ));
});

// Callback function to process data
function process_data_callback($data) {
    // Access data sent from the client-side
    $imagesApiValue = sanitize_text_field($data['imagesApi']);
    $textApiValue = sanitize_text_field($data['textApi']);
    $midjourneyApiKey = sanitize_text_field($data['midjourneyApiKey']);
    $gpt3ApiKey = sanitize_text_field($data['gpt3ApiKey']);
    $experienceValue = sanitize_text_field($data['experience']);
    $preferredTopicsValue = sanitize_text_field($data['preferredTopics']);

    //update_option('images_api_key', $imagesApiValue);
    //update_option('text_api_key', $textApiValue);
    //update_option('midjourney_api_key', $midjourneyApiKey);
    update_option('chatgpt_api_key', $gpt3ApiKey);
    //update_option('experience', $experienceValue);
    //update_option('preferred_topics', $preferredTopicsValue);

    // // Example: Print the received data
    // echo "Images API: $imagesApiValue\n";
    // echo "Text API: $textApiValue\n";
    // echo "Midjourney API Key: $midjourneyApiKey\n";
    // echo "GPT-3 API Key: $gpt3ApiKey\n";
    // echo "Experience: $experienceValue\n";
    // echo "Preferred Topics: $preferredTopicsValue\n";

    // You can return a response if needed
    return new WP_REST_Response('Data received successfully', 200);
}

