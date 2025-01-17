<?php

function generate_image_with_dall_e($api, $prompt, $post_id, $size) {
    $dall_e_api_url = 'https://api.openai.com/v1/images/generations';

    $request_data = array(
        'prompt' => $prompt,
        'n' => 1,
        'size' => $size
    );
    $headers = array(
        'Content-type: application/json',
        'Authorization: Bearer ' . $api,
    );

    try {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $dall_e_api_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request_data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        $response_data = json_decode($response, true);

        if (isset($response_data['data'])) {
            importar_imagem_destaque($response_data['data'][0]['url'], $post_id, $prompt);
            return $response_data;
        } else {
            throw new Exception('API response does not contain image data.');
        }
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}

function search_image_with_google($prompt,$api_key,$search_id,$post_id){
    $google_url='https://www.googleapis.com/customsearch/v1?q='.$prompt.'&key='.$api_key.'&cx='.$search_id.'&searchType=image&rights=cc_attribute';
    $curl =curl_init($google_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como uma string
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Desativa a verificação SSL (não recomendado para produção)
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Erro cURL: ' . curl_error($curl);
    }
    
    // Fecha a conexão cURL
    curl_close($curl);
    $response_data =json_decode($response);
    //print_r(var_dump($response_data->items[0]->link));
    if(isset($response_data->items)){
        echo '<img id="img_test" src="'.$response_data->items[0]->link.'"/>';
        importar_imagem_destaque($response_data->items[0]->link,$post_id,$prompt);
    }else{
        return print_r('Erro na imagem');
    }
}

function upload_image($post_id){
    $file=$_FILES['image_upload'];
    $name=$file['name'];
    $tmp_name = $file['tmp_name'];
    $wp_main_dir = ABSPATH;
    $temp_path = $wp_main_dir.'wp-content/uploads/'. $name;
    move_uploaded_file($tmp_name,$temp_path);
    $filetype = wp_check_filetype( $temp_path, null );
    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name( $name ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    );
    $attachment_id = wp_insert_attachment( $attachment, $temp_path );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attachment_id, $temp_path );
    wp_update_attachment_metadata( $attachment_id, $attach_data );
    set_post_thumbnail( $post_id, $attachment_id );
    return 'Imagem inserida na biblioteca e definida como imagem de destaque com o ID: ' . $attachment_id;

}


function importar_imagem_destaque($imagem_url, $post_id,$image_name) {
    // Certifique-se de que o WordPress está carregado
    if ( ! defined( 'ABSPATH' ) ) {
        require_once( 'wp-load.php' );
    }

    // Faz a requisição segura para obter o conteúdo da imagem
    $args=array('timeout'=>80);
    $response = wp_remote_get( $imagem_url,$args );
    //print_r($response);
    //var_dump($response);

    // Verifica se a requisição foi bem-sucedida
    if ( is_wp_error( $response ) ) {
        // Lida com o erro, se necessário
        return 'Erro ao buscar a imagem: ' . esc_html( $response->get_error_message() );
    } else {
        // Obtém o conteúdo da resposta
        $body = wp_remote_retrieve_body( $response );


        // Gere um nome de arquivo para a imagem (você pode personalizá-lo conforme necessário)
        $filename = md5($image_name . microtime()).'.jpg';

        // Caminho completo para onde a imagem será salva temporariamente
        $wp_main_dir = ABSPATH;
        $temp_path = $wp_main_dir.'wp-content/uploads/'. $filename;
        

        // Salva o conteúdo da imagem em um arquivo temporário
        file_put_contents( $temp_path, $body );

        // Configuração do tipo de mídia a ser inserido na biblioteca
        $filetype = wp_check_filetype( $temp_path, null );

        // Array de dados do arquivo a ser inserido na biblioteca
        $attachment = array(
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Faz o upload do arquivo para a biblioteca de mídia
        $attachment_id = wp_insert_attachment( $attachment, $temp_path );

        // Atualiza metadados do arquivo
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $temp_path );
        wp_update_attachment_metadata( $attachment_id, $attach_data );

        // Define a imagem como imagem de destaque do post
        set_post_thumbnail( $post_id, $attachment_id );

        // Opcional: Exibe o ID do anexo inserido
        return 'Imagem inserida na biblioteca e definida como imagem de destaque com o ID: ' . $attachment_id;
    }
}

