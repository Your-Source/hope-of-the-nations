<?php
/*
Plugin Name: Hope of the Nations
Plugin URI: http://hopeofthenations.nl
Description: Hope of the Nations show the childeren and it is possible to sponsor a child.
Version: 1.0
Author: Your Source
Author URI: http://your-source.nl
*/

// Add hook actions.
add_action('the_posts', 'hotn_page');
add_action('wp_head', 'hotn_page_headers');

/**
 * Page callback for show the children of Hope of the Nations.
 * @param  array $posts All current posts on this path.
 * @return array $posts Post for the page with children.
 */
function hotn_page($posts) {
  global $wp_query;

  if(is_hotn_page()) {
    $library_path = __DIR__ . '/library/hotn';
    include_once $library_path . '/hotnConfig.php';
    include_once $library_path . '/lib/hotn.php';

    $posts[0]->post_title = 'Hope of the Nations';
    $posts[0]->post_content = hotn::load();

  }

  return $posts;
}

/**
 * Add javascript and css files for Hope of the Nations.
 */
function hotn_page_headers() {
  if(is_hotn_page()) {
    // Add javascript file for Hope of the Nations.
    wp_enqueue_script(
      'hotn_script',
      plugins_url('/library/hotn/js/hotn.js', __FILE__),
      array('jquery')
    );

    // Add stylesheet for base theming of Hope of the Nations.
    wp_register_style(
      'hotn-style',
      plugins_url('/library/hotn/css/hotn-style.css', __FILE__)
    );
    wp_enqueue_style('hotn-style');
  }
}

/**
 * Function to check if the current page is hopeofthenations.
 * @return boolean Return TRUE is current page is hopeofthenations.
 */
function is_hotn_page() {
  if($_GET['p'] === 'hopeofthenations'
    || $_SERVER['REQUEST_URI'] === '/hopeofthenations/') {

    return TRUE;
  }
  return FALSE;
}
