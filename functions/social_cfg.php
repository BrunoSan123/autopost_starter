<?php

use Noweh\TwitterApi\Client;

function custom_twitter_publish() {
    register_rest_route('twitter/v1', '/publish', array(
        'methods' => 'POST',
        'callback' => 'twitter_publish_callback',
        'permission_callback' => '__return_true',
    ));
}
function register_facebook_share_endpoint() {
    register_rest_route('custom/v1', '/share-on-facebook/', array(
        'methods' => 'POST',
        'callback' => 'share_on_facebook_callback',
        'permission_callback' => function () {
            // You can add your own permission checks here
            return current_user_can('publish_posts');
        },
    ));
}
function register_linkedin_share_endpoint() {
    register_rest_route('custom/v1', '/share-on-linkedin/', array(
        'methods' => 'POST',
        'callback' => 'share_on_linkedin_callback',
        'permission_callback' => function () {
            // You can add your own permission checks here
            return current_user_can('publish_posts');
        },
    ));
}

add_action('rest_api_init', 'custom_twitter_publish');
add_action('rest_api_init', 'register_facebook_share_endpoint');
add_action('rest_api_init', 'register_linkedin_share_endpoint');



// Callback function for the LinkedIn sharing endpoint
function share_on_linkedin_callback($data) {
    // Retrieve parameters from the REST request
    $token = $data['token'];
    $link = $data['link'];
    $title = $data['title'];
    $message = $data['message'];
    $comment = $data['comment'];

    // Call the share_on_linked_in method
    $user_id=get_linked_in_user_id($token);
    $response = share_on_linked_in($token, $link, $title, $message, $comment,$user_id);

    // Return the response to the client
    return rest_ensure_response(json_decode($response));
}


// Callback function for the custom endpoint
function share_on_facebook_callback($data) {
    // Retrieve parameters from the REST request
    $link = $data['link'];
    $access_token = $data['access_token'];
    $page_id = $data['page_id'];
    $message = $data['message'];

    // Call the share_on_facebook method
    $response = share_on_facebook($link, $access_token, $page_id, $message);

    // Return the response to the client
    return rest_ensure_response(json_decode($response));
}

function verify_social($page_id,$social,$link,$title,$message,$comment){
    global $wpdb;
    $table_name=$wpdb->prefix.'chat_gpt_social';

    $social_type ='';

    switch($social){
        case 'facebook':
            $social_type=$wpdb->get_var("SELECT fb_token FROM $table_name ");
            share_on_facebook($link,$social_type,$page_id,$message);
            break;
        case 'linkedin':
            $social_type=$wpdb->get_var("SELECT likd_in_token FROM $table_name ");
            //wp_send_json($social_type);
            $user_id=get_linked_in_user_id($social_type);
            $result=share_on_linked_in($social_type,$link,$title,$message,$comment,$user_id);
            return $result;
            break;
        case 'twitter':
            $social_type=$wpdb->get_results("SELECT * FROM $table_name ");
            share_on_twitter($link,$message,$social_type->twt_consumer_key,$social_type->twt_consumer_secret,$social_type->twt_access_token,$social_type->twt_token_secret);
    }
}







function share_on_facebook($link,$acces_token,$page_id,$message){
    $endpoint = "https://graph.facebook.com/v18.0/$page_id/feed";

    $data = array(
        'message' => $message,
        'link' => $link,
        'published' => 'true'
    );

        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $acces_token,
            'Content-Type: application/json',
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
}

function get_linked_in_user_id($token){
    $endpointGet='https://api.linkedin.com/v2/userinfo';

    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ];


    $ch = curl_init($endpointGet);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);


    if ($httpCode == 200) {
        // Manipule a resposta JSON conforme necessário
        $responseData = json_decode($response, true);
        return $responseData['sub'];
    } else {
        // Trate erros ou falhas na solicitação
        echo 'Erro na solicitação. Código de resposta: ' . $httpCode;
    }


}

function share_on_linked_in($token,$link,$title,$message,$comment,$userId){
    $endpointPost = 'https://api.linkedin.com/v2/ugcPosts';

    $post_data = json_encode([
        "author" => "urn:li:person:$userId",
        "lifecycleState" => "PUBLISHED",
        "specificContent" => [
            "com.linkedin.ugc.ShareContent" => [
                "shareCommentary" => [
                    "text" => $comment
                ],
                "shareMediaCategory" => "ARTICLE",
                "media" => [
                    [
                        "status" => "READY",
                        "description" => [
                            "text" => $message
                        ],
                        "originalUrl" => $link,
                        "title" => [
                            "text" => $title
                        ]
                    ]
                ]
            ]
        ],
        "visibility" => [
            "com.linkedin.ugc.MemberNetworkVisibility" => "PUBLIC"
        ]
    ]);

        // Configuração da requisição cURL
        $options = array(
            CURLOPT_URL => $endpointPost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'X-Restli-Protocol-Version: 2.0.0'
            ),
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
    
        // Executa a requisição POST
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
}

function twitter_publish_callback($request) {
    
    
    $link = $request['link'];
    $message = $request['message'];
    $consumerKey = $request['consumerKey'];
    $consumerSecret = $request['consumerSecret'];
    $accsToken = $request['accsToken'];
    $accsTokensecret = $request['accsTokensecret'];
    $result = share_on_twitter($link,$message,$consumerKey,$consumerSecret,$accsToken,$accsTokensecret);

    if (!empty($result)) {
        return rest_ensure_response($result, 'bn');
    } else {
        return rest_ensure_response(array('message' => 'No matching posts found'));
    }
}
function share_on_twitter($link,$message,$consumerKey,$consumerSecret,$accsToken,$accsTokensecret) {
    $url = 'https://api.twitter.com/2/tweets';
    $timestamp = time();
    $oauth_nonce = md5(mt_rand());
    
    $oauthBaseString = 'POST&' . rawurlencode($url) . '&';
    $oauthParameters = [
        'oauth_consumer_key' => $consumerKey,
        'oauth_nonce' => $oauth_nonce,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp' => $timestamp,
        'oauth_token' => $accsToken,
        'oauth_version' => '1.0',
    ];

    // Ordena os parâmetros
    ksort($oauthParameters);
    $oauthBaseString .= rawurlencode(http_build_query($oauthParameters, '', '&', PHP_QUERY_RFC3986));
    $oauthKey = rawurlencode($consumerSecret) . '&' . rawurlencode($accsTokensecret);
    $oauthSignature = base64_encode(hash_hmac('sha1', $oauthBaseString, $oauthKey, true));

    $headers = [
        'Content-Type: application/json',
        'Authorization: OAuth ' .
            'oauth_consumer_key="' . rawurlencode($consumerKey) . '",' .
            'oauth_nonce="' . rawurlencode($oauth_nonce) . '",' .
            'oauth_signature="' . rawurlencode($oauthSignature) . '",' .
            'oauth_signature_method="HMAC-SHA1",' .
            'oauth_timestamp="' . rawurlencode($timestamp) . '",' .
            'oauth_token="' . rawurlencode($accsToken) . '",' .
            'oauth_version="1.0"',
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"text": "'.$message.': '.$link. '"}',
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;   

}