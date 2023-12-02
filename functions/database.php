<?php
function activate_my_database_plugin() {
    create_dashboard_table();
    create_autopost_config();
    create_autopost_post_user();
}


function create_autopost_config(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'autopost_config';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        words_per_article INT(50) NOT NULL,
        article_type VARCHAR(255) NOT NULL,
        writen_tone VARCHAR(255) NOT NULL,
        text_style VARCHAR(255) NOT NULL,
        image_style  VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);



}


function create_dashboard_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'chat_gpt_dashboard';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        completion_tokens INT(11) NOT NULL,
        prompt_tokens INT(11) NOT NULL,
        total_tokens INT(11) NOT NULL,
        model VARCHAR(255) NOT NULL,
        created DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function create_autopost_post_user(){
    global $wpdb;
    $table_name=$wpdb->prefix.'autopost_plugin_users';
    $charset_collate = $wpdb->get_charset_collate();

    $sql="CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        author_name VARCHAR(255) NOT NULL,
        writen_articles INT(59) NOT NULL,
        active BOOLEAN NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
