<?php

function adicionar_metabox_personalizada(){
    add_meta_box(
        'id_metabox_personalizada',
        'Recriar Thumnail com Dalle',
        'conteudo_metabox_dalle',
        'post',
        'side'
    );

    add_meta_box(
        'id_metabox_mj',
        'Recriar Thumbnail com Midjourney',
        'conteudo_metabox_mj',
        'post',
        'side'
    );

    add_meta_box(
        'id_metabox_autolink',
        'Recriar links',
        'conteudo_metabox_autolink',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'adicionar_metabox_personalizada');

function conteudo_metabox_dalle($post) {
    $valor_metabox = get_post_meta($post->ID, 'chave_metabox', true);
    ?>
    <button class="components-button is-secondary" data-post-image-id="<?php echo $post->ID?>" onclick="recreate_image(this)">Recriar Thumbnail com DALLE</button>
    <span class="chatgpt-loading" style="display:none;"><img src="https://gptautopost.com/wp-content/plugins/chatgpt-post-creator/load.gif" alt="Carregando..." /></span>'
    <?php
}

function conteudo_metabox_mj($post){
    ?>
    <button class="components-button is-secondary" data-post-mj-id="<?php echo $post->ID?>" onclick="recreate_image_mj(this)">Recriar Thumbnail com Midjourney</button>
    <span class="chatgpt-loading" style="display:none;"><img src="https://gptautopost.com/wp-content/plugins/chatgpt-post-creator/load.gif" alt="Carregando..." /></span>'
    <?php
}


function conteudo_metabox_autolink($post){
    ?>
    <button class="components-button is-secondary" data-post-autolink-id="<?php echo $post->ID?>"  onclick="reacreate_auto_link(this)">Recriar auto-link</button>
    <span class="chatgpt-loading" style="display:none;"><img src="https://gptautopost.com/wp-content/plugins/chatgpt-post-creator/load.gif" alt="Carregando..." /></span>'
    <?php
}