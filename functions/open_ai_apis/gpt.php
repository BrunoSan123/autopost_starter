<?php

function gpt_query($selected_model,$key,$prompt,$temperature,$max_ctx_tokens,$repetition_penalty,$api_key){
    $url = 'https://api.openai.com/v1/chat/completions';
    $body = array(
        'model' => $selected_model,
        'messages' => array(
            array(
                'role' => 'system',
                'content' => $key
            ),
            array(
                'role' => 'user',
                'content' => $prompt
            ),
        ),
        'temperature' => $temperature,
        'max_tokens' => $max_ctx_tokens, // Reduzindo um pouco para ter uma margem de segurança
        'frequency_penalty' => $repetition_penalty,
    );
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
    //curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 0); // isso faz esperar indefinidamente
    curl_setopt($curl, CURLOPT_TIMEOUT, 800);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);

    if ($response === false) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        return 'Error: ' . $error_msg;
    }
    curl_close($curl);
    $json_response = json_decode($response, true);
    insert_gpt_response_data($json_response);
    return $json_response;
}

function gpt_query_large($selected_model,$text,$final_prompt,$temperature,$max_ctx_tokens,$api_key){
    $final_body = array(
        'model' => $selected_model,
        'messages' => array(
            array(
                'role' => 'system',
                'content' => $text,
            ),
            array(
                'role' => 'user',
                'content' => $final_prompt,
            ),
        ),
        'temperature' => $temperature,
        'max_tokens' => $max_ctx_tokens,
    );
    $second_headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    );
    $url_second = 'https://api.openai.com/v1/chat/completions';
    $curl_final_text = curl_init();
    curl_setopt($curl_final_text, CURLOPT_URL, $url_second);
    curl_setopt($curl_final_text, CURLOPT_POST, 1);
    curl_setopt($curl_final_text, CURLOPT_POSTFIELDS, json_encode($final_body));
    curl_setopt($curl_final_text, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($curl_final_text, CURLOPT_CONNECTTIMEOUT , 0); // isso faz esperar indefinidamente
    curl_setopt($curl_final_text, CURLOPT_TIMEOUT, 800);
    curl_setopt($curl_final_text, CURLOPT_HTTPHEADER, $second_headers);

    $response_new = curl_exec($curl_final_text);

    if ($response_new === false) {
        $error_msg = curl_error($curl_final_text);
        curl_close($curl_final_text);
        return array('Error: ' . $error_msg);
    }
    curl_close($curl_final_text);
    $json_final_response = json_decode($response_new, true);
    insert_gpt_response_data($json_final_response);
    return $json_final_response;
}
function gpt_usage_request($api_key) {
    //$url = 'https://api.openai.com/dashboard/billing/credit_grants';
    //$url = "https://api.openai.com/dashboard/billing/subscription";
    $url = "https://api.openai.com/dashboard/billing/usage";

    $headers = array(
        'Authorization: Bearer ' . $api_key,
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($curl);

    if ($response === false) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        return array('Error: ' . $error_msg);
    }

    curl_close($curl);
    $json_final_response = json_decode($response, true);

    return $json_final_response;
}
