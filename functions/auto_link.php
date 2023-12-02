<?PHP

function custom_autolink_search_route_init() {
    register_rest_route('autolink/v1', '/search', array(
        'methods' => 'POST',
        'callback' => 'custom_autolink_search_callback',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'custom_autolink_search_route_init');

function custom_autolink_search_callback($request) {
    $search_string = $request->get_param('search');

    if (empty($search_string)) {
        return rest_ensure_response(array('error' => 'Search parameter is required'));
    }

    $matching_posts = append_related_posts_as_gutenberg_block($search_string);

    if (!empty($matching_posts)) {
        return rest_ensure_response($matching_posts);
    } else {
        return ""; //rest_ensure_response(array('message' => 'No matching posts found'));
    }
}

function search_posts_by_content($search_string) {
    // Define an array of excluded words.
    $excluded_words = array(
        'o', 'a', 'os', 'as', 'um', 'uma', 'uns', 'umas', 'de', 'do', 'da', 'dos', 'das', 'para', 'com', 'sem',
        'em', 'por', 'na', 'no', 'nas', 'nos', 'sobre', 'sob', 'entre', 'aqui', 'ali', 'além', 'quem', 'como',
        'onde', 'quando', 'qual', 'porque', 'porquê', 'porquê', 'até', 'mais', 'menos', 'muito', 'muitos',
        'muita', 'muitas', 'também', 'ainda', 'mesmo', 'outro', 'outra', 'outras', 'outros', 'cada', 'um', 'dois',
        'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove', 'dez'
    );
    
    // Split the search string into individual words.
    $search_words = explode(' ', $search_string);
    $search_words = array_map('strtolower', $search_words);
    // Remove excluded words from the search words.
    $search_words = array_diff($search_words, $excluded_words);
    
    // Initialize an empty array to store matching posts.
    $matching_posts = array();

    // WP_Query to search for posts containing each word in their content.
    foreach ($search_words as $word) {
        $args = array(
            's' => $word,  // Search word
            'post_type' => 'post',  // Change 'post' to the desired post type
            'post_status' => 'publish',
            'posts_per_page' => -1, // Retrieve all matching posts
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_title = sanitize_text_field(get_the_title());
                $post_title_stripped = wp_strip_all_tags(html_entity_decode($post_title, ENT_QUOTES, 'UTF-8'));
                $post_link = get_permalink();
                // Add the post title and link to the matching_posts array.
                if($post_title_stripped == $search_string)
                    break;
                $matching_posts[] = "<a href='{$post_link}'>{$post_title}</a>";
            }
        }
        // Restore the global post data.
        wp_reset_postdata();
    }

    return $matching_posts;
}


function append_related_posts_as_gutenberg_block($search_string, $text = "", $limit = 10)
{
    try {
        $matching_posts = search_posts_by_content($search_string);

        if (empty($matching_posts)) {
            return; //throw new Exception("No matching posts found.");
        }

        $text .= "<div class='wp-block-related-posts'>
                    <h2>Posts relacionados</h2>";
        // Use array_slice to limit the number of items
        if (count($matching_posts) > $limit) {
            // Use array_slice to limit the number of items
            $limited_posts = array_slice($matching_posts, 0, $limit);
        } else {
            // The array is already within the limit
            $limited_posts = $matching_posts;
        }

        foreach ($limited_posts as $post_link) {
            $text .= "$post_link<br>\n";
        }

        $text .= "</div>";

        return $text;
    } catch (Exception $e) {
        // Handle the exception (e.g., log the error, display a message, etc.)
        error_log("Error in append_related_posts_as_gutenberg_block: " . $e->getMessage());
        // Optionally, you can return a default message or do something else
        return "An error occurred: " . $e->getMessage();
    }
}



?>