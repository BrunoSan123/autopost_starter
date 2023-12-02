<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;
$gpt_token = get_option('chatgpt_api_key');

// Check if $gpt_token is set and not an empty string
if (isset($gpt_token) && !empty($gpt_token)) {
    $show_quickstart = false;
} else {
    // Handle the case when $gpt_token is not set or is an empty string
    $show_quickstart = true;
}




$quickstart_url = 'wp-admin/admin.php?page=chat_gpt_quick_start_part_1';
if ($show_quickstart) {
    ?>
    <script>
        var quickstartUrl = '/wp-admin/admin.php?page=chat_gpt_quick_start_part_1';
        window.location.href = quickstartUrl;
        <?php update_option('chatgpt_api_key','holder')?>
    </script>
    <?php

}


function count_words_in_posts($query) {
    // Initialize the total word count
    $total_word_count = 0;


    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the content of the post
            $post_content = get_the_content();

            // Strip HTML and PHP tags
            $post_content = strip_tags($post_content);

            // Split the content into words
            $words = preg_split('/\s+/', $post_content);

            // Count the number of words in the post
            $word_count = count($words);

            // Add the word count of the current post to the total
            $total_word_count += $word_count;
        }

        // Reset post data
        wp_reset_postdata();
    }

    return $total_word_count;
}

$args = array(
    'post_type' => 'post', // Replace with your custom post type if needed
    //'name__like' => 'post_name_', // Replace with your desired post name pattern
    'meta_query' => array(
        array(
            'key'   => 'autopost',
            'value' => 'true',
        ),
    ),
    'post_status' => 'publish', // Include only published posts
    'posts_per_page' => -1, // Retrieve all matching posts
);

$posts = new WP_Query($args);

$post_count = $posts->found_posts;
$word_count = count_words_in_posts($posts);

$gpt_3_model = 'gpt-3.5-turbo-16k-0613'; 
$gpt_4_model='gpt-4';
$gpt_3_total_sum = get_total_tokens_sum_for_model($gpt_3_model);
$gpt_4_total_sum=get_total_tokens_sum_for_model($gpt_4_model);
$gpt3_general_billing=get_genreal_tokens_value($gpt_3_model);
$gpt4_general_billing=get_genreal_tokens_value($gpt_4_model);

function billing_sum($total_prompts,$total_completions, $model) {
  
     $prompt_billing=0;
     $completion_billing=0;
    switch ($model) {
        case "gpt-3.5-turbo-16k-0613":
            if ($total_prompts == 1000) {
                 $prompt_billing=$total_prompts * 0.0010;
            } elseif ($total_completions == 1000) {
                 $completion_billing=$total_completions * 0.0020;
            }
            break;
        case "gpt-4":

            if ($total_prompts == 1000) {
                $prompt_billing=$total_prompts * 0.03;
            } elseif ($total_completions == 1000) {
                $completion_billing=$total_completions * 0.06;
            }
            break;
    }

    return $prompt_billing+$completion_billing;
}



$chartDataByDay = getChartDataByDay();
$chartDataByDayJSON = json_encode($chartDataByDay);

$plugin_url = plugins_url('auto_post_application', "");
$img_article_line = $plugin_url . '/images/remix-icons/Socument/article-line.svg';
$img_comm = $plugin_url . '/images/remix-icons/Communication/chat-3-line.svg';
$img_words = $plugin_url . '/images/remix-icons/Editor/a-b.svg';
$img_magic_fill = $plugin_url . '/images/remix-icons/Design/magic-fill.svg';

require_once(dirname(__FILE__).'/apply_dark_mode.php');
?>
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'dashboard.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'dashboard-dark.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'general.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'general-dark.css');?>" /> 

<div  class="wrap <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'dark' ? 'dark' : ''; ?>">
    <h2><?php _e('Dashboard', 'autopost'); ?></h2>

    <div class="row">
        <!-- Left Column for Usage Cost -->
        <div class="column card-background" style="width: 30%;">
            <div class="create-post-column">
                <button id="create-post" class="create-post" onClick="openModal('post-modal'); toggleInputAndTextarea('topics','', false)">
                    <h3><?php _e('Create new post', 'autopost'); ?> </h3>
                    <span><?php _e('Generate with AI', 'autopost'); ?> <img src="<?php echo($img_magic_fill) ?>" alt="" srcset=""></span>
                </button>
            </div>
            
            <div class="card item-div billing card-background">
                <h3><?php _e('Usage Cost', 'autopost'); ?></h3>
                <table class="dashboard_table">
                    <tr>
                        <td><?php echo $gpt_3_model; ?></td>
                        <td class="ia-data"><?php echo $gpt_3_total_sum.'tokens ~'.' $'.billing_sum($gpt3_general_billing[0]->prompts,$gpt3_general_billing[0]->completion,$gpt_3_model); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $gpt_4_model; ?></td>
                        <td class="ia-data"><?php echo $gpt_4_total_sum.'tokens ~'.' $'.billing_sum($gpt4_general_billing[0]->prompts,$gpt4_general_billing[0]->completion,$gpt_4_model); ?></td>
                    </tr>
                </table>
            </div>
        </div>


        <!-- Right Column for other cards -->
        <div class="column card-background">
            <div class="row">
                <!-- Generated Articles Card -->
                <div class="card item-div sumary">
                    <div class="icon-container">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1310_4017)">
                        <path d="M20 22.5H4C3.73478 22.5 3.48043 22.3946 3.29289 22.2071C3.10536 22.0196 3 21.7652 3 21.5V3.5C3 3.23478 3.10536 2.98043 3.29289 2.79289C3.48043 2.60536 3.73478 2.5 4 2.5H20C20.2652 2.5 20.5196 2.60536 20.7071 2.79289C20.8946 2.98043 21 3.23478 21 3.5V21.5C21 21.7652 20.8946 22.0196 20.7071 22.2071C20.5196 22.3946 20.2652 22.5 20 22.5ZM19 20.5V4.5H5V20.5H19ZM7 6.5H11V10.5H7V6.5ZM7 12.5H17V14.5H7V12.5ZM7 16.5H17V18.5H7V16.5ZM13 7.5H17V9.5H13V7.5Z" fill="#503FFD"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_1310_4017">
                        <rect width="24" height="24" fill="white" transform="translate(0 0.5)"/>
                        </clipPath>
                        </defs>
                    </svg>
                    </div>
                    <div>
                        <h3><?php _e('Generated Articles', 'autopost'); ?></h3>
                        <p>Total: <?php echo($post_count);?></p>
                    </div>
                </div>

                <!-- Used Topics Card -->
                <div class="card item-div sumary">
                <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                    <g clip-path="url(#clip0_1310_4024)">
                    <path d="M7.95764 21.324L2.66664 22.5L3.84264 17.209C3.06817 15.7604 2.66416 14.1426 2.66664 12.5C2.66664 6.977 7.14364 2.5 12.6666 2.5C18.1896 2.5 22.6666 6.977 22.6666 12.5C22.6666 18.023 18.1896 22.5 12.6666 22.5C11.024 22.5025 9.40625 22.0985 7.95764 21.324ZM8.24764 19.211L8.90064 19.561C10.0592 20.1801 11.353 20.5027 12.6666 20.5C14.2489 20.5 15.7956 20.0308 17.1112 19.1518C18.4268 18.2727 19.4522 17.0233 20.0577 15.5615C20.6632 14.0997 20.8216 12.4911 20.5129 10.9393C20.2042 9.38743 19.4423 7.96197 18.3235 6.84315C17.2047 5.72433 15.7792 4.9624 14.2274 4.65372C12.6755 4.34504 11.067 4.50346 9.60517 5.10896C8.14336 5.71447 6.89393 6.73984 6.01488 8.05544C5.13583 9.37103 4.66664 10.9177 4.66664 12.5C4.66664 13.834 4.99164 15.118 5.60664 16.266L5.95564 16.919L5.30064 19.866L8.24764 19.211Z" fill="#503FFD"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_1310_4024">
                    <rect width="24" height="24" fill="white" transform="translate(0.666626 0.5)"/>
                    </clipPath>
                    </defs>
                </svg>
                </div>
                    <div>
                        <h3><?php _e('Used Topics', 'autopost'); ?></h3>
                        <p>Total: <?php echo($post_count);?></p>
                    </div>
                </div>

                <!-- Generated Words Card -->
                <div class="card item-div sumary">
                <div class="icon-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <g clip-path="url(#clip0_1310_4031)">
                <path d="M5.3335 15.5V17.5C5.3335 18.554 6.2835 19.5 7.3335 19.5H10.3335V21.5H7.3335C6.27263 21.5 5.25521 21.0786 4.50507 20.3284C3.75492 19.5783 3.3335 18.5609 3.3335 17.5V15.5H5.3335ZM18.3335 10.5L22.7335 21.5H20.5785L19.3775 18.5H15.2875L14.0885 21.5H11.9345L16.3335 10.5H18.3335ZM17.3335 13.385L16.0865 16.5H18.5785L17.3335 13.385ZM3.3335 3.5H9.3335C9.91253 3.50021 10.4791 3.66798 10.9649 3.98307C11.4507 4.29817 11.835 4.74711 12.0713 5.27574C12.3076 5.80437 12.3858 6.39009 12.2966 6.96221C12.2074 7.53433 11.9545 8.06841 11.5685 8.5C11.9545 8.93159 12.2074 9.46567 12.2966 10.0378C12.3858 10.6099 12.3076 11.1956 12.0713 11.7243C11.835 12.2529 11.4507 12.7018 10.9649 13.0169C10.4791 13.332 9.91253 13.4998 9.3335 13.5H3.3335V3.5ZM9.3335 9.5H5.3335V11.5H9.3335C9.59871 11.5 9.85307 11.3946 10.0406 11.2071C10.2281 11.0196 10.3335 10.7652 10.3335 10.5C10.3335 10.2348 10.2281 9.98043 10.0406 9.79289C9.85307 9.60536 9.59871 9.5 9.3335 9.5ZM17.3335 3.5C18.3944 3.5 19.4118 3.92143 20.1619 4.67157C20.9121 5.42172 21.3335 6.43913 21.3335 7.5V9.5H19.3335V7.5C19.3335 6.96957 19.1228 6.46086 18.7477 6.08579C18.3726 5.71071 17.8639 5.5 17.3335 5.5H14.3335V3.5H17.3335ZM9.3335 5.5H5.3335V7.5H9.3335C9.59871 7.5 9.85307 7.39464 10.0406 7.20711C10.2281 7.01957 10.3335 6.76522 10.3335 6.5C10.3335 6.23478 10.2281 5.98043 10.0406 5.79289C9.85307 5.60536 9.59871 5.5 9.3335 5.5Z" fill="#503FFD"/>
                </g>
                <defs>
                <clipPath id="clip0_1310_4031">
                <rect width="24" height="24" fill="white" transform="translate(0.333496 0.5)"/>
                </clipPath>
                </defs>
                </svg>
                </div>
                    <div>
                        <h3><?php _e('Generated Words', 'autopost'); ?></h3>
                        <p>Total: <?php echo($word_count);?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="post-modal" class="modal">
            <?php require_once(plugin_dir_path(__FILE__).'create_post_page.php');?>
    </div>
</div>


<script>
    function openModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = 'block';
    }
    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.style.display = 'none';
    }
</script>


<script>
       const modalPallet=document.querySelectorAll(".switch_input")

        modalPallet.forEach((e)=>{
            e.addEventListener('change',()=>{
                const base_config=document.getElementById("basic_config")
                const advanced= document.getElementById("advanced_config")
                const andvancedInput=document.querySelector(".Advanced-prompt")

                            

                modalPallet.forEach((j,i)=>{
                    if(j!==e){
                        j.checked=false;
                        //topics.value='';
                        andvancedInput.style.display='none'
                    }else{
                        andvancedInput.style.display='block'
                    }

                })

            })
        })
</script>

