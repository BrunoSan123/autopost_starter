<?php

function submit_basic(){
    if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['basic_config']){
    
        $args=array(
            'words_per_article'=>$_POST['words_per_article'],
            'article_type'=>$_POST['type_per_article'],
            'writen_tone'=>$_POST['written_tone'],
            'text_style'=>$_POST['generated_text_language'],
            'aspect_ratio'=>$_POST['opcao'],
            'image_style'=>$_POST['image-style'],
            'image_quantity'=>$_POST['quantity-of-images'],
        );

        $config=retriveAutopostconfig();

        if(empty($config)){
            saveBasicConfig($args);
        }else{
            updateBasicConfig($args);
        }
        
        
    }

}

function submit_advanced(){
    if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['advanced_config']){
    
        $args=array(
            'prompt_for_intro'=>$_POST['prompt_intro'],
            'prompt_for_sections'=>$_POST['prompt_sections'],
            'prompt_title'=>$_POST['prompt_title'],
            'prompt_for_conclusion'=>$_POST['prompt_conclusion'],
            'prompt_for_custom'=>$_POST['custom_section'],
        );

        $config=retriveAutopostconfig();

        if(empty($config)){
            saveAdvancedConfig($args);
        }else{
            updateAdvancedConfig($args);
        }

        
       
    }

}

function edit_author_modal($id,$name,$email){
    
        $result=update_user($id,$name,$email);

        wp_send_json($result);

    }

function delete_user_settings($id){
    $result=delete_user($id);

    return wp_send_json($result);
}


