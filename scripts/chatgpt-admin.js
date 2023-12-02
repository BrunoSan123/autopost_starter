



async function change_lenguage(link){
    const language=link.getAttribute("id");
    console.log(language);
    const load=`<svg width="40" height="40" viewbox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
    <circle cx="20" cy="20" fill="none" r="10" stroke="#503FFD" stroke-width="2">
      <animate attributeName="r" from="8" to="20" dur="1.5s" begin="0s" repeatCount="indefinite"/>
      <animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0s" repeatCount="indefinite"/>
    </circle>
    <circle cx="20" cy="20" fill="#503FFD" r="10"/>
  </svg>`
    link.innerHTML+=load;
    const change_language=await fetch('/wp-json/custom/v1/change-language',{
        method:'POST',
        body:JSON.stringify({new_language:language}),
        headers:{"Content-Type":"application/json"}
    })
    if(change_language.ok){
        location.reload();
        console.log(change_language.json())
    }else{
        alert('its not possible change the language')
    }

}





