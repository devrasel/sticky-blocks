<?php 
//******
// adding admin options..
// ******
 ?>

<div class="wrap">

<?php
// global values
    global $wpdb;
	$table_name = $wpdb->prefix . 'stickyblocks';
	$succStky = "";
	$failedStky = "";
	$delmsg = "";
	$delmsgfail = "";

// getting classes and ids as a text to DB 
	if( isset( $_POST['stkyinsert'])) {

       $stky_n = sanitize_text_field($_POST['stkyn']);
	   $stky_con = sanitize_text_field($_POST['stkycon']);
	   $stky_colleft = sanitize_text_field($_POST['stkycolleft']);
	   $stky_colright = sanitize_text_field($_POST['stkycolright']);
	   $stky_sec = sanitize_text_field($_POST['stkysec']);

	   if ($stky_n != '' && $stky_con != '' && $stky_colleft != '' && $stky_colright != '' && $stky_sec != '') :
	   $sql = $wpdb->insert(
						$table_name, 
					array(
						"stkyname" => $stky_n, 
						"stkycon" => $stky_con, 
						'stkycolleft' => $stky_colleft, 
						'stkycolright' => $stky_colright,
						"stkysec" => $stky_sec
					));
		
		if($sql == true){
			$succStky .= "<p>Succesfully Added!</p>";
		} else{
			echo "<script> alert('Error occured! Try again later'); </script>";
		}
	else:
		$failedStky .= "<div>Please fill up all required fields</div>";
	endif;
	}

	// delete actions
	$user = wp_get_current_user();
	$allowed_roles = array( 'administrator' );
	if (!empty($_POST['delstktid']) && array_intersect( $allowed_roles, $user->roles ) ) {
		$stk_id = sanitize_text_field($_POST['delstktid']);
		$sqldelAction = $wpdb->delete( $table_name, [ 'id' => $stk_id ], [ '%d' ] );

		if ($sqldelAction == true){
			$delmsg .= "<div>Item has been Deleted!</div>";
		} else{
			$delmsgfail .= "<div>Error!</div>";
		}
	}
?>

<h2>All Sticky Blocks</h2>
	<table class="manage-all-stky-list wp-list-table widefat fixed striped pages stkyItems">
    <thead>
    	<tr>
			<td class="column-date"> Block Name </td> 
			<td>Container Selector</td> 
			<td>Left Column</td>
			<td>Right Column</td>
			<td>Sticky Section</td>
			<td>Action</td>
		</tr>
    </thead>
	
	<?php 
		$results  = $wpdb->get_results( "SELECT * FROM $table_name" );
		if(!empty($results)) :
		foreach($results as $row) : ?>
		<tr>
			<td class="stkyname"><?php echo esc_html( $row->stkyname ); ?></td> 
			<td class="stkycon"><?php echo esc_html( $row->stkycon ); ?></td> 
			<td class="stkycolleft"><?php echo esc_html( $row->stkycolleft ); ?></td>
			<td class="stkycolright"><?php echo esc_html( $row->stkycolright ); ?></td>
			<td class="stkysec"><?php echo esc_html( $row->stkysec ); ?></td>
			<td> 
			<form method="POST">
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
    <p><input class="button button-primary" type="submit" name="stkyinsert" class="regular-text" value="Add and Save" /></p>
</form>

 
<br>
<div class="stky-plugin-details">
	<h3>Help & Documentations</h3>
	<p>If you like this plugin give us your <a target="_blank" href="https://www.speakbd.com/contact">comments </a>  <span style="padding: 0 10px;">|</span> You can request a custom <a target="_blank" href="https://www.speakbd.com/contact"> Support</a></p>
</div>
	<style>
		.wrap{
			position: reative;
		}
		#stkyr p {
		position: absolute;
		top: 10px;
		right: 10px;
		background-color: #5bcb975e;
		color: #000;
		padding: 14px;
		font-size: 14px;
		border: 1px solid #5bcb97;
	}
	#stkyr div, #stkyrfailed div {
		position: absolute;
		top: 10px;
		right: 10px;
		background-color: #ff000036;
		color: #000;
		padding: 14px;
		font-size: 14px;
		border: 1px solid red;
	}
	</style>
	<span style="display:none;" id="stkyr"><?php if(!empty($succStky)) {echo wp_kses_post($succStky); }else{ echo wp_kses_post($failedStky);} ?></span>
    <span style="display:none;" id="stkyrfailed"><?php if(!empty($delmsg)) {echo wp_kses_post($delmsg); }else{ echo wp_kses_post($delmsgfail);} ?></span>
</div> 

<script>
		
	jQuery(document).ready(function( $ ){

		$("#stkyr, #stkyrfailed").show();
		setTimeout(function() {
			$("#stkyr, #stkyrfailed").hide(300);
	},1000);

	});
	
</script>
