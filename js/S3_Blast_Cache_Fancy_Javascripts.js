   jQuery('#s3_blast_cache_test').click(function() {
       
				var KEY   =jQuery('#S3_BLAST_CACHE_AWS_KEY').val();  
				var SECRET=jQuery('#S3_BLAST_CACHE_AWS_SECRET').val();  
				var DOMAIN=jQuery('#S3_BLAST_CACHE_BUCKET').val();  
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
      
   jQuery('#s3_blast_cache_create').click(function() {
	   
	   	console.log('Starting');
        var BarfUpMessage = jQuery('#S3BucketStatus');
        var DATA2PASS={CREATEHTML:'CREATEHTML'};
        
		BarfUpMessage.html('Creating HTML for cache.');
				
            jQuery.post('',DATA2PASS, function(data) {
				  BarfUpMessage.html('<pre>'+data+'</pre>');
			      
			 } ); // , 'json'
			 
		 
			}
		  );
        
        
        
