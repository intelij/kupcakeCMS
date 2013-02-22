<?php
/*
Plugin Name: User Registration
Description: Members CRUD
Plugin URI: www.fnkdesigns.co.uk
Author: Khululekani Mkhonza
Author URI: www.fnkdesigns.co.uk
*/


global  $wpdb;

define('USER_INFO_TABLE',$wpdb->prefix.'user_info');

add_action('admin_menu', 'Crud_menu');

function Crud_menu()
{
  add_menu_page('REGISTERED USER','REGISTERED USER','manage_options', 'CRUD_APPLICATION','Action');	
}

function Create_Table()
{
	global $wpdb;
	$table_name = USER_INFO_TABLE;
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
	{
		$sql = "CREATE TABLE " . $table_name . " (
																							id INT(11) NOT NULL AUTO_INCREMENT,
																							user_name TEXT,
																							user_email VARCHAR(100),
																							PRIMARY KEY  id (id)
																							);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}


function Display_results()
{
	Create_Table();
	global  $wpdb;
	$res = $wpdb->get_results( 'SELECT * FROM ' . USER_INFO_TABLE . ' ORDER BY ' . USER_INFO_TABLE . '.user_name ASC');
	if(!empty($res))
	{

		?>
		<table class="widefat page fixed" width="100%" cellpadding="3" 
		cellspacing="3">
		<thead>
			<th><?php _e('ID','CRUD_APPLICATION')?></th>
			<th><?php  _e('User Name','CRUD_APPLICATION')?></th>
			<th><?php   _e('Email','CRUD_APPLICATION')?></th>
			<th><?php  _e('Edit','CRUD_APPLICATION')?></th>
			<th><?php _e('Delete','CRUD_APPLICATION')?></th>
		</thead>
		<?php
		$class = '';
		foreach($res as $res)
		{
			//make the rows look nice by alternating the colors of the 
			//row.. Prebuilt feature
			$class = ($class == 'alternate') ? '' : 'alternate';
			?>
			<tr class="<?php echo $class; ?>">
				<th scope="row"><?php echo $res->id;?></th>
				<td><?php echo $res->user_name; ?></td>
				<td><?php echo $res->user_email ?></td>
				<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?page=CRUD_APPLICATION&amp;action=delete&amp;id=<?php echo $res->id;?>" class="delete" onclick="return confirm('<?php _e('Are you sure you want to delete this quote?','CRUD_APPLICATION'); ?>')">	<?php echo __('Delete','CRUD_APPLICATION'); ?></a></td>
				<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?page=CRUD_APPLICATION&amp;action=edit&amp;id=<?php echo $res->id;?>" class="delete""><?php echo __('Edit','CRUD_APPLICATION'); ?></a></td>
			</tr>
			<?php
		}
	}
}
	
	
function Add_Action1()
{
	?>
		<div class="wrap">
			<form class="wrap" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=CRUD_APPLICATION">
				<div id="linkadvanceddiv" class="postbox">
					<div style="float: left; width: 100%; clear: both;" class="inside">
						<table cellpadding="5" cellspacing="5">
							<tr rowspan=2><td><h1>CRUD APPLICATION</h1></td></tr>
							<tr><td><?php _e('User Name', 'CRUD_APPLICATION'); ?></td><td><input type="text" name="username" class="input" size="40" maxlength="200" value=""/></td></tr>
							<tr><td><?php _e('User Email','CRUD_APPLICATION');?></td><td><input type="text" name="useremail" class="input" size="40" maxlength="200" value=""/></td></tr>
							<tr><td><?php _e('Blog URL', 'CRUD_APPLICATION'); ?></td><td><input type="text" name="blogurl" class="input" size="40" maxlength="200" value=""/></td></tr>
							<tr><td><?php _e('Terms','CRUD_APPLICATION');?></td><td><input type="checkbox" name="terms" class="input_" size="40" maxlength="20" value=""/></td></tr>
							<tr><td><input type="submit" name="save" class="button bold" value="<?php _e('Save', 'CRUD_APPLICATION'); ?> &raquo;" /></td></tr>
						</table>
					</div>
				</div>
			</form>
		</div>
	<?php
}


function Edit_Action()
{
	global $wpdb;
	$quotes = $wpdb->get_results( 'SELECT * FROM ' . USER_INFO_TABLE. ' where '.USER_INFO_TABLE.'.id= '.$_GET['id']);

	?>
		<div class="wrap">
			<form class="wrap" method="post" action="<?php echo the_permalink(); ?>">
				<div id="linkadvanceddiv" class="postbox">
					<div style="float: left; width: 100%; clear: both;" class="inside">
						<table cellpadding="5" cellspacing="5">
							<tr rowspan=2><td><h1>CRUD APPLICATION</h1></td></tr>
							<tr><td><?php _e('User Name', 'CRUD_APPLICATION'); ?></td><td><input type="text" name="username" class="input" size="40"	maxlength="200" value="<?php echo $quotes[0]->user_name; ?>"/></td></tr>
							<tr><td><?php _e('User Email','CRUD_APPLICATION');?></td><td><input type="text" name="useremail" class="input" size="40" maxlength="200" value="<?php echo $quotes[0]->user_email; ?>"/></td></tr>
							<tr><td><input type="submit" name="save" class="button bold" value="<?php _e('Update', 'CRUD_APPLICATION'); ?> &raquo;" /></td></tr>
						</table>
					</div>
				</div>
			</form>
		</div>
	<?php	
}


function Action()
{
	global $wpdb;

	$action     = !empty($_REQUEST['action']) ? $_REQUEST['action'] : 'add';
	if($action=='add')
	{
		Add_Action1();

		$username = $_REQUEST['username'];
		$useremail = $_REQUEST['useremail'];
		$blogurl = $_REQUEST['blogurl'];

		if($username!="" && $useremail!="")
		{
			$sql = "INSERT INTO " . USER_INFO_TABLE . " (user_name,user_email) VALUES ('$username','$useremail');";
			//run the query.
			$wpdb->get_results($sql);
		}

		//Display_results();
		Display_terms();
	}
	
	if($action=='edit')
	{
		Edit_Action();

		$username = $_REQUEST['username'];
		$useremail = $_REQUEST['useremail'];

		if($username!="" && $useremail!="")
		{
			$sql = "UPDATE " . USER_INFO_TABLE . " SET user_name = ".'"'.$username.'"'.",user_email = ".'"'.$useremail.'"'." where id = ".$_GET['id'];
			//run the query.
			$wpdb->get_results($sql);
		}


		Display_results();
	}

	if($action == 'delete')
	{
		$id = $_GET['id'];
		//do we have a quote id?
		if(!empty($id))
		{
			//the query
			$sql = "DELETE FROM " . USER_INFO_TABLE . 
			" WHERE id='" . mysql_real_escape_string($id) . "'";
			//run the query
			$wpdb->get_results($sql);
			//check that it worked... The query
			//run it

			//did we get anything back? If so it didn't delete
		}
		?>
		<div class="updated">
		<?php _e('Deleted successfully','rndmqmker'); ?></?></div>
		<?php
		Add_Action1();
		Display_results();
	}


}

function Display_terms()
{
	Create_Table();
	global  $wpdb;
	$res = $wpdb->get_results( 'SELECT * FROM ' . USER_INFO_TABLE . ' ORDER BY ' . USER_INFO_TABLE . '.user_name ASC');

	print "<h1>I am here...</h1>";
	print_r($_REQUEST);
		
	?>
	<!DOCTYPE html>
	<html>
	  <head>
	    <title></title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <!-- Bootstrap -->
	    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
	    <link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen">
    
	  </head>
	  <body>
	<div class="container">
		<?php

			print_r($_REQUEST); 
		
}
