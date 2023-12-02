<?php
  require_once(dirname(__FILE__).'/open_ai_apis/gpt.php');

  function generate_keyword_list($nich, $model, $api, $quantity){
    $temperature = 0.7;
    $max_ctx_tokens = 700;
    $nich_prompt = 'Crie uma lista de ' . $quantity . ' palavras-chaves para determinado topico' . $nich;

    $nich_response = gpt_query_large($model, $nich, $nich_prompt, $temperature, $max_ctx_tokens, $api);

    if (isset($nich_response['error'])) {
        return array('Error: ' . $nich_response['error']['message']);
    } else {
        $nich_generated_text = $nich_response['choices'][0]['message']['content'];
        $nich_generated_lines = explode("\n", $nich_generated_text);

        $nich_result = array();
        foreach ($nich_generated_lines as $line) {
            // Use preg_match para encontrar o número e a descrição
            if (preg_match('/^(\d+)\. (.+)$/', $line, $matches)) {
                $posicao = $matches[1];
                $descricao = $matches[2];
                $nich_result[$posicao] = $descricao;
            }
        }
        return $nich_result;
    }
    
}
