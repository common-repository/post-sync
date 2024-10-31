<?php
/**
 * Plugin Name: Post Sync
 * Plugin URI: http://wp-coder.net
 * Description: Post on multipile remote wordpress sites when you publish a new post.
 * Version: 1.1
 * Author: Darell Sun
 * Author URI:  http://wp-coder.net
 *
 * @package PostSync
 */

require_once('include/RemoteHandler.php');
/* Set up the plugin. */
add_action('plugins_loaded', 'post_sync_setup');  
/* Create wp_sync table when admin active this plugin*/
register_activation_hook(__FILE__,'post_sync_create_table');

/*
 *Create wp_sync database table for this plugin
*/
function post_sync_create_table(){

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   
  global $wpdb;
  $sites_table = $wpdb->prefix . 'sync';
  $log_table = $wpdb->prefix . 'sync_log';
	
  if(!get_option('post_sync_version') && $wpdb->get_var( "SHOW TABLES LIKE '$sites_table'" ) == $sites_table){
	 $sql = "CREATE TABLE " . $sites_table . " (
    id   bigint(20) auto_increment primary key,
    url  varchar(100),
    name varchar(32),
    username varchar(32),
    password varchar(32),
    status varchar(10) default 'enable',
    processed text,
    type varchar(32)
    );";
	 dbDelta($sql);   
  }	
   
   $new_version = '1.1';
   if(get_option('post_sync_version') != $new_version){
	  update_option('post_sync_version', '1.1');   
   }  
  	 
  if( $wpdb->get_var( "SHOW TABLES LIKE '$sites_table'" ) != $sites_table ){
	$sql = "CREATE TABLE " . $sites_table . " (
    id   bigint(20) auto_increment primary key,
    url  varchar(100),
    name varchar(32),
    username varchar(32),
    password varchar(32),
    status varchar(10) default 'enable',
    processed text,
    type varchar(32)
    );";
        
    dbDelta($sql); 
    $h = fopen(dirname(__FILE__).'/log.txt', 'a'); fwrite($h, $sql); fclose($h);
  } 
  
  if( $wpdb->get_var( "SHOW TABLES LIKE '$log_table'" ) != $log_table ){
	$sql = "CREATE TABLE " . $log_table . "(
    id bigint(20) auto_increment primary key,
    name varchar(32),
    message varchar(100),
    time varchar(32)
    );";  
    
    dbDelta($sql);  
    $h = fopen(dirname(__FILE__).'/log.txt', 'a'); fwrite($h, $sql); fclose($h);
  }
  
	 
  

}

/* 
 * Set up the post sync plugin and load files at appropriate time. 
*/
function post_sync_setup(){
   /* Set constant path for the plugin directory */
   define('POST_SYNC_DIR', plugin_dir_path(__FILE__));
   define('POST_SYNC_ADMIN', POST_SYNC_DIR.'/admin/');

   /* Set constant path for the plugin url */
   define('POST_SYNC_URL', plugin_dir_url(__FILE__));
   define('POST_SYNC_CSS', POST_SYNC_URL.'css/');
   define('POST_SYNC_JS', POST_SYNC_URL.'js/');

   if(is_admin())
      require_once(POST_SYNC_ADMIN.'admin.php');

   /*Print style */
   add_action('wp_print_styles', 'post_sync_style');
 
   /* print script */
   add_action('wp_print_scripts', 'post_sync_script');

   /* post action */
   add_action('publish_post', 'post_sync');
   do_action('post_sync_loaded');
}

function post_sync_style(){
  
}
function post_sync_script(){
 
}

function post_sync($postID){
  $post_data = get_post($postID);
  $title = $post_data->post_title;
  $content = $post_data->post_content;
  
  // current post categries
  $postCategories = array();
  if (get_the_category($postID)) {
	 	$categories = get_the_category($postId);
		foreach($categories as $category) {
			$postCategories[] = $category->cat_name;
		}
  }	
  /* new class for handling remote post request*/
  $remote = new RemoteHandler();
  global $wpdb;
  $sites = $wpdb->get_results("SELECT * FROM wp_sync"); 
  
  if(!empty($sites)){
	foreach($sites as $site){
	   if($site->status == 'enable'){
		  //check weather this postId has been post or not 
		  
		  $processed = unserialize($site->processed);
		  //var_dump($postID);
		  if(in_array($postID, $processed)){
			continue;  
		  } 
		  		  
		  switch ($site->type) {  
			  case 'wp':
			        $result = $remote->newWP($title, $content, $site->url, $site->username, $site->password, '');
			  break;
			  case 'twitter':
			        $result = $remote->newTwitter($site->username, $site->password, $title);
			  break;  
		  }		   
		  
		  //update the processed post id on database
		  $processed[] = $postID;
		  //var_dump($processed);
		  $processed_str = serialize($processed);
		  $wpdb->update( $wpdb->prefix.'sync', array('processed' => $processed_str), array('id' => $site->id));     
		  
		  
		  //log
		  //var_dump($result);
		  $current_time = date('y-m-d H:i:s');
		  //insert a record on log table
	      $wpdb->insert($wpdb->prefix.'sync_log', array('name' => $site->name, 'message' => $result, 'time' => $current_time));   
		  		  
	      
	   }else{
		  continue;   
	   }	    	
	}  
  } 
  	
}
?>
