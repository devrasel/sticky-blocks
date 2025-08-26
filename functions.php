<?php 
// adding admin menus and hooks
// *****

function sticky_block_admin_menu() {
    add_menu_page(
        __( 'Sticky Blocks', 'wpstickyblocks' ),
        'Sticky Blocks',
        'manage_options',
		'stickyblocks',
        function(){ include 'backend/admin-sticky.php'; },
		'dashicons-sticky',
        20
    );

    add_submenu_page(
        'stickyblocks',
        __('Custom CSS', 'sticky-blocks'),
        __('Custom CSS', 'sticky-blocks'),
        'manage_options',
        'stickyblocks-custom-css',
        function(){ include 'backend/admin-custom-css.php'; }
    );
}
add_action( 'admin_menu', 'sticky_block_admin_menu' );

function stky_admin_scripts() {
    // Enqueue admin script
    wp_enqueue_script( 'stky_admin_script', plugin_dir_url(__FILE__) . 'inc/stky_admin_scripts.js', array('jquery'), '1.0.0', true );

    // Add inline style for custom CSS block
    $custom_css_style = '
        .stky-custom-css-block textarea {
            width: 100%;
            min-height: 200px;
            font-family: monospace;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid #ccc;
            padding: 10px;
            box-sizing: border-box;
        }
    ';
    wp_add_inline_style( 'wp-admin', $custom_css_style ); // 'wp-admin' is a handle for admin CSS

}
add_action( 'admin_enqueue_scripts', 'stky_admin_scripts' );


// adding class to the scripts
add_action('wp_footer','sticky_block_load_js');

// Enqueue custom CSS
function stky_custom_css_output() {
    $custom_css = get_option( 'stky_custom_css', '' );
    if ( ! empty( $custom_css ) ) {
        wp_add_inline_style( 'stky_styles', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'stky_custom_css_output', 15 );

function sticky_block_load_js(){

	global $wpdb, $wp;
	$table_stky = $wpdb->prefix . 'stickyblocks';
	$results  = $wpdb->get_results( "SELECT * FROM $table_stky" );

	if(!empty($results)) :
	       $script_data = array();
	       foreach($results as $row) {
	           $script_data[] = array(
	               'stkycon' => esc_html($row->stkycon),
	               'stkycolleft' => esc_html($row->stkycolleft),
	               'stkycolright' => esc_html($row->stkycolright),
	               'stkysec' => esc_html($row->stkysec),
                   'display_on' => esc_html($row->display_on),
                   'specific_ids' => esc_html($row->specific_ids),
                   'specific_urls' => esc_html($row->specific_urls),
	           );
	       }
	       wp_localize_script( 'stky_script', 'stky_blocks_data', $script_data );

           // Localize current page/post data
           $current_page_data = array(
               'is_front_page' => is_front_page(),
               'is_home' => is_home(),
               'is_page' => is_page(),
               'is_single' => is_single(),
               'is_singular' => is_singular(),
               'post_type' => get_post_type(),
               'current_id' => get_the_ID(),
               'current_url' => home_url( add_query_arg( array(), $wp->request ) )
           );
           wp_localize_script( 'stky_script', 'stky_current_page_data', $current_page_data );

	endif;
}

/// Storing data 
global $stky_db_version;
$stky_db_version = '1.0.0';

function stky_install() {
	global $wpdb;
	global $stky_db_version;

	$table_name = $wpdb->prefix . 'stickyblocks';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		stkyname tinytext NOT NULL,
		stkycon varchar(255) NOT NULL,
		stkycolleft varchar(255) NOT NULL,
		stkycolright varchar(255) NOT NULL,
		stkysec varchar(255) NOT NULL,
		display_on varchar(50) DEFAULT 'entire_website' NOT NULL,
		specific_ids text DEFAULT '' NOT NULL,
		specific_urls text DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

    if ( get_option( 'stky_db_version' ) !== $stky_db_version ) {
        dbDelta( $sql );
        update_option( 'stky_db_version', $stky_db_version );
    }
}



