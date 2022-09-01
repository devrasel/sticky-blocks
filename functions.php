<?php 
// adding admin menus and hooks
// *****

function sticky_block_admin_menu() {
    add_menu_page(
        __( 'Sticky Blocks', 'wpstickyblocks' ),
        'Sticky Blocks',
        'manage_options',
		'stickyblocks',
        'sticky_block_admin_menu_list',
		'dashicons-sticky',
        20
    );
}
add_action( 'admin_menu', 'sticky_block_admin_menu' );

function sticky_block_admin_menu_list(){
	include 'backend/admin-sticky.php'; 
}

// adding class to the scripts
add_action('wp_footer','sticky_block_load_js');

function sticky_block_load_js(){

	global $wpdb;
	$table_stky = $wpdb->prefix . 'stickyblocks';
	$results  = $wpdb->get_results( "SELECT * FROM $table_stky" );

	if(!empty($results)) :
		?> <script> jQuery(document).ready(function($){ 
		<?php	foreach($results as $row) {	?>   

			if($('<?php echo esc_html($row->stkycon); ?>') !== '') {
				$('<?php echo esc_html($row->stkycon); ?>').addClass('stickyBlockWrapper'); 
			} else {console.log('No such class or ids');}

			if($('<?php echo esc_html($row->stkycolleft); ?>') !== '') {
				$('<?php echo esc_html($row->stkycolleft); ?>').addClass('colLeft'); 
			}
			if($('<?php echo esc_html($row->stkycolright); ?>') !== '') {
				$('<?php echo esc_html($row->stkycolright); ?>').addClass('colRight'); 
			}
			if($('<?php echo esc_html($row->stkysec); ?>') !== '') {
				$('<?php echo esc_html($row->stkysec); ?>').addClass('sticky'); 
			}

   <?php  }  ?>
		});
		
	</script>
	<?php
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
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	add_option( 'stky_db_version', $stky_db_version );
}



