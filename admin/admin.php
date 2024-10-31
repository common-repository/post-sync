<?php
/* Admin functions to set and save settings of the post sync
 * @package PostSync
*/

require_once('pages.php');
/* Initialize the theme admin functions */
add_action('init', 'post_sync_admin_init');

function post_sync_admin_init(){
    add_action('admin_menu', 'post_sync_settings_init');
    add_action('wp_ajax_post_sync_crud','post_sync_save_settings');
    add_action('admin_init', 'post_sync_admin_style');
    add_action('admin_init', 'post_sync_admin_script');
}

function post_sync_settings_init(){
   global $post_sync; 
   $post_sync->settings_page = add_menu_page( 'PostSync', 'PostSync', 0, 'site-list', 'post_sync_list' );
   add_submenu_page('site-list', 'Remote List', 'Remote List', 0, 'site-list', 'post_sync_list' );
   $post_sync->new_page = add_submenu_page('site-list', 'Add Remote WP', 'Add Remote WP', 0, 'add-site', 'post_sync_settings_page' );
   $post_sync->twitter_page = add_submenu_page('site-list', 'Add Twitter Account', 'Add Twitter Account', 0, 'add-twitter', 'post_sync_twitter_page');
   add_submenu_page('site-list', 'Log', 'Log', 0, 'post-log', 'post_sync_log');
   /* Make sure the settings are saved. */
   add_action( "load-{$post_sync->new_page}", 'post_sync_create_settings_meta_boxes');
   add_action("load-{$post_sync->twitter_page}", 'post_sync_create_twitter_meta_boxes');
}

function post_sync_admin_style(){
  $plugin_data = get_plugin_data( POST_SYNC_DIR . 'post_sync.php' );
	
	wp_enqueue_style( 'post-sync-admin', POST_SYNC_CSS . 'style.css', false, $plugin_data['Version'], 'screen' );	
  wp_enqueue_style( 'post-sync-new', POST_SYNC_CSS . 'new.css', false, $plugin_data['Version'], 'screen' );     
}
function post_sync_admin_script(){}


function post_sync_settings_update_message(){
?>
  <div class="updated fade">
		<p><?php _e( 'You have just save this remote node. Please active it when you use it.', 'post-sync' ); ?></p>
	</div>
<?php		
}
function post_sync_error_handler(){
?>
   <div class="error">
		<p><?php _e( 'Site with same name exists, please select different name for site.', 'post-sync' ); ?></p>
  </div>
<?php	
}
?>
