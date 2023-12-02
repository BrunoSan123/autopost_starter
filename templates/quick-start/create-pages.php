<?php

function create_quick_start_pages() {
    // Define os parâmetros da nova página
     add_filter('theme_page_templates','my_theme_register',10,3);
}

// Chame a função para criar a página via código
function autopost_custom_template(){
    $temps=[];
    $temps['page-start-template.php']='Autopost template';
    return $temps;
}

function my_theme_register($page_templates,$theme,$post){
    $templates= autopost_custom_template();
    foreach($templates as $tk=>$tv){
        $page_templates[$tk]=$tv;
    }
    return $page_templates;

}

