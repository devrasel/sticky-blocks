<?php
/**
 * @link              https://profiles.wordpress.org/wprasel/
 * @since             1.0.0
 * @package           Sticky_Blocks
 *
 *
* @wordpress-plugin
 * Plugin Name:       Sticky Blocks
 * Plugin URI:        https://speakbd.com
 * Description:       Easily make multiple block sticky within a single page or post even in custom post.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Author:            Rasel Ahmed
 * Author URI:        https://profiles.wordpress.org/wprasel/
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
 add_action('wp_enqueue_scripts', 'stky_blocks_scripts'); 
  
// register activation hooks
register_activation_hook( __FILE__, 'stky_install' );
