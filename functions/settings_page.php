<?php

function chatgpt_plugin_settings_page()
{
    require_once(dirname(__FILE__) . '/pages/apply_dark_mode.php');
?>

    <link rel="stylesheet" type="text/css" href="<?php echo (plugin_dir_url(__FILE__) . 'settings.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo (plugin_dir_url(__FILE__) . 'settings-dark.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo (plugin_dir_url(__FILE__) . 'pages/general.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo (plugin_dir_url(__FILE__) . 'pages/general-dark.css'); ?>" />
    <div id="tab-general" class="wrap settings-field <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'dark' ? 'dark' : ''; ?>">

        <div class="background-overlay"></div>

        <!--	<h2><?php _e('ChatGPT Autopost Settings', 'textdomain'); ?></h2> 
			oculto por hora	-->
        <br>
        <br>
        <br>

        <!-- Create tab navigation -->
        <p class="nav-tab-wrapper tab-style">
            <a href="#" class=" tab-style-button tab-active">General Preferences</a>
            <a href="#" class=" tab-style-button">Generation Preferences</a>
            <a href="#" class=" tab-style-button">AI APIs</a>
            <a href="#" class=" tab-style-button">Authors</a>
        </p>

        <!-- Create tab content for General Preferences-->
        <?php
        $file_path = plugin_dir_path(__FILE__) . 'settings/settings-preferences.php';
        if (file_exists($file_path)) {
            ob_start();
            include($file_path);
            $file_content = ob_get_clean();
            echo $file_content;
        } else {
            echo "File not found: $file_path";
        }
        ?>



        <!-- Create tab content for Generation Preferences -->
        <?php
        $file_path = plugin_dir_path(__FILE__) . 'settings/settings-general.php';
        if (file_exists($file_path)) {
            ob_start();
            include($file_path);
            $file_content = ob_get_clean();
            echo $file_content;
        } else {
            echo "File not found: $file_path";
        }
        ?>
        </form>

    </div>

    <!-- Create tab content for OpenAI settings -->
    <?php
    $file_path = plugin_dir_path(__FILE__) . 'settings/settings-page-aiapis.php';
    if (file_exists($file_path)) {
        ob_start();
        include($file_path);
        $file_content = ob_get_clean();
        echo $file_content;
    } else {
        echo "File not found: $file_path";
    }
    ?>
    <!-- Create tab content for Authors settings -->
    <?php
    $file_path = plugin_dir_path(__FILE__) . 'settings/settings-page-authors.php';
    if (file_exists($file_path)) {
        ob_start();
        include($file_path);
        $file_content = ob_get_clean();
        echo $file_content;
    } else {
        echo "File not found: $file_path";
    }
    ?>
<?php
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        if (isset($_POST['chatgpt_api_key'])) {
            $api_key = sanitize_text_field($_POST['chatgpt_api_key']);
            update_option('chatgpt_api_key', $api_key);
        }
        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }
}
?>