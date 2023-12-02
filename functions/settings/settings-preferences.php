
		<div class="tab-content" id="tab-general-preferences">
		
		<!--class="tab-content" id="tab-general-preferences" class="settings-field">-->
       
		             
            <div class="tab-content-card-gp">
                        <h3><?php _e('Setup your preferences for the platform','autopost')?></h3>
            </div>
			<br>
			
			<div class="tab-content-card-gp">
			<form method="post" action="" class="">
				
				<div class="general-pref">
					<div class="general-pref0">
						<div class="general-pref1 ">

						
							<div class="gp-inter form">
								<label class="settings-subitem"><?php _e('Interface language','autopost')?></label>
								<select class="" id="interface-language" name="type_per_article" onchange="change_lenguage_select(this)">
<!-- Select IDIOMA 

									<?php/*
										foreach (seu_plugin_dados['idiomas'] as $codigoIdioma => $nomeIdioma) {
										echo "<option value='$codigoIdioma'>$nomeIdioma</option>";
										}*/
									?>
						
 select IDIOMA -->	
									<option value=""><?php _e('Select Your Languague','autopost')?></option>
									<option id="en_US" value="en_US"><?php _e('Default (English)','autopost')?></option>
									<option id="pt_BR" value="pt_BR"><?php _e('Português','autopost')?></option>
								</select>
								

<!-- SCRIPT IDIOMA-->		

<script>
/*
jQuery(document).ready(function($) {
    // Recupere o idioma do cookie ou use o idioma padrão
    var idiomaSelecionado = obterCookie('idioma') || 'en';

    // Defina o idioma no select
    $('#seletorIdioma').val(idiomaSelecionado);

    // Adicione um manipulador de eventos para alterações no select
    $('#seletorIdioma').change(function() {
        // Obtenha o valor selecionado
        var novoIdioma = $(this).val();

        // Armazene o novo idioma em um cookie
        definirCookie('idioma', novoIdioma, 365);

        // Recarregue a página para aplicar a mudança de idioma
        location.reload();
    });

    // Definir  cookie
    function definirCookie(nome, valor, dias) {
        var dataExpiracao = new Date();
        dataExpiracao.setDate(dataExpiracao.getDate() + dias);
        var cookie = nome + '=' + valor + '; expires=' + dataExpiracao.toUTCString() + '; path=/';
        document.cookie = cookie;
    }

    // get cookie value
    function obterCookie(nome) {
        var nomeCookie = nome + '=';
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf(nomeCookie) == 0) {
                return cookie.substring(nomeCookie.length, cookie.length);
            }
        }
        return null;
    }
});
*/
document.addEventListener('DOMContentLoaded', function(){
	const language=document.documentElement.lang;
	const language_country=language.split("-")[1]
	console.log(language_country);
	const languageSelect= document.getElementById("interface-language")
	var allowedLanguages = [ "BR","US" ];

	Array.from(languageSelect.options).forEach((e)=>{
		var option =e.value.split("_")[1];
		console.log(option);

		if (allowedLanguages.includes(option) && option === language_country) {
			e.selected = true;
		}
	})

})




async function change_lenguage_select(link){
    const language=link.value;
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
</script>


		
								
							</div>
							<div class="gp-inter form ">
							<label for="darkModeSelect" class="settings-subitem"><?php _e('Theme','autopost')?></label>
								<select class="" id="darkModeSelect" onchange="changeDarkMode()" name="theme">
									<option value="light"><?php _e('Default (Light mode)','autopost')?></option>
									<option value="dark"><?php _e('Dark Mode','autopost')?></option>
							</select>
							</div>
						</div>	
					</div>
				</div>
			</form>
            </div>
			
			<script>
			  function changeDarkMode() {
				  
				  const darkModeSelect = document.getElementById('darkModeSelect');
					  const contentElement = document.getElementById('tab-general');
					  const wpcontentElement = document.getElementById("wpcontent");
				  const selectedMode = darkModeSelect.value;
				  
				  contentElement.classList.toggle('dark', selectedMode === 'dark');
				  wpcontentElement.classList.toggle('dark', selectedMode === 'dark');

				document.cookie = `darkMode=${selectedMode}; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/`;
  }
</script>

		<div class="backdrop-bottom">
		</div>
		
		</div>
       