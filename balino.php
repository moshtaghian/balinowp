<?php
/**
 * Plugin Name: Balino Plugin
 * Plugin URI: http://balino.ir
 * Description: balino plugin
 * Version: 1.0
 * Author: Mousa mohtaghian
 * Author URI: http://mousa.moshtaghian.com
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}



define("HASH_ID_SALT", "@balino_hajmousa_taher#");
define("HASH_ID_ALPHABET", "abcdefghijklmnopqrstuvwxyz1234567890");

define("BALINO_SMS_URL", "http://www.smstarin.ir/webservice/smsService.php?wsdl");
define("BALINO_SMS_USER", "taebi");
define("BALINO_SMS_PASS", "7125344");
define("BALINO_SMS_NUMBER", "10009140033114");

function getHashID()
{
  require_once(plugin_dir_path(__FILE__) . 'includes/Hashids/HashGenerator.php');
  require_once(plugin_dir_path(__FILE__) . 'includes/Hashids/Hashids.php');
  return new Hashids\Hashids(HASH_ID_SALT, 40, HASH_ID_ALPHABET);
}

add_action('rest_api_init', function () {
  require_once(plugin_dir_path(__FILE__) . 'rest/AuthRestController.php');

  $hashids = getHashID();

  $namespace = "balino/v1";
  $auth = new AuthRestController($hashids);
  $auth->register_routes($namespace);


  // register_rest_route('balino/v1', 'latest-posts/(?P<category_id>\d+)', array(
  //   'methods'  => 'GET',
  //   'callback' => 'get_latest_posts_by_category'
  // ));
});

// function get_latest_posts_by_category($request)
// {

//   $args = array(
//     'category' => $request['category_id']
//   );

//   $posts = get_posts($args);
//   if (empty($posts)) {
//     return new WP_Error('empty_category', 'there is no post in this category', array('status' => 404));
//   }

//   $response = new WP_REST_Response($posts);
//   $response->set_status(200);

//   return $response;
// }

add_action('admin_menu', 'balino_menu_manager');
// action function for above hook
function balino_menu_manager()
{
  add_menu_page(__('Balino', 'balino-plugin'), __('Balino', 'balino-plugin'), 'manage_options', 'balino-home-handle', 'balino_home_page');
  add_submenu_page('balino-home-handle', __('Balino Services', 'balino-plugin'), __('Balino Services', 'balino-plugin'), 'manage_options', 'balino-services', 'create_services_page');
}


// mt_toplevel_page() displays the page content for the custom Test Toplevel menu
function balino_home_page()
{
  echo "<h2>salama</h2>";
}

// mt_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
function create_services_page()
{
  require_once(plugin_dir_path(__FILE__) . 'controllers/BalinoServicesController.php');
  $servicesTable = new BalinoServicesController();
  $servicesTable->prepare_items();
  $servicesTable->display();
}

// mt_sublevel_page2() displays the page content for the second submenu
// of the custom Test Toplevel menu
function mt_sublevel_page2()
{
  echo "<h2>" . __('Test Sublevel2', 'balino-plugin') . "</h2>";
}
