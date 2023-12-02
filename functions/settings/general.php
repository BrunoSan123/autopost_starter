O conteúdo das configurações gerais não funcionam no momento<br>

<div class="forms-container">
        <div id="id-form1" class="form active">
            <div class="settings-title">
			<h2>Default Creation Mode</h2> 
			<label for="toggleform1" class="switch" >
			<input id="toggleform1" type="checkbox" onchange="" checked></input>
			<span class="slider round"></span>			
			</label>
			</div>
			<p class="settings-title-subtitle">Customize your generations with some help</P>
			
            <p class="settings-subitem-header">Generation customization</p>
			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="words_per_article" class="settings-subitem">Words per Article:</label>
				<p class="settings-title-subtitle">How many words each generated article must have</p>
                <input class="general-input-box" type="number" id="words_per_article" name="words_per_article" value="4">

                <label for="type_per_article" class="settings-subitem">Type per Article</label>
                <select id="type_per_article" name="type_per_article">
                    <option value="Blog">Default (Blog post)</option>
                    <option value="News">News</option>
                    <option value="Review">Review</option>
                </select>

                <label for="written_tone" class="settings-subitem">Written Tone</label>
                <select id="written_tone" name="written_tone">
                    <option value="Neutral">Default (Neutral)</option>
                    <option value="Casual">Casual</option>
                    <option value="Professional">Professional</option>
                </select>
				
				
				<p class="settings-subitem-header">Text style</p>
				
                <label for="generated_text_language" class="settings-subitem">Generated Text Language</label>
                <select id="generated_text_language" name="generated_text_language">
                    <option value="en">Default (English)</option>
                    <option value="ptbr">Portugues</option>
                    <option value="Arr">Pirata</option>
                </select>
				
				<label for="generated_text_language" class="settings-subitem">Select font</label>
                <select id="select_font" name="font">
                    <option value="default">Default (DM Sans)</option>
                    <option value="arial">Arial</option>
                    <option value="verdana">Verdana</option>
                </select>
				
				<p class="settings-subitem-header">Image Options</p>
				
                <label for="aspect_ratio" class="settings-subitem">Aspect Ratio</label>
				<p>Select your prefered aspect ratio to use when generating images</p>		   
				<div class="aspect">
					<input type="radio" name="opcao" value="none" id="none" class="radio-button">
					<label for="none" class="radio-label">none</label>
					
					<input type="radio" name="opcao" value="11" id="11" class="radio-button" checked>
					<label for="11" class="radio-label">1:1</label>
					
					<input type="radio" name="opcao" value="23" id="23" class="radio-button">
					<label for="23" class="radio-label">2:3</label>	
					
					<input type="radio" name="opcao" value="45" id="45" class="radio-button">
					<label for="45" class="radio-label">4:5</label>
					
					<input type="radio" name="opcao" value="169" id="169" class="radio-button">
					<label for="169" class="radio-label">16:9</label>
			</div>
				<br>
				<br>
				<label for="image-style" class="settings-subitem">Image style</label>
                <select id="image-style" name="image-style">
                    <option value="Illustration">Default (Illustration)</option>
                    <option value="Photography">Photography</option>
                    <option value="painting">Painting</option>
                </select>
				
				 <label for="quantity-of-images" class="settings-subitem">Quantity of images</label>
					<p class="settings-title-subtitle">How many images you want to generate automatically per article</p>
						<div class="img-qtty">
							<p style="font-size: 14px;">Let the AI define for me</p>
								<label class="switch" id="ai-images">
								<input type="checkbox" checked>
								<span class="slider round"></span>
								</label>
								<input class="general-input-box" type="number" id="quantity-of-images" name="quantity-of-images" value="4">
						</div>
			
                

				
	    			
                <input type="submit" value="Save Default Settings" class="submit-button">
            </form>
        </div>
		
<!-- FORMULÁRIO ADVANCED -->		

        <div id="id-form2" class="form">
		 <div class="settings-title">
			<h2>Advanced Creation Mode</h2>
			<label for="toggleform2" class="switch" >
			<input id="toggleform2" type="checkbox" onchange=""></input>
			<span class="slider round"></span>
			</label>
			</div>
			<p class="settings-title-subtitle">Obs: Activate this mode will disable the Default Creation Mode</P>
			
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="prompt_title" class="settings-subitem-header">Prompt for Titles</label>
                <textarea id="prompt_title" name="prompt_title" rows="4" placeholder="Crie uma lista de ${quantity}  palavras-chaves para determinado topico ${topic}"></textarea>
				<p style="position: relative;  top: -50px;"> This prompt will build your <strong>Titles</strong></p>

                <label for="prompt_intro" class="settings-subitem-header">Prompt for Intros</label>
                <textarea id="prompt_intro" name="prompt_intro" rows="4" placeholder="Tendo [key] como tema. Gere a seção Introdução com tom explicativo, formal, com ao menos 250 palavras"></textarea>
				<p style="position: relative;  top: -50px;"> This prompt will build your <strong>Intros</strong></p>

                <label for="prompt_sections" class="settings-subitem-header">Prompt for Sections</label>
                <textarea id="prompt_sections" name="prompt_sections" rows="4" placeholder="Tendo [key] como tema. Gere a seção com tom explicativo, formal, com 250 palavras"></textarea>
				<p style="position: relative;  top: -50px;"> This prompt will build your <strong>Sections</strong></p>

                <label for="prompt_conclusion" class="settings-subitem-header">Prompt for Conclusion</label>
                <textarea id="prompt_conclusion" name="prompt_conclusion" rows="4" placeholder="Termine um artigo tendo [key] como tema."></textarea>
				<p style="position: relative;  top: -50px;"> This prompt will build your <strong>Conclusions</strong></p>
				
				<label for="prompt_conclusion" class="settings-subitem-header">Custom Section</label>
                <textarea id="prompt_conclusion" name="custom_section" rows="4" placeholder="Lorem ipsum dolor sit amet"></textarea>
			

                <input type="submit" value="Save Advanced Settings" class="submit-button" name="advanced_config">
            </form>
        </div>
	</div>
	
		
<script>
//   jQuery(document).ready(function($) {
//     // Inicialmente, adicione a classe ativa ao primeiro formulário
//     $('#id-form1').addClass('active');
// 	$('#id-form2').removeClass('active');

//     // Adicione um manipulador de eventos para o switch do form1
//     $('#toggleform1').change(function() {
       
// 		// Remova a classe ativa de ambos os formulários
//         $('#id-form1, #id-form2').removeClass('active');

		
//         if ($(this).is(':checked')) {
//             $('#id-form1').addClass('active');
// 			$('#id-form2').removeClass('active');
// 			$('#toggleform1').prop('disabled', true);
//             $('#toggleform2').prop('disabled', false);
			
// 		} else {
//             $('#id-form1').removeClass('active');
// 			$('#id-form2').addClass('active');
// 			$('#toggleform1').prop('disabled', false);
//             $('#toggleform2').prop('disabled', true);
// 		}
// 	});

//     // Adicione um manipulador de eventos para o switch do form2
//     $('#toggleform2').change(function() {
// 		//confira novamente
//         if ($(this).is(':checked')) {
//             $('#id-form2').addClass('active');
// 			$('#id-form1').removeClass('active');
// 			} else {
//             $('#id-form2').removeClass('active');
//         }
//     });
// });
  
  
  
  
  /*
  
  jQuery(document).ready(function($) {
    // Inicialmente, adicione a classe ativa ao primeiro formulário
    $('#id-form1').addClass('ativo');

    // Adicione um manipulador de eventos para o switch
    $('#toggleform1').change(function() {
        // Remova a classe ativa de ambos os formulários
     //   $('id-form1, #id-form2').removeClass('ativo');

        // Adicione a classe ativa ao formulário correspondente
        if ($(this).is(':checked')) {
            $('#id-form1').addClass('ativo');
        } else {
            $('#id-form1').removeClass('ativo');
        }
		
		if ($(this).is(':checked')) {
            $('#id-form2').addClass('ativo');
        } else {
            $('#id-form2').removeClass('ativo');
        }
    });
});
	
  */
</script>
