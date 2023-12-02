<?php 
//require_once(plugin_dir_path(__FILE__) . '/functions/stable_diffusion_functions.php');
//require_once(dirname(__FILE__) . '/stable_diffusion_functions.php');
require_once(dirname(__FILE__).'/ia_image_generation_functions.php');
require_once(dirname(__FILE__).'/open_ai_apis/gpt.php');
function custom_gpt_router_init() {
    register_rest_route('text_generate/v1/', '/gpt-generate-text/', array(
        'methods' => 'GET', 'POST',
        'callback' => 'custom_gpt_generate_text',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'custom_gpt_router_init');

function register_custom_rest_route() {
    register_rest_route('text_generate/v1', 'usage', array(
        'methods' => 'GET', // You can change the HTTP method (GET, POST, etc.)
        'callback' => 'generate_gpt_request_usage',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'register_custom_rest_route');
class GPTRequest {
    public $api_key;
    public $key;
    public $temperature;
    public $max_ctx_tokens;
    public $amount_to_gen;
    public $repetition_penalty;
    public $big_text_count;
    public $selected_model;
    public $language;

    public function __construct($api_key, $key, $temperature = 0.7, $max_ctx_tokens = 700, $amount_to_gen = 400, $repetition_penalty = 1.0, $big_text_count = 4, $selected_model = 'gpt-3.5-turbo-16k') {
        $this->api_key = $api_key;
        $this->key = $key;
        $this->temperature = $temperature;
        $this->max_ctx_tokens = $max_ctx_tokens;
        $this->amount_to_gen = $amount_to_gen;
        $this->repetition_penalty = $repetition_penalty;
        $this->big_text_count = $big_text_count;
        $this->selected_model = $selected_model;
    }
    public function generate_gpt_request($text = '', $keyword = '', $prompt = '[i]', $trim = true) {
        $final_prompt = $this->promptParser(' write the text in '.$language . ' about '.$prompt);
        $json_final_response = gpt_query_large($this->selected_model, $text, $final_prompt, $this->temperature, $this->max_ctx_tokens, $this->api_key);

        if (isset($json_final_response['error'])) {
            return array('Error: ' . $json_final_response['error']['message']);
        } else {
            $real_generated_text = $json_final_response['choices'][0]['message']['content'];

            if ($trim) {
                $real_generated_text = $this->trim_sentences($real_generated_text);
            }

            return $real_generated_text;
        }
    }
    
    public function trim_sentences($text, $stop = '.') {
        $lastPeriodPos = strrpos($text, $stop);
        if ($lastPeriodPos !== false) {
            return substr($text, 0, $lastPeriodPos + 1);
        } else {
            return $text; // If the stop character is not found, return the original text
        }
    }

    private function promptParser($prompt_value) {
        return str_replace('[i]', $this->key, $prompt_value);
    }
}
function generate_gpt_request_usage($keyword = 'test', $prompt = 'test') {
    
    $api_key = get_option('chatgpt_api_key');
    //$model = "gpt-3.5-turbo-16k";
    $model ="gpt-4";
    $text = "phantasy star online 2 ngs";
    $final_prompt = $text;
    //$json_final_response = gpt_usage_request($api_key);
    $json_final_response = gpt_query_large($model, $text, $final_prompt, 0.7, 300, $api_key);
    return $json_final_response;
}
function custom_gpt_generate_text($request) {
    
    // Extract and sanitize the parameters
    $keyword = $request->get_param('gpt_field') ? sanitize_text_field($request->get_param('gpt_field')) : '';
    $temperature = $request->get_param('temperature') ? (float) $request->get_param('temperature') : 0.7;
    $max_ctx_tokens = $request->get_param('maxCtxTokens') ? (int) $request->get_param('maxCtxTokens') : 700;
    $amount_to_gen = $request->get_param('amountToGen') ? (int) $request->get_param('amountToGen') : 400;
    $repetition_penalty = $request->get_param('repetitionPenalty') ? (float) $request->get_param('repetitionPenalty') : 1.0;
    $big_text_count = $request->get_param('bigTextCount') ? (int) $request->get_param('bigTextCount') : 3;
    $image_number=retriveAutopostconfig();


    // Call your GPT code with the extracted parameters
    $api_key=get_option('chatgpt_api_key');
    $GPTrequest = new GPTRequest($api_key, $keyword, $temperature, $max_ctx_tokens, $amount_to_gen, $repetition_penalty, $big_text_count, 'gpt-3.5-turbo-16k');
    $response = chatgpt_generate_text_request($GPTrequest);
    $html_content = wrap_sections_with_h1($response);
    $html_content = add_images_between_sections($response,$image_number->image_quantity, $api_key);
    $html_content = append_related_posts_as_gutenberg_block($keyword, $html_content);
    generate_post($keyword, $html_content);
}

function chatgpt_generate_text_request(GPTRequest $request) {
    $prompt = 'Crie uma outline com ao menos 10 seções sobre esse conteúdo' . $request->key . ', considere uma estrutura de reviews';

    // Make sure to access the parameters from the $request object
    $selected_model = $request->selected_model;
    $temperature = $request->temperature;
    $max_ctx_tokens = $request->max_ctx_tokens;
    $repetition_penalty = $request->repetition_penalty;
    $api_key = $request->api_key;

    $json_response = gpt_query($selected_model, $request->key, $prompt, $temperature, $max_ctx_tokens, $repetition_penalty, $api_key);
    
    if (isset($json_response['error'])) {
        return 'Error: ' . $json_response['error']['message'];
    } else {
        $generated_text = '';
        if (isset($json_response['choices'])) {
            $messages = $json_response['choices'];
            foreach ($messages as $message) {
                if ($message['message']['role'] == 'assistant') {
                    $generated_text = $message['message']['content'];
                    $final_text = generate_big_text_request($generated_text, $api_key, $temperature, $request->amount_to_gen, $request->big_text_count, $selected_model);
                    //echo '<p>'.$final_text.'</p>';
                }
            }
        } else {
            return 'Error: Unexpected response format.';
        }
    }
    return $final_text;
}


function generate_big_text_request($text, $api_key, $temperature, $max_ctx_tokens, $rounds = 2, $selected_model = 'gpt-3.5-turbo-16k') {
    $big_texts = array(); // Initialize an array to store generated texts

    for ($i = 0; $i <= $rounds; $i++) {
        $final_prompt = 'Tendo em vista esta estrutura. Gere a seção [' . $i . '] com tom explicativo, formal, com ao menos 250 palavras';
        $json_final_response=gpt_query_large($selected_model,$text,$final_prompt,$temperature,$max_ctx_tokens,$api_key);

        if (isset($json_final_response['error'])) {
            return array('Error: ' . $json_final_response['error']['message']);
        } else {
            $real_generated_text = $json_final_response['choices'][0]['message']['content'];
            $big_texts[] = $real_generated_text; // Add each generated text to the array
        }
    }
    return $big_texts; // Return the array of generated texts
}


function generate_image_prompt($text, $api_key, $model = 'gpt-3.5-turbo-16k', $temperature = 0.7, $max_ctx_tokens = 250) {
    $big_text = ''; // Initialize an array to store generated texts
    //print_r($text);

    $final_prompt = 'Examples of high quality prompt for stunning photorealistic illustration for text-to-image models (Stable Diffusion, midjourney or Dalle2) are

     trending on pixiv, detailed, clean lines, sharp lines, crisp lines, award winning illustration, masterpiece, 4k, eugene de blaas and ross tran, vibrant color scheme, intricately detailed
    
     alberto seveso and geo2099 style, trending on artstation, butterflies, floral, sharp focus, studio photo, intricate details, highly detailed, by Tvera and wlop and artgerm
     
     with the examples given, convert this text to a prompt like the examples given:
     '.$text.'
     Give me a single example, Your secondary goal is to use the fewest tokens possible';
        $json_final_response=gpt_query_large($model,$text,$final_prompt,$temperature,$max_ctx_tokens,$api_key);

        if (isset($json_final_response['error'])) {
            return array('Error: ' . $json_final_response['error']['message']);
        } else {
            $real_generated_text = $json_final_response['choices'][0]['message']['content'];
            $big_text = $real_generated_text; // Add each generated text to the array
        }
    //logging("generate_image_prompt() \n received: $text \n generated prompt: $big_text");
    return $big_text; // Return the array of generated texts
}
function generate_post($post_title, $post_content, $image_id = 0){
    $new_post = array(
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_status' => 'draft',
        'post_type' => 'post', // Change to your desired post type
        'post_name' => $post_title,
        'meta_query' => array(
            array(
                'key'   => 'autopost',
                'value' => 'true',
            ),
        ),
    );

    $post_id = wp_insert_post($new_post);

    if ($post_id) {
        $attach_id = $image_id;

        if (!is_wp_error($attach_id)) {
            // Set the attachment as the featured image for the post
            //update_post_meta($post_id, '_thumbnail_id', $attach_id);
            //set_post_thumbnail($post_id, $attach_id);
            
        }

        // Redirect to the newly created post
        //wp_redirect(get_permalink($post_id));

        $edit_post_url = get_edit_post_link($post_id, '');
        wp_redirect($edit_post_url);
        exit;
    } else {
        echo 'Error creating the post';
    }
}
/**
 * Add images between text sections.
 *
 * @param array    $text_sections   Array of text sections.
 * @param int      $image_quantity  Number of images to generate.
 * @param string   $api_key         API key for external services.
 * @param bool     $offset          Whether to shift the array offset.
 * @param int      $width           Image width.
 * @param int      $height          Image height.
 * @param int      $steps           Number of steps for image generation.
 * @return string  HTML content with text and images.
 */
function add_images_between_sections($text_sections,$image_quantity, $api_key, $offset = false, $width = 512, $height = 512, $steps = 20){
    // Check if the GPT response is an array
    if (is_array($text_sections)) {
        // Initialize the HTML content
        $htmlContent = '';
        if ($offset){
            array_shift($text_sections);
        }
        // Loop through the array and generate image prompts
        //print_r($response);
        for($i=0;$i<$image_quantity;$i++) {
            $imagePrompt = generate_image_prompt($text_sections[$i], $api_key);
            //$imagePrompt = "Stunning photorealistic illustration of a mesmerizing sunset landscape, with vibrant colors and intricate details, trending on Behance.";
            if (!is_array($imagePrompt) && !empty($imagePrompt)) {
                // Call custom_txt2img_request for each image prompt
                $imageResponse = dalle_txt_2_image($imagePrompt,'512x512',$api_key);

                // Check if the image response is valid
                if (isset($imageResponse)) {
                    
                    $path=get_site_url().'/wp-content/uploads/'.$imageResponse;
                    $htmlContent .= '<p>' . $text_sections[$i] . '</p>';
                    if(!empty($path)){
                        $htmlContent .= '<img src="' . esc_url($path) . '">';
                    }
                }
                else {
                    logging("image response error line 218 $imageResponse");
                    $htmlContent .= '<p>' . $text_sections[$i] . '</p>';
                    $htmlContent .= '<img href="erro ao gerar imagem, sem prompt">';
                }
            }
            else{
                $htmlContent .= '<p>' . $text_sections[$i] . '</p>';
                $htmlContent .= '<img href="erro ao gerar imagem, sem prompt">';
            }
        }
        return $htmlContent;
    } else {
        return rest_ensure_response(array('error' => 'Invalid response from GPT.'));
    }
}
function generate_image_dall_e_html($api_key,  $Prompt, $height = 512, $width = 512 ){
    $imagePrompt = generate_image_prompt($Prompt, $api_key);
    $imageResponse = dalle_txt_2_image($imagePrompt,'512x512',$api_key);
    $htmlContent = '';
    // Check if the image response is valid
    if (isset($imageResponse)) {
        
        $path=get_site_url().'/wp-content/uploads/'.$imageResponse;
        if(!empty($path)){
            $htmlContent .= '<p><img src="' . esc_url($path) . '"></p>';
        }
    }
    else {
        logging("image response error line 218 $imageResponse");
        $htmlContent .= '<img href="erro ao gerar imagem, sem prompt">';
    }
    return $htmlContent;
}
function wrap_sections_with_h1($textArray) {
    // Initialize an empty array to store the processed sections
    $processed_sections = array();

    foreach ($textArray as $text) {
        // Split the text into sections based on double line breaks
        $sections = preg_split('/\n\n+/', $text);

        foreach ($sections as $section) {
            // Trim leading and trailing whitespace from each section
            $section = trim($section);

            // Skip empty sections
            if (!empty($section)) {
                // Wrap the section with <h1> tags
                $processed_sections[] = '<h1>' . $section . '</h1>';
            }
        }
    }

    // Combine the processed sections into a single string
    $html_text = implode("\n\n", $processed_sections);

    return $html_text;
}
function trim_sentences($text, $stop = '.'){
    $lastPeriodPos = strrpos($text, $stop);
    if ($lastPeriodPos !== false) {
        return substr($text, 0, $lastPeriodPos + 1);
    } else {
        return $text; // If the stop character is not found, return the original text
    }
}
function trim_sentences_in_array($array, $stop = '.'){
    foreach ($array as $key => $text) {
        $lastPeriodPos = strrpos($text, $stop);
        if ($lastPeriodPos !== false) {
            $array[$key] = substr($text, 0, $lastPeriodPos + 1);
        } // No else condition because we want to keep the original text if the stop character is not found.
    }
    return $array;
}
