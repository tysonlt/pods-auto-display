<?php        

/**
 * Plugin Name: Pods Auto-Display
 * Description: Automatically displays Pods custom fields below custom post content.
 * Version: 0.4.1
 * Author: Tyson Lloyd Thwaites
 * License: GPL2
 */

require_once(dirname(__FILE__).'/PodsAutoDisplay.php');
require_once(dirname(__FILE__).'/PodsAutoDisplaySettings.php');
require_once(dirname(__FILE__).'/PodsAutoDisplayDefaultFormatter.php');

//first, make sure Pods is installed
register_activation_hook( __FILE__, 'pods_auto_display_activate_plugin' );
function pods_auto_display_activate_plugin() {
	if (!function_exists('pods_api')) {
		trigger_error('The Pods Framework plugin must be active.', E_USER_ERROR);
	}
}

//instantiate plugin
$__pluginInstance_PodsAutoDisplay = new PodsAutoDisplay();
	
//add main filter.
add_filter(
	'the_content', 
	array($__pluginInstance_PodsAutoDisplay, 'handleFilter'), 
	$__pluginInstance_PodsAutoDisplay->getFilterPriority()
); 
	
//add some formatting filters
$__pluginInstance_PodsAutoDisplay_formatter = new PodsAutoDisplayDefaultFormatter();
add_filter('pods_auto_display_format_field_type_email', array($__pluginInstance_PodsAutoDisplay_formatter, 'formatEmail'), 1, 2);
add_filter('pods_auto_display_format_field_type_website', array($__pluginInstance_PodsAutoDisplay_formatter, 'formatWebsite'), 1, 2);
add_filter('pods_auto_display_format_field_type_file', array($__pluginInstance_PodsAutoDisplay_formatter, 'formatFile'), 1, 2);
add_filter('pods_auto_display_format_field_type_color', array($__pluginInstance_PodsAutoDisplay_formatter, 'formatColor'), 1, 2);
add_filter('pods_auto_display_format_field_type_pick', array($__pluginInstance_PodsAutoDisplay_formatter, 'formatPick'), 1, 2);

//if we're in admin, show the admin page
if (is_admin()) {
	require_once(dirname(__FILE__).'/PodsAutoDisplayAdmin.php');
	new PodsAutoDisplayAdmin();
}