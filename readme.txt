=== Pods Auto-Display ===
Contributors: tysonlt
Tags: pods, filter, custom post types
Requires at least: 3.0.1
Tested up to: 3.7.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically displays Pods custom fields below custom post content.

== Description ==
This simple plugin displays a Pod's custom fields at the bottom of a custom post type. It works by filtering the_content, so it should work with any theme or template.

Needless to say, it requires the Pods Framework plugin to be installed. 

You have the option to enable the plugin for certain pods, and to specify which fields should be automatically displayed. The plugin will look for a Pods template with the name (pod-name)_detail. This name is editable with a filter: pods_auto_display_template_name_(post_type). If that template is found, it will be displayed under the post content. 

If the template is not found, the plugin will create an unordered list under the post content. Each list item has the field label in bold, followed by the field value. Empty values will not be displayed. All of the output is filtered, so you could change it to print a table if you wanted to.

== Installation ==
1. Standard WP plugin install. Either download it to your plugins directory or install via Wordpress.

== Changelog ==

= 0.4.1 =
* Fixed bug #2049: some pod fields weren't displayed in settings page

= 0.4 =
* Lots of changes to make plugin ready for general use
* Add a number of filters for user to control output
* Admin page allows user to control which fields will be displayed
* Admin page allows user to set template name for each pod
* Now displays all types of fields correctly
* Automatically displays website fields and Page relationships as links
* Added some simple anti-spam script for displaying email addresses

= 0.3 =
* Ghastly error caused by typo - sorry folks

= 0.2 =
* Cleaned up code
* Added filter: pods_auto_display_template_name_(post_type)
* Added admin screen to enable for specific pods.

= 0.1 =
* Initial release.
* Works, but cannot be disabled per pod. It's on or off.
