   jQuery('#s3_Cache_Blaster_test').click(function() {
       
			alert('hi');
				var KEY   =jQuery('#S3_Cache_Blaster_AWS_KEY').val();  
				var SECRET=jQuery('#S3_Cache_Blaster_AWS_SECRET').val();  
				var DOMAIN=jQuery('#S3_Cache_Blaster_BUCKET').val();  
		
				var DATA2PASS={ISTEST:'THISISATEST',KEY:KEY,SECRET:SECRET,DOMAIN:DOMAIN};
 
      // 	console.log("Checking to see if "+DOMAIN+" Access works.");
            jQuery.post('',DATA2PASS, function(data) {
        var BarfUpMessage = jQuery('#S3BucketStatus');
			      BarfUpMessage.html('<pre>'+data+'</pre>');
           //	console.log(data);
       		 } ); // , 'json'
       	 } 
        );
        
        
 // // // // // // // // // // // // // // // // // // // // // // // // // // // // 
      
   jQuery('#s3_Cache_Blaster_create').click(function() {
	   
	   	console.log('Starting');
        var BarfUpMessage = jQuery('#S3BucketStatus');
        var DATA2PASS={CREATEHTML:'CREATEHTML'};
        var S3_BLAST_CACHE_PLUGIN_DIR=jQuery('#S3_Cache_Blaster_PLUGIN_DIR').val(); 
		BarfUpMessage.html('Creating HTML for cache.<img src="'+S3_BLAST_CACHE_PLUGIN_DIR+'images/spinner.gif">');
				
            jQuery.post('',DATA2PASS, function(data) {
				  BarfUpMessage.html('<pre>'+data+'</pre>');
			      
			 } ); // , 'json'
			 
		 
			}
		  );
        
        
        
