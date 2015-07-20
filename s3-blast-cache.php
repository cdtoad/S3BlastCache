<?php
 
/*
Plugin Name: S3 Blast Cache
Plugin URI:  http://www.JargonBox.com/S3BlastCache
Description: Exports your wordpress content into HTML format and loads to S3 for a super fast static website
Version:     0.1-alpha
Author:      Dave Miyares
Author URI:  http://www.JargonBox.com/about
 */
 
 add_action('admin_menu', 's3_blast_cache_admin_actions');
 
 function s3_blast_cache_admin_actions() {
	 
	 add_options_page('S3 Blast Cache','S3 Blast Cache','manage_options',__FILE__,'s3_blast_cache_admin_menu_page');
	 
	}


  function s3_blast_cache_admin_menu_page(){
  	
  	require_once('S3.php');
  	 
  	if(isset($_POST['s3_blast_cache_save'])){
  		
  	 	 $x=	PRINT_R($_POST,true);echo '<pre>'.$x.'</pre>';
  		 $s3_blast_cache_array_save=array('AWSKEY'=>$_POST['S3_BLAST_CACHE_AWS_KEY'],
  		 																	'AWSSECRET'=>$_POST['S3_BLAST_CACHE_AWS_SECRET'],
  		 																	'BUCKET'=>$_POST['S3_BLAST_CACHE_BUCKET']);
  		 																
  		 
  		 #	ECHO '<PRE>';  		 print_r($s3_blast_cache_array_save);  		 ECHO '</PRE>';

  		 update_option('S3_BLAST_CACHE_CONFIG',$s3_blast_cache_array_save); // SAVE THE 4 VARIABLES
  		 
  		 // reload variables to show on form.
  		 		$S3_BLAST_CACHE_AWS_KEY=$_POST['S3_BLAST_CACHE_AWS_KEY'];
  				$S3_BLAST_CACHE_AWS_SECRET=$_POST['AWSSECRET'];
  				$S3_BLAST_CACHE_BUCKET=$_POST['BUCKET'];
  		
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
 		
 				 
 						<table class="widefat fixed" cellspacing="0" BORDER="0">
 						<thead>
			 				<th WIDTH="15%"><h3>S3 Settings</h3></th><th>&nbsp;</th>
 						</thead>
 							<tr><td WIDTH="15%">AWS ACCESS KEY ID</td>    <td><input type="text" name="S3_BLAST_CACHE_AWS_KEY" value="<?=$S3_BLAST_CACHE_AWS_KEY?>" id="S3_BLAST_CACHE_AWS_KEY"></td></tr>
 							<tr><td WIDTH="15%">AWS SECRET ACCESS KEY</td><td><input type="PASSWORD" name="S3_BLAST_CACHE_AWS_SECRET" value="<?=$S3_BLAST_CACHE_AWS_SECRET?>" id="S3_BLAST_CACHE_AWS_SECRET"></td></tr>
 							<tr><td WIDTH="15%">S3 TARGET BUCKET/DOMAIN NAME</td><td><input type="TEXT" name="S3_BLAST_CACHE_BUCKET" value="<?=$S3_BLAST_CACHE_BUCKET?>" id="S3_BLAST_CACHE_BUCKET"> Should match domain name</td></tr>
 						</table>
 							<br />
 
 						
 				<input type="button" id="s3_blast_cache_test" name="s3_blast_cache_test" value="Test" class="button-primary"> 						
 				<input type="submit" id="s3_blast_cache_save" name="s3_blast_cache_save" value="Save" class="button-primary">
</form>
 	
 	<div id="S3BucketStatus" value="Untested">Untested</div>
 	
</div>
<?php
  	
  }
  
  

function my_enqueue($hook) {
    if ( 'settings_page_S3BlastCache/s3-blast-cache' != $hook ) {
    	
  #  	print('--->'.$hook.'<---');
        return;
    }

    wp_enqueue_script( 'S3_BLAST_CACHE_FANCY', plugin_dir_url( __FILE__ ) . 'js/S3_Blast_Cache_Fancy_Javascripts.js','','v0.001',true );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );