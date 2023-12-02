<aside id="sidebar" class="widget-area">
    <h2>CONFIGURAÇÕES</h2>
    <div class="lumini_items">
        <input type="checkbox" name="CLEAN" id="clean"  class="input_clean input_gpt">
        <label class="dark_light_radio" for="clean" onClick="disableDarkMode()">
            <div class="radio-btn"></div>
            <span data-notranslate >CLEAN</span>
        </label>
        <input  type="checkbox" name="DARK" id="dark"  class="input_dark input_gpt">
        <label class="dark_light_radio" for="dark" onClick="enableDarkMode()">
            <div class="radio-btn"></div>
            <span data-notranslate >DARK</span>
        </label>
    </div>
    <div class="sidebar_item">
    <div class="authors selection_item">
    <p class="autor_select" style="padding-left: 5%;"><strong>AUTOR:</strong></p>
         
    <div class="authors_item">
      <?php $count_author = 0; ?>
      <?php foreach ($authors as $author) : ?>
          <?php
          $is_first_author = ($count_author === 0);
          $checked_attribute = $is_first_author ? 'checked' : '';
          ?>
          <input type="checkbox" name="chatgpt_author" value="<?php echo esc_attr($author->ID) ?>" class="author_input" id="chatgpt_author-<?php echo $count_author ?>" <?php echo $checked_attribute ?>>
          <label for="chatgpt_author-<?php echo $count_author ?>" class="authors_input_label"><?php echo esc_html($author->display_name) ?></label>
          <?php
          $count_author++;
          endforeach;
          ?>
      <input type="checkbox" value="criar usuario" class="author_input" id="new_user">
      <label for="new_user" class="authors_input_label new_author">Criar Usuario</label>
    </div>
  

    <div class="category selection_item">
    <p class="category_select" style="padding-left: 5%;"><strong>CATEGORIA:</strong></p>
    <div class="category_item">
      <?php $count_category = 0; ?>
      <?php foreach ($categories as $category) : ?>
          <?php
          $is_first_category = ($count_category === 0);
          $checked_attribute = $is_first_category ? 'checked' : '';
          ?>
          <input type="checkbox" name="chatgpt_category" value="<?php echo esc_attr($category->term_id) ?>" class="category_input" id="chatgpt_category-<?php echo $count_category ?>" <?php echo $checked_attribute ?>>
          <label for="chatgpt_category-<?php echo $count_category ?>" class="authors_input_label"><?php echo esc_html($category->name) ?></label>
          <?php
          $count_category++;
          endforeach;
          ?>
      <input type="checkbox" value="criar categoria" class="category_input" id="new_category">
      <label for="new_category" class="authors_input_label new_category">Criar Categoria</label>
  </div>
  
</div>

    <div class="post_schedule">
    <p style="padding-left: 5%;"><strong>STATUS:</strong></p>
            <div class="lumini_items">
                <input class="schedule_input" type="radio" id="post_status_auto" name="post_status" value="auto" checked>
                <label class="input_schedul dark_light_radio" for="post_status_auto">
                    <div class="radio_schedule"></div>
                    <?php _e('PUBLICAR', 'autopost'); ?>
                </label><br>
            </div>
            
            <div class="lumini_items">
                <input class="schedule_input"  type="radio" id="post_status_draft" name="post_status" value="draft" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?>>
                <label class="input_schedule dark_light_radio" for="post_status_draft">
                    <div class="radio_schedule"></div>
                    <?php _e('RASCUNHO', 'autopost'); ?>
                </label>
            </div>
            <?php if ( chatgpt_freemius_integration()->is_not_paying() ) : ?><span>(Versão Premium)</span>
            <?php endif; ?>
            <div class="lumini_items">
                <input class="schedule_input schedulee"  type="radio" id="post_status_schedule" name="post_status" value="schedule" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?>> 
                <label class="input_schedule dark_light_radio" for="post_status_schedule">
                    <div class="radio_schedule"></div>
                    AGENDAR
                </label>
            </div>
            <span id="schedule_datetime_container">
                  <input type="datetime-local" name="schedule_datetime" id="schedule_datetime" value="" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?>>
            </span>
            <div class="lumini_items">
                <input class="schedule_input schedulee"  type="radio" id="smart-post_status_schedule" name="post_status" value="post_queue" <?php echo chatgpt_freemius_integration()->is_not_paying() ? 'disabled' : ''; ?>> 
                <label class="input_schedule dark_light_radio" for="smart-post_status_schedule">
                    <div class="radio_schedule"></div>
                    AGENDAR POSTS DIARIOS
                </label>
            </div>

    </div>
    <div class="post_sections">
        <p style="padding-left: 5%;"><strong>SECTIONS:</strong></p>
        <div class="lumini_items" style="
        height: 40px;
    ">
            <input class="schedule_input" type="number" id="sections_count" name="sections_count" min="1" value="5" style="visibility:visible; width:100px;">
        </div>
    </div>

    <div class="nichs">
      <div>
        <input type="checkbox" name="keyword" id="key" value="keyword" class="text_type">
        <label for="key">Palavra-chave</label>
        <input type="checkbox" name="post_title" id="post" value="post_title" class="text_type">
        <label for="post">Titulo para post</label>
      </div>
      <select name="quantity" id="steps">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
        <option value="60">60</option>
        <option value="70">70</option>
        <option value="80">80</option>
        <option value="90">90</option>
        <option value="100">100</option>
      </select>
      <input type="text" name="nichs" id="nich" placeholder="Digite seu nicho">
      <button class="topics_generate">Gerar tópicos</button>
      <div class="loading_div"></div>
    </div>

    <div class="image_daali">
    <p style="padding-left: 5%;"><strong>IMAGEM:</strong></p>

    <div data-notranslate class="lumini_items">
                <input class="ia_image_input" type="radio" id="ia_dalle" name="ia_dalle" value="auto">
                <label class="input_ia_image dark_light_radio" for="ia_dalle">
                    <div class="radio-btn-image"></div>
                    DALL-E
                </label><br>
    </div>
    <div data-notranslate class="lumini_items">
                <input class="ia_image_input" type="radio" id="ia_midjournal" name="ia_midjournal" value="auto">
                <label class="input_ia_image dark_light_radio" for="ia_midjournal">
                    <div class="radio-btn-image"></div>
                    MIDJOURNAL
                </label><br>
    </div>
    <div data-notranslate class="lumini_items">
                <input class="ia_image_input" type="radio" id="ia_google_image" name="ia_google_image" value="auto">
                <label class="input_ia_google dark_light_radio" for="ia_google_image">
                    <div class="radio-btn-image"></div>
                    GOOGLE
                </label><br>
    </div>
    <div data-notranslate class="lumini_items">
                <input class="ia_image_input upload-image" type="radio" id="ia_send" name="ia_send" value="auto">
                <label class="input_ia_image dark_light_radio" for="ia_send">
                    <div class="radio-btn-image"></div>
                    ENVIAR IMAGEM
                </label><br>
    </div>
    <input type="file" name="image_upload" id="image_upload">
    </div>

<script>
    const pageBackground = document.getElementById("wpbody-content");
const sidebar = document.getElementById("sidebar");
const switch_container = document.getElementById("chatgpt_model");
const gpt_textareas = document.querySelectorAll(".chat_textarea");
const selection_items = document.querySelectorAll(".selection_item");

// Function to set a cookie
function setCookie(name, value, days) {
  const expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
  document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

// Function to get a cookie by name
function getCookie(name) {
  const cookieName = `${name}=`;
  const cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i].trim();
    if (cookie.indexOf(cookieName) === 0) {
      return cookie.substring(cookieName.length, cookie.length);
    }
  }
  return null;
}

// Check if dark mode is enabled using the cookie
const darkModeCookie = getCookie("darkModeEnabled");
if (darkModeCookie === "true") {
  enableDarkMode();
}

// Function to enable dark mode and set the cookie
function enableDarkMode() {
  pageBackground.classList.add("dark");
  sidebar.classList.add("sidebar-dark");
  switch_container.classList.add("sidebar-dark");
  gpt_textareas.forEach((e) => {
    e.classList.add("dark");
  });
  selection_items.forEach((e) => {
    e.classList.add("arrow_white");
  });

  // Set the dark mode cookie to true
  setCookie("darkModeEnabled", "true", 365); // Expires in 365 days
}

// Function to disable dark mode and set the cookie
function disableDarkMode() {
  pageBackground.classList.remove("dark");
  sidebar.classList.remove("sidebar-dark");
  switch_container.classList.remove("sidebar-dark");
  gpt_textareas.forEach((e) => {
    e.classList.remove("dark");
  });
  selection_items.forEach((e) => {
    e.classList.remove("arrow_white");
  });

  // Set the dark mode cookie to false
  setCookie("darkModeEnabled", "false", 365); // Expires in 365 days
}
</script>

</aside>