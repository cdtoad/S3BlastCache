   jQuery('#s3_blast_cache_test').click(function() {
       

				var KEY   =jQuery('#S3_BLAST_CACHE_AWS_KEY').val();  
				var SECRET=jQuery('#S3_BLAST_CACHE_AWS_SECRET').val();  
				var DOMAIN=jQuery('#S3_BLAST_CACHE_BUCKET').val();  
				
				var DATA2PASS={KEY:KEY,SECRET:SECRET,DOMAIN:DOMAIN};
				 
       	console.log("Checking to see if "+DOMAIN+" Access works.");
        
          jQuery.post('/wp-content/plugins/S3BlastCache/s3-blast-cache-test-access-to-bucket.php',DATA2PASS, function(data) {
//           	alert(data);
				
        
        
        
        var BarfUpMessage = jQuery('#S3BucketStatus');
			      BarfUpMessage.html('<pre>'+data+'</pre>');
 
    
    
    		
           	console.log(data);
     
          	
           	
           	
       		 } ); // , 'json'
           
           
           
       	 } 
        );
       
       

  
 
       