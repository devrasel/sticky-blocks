<?php 
//******
// adding admin options..
// ******
 ?>

<div class="wrap">

<?php
    $error_message = get_transient( 'stky_admin_error_message' );
    if ( $error_message ) {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $error_message ) . '</p></div>';
        delete_transient( 'stky_admin_error_message' ); // Clear the message after display
    }

    $success_message = get_transient( 'stky_admin_success_message' );
    if ( $success_message ) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $success_message ) . '</p></div>';
        delete_transient( 'stky_admin_success_message' ); // Clear the message after display
    }
?>

<?php
// global values
    global $wpdb;
	$table_name = $wpdb->prefix . 'stickyblocks';
    $current_action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    $current_id = isset($_GET['id']) ? absint($_GET['id']) : 0;

    $stky_n = '';
    $stky_con = '';
    $stky_colleft = '';
    $stky_colright = '';
    $stky_sec = '';
    $display_on = 'entire_website';
    $specific_ids = '';
    $specific_urls = '';

    if ( 'edit' === $current_action && $current_id > 0 ) {
        $item = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $current_id ) );
        if ( $item ) {
            $stky_n = $item->stkyname;
            $stky_con = $item->stkycon;
            $stky_colleft = $item->stkycolleft;
            $stky_colright = $item->stkycolright;
            $stky_sec = $item->stkysec;
            $display_on = $item->display_on;
            $specific_ids = $item->specific_ids;
            $specific_urls = $item->specific_urls;
        } else {
            add_settings_error( 'stky_messages', 'stky_message', __( 'Item not found.', 'sticky-blocks' ), 'error' );
            $current_action = ''; // Reset action to show main list
        }
    }



// getting classes and ids as a text to DB
	if( isset( $_POST['stkyinsert'])) {
        if ( ! isset( $_POST['stky_add_block_nonce'] ) || ! wp_verify_nonce( $_POST['stky_add_block_nonce'], 'stky_add_block_action' ) ) {
            // Nonce verification failed, die or handle error
            wp_die( 'Security check failed!' );
        }

       $stky_n_post = sanitize_text_field($_POST['stkyn']);
	   $stky_con_post = sanitize_text_field($_POST['stkycon']);
	   $stky_colleft_post = sanitize_text_field($_POST['stkycolleft']);
	   $stky_colright_post = sanitize_text_field($_POST['stkycolright']);
	   $stky_sec_post = sanitize_text_field($_POST['stkysec']);
	      $display_on_post = sanitize_text_field($_POST['stky_display_on']);
	      $specific_ids_post = sanitize_text_field($_POST['stky_specific_ids']);
	      $specific_urls_post = sanitize_textarea_field($_POST['stky_specific_urls']);

	   if ($stky_n_post != '' && $stky_con_post != '' && $stky_colleft_post != '' && $stky_colright_post != '' && $stky_sec_post != '') :
	   $sql = $wpdb->insert(
	   		$table_name,
	   	array(
	   		"stkyname" => $stky_n_post,
	   		"stkycon" => $stky_con_post,
	   		'stkycolleft' => $stky_colleft_post,
	   		'stkycolright' => $stky_colright_post,
	   		"stkysec" => $stky_sec_post,
	                       "display_on" => $display_on_post,
	                       "specific_ids" => $specific_ids_post,
	                       "specific_urls" => $specific_urls_post
	   	));
	 
	 if($sql == true){
	  set_transient( 'stky_admin_success_message', __( 'Successfully Added!', 'sticky-blocks' ), 30 );
	   	       wp_redirect( admin_url( 'admin.php?page=stickyblocks' ) );
	   	       exit;
	 } else{
	   	       // Debugging: Output last query and error
	   	       set_transient( 'stky_admin_error_message', __( 'Error occurred! Please try again later.', 'sticky-blocks' ), 30 );
	   	       wp_redirect( admin_url( 'admin.php?page=stickyblocks' ) );
	   	       exit;
	 }
	else:
	 set_transient( 'stky_admin_error_message', __( 'Please fill up all required fields.', 'sticky-blocks' ), 30 );
	   	   wp_redirect( admin_url( 'admin.php?page=stickyblocks' ) );
	   	   exit;
	endif;
	}

    // Handle update action
    if ( isset( $_POST['stkyupdate'] ) ) {

        $stky_n_post = sanitize_text_field($_POST['stkyn']);
        $stky_con_post = sanitize_text_field($_POST['stkycon']);
        $stky_colleft_post = sanitize_text_field($_POST['stkycolleft']);
        $stky_colright_post = sanitize_text_field($_POST['stkycolright']);
        $stky_sec_post = sanitize_text_field($_POST['stkysec']);
        $item_id = absint($_POST['stkyid']);
        if ( ! isset( $_POST['stky_edit_block_nonce'] ) || ! wp_verify_nonce( $_POST['stky_edit_block_nonce'], 'stky_edit_block_action_' . $item_id ) ) {
            wp_die( 'Security check failed!' );
        }

        $display_on_post = sanitize_text_field($_POST['stky_display_on']);
        $specific_ids_post = sanitize_text_field($_POST['stky_specific_ids']);
        $specific_urls_post = sanitize_textarea_field($_POST['stky_specific_urls']);

        if ($stky_n_post != '' && $stky_con_post != '' && $stky_colleft_post != '' && $stky_colright_post != '' && $stky_sec_post != '' && $item_id > 0) :
            $updated = $wpdb->update(
                $table_name,
                array(
                    "stkyname" => $stky_n_post,
                    "stkycon" => $stky_con_post,
                    'stkycolleft' => $stky_colleft_post,
                    'stkycolright' => $stky_colright_post,
                    "stkysec" => $stky_sec_post,
                    "display_on" => $display_on_post,
                    "specific_ids" => $specific_ids_post,
                    "specific_urls" => $specific_urls_post
                ),
                array( 'id' => $item_id ),
                array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
                array( '%d' )
            );

            if ( $updated === false ) {
                set_transient( 'stky_admin_error_message', __( 'Error occurred! Update failed.', 'sticky-blocks' ), 30 );
                wp_redirect( admin_url( 'admin.php?page=stickyblocks&action=edit&id=' . $item_id ) ); // Redirect back to edit page
                exit;
            } elseif ( $updated === 0 ) {
                set_transient( 'stky_admin_success_message', __( 'No changes detected for update.', 'sticky-blocks' ), 30 );
                wp_redirect( admin_url( 'admin.php?page=stickyblocks&action=edit&id=' . $item_id ) );
                exit;
            } else {
                set_transient( 'stky_admin_success_message', __( 'Successfully Updated!', 'sticky-blocks' ), 30 );
                wp_redirect( admin_url( 'admin.php?page=stickyblocks&action=edit&id=' . $item_id ) );
                exit;
            }
        else:
            set_transient( 'stky_admin_error_message', __( 'Please fill up all required fields for update.', 'sticky-blocks' ), 30 );
            wp_redirect( admin_url( 'admin.php?page=stickyblocks&action=edit&id=' . $item_id ) );
            exit;
        endif;
    }


	// delete actions
	if (!empty($_POST['delstktid']) && current_user_can('manage_options') ) {
	       // Nonce verification for delete action
	       $stk_id = sanitize_text_field($_POST['delstktid']);
	       if ( ! isset( $_POST['stky_delete_block_nonce'] ) || ! wp_verify_nonce( $_POST['stky_delete_block_nonce'], 'stky_delete_block_action_' . $stk_id ) ) {
	           wp_die( 'Security check failed!' );
	       }

		$sqldelAction = $wpdb->delete( $table_name, [ 'id' => $stk_id ], [ '%d' ] );

		if ($sqldelAction == true){
			set_transient( 'stky_admin_success_message', __( 'Item has been Deleted!', 'sticky-blocks' ), 30 );
		          wp_redirect( admin_url( 'admin.php?page=stickyblocks' ) );
		          exit;
		} else{
			set_transient( 'stky_admin_error_message', __( 'Error deleting item!', 'sticky-blocks' ), 30 );
		          wp_redirect( admin_url( 'admin.php?page=stickyblocks' ) );
		          exit;
		}
	}
?>


<?php if ( 'edit' === $current_action && $current_id > 0 && $item ) : ?>
    <h2>Edit Sticky Block</h2>
    <form method="post">
        <input type="hidden" name="stkyid" value="<?php echo absint( $current_id ); ?>" />
        <table class="widefat fixed striped pages">
            <thead>
                <tr>
                    <td class="column"> Options - All fields are required </td>
                    <td> Edit Block Name, IDs or Classes </td>
                </tr>
            </thead>
            <tr>
                <td>Sticky Block Name</td>
                <td>
                    <input type="text" placeholder="Block Name" class="regular-text" name="stkyn" value="<?php echo esc_attr( $stky_n ); ?>" />
                </td>
            </tr>
            <tr>
                <td>Add Container selector (Class or ID ex: .container or #container)</td>
                <td>
                    <input type="text" placeholder="Container class or ID" class="regular-text" name="stkycon" value="<?php echo esc_attr( $stky_con ); ?>" />
                </td>
            </tr>
            <tr>
                <td>Left column selector (Class or ID ex: .container .column-left or #container #column-left)</td>
                <td><input type="text" placeholder="Column class or ID" class="regular-text" name="stkycolleft" value="<?php echo esc_attr( $stky_colleft ); ?>" />
                </td>
            </tr>
            <tr>
                <td>Right column selector (Class or ID ex: .container .column-right or #container #column-right)</td>
                <td><input type="text" placeholder="Column class or ID" class="regular-text" name="stkycolright" value="<?php echo esc_attr( $stky_colright ); ?>" />
                </td>
            </tr>
            <tr>
                <td>Sticky section selector (Class or ID ex: .stickyWrapper or #stickyWrapper)</td>
                <td><input type="text" placeholder="Sticky section class or ID" class="regular-text" name="stkysec" value="<?php echo esc_attr( $stky_sec ); ?>" />
                </td>
            </tr>
        </table>

        <h3>Display Options</h3>
        <table class="widefat fixed striped pages">
            <tr>
                <td>Display On</td>
                <td>
                    <select name="stky_display_on" id="stky_display_on">
                        <option value="entire_website" <?php selected( $display_on, 'entire_website' ); ?>>Entire Website</option>
                        <option value="all_pages" <?php selected( $display_on, 'all_pages' ); ?>>All Pages</option>
                        <option value="all_posts" <?php selected( $display_on, 'all_posts' ); ?>>All Posts</option>
                        <option value="specific_ids" <?php selected( $display_on, 'specific_ids' ); ?>>Specific Pages, Posts, or Items (by ID)</option>
                        <option value="all_products" <?php selected( $display_on, 'all_products' ); ?>>All WooCommerce Products</option>
                        <option value="other_urls" <?php selected( $display_on, 'other_urls' ); ?>>Other URLs (Full URLs)</option>
                    </select>
                </td>
            </tr>
            <tr class="stky_specific_ids_row">
                <td>Specific Page/Post IDs (comma separated)</td>
                <td>
                    <textarea name="stky_specific_ids" rows="3" cols="50" class="large-text code"><?php echo esc_textarea( $specific_ids ); ?></textarea>
                    <p class="description">Enter comma-separated Page or Post IDs where this sticky block should appear (e.g., 10, 25, 30).</p>
                </td>
            </tr>
            <tr class="stky_specific_urls_row">
                <td>Specific URLs (one per line)</td>
                <td>
                    <textarea name="stky_specific_urls" rows="5" cols="50" class="large-text code"><?php echo esc_textarea( $specific_urls ); ?></textarea>
                    <p class="description">Enter full URLs (one per line) where this sticky block should appear (e.g., https://example.com/my-page/).</p>
                </td>
            </tr>
        </table>
        <?php wp_nonce_field( 'stky_edit_block_action_' . $current_id, 'stky_edit_block_nonce' ); ?>
        <p>
            <input class="button button-primary" type="submit" name="stkyupdate" value="Update and Save" />
            <a href="<?php echo admin_url( 'admin.php?page=stickyblocks' ); ?>" class="button">Back to list</a>
        </p>
    </form>
<?php else : ?>
    <h2>All Sticky Blocks</h2>
	<table class="manage-all-stky-list wp-list-table widefat fixed striped pages stkyItems">
    <thead>
    	<tr>
			<td class="column-date"> Block Name </td>
			<td>Container Selector</td>
			<td>Left Column</td>
			<td>Right Column</td>
			<td>Sticky Section</td>
			<td>Display Option</td>
			         <td>Specific Rules</td>
			<td>Actions</td>
		</tr>
			 </thead>
	
	<?php
		$results  = $wpdb->get_results( "SELECT * FROM $table_name" );
		if(!empty($results)) :
		foreach($results as $row) :
			         $specific_rules_output = '';
			         if ( $row->display_on === 'specific_ids' ) {
			             $specific_rules_output = esc_html( $row->specific_ids );
			         } elseif ( $row->display_on === 'other_urls' ) {
			             $urls = array_filter( array_map( 'trim', explode( "\n", $row->specific_urls ) ) );
			             $last_parts = array_map( function( $url ) {
			                 $path = parse_url( $url, PHP_URL_PATH );
			                 if ( $path ) {
			                     return basename( $path );
			                 }
			                 return $url; // Fallback to full URL if path is not found
			             }, $urls );
			             $specific_rules_output = esc_html( implode( ', ', $last_parts ) );
			         }
			     ?>
		<tr>
			<td class="stkyname"><?php echo esc_html( $row->stkyname ); ?></td>
			<td class="stkycon"><?php echo esc_html( $row->stkycon ); ?></td>
			<td class="stkycolleft"><?php echo esc_html( $row->stkycolleft ); ?></td>
			<td class="stkycolright"><?php echo esc_html( $row->stkycolright ); ?></td>
			<td class="stkysec"><?php echo esc_html( $row->stkysec ); ?></td>
			<td><?php echo esc_html( str_replace( '_', ' ', ucfirst( $row->display_on ) ) ); ?></td>
			         <td><?php echo $specific_rules_output; ?></td>
			<td>
			             <a href="<?php echo admin_url( 'admin.php?page=stickyblocks&action=edit&id=' . absint( $row->id ) ); ?>" class="button">Edit</a>
			             <form method="POST" style="display: inline-block;">
			                 <?php wp_nonce_field( 'stky_delete_block_action_' . $row->id, 'stky_delete_block_nonce' ); ?>
			                 <input type="hidden" name="action" value="stky_delete_event">
			                 <input type="hidden" name="delstktid" value="<?php echo esc_html( $row->id ); ?>">
			                 <input type="submit" class="button" value="Delete" onclick="return confirm('Are you sure to delete this item?');"/>
			             </form>
			     </td>
		</tr>
		
	<?php endforeach; endif; ?>


    </table>


<h2>Add New Sticky Block</h2>
<form method="post">
	<table class="widefat fixed striped pages">
    <thead>
    	<tr>
			<td class="column"> Options - All fields are required </td>
			<td> Add Block Name, IDs or Classes </td>
		</tr>
    </thead>
     <tr>
		 <td>Sticky Block Name</td>
		 <td>
		  <input type="text" placeholder="Block Name" class="regular-text" name="stkyn" />
		 </td>
	</tr>
	<tr>
		<td>Add Container selector (Class or ID ex: .container or #container)</td>
		<td>
			<input type="text" placeholder="Container class or ID" class="regular-text" name="stkycon" />
		</td>
	</tr>
	<tr>
		<td>Left column selector (Class or ID ex: .container .column-left or #container #column-left)</td>
		   <td><input type="text" placeholder="Column class or ID" class="regular-text" name="stkycolleft" />
		</td>
	</tr>
	<tr>
		<td>Right column selector (Class or ID ex: .container .column-right or #container #column-right)</td>
		  <td><input type="text" placeholder="Column class or ID" class="regular-text" name="stkycolright" />
		</td>
	</tr>
		<tr>
			<td>Sticky section selector (Class or ID ex: .stickyWrapper or #stickyWrapper)</td>
			<td><input type="text" placeholder="Sticky section class or ID" class="regular-text" name="stkysec" />
    	</td>
		</tr>
    </table>

    <h3>Display Options</h3>
    <table class="widefat fixed striped pages">
        <tr>
            <td>Display On</td>
            <td>
                <select name="stky_display_on" id="stky_display_on_add">
                    <option value="entire_website">Entire Website</option>
                    <option value="all_pages">All Pages</option>
                    <option value="all_posts">All Posts</option>
                    <option value="specific_ids">Specific Pages, Posts, or Items (by ID)</option>
                    <option value="all_products">All WooCommerce Products</option>
                    <option value="other_urls">Other URLs (Full URLs)</option>
                </select>
            </td>
        </tr>
        <tr class="stky_specific_ids_row">
            <td>Specific Page/Post IDs (comma separated)</td>
            <td>
                <textarea name="stky_specific_ids" rows="3" cols="50" class="large-text code"></textarea>
                <p class="description">Enter comma-separated Page or Post IDs where this sticky block should appear (e.g., 10, 25, 30).</p>
            </td>
        </tr>
        <tr class="stky_specific_urls_row">
            <td>Specific URLs (one per line)</td>
            <td>
                <textarea name="stky_specific_urls" rows="5" cols="50" class="large-text code"></textarea>
                <p class="description">Enter full URLs (one per line) where this sticky block should appear (e.g., https://example.com/my-page/).</p>
            </td>
        </tr>
    </table>
    <?php wp_nonce_field( 'stky_add_block_action', 'stky_add_block_nonce' ); ?>
    <p><input class="button button-primary" type="submit" name="stkyinsert" class="regular-text" value="Add and Save" /></p>
</form>

 
<br>

<br>
<h3>Tutorial</h3>
<div class="stky-tutorial-video">
	   <iframe width="560" height="315" src="https://www.youtube.com/embed/id" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encryptedmedia; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
<br>
<div class="stky-plugin-details">
	<h3>Help & Documentations</h3>
	<p>If you like this plugin give us your 
        <a target="_blank" href="https://wordpress.org/plugins/sticky-blocks/">comments </a>  
        <span style="padding: 0 10px;">|</span> You can request a custom
         <a target="_blank" href="https://www.webextended.com/contact"> Support</a>
    </p>
</div>
<?php endif; ?>
</div>
