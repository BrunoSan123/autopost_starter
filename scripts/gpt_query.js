const pg_auto_post = document.body.classList.contains(
  "auto-post_page_chatgpt_projects_page"
);
const dashboard=document.body.classList.contains("auto-post_page_chatgpt_dashboard_page");

if (pg_auto_post || dashboard) {
  const quantity=document.getElementById("steps");
  const entry_point = document.getElementById("nich");
  const topic_button = document.querySelector(".topics_generate");
  const gpt_api_key = document.getElementById("chatgpt_api_key");
  const verify_topic=document.getElementById("topics");
  const modal_item= document.querySelector(".modal-item")
  const steps_fields =document.getElementById("steps")
  const helperContainer=document.querySelector(".helper")
  const key_style=document.getElementById("key")
  const post_style= document.getElementById("post")
  const loading_div =document.querySelector(".loading_div")

  async function wp_gpt_token(){
    const key= await fetch('/wp-json/custom/v1/gpt_key')
    const data= await key.json();
    return data
  }
 

  //query para ser feita ao gpt
  async function gpt_query(
    selectedModel,
    text,
    finalPrompt,
    temperature,
    maxCtxTokens,
    apiKey
  ) {
    const finalBody = {
      model: selectedModel,
      messages: [
        {
          role: "system",
          content: text,
        },
        {
          role: "user",
          content: finalPrompt,
        },
      ],
      temperature: temperature,
      max_tokens: maxCtxTokens,
    };

    const secondHeaders = {
      "Content-Type": "application/json",
      Authorization: `Bearer ${apiKey}`,
    };

    const urlSecond = "https://api.openai.com/v1/chat/completions";

    try {
      const response = await fetch(urlSecond, {
        method: "POST",
        headers: secondHeaders,
        body: JSON.stringify(finalBody),
        timeout: 800,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const jsonFinalResponse = await response.json();
      return jsonFinalResponse;
    } catch (error) {
      return { error: error.message };
    }
  }

  async function update_billing(gpt_data){
    console.log(gpt_data)
    const update_query= await fetch('/wp-json/custom/v1/update_token_billing',{
      method:"POST",
      headers:{"Content-Type": "application/json"},
      body:JSON.stringify(gpt_data)
    });
    if(!update_query.ok){
      throw new Error(`HTTP error! Status: ${update_query.status}`);
    }

    const response= await update_query.json();
    return response;
  }


  async function get_translate_token(){
    const token =await fetch('/wp-json/custom/v1/get_translate');
    const response=await token.json();
    return response;
  }

  async function translate_prompt(prompt,target){
    const translate_token =await get_translate_token();

    if(prompt !=null && target !=null){
      
    const text_to_translate=await fetch(`https://translation.googleapis.com/language/translate/v2?key=${translate_token}`,{
      method:'POST',
      body:JSON.stringify({q:prompt,target:target,format:'text'}),
      headers:{
        "Content-Type":"application/json"
      }

    })

    const translated_text= await text_to_translate.json();

    return  translated_text.data.translations[0].translatedText;

    }else{
      return null;
    }

  }

  //geração de palavras chaves com os dados do inputs e  com um prompt já feito
  async function gpt_get_keys(topic, model, api, quantity) {
    let temperature = 0.7;
    let max_ctx_tokens = 700;
    let nich_prompt
    let new_prompt
    if(key_style.checked){
      nich_prompt = `Crie uma lista de ${quantity}  palavras-chaves para determinado topico ${topic}`;
      new_prompt =await translate_prompt(nich_prompt,"en");
    }else if(post_style.checked){
      nich_prompt=`Crie uma lista de ${quantity} titulos para atigo de blog para determinado topico ${topic}`;
      new_prompt =await translate_prompt(nich_prompt,"en");
    }else{
      alert('no style selected');
    }
    
    let response = await gpt_query(
      model,
      topic,
      new_prompt,
      temperature,
      max_ctx_tokens,
      api
    );

    update_billing(response);

    if (response.error) {
      return [`Error: ${response.error.message}`];
    } else {
      const nichGeneratedText = response.choices[0].message.content;
      const nichGeneratedLines = nichGeneratedText.split("\n");

      const nichResult = {};
      nichGeneratedLines.forEach((line) => {
        // Use a regular expression to find the number and description
        const matches = line.match(/^(\d+)\. (.+)$/);
        if (matches) {
          const posicao = matches[1];
          const descricao = matches[2];
          nichResult[posicao] = descricao;
        }
      });

      return nichResult;
    }
  }


  topic_button.addEventListener("click", async (e) => {
    e.preventDefault();
    loading_div.textContent="carregando....."
    const topics_area = document.querySelector(".chatgpt_keys");
    const topics_fields=document.querySelector(".single_key")
    const api_key= await wp_gpt_token();
    const response = await gpt_get_keys(
      entry_point.value,
      "gpt-3.5-turbo-16k",
      api_key,
      parseInt(quantity.selectedOptions[0].value)
    );
    loading_div.textContent=""
    const list = Object.keys(response);
      if(topics_area){
        list.forEach((i) => {
          response[i]=response[i].replace(/"/g,'');
          topics_area.value += response[i] + "\n";
          });
      }else{
        response[list[0]]=response[list[0]].replace(/"/g,'');
        topics_fields.value=response[list[0]]
      }



  });
}
