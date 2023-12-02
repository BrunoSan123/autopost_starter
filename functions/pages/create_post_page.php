<?php
?>
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'create_post_page.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'create_post_page-dark.css');?>" />
<form id="post_create_form">
    
    <div class="modal-content ">
        <span class="close" onClick="closeModal('post-modal')">&times;</span>
        <div class="modal-item">
            <div class="header_contents">
                <h1 class="modal_header"><?php _e('Lets enhance the criativity!', 'autopost')?></h1>
                <small><?php _e('Define yout topics,they will be used to create your post content' , 'autopost')?></small>
            </div>
            <!-- Form section for Topics -->
            <div class="input_descriptions">
                <h2><?php _e('Topics to generate', 'autopost'); ?></h2>
                <small class="general"><?php _e('Generate a topic with AI' , 'autopost')?></small>
            </div>
            <!--<p><?php _e('This is a description placeholder', 'autopost'); ?></p>-->



            <div class="form-section helper">
                <input type="text" id="topics" class="single_key" name="topics">
            </div>

            
        </div>
            <!-- Form section for Post Preferences -->
            <div id="advanced-prompt" class="drawer Advanced-prompt modal-item" style="display:none">
                            <h3 onclick="openDrawer('.Advanced-prompt')" class="open-drawer-ct"><?php _e('Create with your own prompt', 'autopost'); ?></h3>
                            <p><?php _e('Using this mode will can use your skills to enhance even more the creation with Autopost', 'autopost'); ?></p>
                            <div class="drawer-content">
                                <div>
                                    <label for="prompt_title">Prompt for Title:</label>
                                    <textarea id="prompt_title" name="prompt_title" rows="4"></textarea>
                                </div>
                                
                                <div>
                                    <label for="prompt_intro">Prompt for Intro:</label>
                                    <textarea id="prompt_intro" name="prompt_intro" rows="4"></textarea>
                                </div>
                                
                                <div>
                                    <label for="prompt_sections">Prompt for Sections:</label>
                                    <textarea id="prompt_sections" name="prompt_sections" rows="4"></textarea>
                                </div>
                                
                                <div>
                                    <label for="prompt_conclusion">Prompt for Conclusion:</label>
                                    <textarea id="prompt_conclusion" name="prompt_conclusion" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
            

            <div class="drawer gen-custom modal-item">
                <h3 onclick="openDrawer('.gen-custom')" class="open-drawer-ct"><?php _e('Generation Customization', 'autopost'); ?></h3>
                <p><?php _e('Customize your generation', 'autopost'); ?></p>
                <div class="drawer-content drawer-content-column">
                    <!-- Content for the drawer -->
                    <div class="form-section">
                        <label for="type_article">Type of article</label>
                        <select id="type_article" name="type_article">
                            <option value="blog">Blog</option>
                            <option value="Other Option"><?php _e('Other Option', 'autopost'); ?></option>
                        </select>
                    </div>

                    <div class="form-section">
                        <label for="writen_tone"><?php _e('Writen Tone', 'autopost'); ?></label>
                        <select id="writen_tone" name="writen_tone">
                            <option value="default">default</option>
                            <option value="Other tone"><?php _e('Other tone', 'autopost'); ?></option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Form section for Advanced Mode Preferences -->
            <div class="drawer Advanced-Preferences modal-item">
                <h3 onclick="openDrawer('.Advanced-Preferences')" class="open-drawer-ct"><?php _e('Advanced Mode Preferences', 'autopost'); ?></h3>
                <p><?php _e('Choose how to generate', 'autopost'); ?></p>
                <div class="drawer-content">
                    <!-- Content for the drawer -->
                    <div class="form-section">
                        <label for="llm">LLM</label>
                        <select id="llm" name="llm">
                            <option value="gpt-3.5-turbo" selected>gpt-3.5-turbo</option>
                            <option value="gpt-3.5-turbo-16k">gpt-3.5-turbo-16k</option>
                            <option value="gpt-4">gpt-4</option>
                            <option value="gpt-4-1106-preview">gpt-4-1106-preview</option>
                        </select>
                    </div>

                    <div class="form-section">
                        <label for="image_generators"><?php _e('Image Generators', 'autopost'); ?></label>
                        <select id="image_generators" name="image_generators">
                            <option value="dalle">Dall-e</option>
                            <option value="midjourney">MidJourney</option>
                            <option value="Stable Diffusion">Stable Diffusion</option>
                            <option value="Google">Google</option>
                            <option value="upload">Upload Image</option>
                            <option value="Other Image Generator"><?php _e('Other Image Generator', 'autopost'); ?></option>
                        </select>
                        <div id="upload_image_section" style="display:none;">
                            <input type="file" id="image_upload" name="image_upload" onchange="previewImage()" />
                            <img id="image_preview" src="#" alt="Image Preview" />
                            <button type="button" id="upload_button">Upload</button>
                            <p id = "upload_success" style="display:none;">Sucessful</p>
                            <input type="text" id="image_id" name="image_id" value="1" hidden>
                        </div>

                        <script>
                            function previewImage() {
                                var input = document.getElementById('image_upload');
                                var preview = document.getElementById('image_preview');

                                var file = input.files[0];
                                var reader = new FileReader();

                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            }
                            jQuery(document).ready(function ($) {
                                $('#image_generators').change(function () {
                                    if ($(this).val() === 'upload') {
                                        $('#upload_image_section').show();
                                        $('#image_upload').show();
                                    } else {
                                        $('#upload_image_section').hide();
                                        $('#image_upload').show();
                                    }
                                });

                                $('#upload_button').on('click', function () {
                                    var imageFile = $('#image_upload')[0].files[0];
                                    var formData = new FormData();
                                    formData.append('file', imageFile);

                                    fetch('/wp-json/custom/v1/image_upload', {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            // Include any additional headers if needed
                                        },
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        // Assuming the server returns the image ID
                                        console.log(data);
                                        //alert('Image uploaded. Image ID: ' + data.image_id);
                                        var image_id_upload = document.getElementById('image_id').value = data.image_id;
                                        console.log("id da imagem = "+image_id_upload);
                                        $('#upload_success').show();
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        alert('Error uploading image. ' + error.message);
                                    });
                                });
                            });
                        </script>
                    </div>

                </div>
            </div>
            <!-- Form section for Advanced Mode Preferences -->
            <div class="drawer text-style modal-item">
                <h3 onclick="openDrawer('.text-style')" class="open-drawer-ct"><?php _e('Text Style', 'autopost'); ?></h3>
                <p><?php _e('Define style and language', 'autopost'); ?></p>
                <div class="drawer-content">
                    <!-- Content for the drawer -->
                    <div class="form-section">
                        <label for="textlanguage"><?php _e('Text Language:', 'autopost'); ?></label>
                        <select id="textlanguage" name="textlanguage">
                        </select>
                    </div>
                </div>
            </div>

            
            <div class="drawer ia-control modal-item">
                <h3 onclick="openDrawer('.ia-control')" class="open-drawer-ct"><?php _e('iacontrol', 'autopost'); ?></h3>
                <p><?php _e('Customize your generations', 'autopost'); ?></p>
                <div class="drawer-content">
                    <div class="flex-line">
                        <div class=" item-wrap-column">
                            <label for="temperature"><?php _e('Temperature:', 'autopost'); ?></label>
                            <input type="range" id="temperature" name="temperature" min="0" max="1" step="0.01" value="0.7" oninput="this.nextElementSibling.value = this.value">
                            <output>0.7</output>
                        </div>
                        
                        <div class=" item-wrap-column">
                            <label for="max_ctx_tokens"><?php _e('Max Ctx. Tokens:', 'autopost'); ?></label>
                            <input type="range" id="max_ctx_tokens" name="max_ctx_tokens" min="0" max="5000" step="5" value="1024" oninput="this.nextElementSibling.value = this.value">
                            <output>1024</output>
                        </div>       
                        
                        <div class=" item-wrap-column">
                            <label for="amount_to_gen"><?php _e('Amount to Generate:', 'autopost'); ?></label>
                            <input type="range" id="amount_to_gen" name="amount_to_gen" min="0" max="5000" step="1" value="400" oninput="this.nextElementSibling.value = this.value">
                            <output>400</output>
                        </div>
                        
                        <div class=" item-wrap-column">
                            <label for="repetition_penalty"><?php _e('Repetition Penalty:', 'autopost'); ?></label>
                            <input type="range" id="repetition_penalty" name="repetition_penalty" min="-2" max="2" step="0.01" value="0.0" oninput="this.nextElementSibling.value = this.value">
                            <output>0.0</output>
                        </div>
                        
                        <div class=" item-wrap-column">
                            <label for="big_text_count"><?php _e('Big Text Count:', 'autopost'); ?></label>
                            <input type="range" id="big_text_count" name="big_text_count" min="0" max="10" step="1" value="2" oninput="this.nextElementSibling.value = this.value">
                            <output>2</output>
                        </div>
                    </div>
                </div>
            </div>


            
            
        
    </div>
    <div class="form-right-panel">
            <div class="drawer Post-Preferences modal-item">
                <h3 onclick="openDrawer('.Post-Preferences')" class="open-drawer-ct"><?php _e('Publishing settings', 'autopost'); ?></h3>
                <div class="drawer-content">
                    <!-- Content for the drawer -->
                    <p style="padding-left: 5%;"><strong><?php _e('STATUS:', 'autopost'); ?></strong></p>
                    <div id="post_status">
                        <div>
                            <input type="radio" name="post_status" value="publish" id="publish" checked>
                            <label for="publish"><?php _e('Publish', 'autopost'); ?></label>
                        </div>
                        
                        <div>
                            <input type="radio" name="post_status" value="draft" id="draft">
                            <label for="draft"><?php _e('Draft', 'autopost'); ?></label>
                        </div>
                        <div>
                            <input type="radio" name="post_status" value="future" id="schedule">
                            <label for="schedule"><?php _e('Schedule', 'autopost'); ?></label>
                        </div>
                        <div>
                            <input type="radio" name="post_status" value="post_queue" id="daily_schedule">
                            <label for="daily_schedule"><?php _e('Daily Schedule', 'autopost'); ?></label>
                        </div>
                    </div>
                    <p class="autor_select" style="padding-left: 5%;"><strong><?php _e('AUTOR:', 'autopost'); ?></strong></p>
                
                    <div class="">
                    <?php
                        $count_author = 0; 
                        $args = array(
                            'role__in' => array('administrator', 'editor', 'author', 'contributor'),
                            'orderby' => 'display_name',
                            'order' => 'ASC',
                        );
                        $authors = get_users($args); // Using WordPress function get_users() to retrieve users
                        $tmp_a = retrieveUserGeneral();
                        $selected_author = !empty($tmp_a[0]->author_name) ? $tmp_a[0]->author_name : '';
                    ?>
                    <select name="chatgpt_author" class="" id="chatgpt_author">
                        <?php foreach ($authors as $author) : ?>
                            <?php
                            $selected_attribute = ($author->display_name === $selected_author) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_attr($author->display_name) ?>" <?php echo $selected_attribute ?>>
                                <?php echo esc_html($author->display_name) ?>
                            </option>
                        <?php endforeach;?>
                    </select>

                    </div>
                    <p class="category_select" style="padding-left: 5%;"><strong><?php _e('CATEGORIA:', 'autopost'); ?></strong></p>
                    <div class="">
                        <select name="chatgpt_category" class="" id="chatgpt_category">
                            <?php $categories = get_categories(array('hide_empty' => 0));?>
                            <?php foreach ($categories as $category) : ?>
                                <?php
                                $is_first_category = ($category === reset($categories)); // Check if it's the first category
                                $selected_attribute = $is_first_category ? 'selected' : '';
                                ?>
                                <option value="<?php echo esc_attr($category->term_id) ?>" <?php echo $selected_attribute ?>>
                                    <?php echo esc_html($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="new_category">
                                <?php _e('Create Category', 'autopost'); ?>
                            </option>
                        </select>
                    </div>


                    <br>
                    <span id="schedule-fields" style="display: none;">
                        <input type="datetime-local" name="schedule_datetime" id="schedule_datetime" value="" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?>>
                    </span>
                </div>
            </div>

<!-- Buttons for Back and Generate with AI -->
            <div style="
    display: flex;
    flex-direction: column;
    align-items: stretch;
">
                <button type="button" id="generate-button" onclick="sendFormData()" class="button-style">Generate with AI</button>
                <button type="button" id="back-button" onClick="closeModal('post-modal')" class="button-style-alt">Back</button>
            </div>
            
        </div>
</form>
<div class="fullscreen-modal" id="fullscreenModal">
    <div class="message" id="loadingMessage">Iniciando IA...</div>
    <div class="loading-bar">
        <div class="progress-bar" id="progressBar" style="
    width: 0%;
"></div>
    </div>
</div>
<script>
    function start_reload(){
        document.getElementById("fullscreenModal").style.display = "flex";
        
        setTimeout(function () {
            document.getElementById("progressBar").style.width = "100%";
        }, 2000);
        setTimeout(function () {
            document.getElementById("loadingMessage").innerText = "Reloading...";
            document.getElementById("progressBar").style.background = "#40f0d9";
        }, 7000);
        // Simulate loading for 5 seconds
        setTimeout(function () {
            // Hide the fullscreen modal after 5 seconds
            //document.getElementById("fullscreenModal").style.display = "none";
            
            // Reload the page
            location.reload();
        }, 8000);
    }
</script>
<style>
        /* Fullscreen modal styles */
        .fullscreen-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 999;
            flex-direction: column;
            background-blend-mode: overlay;
            backdrop-filter: blur(12px);
        }

        /* Loading bar styles */
        .loading-bar {
            width: 70%;
            background: #fff;
            height: 20px;
            position: relative;
            border-radius: 20px;
        }

        .progress-bar {
            width: 0%;
            height: 100%;
            border-radius: 20px;
            background: #4CAF50;
            transition: width 5s ease-in-out;
        }

        /* Message styles */
        .message {
            color: #fff;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
<script>

function openDrawer(drawerId){
    var drawer = document.querySelector(drawerId);
    drawer.classList.toggle('open');
}
$('#post_create_form').submit(function(e) {
   e.preventDefault();

})
</script>

<script>

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
async function sendFormData() {
    // Collect form data
    //const command = document.getElementById("command").value;
    const topics = document.getElementById("topics").value;
    const llm = document.getElementById("llm").value;
    const imageGenerators = document.getElementById("image_generators").value;
    const temperature = document.getElementById("temperature").value;
    const maxCtxTokens = document.getElementById("max_ctx_tokens").value;
    const amountToGen = document.getElementById("amount_to_gen").value;
    const repetitionPenalty = document.getElementById("repetition_penalty").value;
    const bigTextCount = document.getElementById("big_text_count").value;

    const chatgpt_author = document.getElementById("chatgpt_author").value;
    const chatgpt_category = document.getElementById("chatgpt_category").value;
    const post_status = getSelectedValueFromRadial("post_status");
    const schedule_datetime = document.getElementById("schedule_datetime").value;

    const textlanguage = document.getElementById("textlanguage").value;

    const type_article = document.getElementById("type_article").value;
    const writen_tone = document.getElementById("writen_tone").value;

    const image_id = document.getElementById('image_id').value;


    

    // Create a data object with the form value
    

    const data = {
        //command: command,
        topics: topics,
        llm: llm,
        image_generators: imageGenerators,
        temperature: temperature,
        max_ctx_tokens: maxCtxTokens,
        amount_to_gen: amountToGen,
        repetition_penalty: repetitionPenalty,
        big_text_count: bigTextCount,
        chatgpt_author: chatgpt_author,
        chatgpt_category: chatgpt_category,
        post_status: post_status,
        schedule_datetime: schedule_datetime,
        language: textlanguage,
        type_article: type_article,
        writen_tone: writen_tone,
        image_id: image_id,
    };

    // Replace 'your_server_url_here' with the actual URL of your server/router

    const url = '/wp-json/custom/v1/process_form';
    
    fetch(url, {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        // Handle the response as needed
        return response.json();
    })
    .then(data => {
        // Handle the data from the server's response
        console.log(data);
    })
    .catch(error => {
        // Handle errors
        console.error('There was a problem with the fetch operation:', error);
    })
    .finally(() => {
        // setTimeout(() => {
        //     window.location.reload();
        // }, 2000);
    });
    start_reload();
}
function getSelectedValueFromRadial(id) {
        var radioButtons = document.getElementsByName(id);

        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                console.log(radioButtons[i].checked);
                return radioButtons[i].value;
            }
        }

        // Default value if none is selected
        return null;
    }
function toggleInputAndTextarea(id, class_name ,isTextArea) {
    const inputElement = document.getElementById(id);
    //const advancedModal = document.getElementById("advanced-prompt");
    if (isTextArea) {
        if (inputElement.tagName === "INPUT") {
            const textareaElement = document.createElement("textarea");
            textareaElement.id = id;
            textareaElement.name = id;
            textareaElement.classList.add(class_name)
            textareaElement.value = inputElement.value;

            inputElement.parentNode.replaceChild(textareaElement, inputElement);
            //advancedModal.style.display = "block";
        }
    } else {
        if (inputElement.tagName === "TEXTAREA") {
            const newInputElement = document.createElement("input");
            newInputElement.type = "text";
            newInputElement.id = id;
            newInputElement.name = id;
            newInputElement.classList.add("single_key")
            newInputElement.value = inputElement.value;

            inputElement.parentNode.replaceChild(newInputElement, inputElement);
            //advancedModal.style.display = "none";
        }
    }
}

</script>
<script>
jQuery(document).ready(function($) {
    $('input[name="post_status"]').change(function() {
        var selectedOption = $(this).val();
        if (selectedOption === 'future') {
            $('#schedule-fields').show();
        } else {
            $('#schedule-fields').hide();
        }
    });
});

</script>


<style>
    
/* Form Section Styles */
.form-section {
    margin: 10px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

/* Checkboxes */
input[type="checkbox"] {
    margin-left: 10px;
}

/* Text Inputs */
input[type="text"] {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Dropdowns */
select {
    width: 100%;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Buttons */
button {
    background-color: #0073e6;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 5px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #005aa1;
}

/* Additional Styles (e.g., for headings and descriptions) */
h2, p {
    margin: 10px 0;
}
/* Style for the drawer container */
.drawer {
    position: relative;
    display: flex;
    height: 100%;
    background-color: #fff;
    box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
    flex-direction: column;
    flex-wrap: nowrap;
    align-content: flex-start;
    align-items: flex-start;
}
.flex-line{
    display: flex;
    flex-direction: column;
    align-content: flex-start;
    align-items: stretch;
    flex-wrap: nowrap;
}

/* Style for the drawer content (hidden by default) */
.drawer-content {
  display: block; /* Change from "none" to "block" */
  padding: 0px;
  width: -webkit-fill-available;
  max-height: 0; /* Initially hidden with a height of 0 */
  overflow: hidden; /* Hide content that overflows the max-height */

  /* Add CSS transitions for a smooth animation */
  transition: max-height 0.5s ease;
}

/* Show the content when the drawer is open */
.drawer.open .drawer-content {
  max-height: 1000px; /* Adjust this value to accommodate your content */
  flex-direction: column;
    flex-wrap: nowrap;
    width: 100%;
}
.drawer-content div {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: left;
}
.drawer-content label{
    color: var(--brand-light-primary-light, #5445EB);
}
.drawer-content-column{
    display: flex;
    flex-direction: row !important;
    justify-content: space-around;
    align-items: center;
}



</style>