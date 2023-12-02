

<!-- Content for section Generation Preferences -->
<?php 

require_once(dirname(__FILE__).'/settings_backend.php');
$config=retriveAutopostconfig()[0];
?>

<div class="tab-content" id="tab-generation-preferences">
            <form method="post" action="#" class="settings-field">
             
             <div class="tab-content-card full">
                        <h3><?php _e('Generating Preferences','autopost')?></h3>
                        <b><?php _e('Add your own touch and customize how you want the AI’s outputs to be.','autopost')?></b>
                        <div style="align-self: stretch"><span><?php _e('Obs: These settings and preferences will be applied to all future generations you create. You can customize them individually for each article when making edits','autopost')?></span></div>
            </div>
<br>


<div class="forms-container">
        <div id="id-form1" class="form">
            <div class="settings-title">
			<h2><?php _e('Default Creation Mode','autopost')?></h2> 
			</div>
			<p class="settings-title-subtitle"><?php _e('Customize your generations with some help','autopost')?></P>
			
            <p class="settings-subitem-header"><?php _e('Generation customization','autopost')?></p>
			
			<form action="#" method="post">
                <label for="words_per_article" class="settings-subitem"><?php _e('Words per Article:','autopost')?></label>
				<p class="settings-title-subtitle"><?php _e('How many words each generated article must have','autopost')?></p>
                <input class="general-input-box basic_input"  type="number" id="words_per_article" name="words_per_article" value="<?php echo isset($config->words_per_article)?$config->words_per_article:0?>">

                <label for="type_per_article" class="settings-subitem"><?php _e('Type per Article','autopost')?></label>
                <select id="type_per_article" class="basic_input"  name="type_per_article">
                <?php if(isset($config->article_type)):?>
                    <option value="<?php echo$config->article_type ?>" selected><?php _e('Default '.'('.$config->article_type.')','autopost')?></option>
                <?php endif?>
                    <option value="Blog"><?php _e('(Blog post)','autopost')?></option>
                    <option value="News"><?php _e('News','autopost')?></option>
                    <option value="Review"><?php _e('Review','autopost')?></option>
                </select>

                <label for="written_tone" class="settings-subitem"><?php _e('Written Tone','autopost')?></label>
                <select id="written_tone" class="basic_input"  name="written_tone">
                <?php if(isset($config->writen_tone)):?>
                    <option value="<?php echo$config->writen_tone ?>" selected><?php _e('Default '.'('.$config->writen_tone.')','autopost')?></option>
                <?php endif?>
                    <option value="Neutral"><?php _e('Neutral','autopost')?></option>
                    <option value="Casual"><?php _e('Casual','autopost')?></option>
                    <option value="Professional"><?php _e('Professional','autopost')?></option>
                </select>
				
				
				<p class="settings-subitem-header"><?php _e('Text style','autopost')?></p>
				
                <label for="generated_text_language" class="settings-subitem"><?php _e('Generated Text Language','autopost')?></label>
                <select id="generated_text_language" class="basic_input"  name="generated_text_language">
                </select>
				
				
				<p class="settings-subitem-header"><?php _e('Image Options','autopost')?></p>
				
				<br>
				<br>
				<label for="image-style" class="settings-subitem"><?php _e('Image style','autopost')?></label>
                <select id="image-style" class="basic_input"  name="image-style">
                <?php if(isset($config->image_style)):?>
                    <option value="<?php echo$config->image_style ?>" selected><?php _e('Default '.'('.$config->image_style.')','autopost')?></option>
                <?php endif?>
                    <option value="Illustration"><?php _e('Illustration','autopost')?></option>
                    <option value="Photography"><?php _e('Photography','autopost')?></option>
                    <option value="painting"><?php _e('Painting','autopost')?></option>
                </select>	
                <input type="submit" value="Save Default Settings" class="submit-button basic_input" name="basic_config">
                <?php submit_basic()?>
            </form>
        </div>
		
<!-- FORMULÁRIO ADVANCED -->		
	</div>
	
		

