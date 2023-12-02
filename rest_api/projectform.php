<?php
// Add your custom route using the rest_api_init hook
require_once(dirname(__FILE__).'/../functions/social_cfg.php');
require_once(dirname(__FILE__)  .'/../functions/daily_schedule.php');
require_once(dirname(__FILE__).'/../functions/translate_query.php');
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/process_form', array(
        'methods' => 'POST',
        'callback' => 'process_form_data',
        'permission_callback' => '__return_true',
    ));
});
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/image_upload', array(
        'methods' => 'POST',
        'callback' => 'process_image_upload',
        'permission_callback' => '__return_true',
    ));
});

function process_image_upload($request) {
    //return $request->get_params();
    // Process the uploaded file
    $file = $_FILES['file'];
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
    return array('image_id' => $attachment_id);
}
class CustomData {
    // Properties
    public $type;
    public $data;
    public $prompt;

    // Constructor
    public function __construct($type, $data, $prompt) {
        $this->type = $type;
        $this->data = $data;
        $this->prompt = $prompt;
    }

    // Getter methods
    public function getType() {
        return $this->type;
    }

    public function getData() {
        return $this->data;
    }

    public function getPrompt() {
        return $this->prompt;
    }
}


// Define the callback function to handle the POST request
function process_form_data(WP_REST_Request $request) {
    // Get the request data
    $data = $request->get_json_params();
    $defaults = array(
        "topics" => "",
        "llm" => "ChatGPT",
        "image_generators" => "Stable Diffusion",
        "temperature" => "0.7",
        "max_ctx_tokens" => "1025",
        "amount_to_gen" => "400",
        "repetition_penalty" => "0",
        "big_text_count" => "2",
    );
    $data = array_merge($defaults, $data);
    //return $data;
    // Explode the topics by newlines
    $topics = explode("\n", $data['topics']);

    // Create an object to store topics and their associated posts
    $topicPosts = array();

    foreach ($topics as $topic) {
        // Remove any leading or trailing whitespace from the topic
        $topic = trim($topic);

        if (!empty($topic)) {
            // Create an individual post for each topic
            $topicData = array_merge($defaults, $data);
            $topicData['topics'] = $topic;
            $topic_to_translate=translate_prompt($topic,$data['language']);
            //return wp_send_json($topic_to_translate);
            
            $post = create_ia_post($topic_to_translate, $topicData);
            $topicPosts[] = (object) array(
                'topic' => $topic_to_translate,
                'post' => $post,
            );
        }
    }
    // Initialize an array to store the generated texts
    $generatedTexts = array();

    foreach ($topicPosts as $topicPost) {
        try {
            $api_key = get_option('chatgpt_api_key');
    
            $topicData = array_merge($defaults, $data);
            $topicData['topics'] = $topicPost->topic;
            $gen = generateImageContent($api_key, $topicPost->post, $topicData,'',true);
            // Generate text for the current topic
            $new_text = creation_buffer($api_key, $topicData);
            
            // Store the generated text for this topic
            $generatedTexts[] = $new_text;
            // Conclude the post for the current topic
            conclude_ia_post($topicPost->post, $new_text, $topicData["post_status"], $topicData["schedule_datetime"]);
        } catch (Exception $e) {
            // Handle the exception here
            echo 'Error: ' . $e->getMessage();
            // Optionally, you can log the error or perform other actions as needed
        }
    }
    

    return rest_ensure_response($generatedTexts);
}


// Function to create a new draft post in WordPress
function create_ia_post($title, $content) {
    if (is_array($content)) {
        // Convert the array to a JSON string
        $content = json_encode($content);
    }
    $post = array(
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => 'draft',
        'post_category' => array(get_cat_ID('onQueue')), // Add 'onqueue' category
        'meta_input'    => array(
            'autopost' => 'true',
            // Add more custom data as needed
        ),
    );
    

    // Insert the post into the database
    $post_id = wp_insert_post($post);

    $link=get_permalink($post_id);

        $toogle=get_option('social_toggle');
        if($toogle==true){
            global $wpdb;
            $table_name=$wpdb->prefix.'chat_gpt_social';
            $pg_id=$wpdb->get_var("SELECT page_id FROM $table_name");
            $fb_active=$wpdb->get_var("SELECT fb_active FROM $table_name");
            $linkdin_active=$wpdb->get_var("SELECT linked_in_active FROM $table_name");
            $twt_active=$wpdb->get_var("SELECT twitter_active FROM $table_name");
            if($fb_active==1){
                verify_social($pg_id,'facebook',$link,'','New Post on this blog','');
            }
            if($linkdin_active==1){
                $result=verify_social(null,'linkedin',$link,'Look this post one','New Post on this blog','');
                //wp_send_json("linkedin".$result);
            }
            if($twt_active==1){
                verify_social(null,'twitter',$link,'','New Post on this blog','');
            }
    
        }
        
    





    return $post_id;
}

// Function to conclude a draft post in WordPress
function conclude_ia_post($post_id, $new_text, $post_status='draft', $schedule_datetime = null) {
    // Remove 'onqueue' category from the post
    wp_remove_object_terms($post_id, get_term_by('slug', 'onqueue', 'category')->term_id, 'category');

    // Prepare the post data
    $post_data = array(
        'ID' => $post_id,
        'post_content' => $new_text,
    );

    // Set the post status based on the provided value
    if ($post_status === 'publish') {
        $post_data['post_status'] = 'publish';
    } elseif ($post_status === 'future' && $schedule_datetime) {
        $post_data['post_status'] = 'future';
        $post_data['post_date'] = $schedule_datetime;
    }elseif($post_status=='post_queue'){
        $post_data['post_status'] = 'post_queue';
    }
    // Update the post
    wp_update_post($post_data);
    //custom_update_post($post_data);
    
    //daily_schedule();
}

function creation_buffer($api_key, $data){
    // Initialize the array to store CustomData objects
    $customDataArray = [];

    // Add the first item with type "text" and prompt from $data['prompt_intro']
    $to_translate_intro=($data['prompt_intro'] != "") ? $data['prompt_intro'] : 'Tendo [i] como tema. Gere a seção Introdução com tom explicativo, formal, com ao menos 250 palavras';

    $introPrompt = translate_prompt($to_translate_intro,$data['language']);
    $to_translate_conclusion=($data['prompt_conclusion'] != "") ? $data['prompt_conclusion'] : 'Termine um artigo tendo [i] como tema.';
    $conclusionPrompt = translate_prompt($to_translate_conclusion,$data['language']);

    $customDataArray[] = new CustomData('text', $data, $introPrompt );

    //$retriveAutopostconfig = retrieveImageQuantity();
    $imageNumber = 1;//(int)$retriveAutopostconfig;
    // Add section prompts and images
    for ($i = 1; $i <= $data['big_text_count']; $i++) {
        // Add CustomData with type "text" and section prompt
        $to_translate_section="Tendo [i] como tema. Gere a seção $i com tom explicativo, formal, com 250 palavras";
        $sectionPrompt = translate_prompt($to_translate_section,$data['language']);
        $customDataArray[] = new CustomData('text', $data, $sectionPrompt);

        // Add CustomData with type "image" and quantity from retriveAutopostconfig()
        if($imageNumber>0){
            $customDataArray[] = new CustomData('image', $data, null);
            $imageNumber--;
        }
    }
    // Add the last item with type "text" and prompt from $data['prompt_conclusion']
    $customDataArray[] = new CustomData('text', $data, $conclusionPrompt);
    // Combine all text and images
    $responses= [];
    foreach ($customDataArray as $customData) {
        if ($customData->getType() === 'text') {
            try {
                $response = generateLLMContent($api_key, $customData->getData(), $customData->getPrompt());
                $responses[] = $response;
            } catch (Exception $e) {
                $responses[] = ('Error generating LLM content: ' . $e->getMessage());
            }
        } elseif ($customData->getType() === 'image') {
            try {
                $response = generateImageContent($api_key, null, $customData->getData(), end($responses));
                $responses[] = $response;
            } catch (Exception $e) {
                $responses[] = ('Error generating image content: ' . $e->getMessage());
            }
        }
    }

    // Add related posts and return the combined text
    if ($data["autolink"] == "on") {
        $topicName = $data["topics"];
        $responses[] = append_related_posts_as_gutenberg_block($topicName);
    }
    
    $newText = implode($responses);
    return $newText;
}



function generateLLMContent($api_key, $data, $prompt = "", $history = "", $section_name = "") {
    $llm = $data['llm'];

    switch ($llm) {
        case "ChatGPT":
        case "gpt-3.5-turbo":
        case "gpt-3.5-turbo-16k":
        case "gpt-4":
        case "gpt-4-1106-preview":
            // Handle text generation using chatgpt_generate_text_request
            $GPTrequest = new GPTRequest(
                $api_key,
                $data['topics'], 
                (float)$data['temperature'], 
                (int)$data['max_ctx_tokens'], 
                (int)$data['amount_to_gen'], 
                (float)$data['repetition_penalty'], 
                (int)$data['big_text_count'],
                $data['llm'],
                $data['language']
            );
            $new_text = $GPTrequest->generate_gpt_request($history, $GPTrequest->key, $prompt);
            if ($section_name != ""){
                $new_text = "<h2>$section_name</h2>\n".$new_text;
            }
            return $new_text;

        case "KoboldAI":
            return "not yet";
        default:
            return "LLM not supported";
    }
}

function generateImageContent($api_key, $post = null, $data = null, $prompt = '',$thumbnail = false) {
    if($data != null){
        $imageGenerators = $data['image_generators'];//"dalle";//$data;
    }else{
        $imageGenerators = "dalle";
    }
    $google_api_key =get_option('google_search_key');
    $google_search_id =get_option('google_search_id');

    switch ($imageGenerators) {
        case "dalle":
            // Handle image generation using generate_image_with_dall_e
            if ($thumbnail){
                $image_prompt='create a realistic image of a'.$data['topics'];
                $gen = generate_image_with_dall_e($api_key, $image_prompt, $post,'1024x1024');
            }
            return $gen;
        case "Google":
            $gog_gen=search_image_with_google($data['topics'],$google_api_key,$google_search_id,$post);
            return $gog_gen;
        case "upload":
            if ($thumbnail) {
                // Set thumbnail for the post
                set_post_thumbnail($post, $data['image_id']);
            } else {
                // Return URL string of the image ID
                $image_url = wp_get_attachment_image_url($data['image_id'], 'full');
                // You can then use $image_url as needed, for example, return it or use it in your code.
                // For simplicity, let's just return it in this example.
                return $image_url;
            }
            break;
            

        default:
            return "Image generator not supported";
    }
}
