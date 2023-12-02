<?PHP 
function custom_txt2img_router_init() {
    register_rest_route('image_generate/v1', '/txt2img/', array(
        'methods' => array('GET', 'POST'), // Support both GET and POST methods
        'callback' => 'custom_txt2img_request',
        'permission_callback' => '__return_true',
        'args' => array(
            'prompt' => array(
                'required' => true,
                'type' => 'string',
            ),
            'width' => array(
                'required' => false,
                'type' => 'integer',
            ),
            'height' => array(
                'required' => false,
                'type' => 'integer',
            ),
            'steps' => array(
                'required' => false,
                'type' => 'integer',
            ),
        ),
    ));
}
add_action('rest_api_init', 'custom_txt2img_router_init');



function custom_txt2img_request($request, $prompt = '', $width = 512, $height = 512, $steps = 20) {
    // Extract and sanitize the request parameters
    $rprompt = sanitize_text_field($request->get_param('prompt'));
    $rwidth = (int) $request->get_param('width');
    $rheight = (int) $request->get_param('height');
    $rsteps = (int) $request->get_param('steps');
    $webuiApiURL = "http://192.168.2.158:7860";

    // // Set default values if parameters are empty
    // $default_prompt = 'Default Prompt'; // Change this to your default prompt
    // $default_width = 512; // Change this to your default width
    // $default_height = 512; // Change this to your default height
    // $default_steps = 10; // Change this to your default steps

    // Assign default values if parameters are empty
    $prompt = !empty($rprompt) ? $rprompt : $prompt;
    $width = ($rwidth > 0) ? $rwidth : $width;
    $height = ($rheight > 0) ? $rheight : $height;
    $steps = ($rsteps > 0) ? $rsteps : $steps;

    $r = custom_txt2img_request_get($prompt, $width, $height, $steps);
    return rest_ensure_response($r);
    
}
function custom_txt2img_request_get($prompt = '', $width = 512, $height = 512, $steps = 20) {
    // Extract and sanitize the request parameters
    //esse webuiApiURL deveria ser salvo nas configs do plugin, aí carrega ele aqui caso empty
    $webuiApiURL = "http://192.168.2.158:7860";
    
    // Define the URL for the external service
    
    $external_api_url = $webuiApiURL.'/sdapi/v1/txt2img';

    // Define the request data to send
    $request_data = array(
        'enable_hr' => false,
        'denoising_strength' => 0,
        'firstphase_width' => 0,
        'firstphase_height' => 0,
        'hr_scale' => 2,
        'hr_upscaler' => 'string',
        'hr_second_pass_steps' => 0,
        'hr_resize_x' => 0,
        'hr_resize_y' => 0,
        'prompt' => $prompt,
        'styles' => ['string'],
        'seed' => -1,
        'subseed' => -1,
        'subseed_strength' => 0,
        'seed_resize_from_h' => -1,
        'seed_resize_from_w' => -1,
        'sampler_name' => 'Euler',
        'batch_size' => 1,
        'n_iter' => 1,
        'steps' => $steps,
        'cfg_scale' => 7,
        'width' => $width,
        'height' => $height,
        'restore_faces' => false,
        'tiling' => false,
        'do_not_save_samples' => false,
        'do_not_save_grid' => false,
        'negative_prompt' => 'string',
        'eta' => 0,
        's_min_uncond' => 0,
        's_churn' => 0,
        's_tmax' => 0,
        's_tmin' => 0,
        's_noise' => 1,
        'override_settings' => [],
        'override_settings_restore_afterwards' => true,
        'sampler_index' => 'Euler',
        'send_images' => true,
        'save_images' => true,
    );

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $external_api_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request_data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 600);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));

    // Execute the cURL request
    try {
        $response_body = curl_exec($curl);
    
        // Check for cURL errors
        if (curl_errno($curl)) {
            throw new Exception('cURL error: ' . curl_error($curl));
        }
    
        // Your existing code for processing the response here...
        
        // Close the cURL session
        curl_close($curl);

        // Parse the response
        $decoded_response = json_decode($response_body, true);

        // Check for errors in the response
        if (is_array($decoded_response) && isset($decoded_response['error'])) {
            return rest_ensure_response(array('error' => $decoded_response['error']));
        }
        // Check if the response contains an image
        if (isset($decoded_response['images'][0]) && ($image_data = $decoded_response['images'][0])) {
            // Decode the base64 image data
            //$image_binary_data = base64_decode(explode(",", $image_data, 2)[1]);
        
            // Use your custom function to save the image and get the attachment ID
            $attachment_id = save_image($image_data, 'Generated Image');
        
            // Get the image URL
            $image_url = wp_get_attachment_url($attachment_id);
        
            // Return both the attachment ID and image URL
            $r = array(
                'attachment_id' => $attachment_id,
                'image_url' => $image_url,
            );
            return ($r);
        } else {
            return array('error' => 'Image not found in the response.');
        }
    } catch (Exception $e) {
        // Handle the exception and return the error response
        curl_close($curl);
        return array(
            'attachment_id' => 0,
            'image_url' => 'erro-na-geracao',
            'error_message' => $e->getMessage(), // Optionally, you can include the error message for debugging
        );
    }
}
function save_image( $base64_img, $title ) {

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = $title . '.jpeg';
	$file_type       = 'image/jpeg';
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
	);

	$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
    return $attach_id;
}