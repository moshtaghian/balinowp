<?php
/**
 * Plugin Name: Balino Plugin
 * Plugin URI: http://balino.ir
 * Description: balino plugin
 * Version: 1.0
 * Author: Mousa mohtaghian
 * Author URI: http://mousa.moshtaghian.com
 */
 
add_action('rest_api_init', function () {
  register_rest_route( 'balino/v1', 'latest-posts/(?P<category_id>\d+)',array(
                'methods'  => 'GET',
                'callback' => 'get_latest_posts_by_category'
      ));
});

function get_latest_posts_by_category($request) {

    $args = array(
            'category' => $request['category_id']
    );

    $posts = get_posts($args);
    if (empty($posts)) {
    return new WP_Error( 'empty_category', 'there is no post in this category', array('status' => 404) );

    }

    $response = new WP_REST_Response($posts);
    $response->set_status(200);

    return $response;
}