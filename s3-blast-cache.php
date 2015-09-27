<?php
 
/*
Plugin Name: S3 Blast Cache
Plugin URI:  http://www.JargonBox.com/S3BlastCache
Description: Exports your wordpress content into HTML format and loads to S3 for a super fast static website
Version:     0.001-Omega
Author:      Dave Miyares
Author URI:  http://www.JargonBox.com/about
 */
 
 
 add_action('admin_init', 's3_blast_cache_test_s3');
 add_action('admin_init', 's3_blast_cache_build_cache');
 add_action('admin_menu', 's3_blast_cache_admin_actions');
 register_activation_hook(__FILE__, 's3_blast_cache_activation'); 
 add_filter('plugin_action_links', 's3_blast_cache_plugin_action_links', 10, 2);
 add_action( 'admin_enqueue_scripts', 'BlastCacheFancyJS' );
 
 
 // 
 
 function s3_blast_cache_activation() {
   global $wpdb;   
   $table_name = $wpdb->prefix . "s3_blast_cache"; 
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {      
      $sql = "CREATE TABLE " . $table_name . " (
          HASH char(32) NOT NULL,
          post_id bigint(20) unsigned not null 
        );";
     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
   }
}

 
 
 
 function s3_blast_cache_build_cache(){
	 
	   		if(isset($_POST['CREATEHTML'])){
  				
  			global $wpdb; 
			
			$s3_blast_cache_DB_posts_table=$wpdb->prefix."posts"; 

	// FIRST LOAD ALL HEADLINES & HASHES INTO AN ARRAY
	
	$sql_pull_posts_pages="select ID,post_title,md5(concat(post_content,post_title,post_modified_gmt)) as HASH from ".$s3_blast_cache_DB_posts_table." where post_type in ('post','page') and post_status='publish';";

	#print($sql_pull_posts_pages);
	
	
	 
	 $s3_blast_cache_post_hash_querey = $wpdb->get_results($sql_pull_posts_pages);
	
		// build a better array based of Post ID
				$s3_blast_cache_post_hash_array=array();
							foreach($s3_blast_cache_post_hash_querey as $post_item_id){

									$s3_blast_cache_post_hash_array[$post_item_id->ID]['HASH']=$post_item_id->HASH;
									$s3_blast_cache_post_hash_array[$post_item_id->ID]['TITLE']=$post_item_id->post_title;
									
									
								}
			
			ksort($s3_blast_cache_post_hash_array);
				#print_r($s3_blast_cache_post_hash_array);
	
	
		// start pulling posts
		
			foreach($s3_blast_cache_post_hash_array as $s3_blast_cache_post){

				#		$REMOTE_HTML=wp_remote_get("http://www.icleveland.com/", array('timeout' => 60, 'blocking' => true ) );
	
						$REMOTE_HTML=wp_remote_get("http://www.icleveland.com/", array('timeout' => 60, 'blocking' => true ) );
							
						}	
				
		// finally pull & process home page since this should update everytime there's a new post.  
		// check logic on this one. bounce of hash too?
		
		$s3_blast_cache_homepage_puller = wp_remote_get( site_url(), array('timeout' => 60, 'blocking' => true ) );			
						
  						exit();
  				 
  				
  		} 
 
 }
 
 

 

 
 
 function s3_blast_cache_admin_actions() {
	 
	 add_options_page('S3 Blast Cache','S3 Blast Cache','manage_options',__FILE__,'s3_blast_cache_admin_menu_page');
	 
	}


  function s3_blast_cache_test_s3(){
	  
	  	// test S3 here
	
		 if(isset($_POST['ISTEST'])){
	###
				$CurrentTimeStamp=date('Y-m-d H:i:s');
				if (!class_exists('S3')) require_once 's3.php';

		// AWS access info

				$awsAccessKey=$_POST['KEY'];
				$awsSecretKey=$_POST['SECRET'];
				$Domain=$_POST['DOMAIN'];

					print("[".$CurrentTimeStamp."] testing....");

					$s3 = new S3($awsAccessKey, $awsSecretKey);
 
 
					if (($contents = $s3->getBucket($Domain,'','',1)) !== false) {
 			
					print("S3 Domain Bucket Found & Working.");
					print("<img src=\"".plugin_dir_url( __FILE__ )."images/tick.png\">\n");
 			
					}  ELSE {
    	 
					echo 'ERROR: Either access credeitals are wrong or bucket/domain ['.$Domain.'] doesn\'t exist on S3';  // yeah need better E.H. here.	

		}


    ###
		
		
		// don't write anything beyone here you'll screw up everything!	
			EXIT();
		}
	  
	  
  }


  function s3_blast_cache_admin_menu_page(){
	  
	 
	 
  	$Upload_Array= wp_upload_dir(); 
    $Upload_to_S3_dir=$Upload_Array['basedir']."/upload_to_S3/";
	 
	 
	// test to see if upload directory exists && create if false
	
		if(!(is_dir($Upload_to_S3_dir))){
			mkdir($Upload_to_S3_dir, 0777);
			}
	
		$LocalURL = str_replace( 'http://', '', str_replace( 'https://', '', get_option( 'siteurl' ) ) );

  	if(isset($_POST['s3_blast_cache_save'])){
  		
  	# 	 $x=	PRINT_R($_POST,true);echo '<pre>'.$x.'</pre>';
  		 $s3_blast_cache_array_save=array('AWSKEY'=>$_POST['S3_BLAST_CACHE_AWS_KEY'],
  		 																	'AWSSECRET'=>$_POST['S3_BLAST_CACHE_AWS_SECRET'],
  		 																	'BUCKET'=>$_POST['S3_BLAST_CACHE_BUCKET']);
  		 																
  		 
  		 #	ECHO '<PRE>';  		 print_r($s3_blast_cache_array_save);  		 ECHO '</PRE>';

  		 update_option('S3_BLAST_CACHE_CONFIG',$s3_blast_cache_array_save); // SAVE THE 4 VARIABLES
  		 
  		 // reload variables to show on form.
  		 			$S3_BLAST_CACHE_AWS_KEY=$s3_blast_cache_array_save['AWSKEY'];
  				    $S3_BLAST_CACHE_AWS_SECRET=$s3_blast_cache_array_save['AWSSECRET'];
  				    $S3_BLAST_CACHE_BUCKET=$s3_blast_cache_array_save['BUCKET'];
  		
  	} ELSE {
  		

  			IF(get_option('S3_BLAST_CACHE_CONFIG')){

  				$s3_blast_cache_array_save=get_option('S3_BLAST_CACHE_CONFIG');
  			
#  			 	 $x=	PRINT_R($_POST,true);echo $x;
  			
  				$S3_BLAST_CACHE_AWS_KEY=$s3_blast_cache_array_save['AWSKEY'];
  				$S3_BLAST_CACHE_AWS_SECRET=$s3_blast_cache_array_save['AWSSECRET'];
  				$S3_BLAST_CACHE_BUCKET=$s3_blast_cache_array_save['BUCKET'];
  			 
  				}
  	}
  	
 ?><div class="wrap">
 		<h2>S3 Blast Cache Settings</h2>
 	
 	<form name="S3_blast_cache_settings_page" action="" method="post">
 	
 						<input type="text" name="dir" value="<?=plugin_dir_url( __FILE__ )?>" id="S3_BLAST_CACHE_PLUGIN_DIR" NAME="S3_BLAST_CACHE_PLUGIN_DIR"  />
 						
 			
 					<table class="widefat fixed" cellspacing="0" BORDER="0">
 						<thead>
			 				<th WIDTH="15%"><h3>S3 Settings <?=$Upload_to_S3_dir?></h3></th><th>&nbsp;</th>
 						</thead>
 							<tr><td WIDTH="15%">AWS ACCESS KEY ID</td>    <td><input type="text" name="S3_BLAST_CACHE_AWS_KEY" value="<?=$S3_BLAST_CACHE_AWS_KEY?>" id="S3_BLAST_CACHE_AWS_KEY" size="50"></td></tr>
 							<tr><td WIDTH="15%">AWS SECRET ACCESS KEY</td><td><input type="PASSWORD" name="S3_BLAST_CACHE_AWS_SECRET" value="<?=$S3_BLAST_CACHE_AWS_SECRET?>" id="S3_BLAST_CACHE_AWS_SECRET" size="50"></td></tr>
 							<tr><td WIDTH="15%">S3 TARGET BUCKET/DOMAIN NAME</td><td><input type="TEXT" name="S3_BLAST_CACHE_BUCKET" value="<?=$S3_BLAST_CACHE_BUCKET?>" id="S3_BLAST_CACHE_BUCKET" size="50"> Should match PUBLIC domain name of site</td></tr>
 							<tr><td WIDTH="15%">Local URL</td><td><input type="TEXT" name="LocalURL" value="<?=$LocalURL?>" id="LocalURL"  disabled size="50"> URL of Private / URL site.  This will be replaced with the domain above</td></tr>
 							<tr><td WIDTH="15%">Working Directory</td><td><input type="TEXT" name="Upload_to_S3_dir" value="<?=$Upload_to_S3_dir?>" id="Upload_to_S3_dir"  disabled size="50"> Working Directory we'll upload to S3</td></tr>
 						
 						</table>
 							<br />
 
 						
 				<input type="button" id="s3_blast_cache_test" name="s3_blast_cache_test"   value="Test" class="button-primary"> 						
 				<input type="submit" id="s3_blast_cache_save" name="s3_blast_cache_save"   value="Save" class="button-primary"> 				
 			  <input type="button" id="s3_blast_cache_create" name="s3_blast_cache_create" value="Create & Upload S3 Blast Cache" class="button-primary">

</form>
 	
 	<div id="S3BucketStatus" name="S3BucketStatus" value="Untested">Untested</div>
 	 
</div>

<?php

	 
	
  }
  
  

function BlastCacheFancyJS($hook) {
    if ( 'settings_page_S3BlastCache/s3-blast-cache' != $hook ) {
    	
  #  	print('--->'.$hook.'<---');
        return;
    }

    wp_enqueue_script( 'S3_BLAST_CACHE_FANCY', plugin_dir_url( __FILE__ ) . 'js/S3_Blast_Cache_Fancy_Javascripts.js','','v0.001',true );
}



function s3_blast_cache_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
    
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=S3BlastCache%2Fs3-blast-cache.php">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}