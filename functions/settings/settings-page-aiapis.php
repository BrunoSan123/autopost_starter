
<!-- Content for section Ai Apis -->


       <div class="tab-content" id="tab-APIs" class="settings-field" style="display: none;">
            
                <div class="tab-content-card">
                        <h3><?php _e('Setup your APIs','autopost')?></h3>
                        <b><?php _e('Customize your API settings here to create both text and images. Choose which one you want to prioritize for text and image generation.','autopost')?></b>
                        <div style="align-self: stretch"><span><?php _e('Obs: The articles in','autopost')?> </span><span id="gradient-text"><?php _e('Generating','autopost')?></span><span><?php _e('state will be continuing being generated with the same API until it’s done.','autopost')?></span></div>
                </div>
                <br>


<div class="tab-content-card">
				<div class="settings-subitem-header" style="color:#23262F;"><?php _e('Select your prefered API for','autopost')?> <b><?php _e('Text','autopost')?></b></div>
				<form method="post" action="">
				 
				 <!-- Suspenso por hora
					<div class="settings-title-api">
					<h1>Select your prefered API for <strong>Image</strong><h1>
					<h1 class="selection-api-page">Selection</h1>
					</div>
					
					<?php /*
                    settings_fields('chatgpt_openai_settings');
                    do_settings_sections('chatgpt_openai_settings');
					submit_button(); */
                    ?>
					-->
					
					<div class="apis-container">					
						<div class="api-single" id="OpenAi">
							<div class="">
								<label for="OpenAI" class="settings-subitem">OpenAI</label><br>
								<input class="input-box" type="text" id="OpenAiToken" name="OpenAiToken" value="<?php echo esc_attr(get_option('chatgpt_api_key')); ?>" placeholder="Insert your API Key here">

								<p class="settings-title-subtitle"><?php _e('Sign up or log in to OpenAI, get your API key and paste above -','autopost')?><strong><a href="openai.com"><?php _e('Click here to get your API key.','autopost')?></a></strong></p>										
							</div>
							<div class="submit-div api-buttons">
								<p><button id="aiapi-token" onclick="aiapi_inserttoken()" class="submit-button">Submit Token</button></p>
								<p><button id="aiapi-removetoken" onclick="aiapi_removetoken()" class="submit-button">Remove Token</button></p>
							</div>

						</div>

					</div>
					<script>
					function aiapi_inserttoken() {
						var openAiToken = document.getElementById('OpenAiToken').value;
						
						// Prepare data to be sent to the server
						var data = {
							action: 'update_openai_token',  // WordPress AJAX action for updating OpenAI token
							openai_token: openAiToken
						};

						// Send AJAX request to the server
						jQuery.post(ajaxurl, data, function(response) {
							// Handle the response from the server
							console.log(response);
							

						});

						alert("token updated");
					}

					function aiapi_removetoken(){
						var openAiToken = document.getElementById('OpenAiToken').value;
						
						// Prepare data to be sent to the server
						var data = {
							action: 'delete_openai_token',  // WordPress AJAX action for updating OpenAI token
							openai_token: openAiToken
						};

						// Send AJAX request to the server
						jQuery.post(ajaxurl, data, function(response) {
							// Handle the response from the server
							console.log(response);
							

						});

						alert("token removed");

					}


					</script>

					<br>
					
					
					<div class="apis-container">					
						<div class="api-single" id="GoogleKey">
						<div class="">
							<label for="GoogleKey" class="settings-subitem">Google Search Key</label><br>
							<input class="general-input-box" type="text" id="GoogleSearchKey" name="GoogleSearchKey" value="<?php echo esc_attr(get_option('google_search_key')); ?>" placeholder="Insert your API Key here">
							<p class="settings-title-subtitle"><?php _e('Sign up or log in to Google, get your API key and paste above - <strong><a href="openai.com">Click here to get your API key.','autopost')?></a></strong></p>										
						</div>
						<div class="submit-div api-buttons">
							<p><button id="GoogleKey-token" onclick="GoogleKey_token()" class="submit-button">Submit Token</button></p>
							<p><button id="GoogleKey-removetoken" onclick="GoogleKey_removetoken()" class="submit-button">Remove Token</button></p>
						</div>

						<script>
						function GoogleKey_token() {
							var googleSearchKey = document.getElementById('GoogleSearchKey').value;

							// Prepare data to be sent to the server
							var data = {
								action: 'update_google_search_key',  // WordPress AJAX action for updating Google Search Key
								google_search_key: googleSearchKey
							};

							// Send AJAX request to the server
							jQuery.post(ajaxurl, data, function(response) {
								// Handle the response from the server
								console.log(response);

							});
						}

						function GoogleKey_removetoken() {
							// You can implement removal logic if needed
							console.log('Remove Google Search Key logic');
						}
						</script>


						</div>

					</div>
					<br>
					
					
					<div class="apis-container">					
						<div class="api-single" id="SearchID">
						<div class="">
							<label for="GoogleSearchId" class="settings-subitem">Google Search ID</label><br>
							<input class="general-input-box" type="text" id="GoogleSearchId" name="GoogleSearchId" value="<?php echo esc_attr(get_option('google_search_id')); ?>" placeholder="Insert your Search ID here">
							<p class="settings-title-subtitle"><?php _e('Id for imageSearch -','autopost')?><strong><a href="openai.com"><?php _e('Click here to get your Search ID.','autopost')?></a></strong></p>										
						</div>
						<div class="submit-div api-buttons">
							<p><button id="SearchID-token" onclick="SearchID_inserttoken()" class="submit-button">Submit Token</button></p>
							<p><button id="SearchID-removetoken" onclick="SearchID_removetoken()" class="submit-button">Remove Token</button></p>
						</div>

						<script>
						function SearchID_inserttoken() {
							var googleSearchId = document.getElementById('GoogleSearchId').value;

							// Prepare data to be sent to the server
							var data = {
								action: 'update_google_search_id',  // WordPress AJAX action for updating Google Search ID
								google_search_id: googleSearchId
							};

							// Send AJAX request to the server
							jQuery.post(ajaxurl, data, function(response) {
								// Handle the response from the server
								console.log(response);

								// You can add further logic to handle the response as needed
							});
						}

						function SearchID_removetoken() {
							// You can implement removal logic if needed
							console.log('Remove Google Search ID logic');
						}
						</script>


						</div>

					</div>
					<br>

					<div class="apis-container">					
						<div class="api-single" id="GTranslate">
						<div class="">
							<label for="GoogTranslate" class="settings-subitem">Translate Api</label><br>
							<input class="general-input-box" type="text" id="GoogTranslate" name="GoogleTranslate" value="<?php echo esc_attr(get_option('google_translate_key')); ?>" placeholder="Insert your Translate API here">
							<p class="settings-title-subtitle"><strong><a href="openai.com"><?php _e('Click here to get your Translate token.','autopost')?></a></strong></p>										
						</div>
						<div class="submit-div api-buttons">
							<p><button id="SearchID-token" onclick="translate_inserttoken()" class="submit-button">Submit Token</button></p>
							<p><button id="SearchID-removetoken" onclick="translate_removetoken()" class="submit-button">Remove Token</button></p>
						</div>

						<script>
						function translate_inserttoken() {
							var googleTranslateToken= document.getElementById('GoogTranslate').value;

							// Prepare data to be sent to the server
							var data = {
								action: 'update_google_tanslate',  // WordPress AJAX action for updating Google Search ID
								translate_api:googleTranslateToken
							};

							// Send AJAX request to the server
							jQuery.post(ajaxurl, data, function(response) {
								// Handle the response from the server
								console.log(response);

								// You can add further logic to handle the response as needed
							});

							alert("token sucefully added");
						}

						function translate_removetoken() {
							// You can implement removal logic if needed
							
							var googleTranslateToken= document.getElementById('GoogTranslate').value;

							// Prepare data to be sent to the server
							var data = {
								action: 'delete_google_tanslate',  // WordPress AJAX action for updating Google Search ID
								translate_api:googleTranslateToken
							};

							// Send AJAX request to the server
							jQuery.post(ajaxurl, data, function(response) {
								// Handle the response from the server
								console.log(response);

								// You can add further logic to handle the response as needed
							});

							alert("token removed");
						}
						</script>


						</div>

					</div>
					<br>
					
					
					</form>
				</div>
                
        <div class="backdrop-bottom">
		</div>        
            
        </div>