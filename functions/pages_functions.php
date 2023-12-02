<?php



function chatgpt_plugin_options_page() {
    
    // Verificar se os dados do formulário foram enviados
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Atualizar a opção com o modelo selecionado
        $selected_model=isset($_POST['chatgpt_model'])?sanitize_text_field($_POST['chatgpt_model']):'gpt-3.5-turbo-16k';
        $sections_count=isset($_POST['sections_count'])?intval($_POST['sections_count']):5;
        update_option('chatgpt_selected_model', $selected_model);
        update_option( 'sections_count', $sections_count);
    }
    
    
    $api_key = get_option('chatgpt_api_key');
    $google_api_key =get_option('google_search_key');
    $google_search_id =get_option('google_search_id');
    $saved_prompt = get_option('chatgpt_saved_prompt');
    $selected_model = get_option('chatgpt_selected_model');
    $sections_count = get_option('sections_count');
     $categories = get_categories(array('hide_empty' => 0));
     $args = array(
        'role__in' => array('administrator', 'editor', 'author', 'contributor'),
        'orderby' => 'display_name',
        'order' => 'ASC',
    );
    $authors = get_users($args);
   
   
   // Lista de modelos de ChatGPT disponíveis
    $models = array(
        'gpt-3.5-turbo-16k' => 'GPT-3.5',
        'gpt-4' => 'GPT-4',
    );

    //icons for gtp_switch
    $icons=array(
        '<svg class="gpt_dash_icos" data-name="ray" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke-width="2" class="h-4 w-4 transition-colors text-brand-green"><path fill="currentColor" d="M9.586 1.526A.6.6 0 0 0 8.553 1l-6.8 7.6a.6.6 0 0 0 .447 1h5.258l-1.044 4.874A.6.6 0 0 0 7.447 15l6.8-7.6a.6.6 0 0 0-.447-1H8.542l1.044-4.874Z"/></svg>',
        '<svg class="gpt_dash_icos" data-name="stars" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke-width="2" class="h-4 w-4 transition-colors group-hover/button:text-brand-purple"><path fill="currentColor" d="M12.784 1.442a.8.8 0 0 0-1.569 0l-.191.953a.8.8 0 0 1-.628.628l-.953.19a.8.8 0 0 0 0 1.57l.953.19a.8.8 0 0 1 .628.629l.19.953a.8.8 0 0 0 1.57 0l.19-.953a.8.8 0 0 1 .629-.628l.953-.19a.8.8 0 0 0 0-1.57l-.953-.19a.8.8 0 0 1-.628-.629l-.19-.953h-.002ZM5.559 4.546a.8.8 0 0 0-1.519 0l-.546 1.64a.8.8 0 0 1-.507.507l-1.64.546a.8.8 0 0 0 0 1.519l1.64.547a.8.8 0 0 1 .507.505l.546 1.641a.8.8 0 0 0 1.519 0l.546-1.64a.8.8 0 0 1 .506-.507l1.641-.546a.8.8 0 0 0 0-1.519l-1.64-.546a.8.8 0 0 1-.507-.506L5.56 4.546Zm5.6 6.4a.8.8 0 0 0-1.519 0l-.147.44a.8.8 0 0 1-.505.507l-.441.146a.8.8 0 0 0 0 1.519l.44.146a.8.8 0 0 1 .507.506l.146.441a.8.8 0 0 0 1.519 0l.147-.44a.8.8 0 0 1 .506-.507l.44-.146a.8.8 0 0 0 0-1.519l-.44-.147a.8.8 0 0 1-.507-.505l-.146-.441Z"/></svg>'
    )
    
     
    ?>
    <script>
        function enableApiKeyEditing() {
            var apiKeyInput = document.getElementById('chatgpt_api_key');
            var googleKeys=document.querySelectorAll('.google_api_fields')
            apiKeyInput.readOnly = !apiKeyInput.readOnly;
            googleKeys.forEach((e)=>{
                e.readOnly=!e.readOnly;
            })
        }
    </script>
    
    <div class="wrap">
        <?php require_once(plugin_dir_path(__FILE__).'../templates/gpt_mode_swicth.php')?>
        <h2 class="autopost_title">Autopost Plus</h2>
        <div class="form_parent">
       
        <form action="" method="post" class="main_form" enctype="multipart/form-data">
           <?php require_once(plugin_dir_path(__FILE__).'../templates/sidebar_plugin.php');?>
            <div class="form_item">
            <?php settings_fields('chatgpt_plugin_options'); ?>
            <?php do_settings_sections('chatgpt_plugin'); ?>

            <p class="api_config" style="text-align: center;"><strong>Configurações de chave API</strong></p>

            <div class="chat_gpt_vonfiguration">
            <p>Caso não tenha uma chave API, gere uma no link: <a href="https://platform.openai.com/account/api-keys" target="_blank">https://platform.openai.com/account/api-keys</a></p>
            <input type="text" id="chatgpt_api_key" name="chatgpt_api_key" value="<?php echo esc_attr($api_key); ?>" readonly />
            <input type="submit" class="button" name="save_api_key" value="Salvar ChaveAPI" />
            <input type="button" class="button" value="Editar Chave API" onclick="enableApiKeyEditing()" />
            <code style="font-size:12px;font-style: italic;">o GPT-3 da OpenAI tem limitações quanto ao número de tokens por resposta, o que pode limitar a extensão dos textos gerados. A versão da Vinci do GPT-3 pode lidar com até 2048 tokens por request, o que em muitos casos pode ser menos do que 2000 palavras, dependendo do idioma e da complexidade do texto.</code>
            <br><br>
            <code>Configure aqui suas credenciais do google para a busa inteligente</code>

            <input type="text" id="google_search_key" class="google_api_fields" name="google_api_key" value="<?php echo esc_attr($google_api_key); ?>" readonly />
            <input type="text" id="google_search_id"  class="google_api_fields" name="google_search_id" value="<?php echo esc_attr($google_search_id); ?>" readonly />
            </div>

    <div class="gpt_input_generation">
        <div class="generation_parent">
         <div class="generation_child">
            <p class="inputs_title"><strong>PROMPT</strong></p>
            <textarea class="chat_textarea" id="chatgpt_prompt" name="chatgpt_prompt" rows="5" cols="100" placeholder="Escreva um artigo sobre {palavra-chave}"><?php echo esc_textarea($saved_prompt); ?></textarea>
            <div class="generate_text_button">
            <input style="margin-top: 3%; text-decoration:none; color:#fff;" type="button" class="button btn_submit_text" id="save_prompt" value="Salvar Prompt" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?> />
            </div>
        </div>

        <?php if ( chatgpt_freemius_integration()->is_not_paying() ) : ?>
            <span>(Versão Premium)</span>
        <?php endif; ?>

           
            <div class="generation_child">
                <p class="inputs_title"><strong>PALAVAS CHAVE</strong></p>
                <textarea class="chatgpt_keys chat_textarea" name="chatgpt_keywords" rows="10" cols="100" placeholder="Palavra-chave 1&#10;Palavra-chave 2&#10;Palavra-chave 3"></textarea>
            </div>
            
            <br><br>
         


        <?php if ( chatgpt_freemius_integration()->is_not_paying() ) : ?>
            <span>(Versão Premium)</span>
        <?php endif; ?>

            <br><br>

        <?php if ( chatgpt_freemius_integration()->is_not_paying() ) : ?>
            <span>(Versão Premium)</span>
        <?php endif; ?>


    <input type="hidden" name="chatgpt_form_submitted" value="1">

    <?php if ( chatgpt_freemius_integration()->is_not_paying() ) : ?>
        <span>(Versão Premium)</span>
    <?php endif; ?>


                <div class="generate_text_button">
                    <input name="submit" class="button btn_submit_text button-primary" type="submit" value="<?php esc_attr_e('Gerar Textos'); ?>" />
            </div>
            </div>
        </div>
            </div>
            </form>
            </div>
        </div>
        <?php require_once(plugin_dir_path(__FILE__).'../templates/category_modal.php');?>
        <?php
    
}


function chatgpt_generate_and_publish_posts() {
    if (!current_user_can('manage_options') || !isset($_POST['chatgpt_form_submitted'])) {
        return;
    }

    if (isset($_POST['submit'])) {
        
        $prompt = isset($_POST['chatgpt_prompt']) ? sanitize_text_field($_POST['chatgpt_prompt']) : '';
        $keywords_string = isset($_POST['chatgpt_keywords']) ? sanitize_textarea_field($_POST['chatgpt_keywords']) : '';
        $keywords = explode("\n", $keywords_string);
        $api_key = get_option('chatgpt_api_key');
        $google_api_key=get_option('google_search_key');
        $google_search_id=get_option('google_search_id');
        $schedule_datetime = isset($_POST['schedule_datetime']) ? sanitize_text_field($_POST['schedule_datetime']) : '';
        $selected_category_id = intval($_POST['chatgpt_category']);
        $selected_author_id = intval($_POST['chatgpt_author']);
        $sections_count = intval($_POST['sections_count']);
        $entry_point=isset($_POST['nichs'])?$_POST['nichs']:'';
        $selected_model = get_option('chatgpt_selected_model');
        $dayli_schedule=isset($_POST['dayli_schedule'])?$_POST['dayli_schedule']:'';
        $post_status_option = $_POST['post_status'];
    

        if(isset($_POST['single_post'])){
            gen_post_ia($api_key, $keywords[0], $prompt, $sections_count, 
            $selected_author_id, $selected_category_id, $google_api_key, $google_search_id, true);
        }
        foreach ($keywords as $keyword) {
            gen_post_ia($api_key, $keyword, $prompt, $sections_count, 
            $selected_author_id, $selected_category_id, $google_api_key, $google_search_id, true);
        }
        if($post_status_option==='post_queue'){
            daily_schedule();
        }
        
        chatgpt_show_success_message();
    }
}

function gen_post_ia($api_key, $keyword, $prompt, $sections_count, 
$selected_author_id, $selected_category_id, $google_api_key, $google_search_id, $trim_sentences = true){
    try {
        $keyword = trim($keyword);
        $complete_prompt = str_replace('{palavra-chave}', $keyword, $prompt);
        $GPTrequest = new GPTRequest($api_key, $keyword, $sections_count);
        $response = chatgpt_generate_text_request($GPTrequest);
        array_shift($response);
        if($trim_sentences){
            $response = trim_sentences_in_array($response);
        }
        $generated_text = add_images_between_sections($response, $api_key);
        $generated_text = append_related_posts_as_gutenberg_block($keyword, $generated_text);
        
        

        if ($generated_text === null || $generated_text === '') {
            throw new Exception('Error: Generated text is empty or null.');
        }


        $post_data = array(
            'post_title'    => $keyword,
            'post_content'  => $generated_text,
            'post_status'   => 'publish',
            'post_author' => $selected_author_id,
            'post_category' => array($selected_category_id),
            'post_type'     => 'post',
        );

        $post_status_option = $_POST['post_status'];
        if ($post_status_option === 'schedule') {
            $post_data['post_status'] = 'future';
            $post_data['post_date'] = date('Y-m-d H:i:s', strtotime($_POST['schedule_datetime']));
        } elseif ($post_status_option === 'draft') {
            $post_data['post_status'] = 'draft';
        }elseif($post_status_option==='post_queue'){
            $post_data['post_status'] = 'post_queue';
        }

        if ($post_status_option === 'schedule' && !empty($schedule_datetime)) {
            $post_data['post_date'] = $schedule_datetime;
            $post_data['post_date_gmt'] = get_gmt_from_date($schedule_datetime);
        }



        $post_id = wp_insert_post($post_data);
        
        if(isset($_POST['ia_send'])){
            upload_image($post_id);
         }
        
        // geração de imagem com o DALL-E se selecionada a opção
        if(isset($_POST['ia_dalle'])){
            generate_image_with_dall_e($api_key,$keyword,$post_id,'1024x1024');
        }

        if(isset($_POST['ia_midjournal'])){
            $token='8iAUPCB_16033GITLY02861AUTOPOST_VER182900';
            $mj_generated_image=generate_image_with_mj($token,$keyword,$post_id);
            if($mj_generated_image===null || $mj_generated_image===''){
                throw new Exception('Error: Generated Image is empty or null');
            }
        }

        // buscca de imagens com a api do customSearch do google
        if(isset($_POST['ia_google_image'])){
            $imagem= search_image_with_google($keyword,$google_api_key, $google_search_id,$post_id);
            if($imagem===null || $imagem===''){
                throw new Exception('Error: Generated Image is empty or null');
            }
            set_post_thumbnail($post_id, $imagem);
        }

        
    } catch (Exception $e) {
        // Log the error message for debugging
        error_log($e->getMessage());

        // Return a friendly error message to the user
        return 'An error occurred while generating the text. Please try again. If the problem persists, contact the site administrator.';
    }
}