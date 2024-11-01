<?php
/*
Plugin Name: WP YouTube Embed Plugin
Plugin URI: http://www.starkdigital.net/wp-youtube-embed-plugin
Description: Lets you to search youtube/vimeo video and insert into post/page.
Version: 1.0.2
Author: Stark Sajid And Sanjay
*/
function openssl_enable_notice(){
    echo '<div class="error"><p>It seem that php_openssl is disabled on your server. Please contact your server administrator to install / enable php_openssl.</p></div>'; 
}
if (!extension_loaded('openssl')) {
    add_action('admin_notices', 'openssl_enable_notice');
}

function curl_enable_notice(){
    echo '<div class="error"><p>It seem that cURL is disabled on your server. Please contact your server administrator to install / enable cURL.</p></div>';
}
if(!function_exists('curl_init')) {
    add_action('admin_notices', 'curl_enable_notice');
}

define( 'YTP_PLUGIN_URL', WP_PLUGIN_URL . '/wp-embed-youtube');
require_once( dirname(__FILE__)."/wey_ajax_functions.php" );
//require_once( dirname(__FILE__)."/lib/api_youtube.php" );

#---- actions ---#
add_action('admin_init','wey_mce_addbuttons'); //add tinymce button
add_action('wp_ajax_show_diaglogbox', '_fnWeyShowDiaglogContent'); //dialog box contnt
add_action('wp_ajax_search_video_by_ajax', '_fnWeySearchVideoByAjaxCallBack'); //search Video Ajax
add_action('wp_ajax_search_vimeo_video_by_ajax', '_fnWeySearchVimeoVideoByAjaxCallBack'); //search Vimeo Video Ajax


function wey_mce_addbuttons() {
  	add_filter("mce_external_plugins", "wey_add_tinymce_plugin");
  	add_filter('mce_buttons', 'wey_register_mce_button');
}

function wey_register_mce_button($buttons) {
  	array_push($buttons, "separator", "wey");
  	return $buttons;
}

function wey_add_tinymce_plugin($plugin_array) {
  	$plugin_array['wey'] = plugin_dir_url(__FILE__).'/js/editor_plugin.js';
   	return $plugin_array;
}