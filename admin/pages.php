<?php
function post_sync_twitter_meta_box(){
  $sync_options = get_option(SYNC_OPTIONS);

	if(!isset($sync_options['twat']))
	{				
	
?>
     <p>If you want to auto-publish your blog posts/pages on your Twitter account, you have to authorize PostSync to publish on it.</p>
     <form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

				<input name="action" value="post_sync_crud" type="hidden">
                <input name="form_action" value="twitter_oauth" type="hidden">
          <p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="Auth" />
					
				</p><!-- .submit -->      
      </form>          
<?php
	} 
} 	

function post_sync_author_meta_box(){
?>
  <p>If you think this pluign is useful for your website, or some more suggestion for this plugin, please tell me, thanks.</p>
  <p><a href="http://www.wp-coder.net/donate/">Donate</a>
</p>
  <p>Author: <a href="http://wp-codet.net">wp-coder.net</a></p>
  
  
<?php  	
}
function post_sync_log(){
?>	
  <div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Post Log', 'post_sync' ); ?></h2> 
                
		<?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) post_sync_settings_update_message(); ?>
                <?php 
                     global $wpdb;
                     $bars = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."sync_log ORDER BY time DESC");                    
                     if(empty($bars)){
					     echo "There are no log info now.";	 
					 }else{ 
                ?>
        <form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

				<input name="action" value="post_sync_crud" type="hidden">
                <input name="form_action" value="clear_log" type="hidden">        	                      
         <table class="widefat post" cellspacing="0"> 
            <thead>
	<tr>
	

	<th scope="col" id="title" class="manage-column column-title" style="">Name</th>	
	<th scope="col" id="author" class="manage-column column-author" style="">Run Time</th>
	<th scope="col" id="categories" class="manage-column column-categories" style="">Message</th>
     
	</tr>
	</thead>
        <tfoot>
	<tr>
	
	<th scope="col"  class="manage-column column-title" style="">Name</th>
	<th scope="col"  class="manage-column column-author" style="">Run Time</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Message</th>    	
	 
	</tr>
	</tfoot>
	<tbody>       
         <?php foreach($bars as $bar) {?>       
                   
                
                    <tr class="bar_item">
                        <td class="col1"><?php echo $bar->name; ?></td>                        
                        <td class="col2"><?php echo $bar->time; ?></td>                              
                        <td class="col3"><?php echo $bar->message; ?></td>                                               
                    </tr>                          
        
	      <?php } ?>		
      </tbody>
      </table>               
      <p class="submit" style="clear: both;">
    	<input type="submit" name="Submit"  class="button-primary" value="clear log" />
 	</p><!-- .submit -->
 	</from>
       <?php 
	     }
       ?>        			
							
	</div><!-- .wrap -->  
	
<?php		
}
function post_sync_list(){
?>	
  <div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Remote List', 'post_sync' ); ?>
                 <a id="add" href="<?php echo admin_url('admin.php?page=add-site'); ?>">(Add New)</a>
        </h2> 
                
		<?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) post_sync_settings_update_message(); ?>
                <?php 
                     global $wpdb;
                     $bars = $wpdb->get_results("SELECT * FROM wp_sync");                    
                     if(empty($bars)){
					     echo "There are no website info now.";	 
					 }else{ 
                ?>
                	                      
         <table class="widefat post" cellspacing="0"> 
            <thead>
	<tr>
	

	<th scope="col" id="title" class="manage-column column-title" style="">Name</th>	
	<th scope="col" id="author" class="manage-column column-author" style="">Status</th>
	<th scope="col" id="categories" class="manage-column column-categories" style="">Edit</th>
	<th scope="col" id="categories" class="manage-column column-categories" style="">Action</th> 
     <th scope="col" id="categories" class="manage-column column-categories" style="">Type</th> 
	</tr>
	</thead>
        <tfoot>
	<tr>
	
	<th scope="col"  class="manage-column column-title" style="">Name</th>
	<th scope="col"  class="manage-column column-author" style="">Status</th>
    <th scope="col" id="categories" class="manage-column column-categories" style="">Edit</th>   
    <th scope="col" id="categories" class="manage-column column-categories" style="">Action</th>    	
	 <th scope="col" id="categories" class="manage-column column-categories" style="">Type</th> 
	</tr>
	</tfoot>
	<tbody>       
         <?php foreach($bars as $bar) {?>       
                   
                
                    <tr class="bar_item">
                        <td class="col1"><?php echo $bar->name; ?></td>                        
                        <td class="col2">
                               <a href="<?php echo admin_url('admin-ajax.php')?>?ID=<?php echo $bar->id; ?>&amp;action=post_sync_crud&amp;form_action=<?php if($bar->status == 'enable'){echo 'disable';}else if($bar->status == 'disable'){echo 'enable';}?>">
                                  <?php if($bar->status == 'enable'){echo 'On';}else if($bar->status == 'disable'){echo 'Off';}?>
                               </a>
                        </td>
                        <?php 
                           switch ($bar->type){
							  case 'wp':
							      $edit_url = admin_url('admin.php?page=add-site&id=').$bar->id;
							  break;   
						      case 'twitter':
						          $edit_url = admin_url('admin.php?page=add-twitter&id=').$bar->id;
						      break;
						   }
                           
                        ?>
                        <td class="col3"><a href="<?php echo $edit_url; ?>">Edit</a></td>
                        <td class="col4">
                           <a href="<?php echo admin_url('admin-ajax.php')?>?ID=<?php echo $bar->id; ?> &amp;action=post_sync_crud&amp;form_action=delete" class="link-delete">Delete</a>
                        </td>
                        <td><?php echo $bar->type; ?></td>
                    </tr>
                          
        
	      <?php } ?>		
      </tbody>
      </table>               
       <?php 
	     }
       ?>        			
							
	</div><!-- .wrap -->  
<?php		
} 

function post_sync_settings_page(){
  global $post_sync;

	$plugin_data = get_plugin_data( POST_SYNC_DIR . 'post_sync.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Remote Website Settings', 'post-sync' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) post_sync_settings_update_message(); ?>
		<?php if ( isset( $_GET['error'] ) && 'true' == esc_attr( $_GET['error'] ) ) post_sync_error_handler(); ?>

		<div id="poststuff">
			<form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

				<input name="action" value="post_sync_crud" type="hidden">
                <input name="form_action" value="<?php if(!empty($_GET['id'])){echo "update";}else{echo "wp";}?>" type="hidden">
                <?php if(!empty($_GET['id'])){ ?>
                <input name="id" value="<?php echo $_GET['id']; ?>" type="hidden">
                 <?php } ?>                
				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $post_sync->new_page, 'normal', $plugin_data ); ?></div>
		            <div class="post-box-container column-2 advanced"><?php do_meta_boxes( $post_sync->new_page, 'advanced', $plugin_data ); ?></div> 			
					
				</div>

				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="<?php if(!empty($_GET['id'])){_e('Update Settings','post-sync'); }else{_e( 'Add', 'post-sync' );} ?>" />
					
				</p><!-- .submit -->

			</form>
							
		</div><!-- #poststuff -->

	</div><!-- .wrap -->  	
<?php
}
function post_sync_twitter_page(){
  global $post_sync;

	$plugin_data = get_plugin_data( POST_SYNC_DIR . 'post_sync.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Twitter Account', 'post-sync' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) post_sync_settings_update_message(); ?>
		<?php if ( isset( $_GET['error'] ) && 'true' == esc_attr( $_GET['error'] ) ) post_sync_error_handler(); ?>

		<div id="poststuff">
			
                <?php if(!empty($_GET['id'])){ ?>
                <input name="id" value="<?php echo $_GET['id']; ?>" type="hidden">
                 <?php } ?>                
				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $post_sync->twitter_page, 'normal', $plugin_data ); ?></div>
		            <div class="post-box-container column-2 advanced"><?php do_meta_boxes( $post_sync->twitter_page, 'advanced', $plugin_data ); ?></div> 			
					
				</div>

											
		</div><!-- #poststuff -->

	</div><!-- .wrap -->  		
<?php
}
function post_sync_create_twitter_meta_boxes(){
   global $post_sync;
    
	add_meta_box( 'post-sync-basic-meta-box', __( 'Twitter Account', 'post-sync' ), 'post_sync_twitter_meta_box', $post_sync->twitter_page, 'normal', 'high' );
    add_meta_box( 'post-sync-author-meta-box', __( 'Like this plugin?', 'post-sync' ), 'post_sync_author_meta_box', $post_sync->twitter_page, 'advanced', 'high' );	
}
function post_sync_create_settings_meta_boxes(){
   global $post_sync;
    
	add_meta_box( 'post-sync-basic-meta-box', __( 'Basic Settings', 'post-sync' ), 'post_sync_basic_meta_box', $post_sync->new_page, 'normal', 'high' );
    add_meta_box( 'post-sync-author-meta-box', __( 'Like this plugin?', 'post-sync' ), 'post_sync_author_meta_box', $post_sync->new_page, 'advanced', 'high' );
	
}

/**
 * Displays basic meta box.
 *
 * @since 0.8
 */
function post_sync_basic_meta_box() { 
  if(!empty($_GET['id'])){
	 global $wpdb;	                 
     $q = "SELECT * FROM wp_sync WHERE id=".$_GET['id'];                     
     $data = $wpdb->get_row($q);
   }                  
?>   
	<table class="form-table">
		<tr>
			<th>
            	<label for="name"><?php _e( 'Name:', 'post-sync' ); ?></label> 
            </th>
            <td>
            	<input id="name" name="name" type="text" value="<?php if(isset($data)){echo $data->name;} ?>" />
            </td>
		</tr>
		<tr>
			<th>
            	<label for="url"><?php _e( 'Url:', 'post-sync' ); ?></label> 
            </th>
            <td>
				<input id="url" name="url" type="text" maxlength="85" value="<?php if(isset($data)){echo $data->url;}else{echo 'http://example.com/xmlrpc.php'; }?>" size="21" maxlength="21" />
            </td>
		</tr>
		<tr>
			<th>
            	<label for="username"><?php _e( 'Username:', 'post-sync' ); ?></label> 
            </th>
            <td>
            	<input id="username" name="username" maxlength="30" value="<?php if(isset($data)){echo $data->username;} ?>" type="text" />
            </td>
		</tr>
		<tr>
			<th>
            	<label for="password"><?php _e( 'Password:', 'post-sync' ); ?></label> 
            </th>
            <td>
            	<input id="password" name="password" maxlength="255" value="<?php if(isset($data)){echo $data->password;}?>" type="password" />
            </td>
		</tr>
	</table><!-- .form-table -->
<?php
}	

function post_sync_save_settings(){
   global $wpdb;
   $settings = array(); 
   $settings['name'] = esc_html($_POST['name']);
   $settings['url'] = esc_html($_POST['url']);
   $settings['username'] = esc_html( $_POST['username'] );
   $settings['password'] = esc_html( $_POST['password'] ); 

   switch ($_POST['form_action']) {     
    case 'twitter':
        $settings['status'] = 'enable';
        $settings['type'] = 'twitter';   
        $processed = array(); 
        $settings['processed'] = serialize($processed);     
        $q = "SELECT * FROM wp_sync WHERE name='".$settings['name']."'";                     
        $data = $wpdb->get_row($q);        
        if(!empty($data)){
		   $redirect = admin_url( 'admin.php?page=add-site&error=true'); 
           wp_redirect($redirect);
        }else{
           $wpdb->insert( 'wp_sync', $settings);
           if($wpdb->insert_id != false){ 
             $redirect = admin_url( 'admin.php?page=add-site&updated=true&id=' ).$wpdb->insert_id; 
             wp_redirect($redirect);
           }
        }     
        break;
    case 'update':
         $where = array('id' => $_POST['id']); 
         $wpdb->update('wp_sync', $settings, $where);
         $redirect = admin_url( 'admin.php?page=add-site&updated=true&id=' ).$_POST['id'];
         wp_redirect($redirect);
         break;
    case 'twitter_update':
         $where = array('id' => $_POST['id']); 
         $wpdb->update('wp_sync', $settings, $where);
         $redirect = admin_url( 'admin.php?page=add-twitter&updated=true&id=' ).$_POST['id'];
         wp_redirect($redirect);
         break;     
    case 'wp':
            
        $settings['status'] = 'enable';
        $settings['type'] = 'wp';   
        $processed = array(); 
        $settings['processed'] = serialize($processed);     
        $q = "SELECT * FROM wp_sync WHERE name='".$settings['name']."'";                     
        $data = $wpdb->get_row($q);        
        if(!empty($data)){
		   $redirect = admin_url( 'admin.php?page=add-site&error=true'); 
           wp_redirect($redirect);
        }else{
           $wpdb->insert( 'wp_sync', $settings);
           if($wpdb->insert_id != false){ 
             $redirect = admin_url( 'admin.php?page=add-site&updated=true&id=' ).$wpdb->insert_id; 
             wp_redirect($redirect);
           }
        }     
        break;
    case 'clear_log' :
        $q = "DELETE FROM ".$wpdb->prefix."sync_log";
        $wpdb->query($q);
        $redirect = admin_url('admin.php?page=post-log');
        wp_redirect($redirect);
        break;        
   }

   switch ($_GET['form_action']){
     case 'disable':
        $where = array('id' => $_GET['ID']);
        $status = array('status' => 'disable');
        $wpdb->update('wp_sync', $status, $where);
        wp_redirect(admin_url('admin.php?page=site-list'));
        break;
     case 'enable':
               
        //enable the selected website
        $where = array('id' => $_GET['ID']);
        $status = array('status' => 'enable');
        $wpdb->update('wp_sync', $status, $where);
        
        wp_redirect(admin_url('admin.php?page=site-list'));
        break;
     case 'delete':
        $query = "DELETE FROM wp_sync WHERE id=".$_GET['ID'];
        $wpdb->query($query);
        wp_redirect(admin_url('admin.php?page=site-list'));
        break;
     
        
   }
}
function post_sync_social_account(){}
?>
