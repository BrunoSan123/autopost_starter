<?php

function translate_prompt($prompt,$target){
    $translateToken = get_option('google_translate_key');

$url = "https://translation.googleapis.com/language/translate/v2?key=" . $translateToken;

$data = array(
    'q' => $prompt,
    'target' => $target,
    'format' => 'text'
);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    // Handle error
    die('Curl error: ' . curl_error($ch));
}

curl_close($ch);

if (isset($responseData['error'])) {
    // Handle API error
    $errorMessage = $responseData['error']['message'] ?? 'Unknown API error';
    return 'API error: ' . $errorMessage;
}

$translatedText = json_decode($response, true)['data']['translations'][0]['translatedText'];

return $translatedText;
}