<?php
/**
 * @link              https://www.webextended.com/contact/
 * @since             1.0.0
 * @package           Sticky_Blocks
 *
 *
* @wordpress-plugin
 * Plugin Name:       Sticky Sidebar for Ads and Blocks
 * Plugin URI:        https://www.webextended.com/contact/
 * Description:       Easily make multiple block sticky within a single page or post even in custom post.
 * Version:           1.0.5
 * Requires at least: 5.6
 * Author:            Rasel Ahmed
 * Author URI:        https://www.webextended.com/contact/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sticky-blocks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

  include dirname(__FILE__) . '/functions.php';  
  
  function stky_blocks_scripts(){ 
	    wp_enqueue_style('stky_styles', plugin_dir_url(__FILE__).'css/style.css', array(), '1.0.0');
	    wp_enqueue_script('stky_script', plugin_dir_url(__FILE__).'inc/stky_scripts.js', array(), '1.0.0', true);
}
 add_action('wp_enqueue_scripts', 'stky_blocks_scripts', 5);
  
// register activation hooks
register_activation_hook( __FILE__, 'stky_install' );

// settings
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'stky_block_page_settings_link');
function stky_block_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( '/admin.php?page=stickyblocks' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}