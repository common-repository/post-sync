<?php
/**
 * Handling Remote request class.
 * 
 * 
 */
class RemoteHandler{
   
   /**
	* Create the class with the specified array as the data.
    */	
   public function RemoteHandler(){}	
  
   /**
	 * Create new post on remote wordpress site.
	 */
   public function newWP($title,$body,$rpcurl,$username,$password,$category,$keywords='',$encoding='UTF-8')
   {
      $title = htmlentities($title,ENT_NOQUOTES,$encoding);
      $keywords = htmlentities($keywords,ENT_NOQUOTES,$encoding);

      $content = array(
        'title'=>$title,
        'description'=>$body,
        'mt_allow_comments'=>0,  // 1 to allow comments
        'mt_allow_pings'=>0,  // 1 to allow trackbacks
        'post_type'=>'post',
        'mt_keywords'=>$keywords,
        'categories'=>array($category)
       );
       $params = array('',$username,$password,$content,true);
       $request = xmlrpc_encode_request('metaWeblog.newPost',$params);
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
       curl_setopt($ch, CURLOPT_URL, $rpcurl);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_TIMEOUT, 1);
       $result['EXE'] = curl_exec($ch);
	   $result['INF'] = curl_getinfo($ch);
	   $result['ERR'] = curl_error($ch);
	   $result['ENO'] = curl_errno($ch);	   
       curl_close($ch);
       if(!empty($result['ERR'])){
		  $message = $result['ERR']; 
	   }else{
		  $message = "Have just publish the post on remote site.";
	   }
       return $message;
    }

    public function newTwitter($username,$password,$message){
	      
         // The twitter API address
         $url = 'http://twitter.com/statuses/update.xml';
         // Alternative JSON version
         // $url = 'http://twitter.com/statuses/update.json';
         // Set up and execute the curl process
         $curl_handle = curl_init();
         curl_setopt($curl_handle, CURLOPT_URL, "$url");
         curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
         curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl_handle, CURLOPT_POST, 1);
         curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
         curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
         $buffer = curl_exec($curl_handle);
         curl_close($curl_handle);
         // check for success or failure
         if (empty($buffer)) {
           $result = 'error';
         } else {
           $result = 'success';
         }
         
         return $result;
	}

}   
?>
