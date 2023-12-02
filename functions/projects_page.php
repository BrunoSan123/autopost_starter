<?php 
$plugin_url = plugins_url('auto_post_application', "");
$img_magic_fill = $plugin_url . '/images/remix-icons/Design/magic-fill.svg';
$img_more_2_line = $plugin_url . '/images/remix-icons/System/more-2-line.svg';
require_once(dirname(__FILE__).'/pages/apply_dark_mode.php');
?>

<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'projects.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'projects-dark.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'pages/general.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo(plugin_dir_url(__FILE__).'pages/general-dark.css');?>" />
<div class="wrap <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'dark' ? 'dark' : ''; ?>">
    <h2><?php _e('Generator Page', 'autopost'); ?></h2>

    <div class="row">
        <div class="search-bar-div">
            <spam><?php _e('Search through your articles', 'autopost'); ?></spam>
            <input type="text" id="search-bar" placeholder="<?php _e('Search...', 'autopost'); ?>">
        </div>
        <div class="row-right-corner">
            <?php require_once(dirname(__FILE__).'/../templates/generate_buttons.php')?>
        </div>
    </div>


    <!-- Div to display posts from a specific category -->
    <div id="filters" class="filters-style">
        <button class="filter-button <?php echo (isset($_GET['post_status']) && ($_GET['post_status'] == 'all' || empty($_GET['post_status'])) ? 'selected-category' : ''); ?>" data-category="all">All</button>
        <button class="filter-button <?php echo (isset($_GET['category_name']) && $_GET['category_name'] == 'onqueue') ? 'selected-category' : ''; ?>" data-category="onqueue">Generating</button>
        <button class="filter-button <?php echo (isset($_GET['post_status']) && $_GET['post_status'] == 'publish') ? 'selected-category' : ''; ?>" data-category="publish">Published</button>
        <button class="filter-button <?php echo (isset($_GET['post_status']) && $_GET['post_status'] == 'draft') ? 'selected-category' : ''; ?>" data-category="draft">Draft</button>
        <button class="filter-button <?php echo (isset($_GET['post_status']) && $_GET['post_status'] == 'future') ? 'selected-category' : ''; ?>" data-category="future">Schedule</button>
    </div>





    <div id="posts-container" class="post-container">
        <?php
        $filter_category = isset($_GET['category_name']) ? $_GET['category_name'] : '';
        $filter_status = isset($_GET['post_status']) ? $_GET['post_status'] : '';

        // Define your custom query parameters based on the selected filter
        $args = array(
            'meta_query' => array(
                array(
                    'key'   => 'autopost',
                    'value' => 'true',
                ),
            ),
            'posts_per_page' => -1, // Display all posts
        );

        if ($filter_category) {
            $args['category_name'] = $filter_category; // Set the category based on the filter
        }

        if ($filter_status) {
            $args['post_status'] = $filter_status; // Set the post status based on the filter
        }

        $posts = new WP_Query($args);

        if ($posts->have_posts()) :
            while ($posts->have_posts()) :
                $posts->the_post();
                $categories = get_the_category();
                $onQueueCategory = false;

                foreach ($categories as $category) {
                    if ($category->slug === 'onqueue') {
                        $onQueueCategory = true;
                        break;
                    }
                }
                ?>
                <div class="post-card" data-post-id="<?php echo get_the_id() ?>">
                    <input type="checkbox" class="choosen_element">
                    <?php
                    $postStatus = get_post_status(get_the_ID());
                    $statusClass = 'post-status-' . $postStatus;
                    // Output post status or "onqueue" category text
                    if ($postStatus && !$onQueueCategory) {
                        echo '<span class="post-status ' . $statusClass . '">' . $postStatus . '</span>';
                    } elseif ($onQueueCategory) {
                        echo '<span class="post-category post-status post-category-onqueue">Gerando</span>';
                    }
                    ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="meter" <?php if (!$onQueueCategory) echo 'style="display: none;"'; ?>>
                        <span style="width: 100%">
                            <span style="transform: translate(-30px, -5px); position: absolute;">
                                <?php _e('Generating', 'autopost'); ?>
                            </span>
                        </span>
                    </div>
                    <div class="post-details">
                        <a href="<?php the_permalink(); ?>"><h3 class="post-title"><?php the_title(); ?></h3></a>
                        <p class="last-modified"><?php _e('Last Modified', 'autopost'); ?>: <?php the_modified_time(get_option('date_format')); ?></p>
                        <img src="<?php echo($img_more_2_line);?>" class="menu-toggle" onclick="toggleMenu('<?php echo get_the_id(); ?>', '<?php echo the_permalink();?>')">
                        <div class="hidden-menu">
                            <button onclick="recreate_image(this)" class="button-item-menu" data-post-image-id="<?php echo get_the_id() ?>">Recreate Image</button>
                            <button onclick="recreate_image_mj(this)" class="button-item-menu" data-post-mj-id="<?php echo get_the_id() ?>">Recreate Image MJ</button>
                            <button onclick="reacreate_auto_link(this)" class="button-item-menu" data-post-autolink-id="<?php echo get_the_id() ?>">Recreate Auto Link</button>
                            <button onclick="duplicate_post(this)" class="button-item-menu" data-post-id="<?php echo get_the_id() ?>">Duplicate Post</button>
                            <button onclick="publish_post(this)" class="button-item-menu" data-post-id="<?php echo get_the_id() ?>">Publish Post</button>
                            <button onclick="delete_post(this)" class="button-item-menu" data-post-id="<?php echo get_the_id() ?>">Delete Post</button>
                            <input type="date" id="post-date-<?php echo get_the_id() ?>">
                            <button onclick="schedule_post(this, document.getElementById('post-date-<?php echo get_the_id() ?>'))" class="button-item-menu" data-post-id="<?php echo get_the_id() ?>">Schedule Post</button>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            _e('No post data', 'autopost');
            ?>
            <div class="card-alt" style="
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-left: auto;
    margin-right: auto;
    margin-top: 5%;
">
                <div>
                    <h1>Let your ideas shine</h1>
                    <div><b>There are no created articles yet</b><span>, hit the button bellow and star to create with AI</span></div>
                </div>
                <div>
                    <div onclick="openModal('post-modal');" class="button-span" style="
    display: flex;
    flex-direction: row;
">
                        <span><?php _e('Create new article', 'autopost'); ?> <img src="<?php echo($img_magic_fill) ?>" alt="" srcset=""></span>
                    </div>
                </div>
            </div>
            <?php
        endif;
        ?>
        <!-- Hidden menu outside the loop -->
        <div class="hidden-menu" id="hidden-menu-template" style="display: none;">
            <a href="#" class="view-post-button" >View Post</a>
        </div>

        <script>
            function toggleMenu(postId, permalink) {
                const hiddenMenu = document.getElementById('hidden-menu-template').cloneNode(true);
                hiddenMenu.id = `hidden-menu-${postId}`;
                hiddenMenu.style.display = 'flex';

                // Update data attributes with postId
                hiddenMenu.querySelector('[href]').href = permalink;

                document.body.appendChild(hiddenMenu);

                const menuToggle = document.querySelector(`[data-post-id="${postId}"] .menu-toggle`);
                const rect = menuToggle.getBoundingClientRect();

                hiddenMenu.style.position = 'absolute';
                hiddenMenu.style.top = `${rect.bottom}px`;
                hiddenMenu.style.left = `${rect.left}px`;

                document.addEventListener('click', function closeMenu(e) {
                    if (!hiddenMenu.contains(e.target) && e.target !== menuToggle) {
                        document.removeEventListener('click', closeMenu);
                        hiddenMenu.remove();
                    }
                });
            }
        </script>
    </div>

    <?php require_once(dirname(__FILE__).'/../templates/laguange_footer.php')?>


    <script>
        const filterButtons = document.querySelectorAll('.filter-button');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
                const currentUrl = new URL(window.location.href);
                
                if (category === 'onqueue') {
                    currentUrl.searchParams.set('category_name', category);
                    currentUrl.searchParams.delete('post_status');
                } else {
                    currentUrl.searchParams.set('post_status', category);
                    currentUrl.searchParams.delete('category_name');
                }
                
                window.location.href = currentUrl.toString();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
    // Get all menu toggle buttons
    // const menuToggleButtons = document.querySelectorAll('.menu-toggle');

    // // Add click event listeners to each menu toggle button
    // menuToggleButtons.forEach(function (button) {
    //     button.addEventListener('click', function () {
    //         // Find the hidden menu associated with this button
    //         const hiddenMenu = this.parentElement.querySelector('.hidden-menu');

    //         // Toggle the menu's visibility
    //         if (hiddenMenu.style.display === 'flex') {
    //             hiddenMenu.style.display = 'none';
    //         } else {
    //             hiddenMenu.style.display = 'flex';
    //         }
    //     });
    // });
});

    </script>




    <div id="post-modal" class="modal">
            <?php require_once(plugin_dir_path(__FILE__).'pages/create_post_page.php');?>
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


    //Funcionalidade de pesquisa interna
    document.addEventListener("DOMContentLoaded", function () {
        const searchBar = document.getElementById("search-bar");
        const postsContainer = document.getElementById("posts-container");

        searchBar.addEventListener("input", function () {
            const searchTerm = searchBar.value.toLowerCase();

            const postCards = postsContainer.querySelectorAll(".post-card");

            postCards.forEach(function (postCard) {
                const postTitle = postCard.querySelector(".post-title").textContent.toLowerCase();

                if (postTitle.includes(searchTerm)) {
                    postCard.style.display = "block"; // Show matching posts
                } else {
                    postCard.style.display = "none"; // Hide non-matching posts
                }
            });
        });
    });


    </script>

    <?php
    
            $path=get_site_url() . '/wp-content/plugins/auto_post_application/scripts/chatgpt-admin.js';



             wp_enqueue_script('chatgpt-admin', $path, array('jquery'), '1.0.0', true);

            $ajax_object = array(
                'ajax_nonce' => wp_create_nonce('chatgpt-ajax-nonce'),
                'ajax_url' => admin_url('admin-ajax.php')
            );

             wp_localize_script('chatgpt-admin', 'chatgpt_ajax_object', $ajax_object);
    ?>

</div>