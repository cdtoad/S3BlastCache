<?php

# moved into main php file.

exit();
#error_reporting(0);

$CurrentTimeStamp=date('Y-m-d H:i:s');
if (!class_exists('S3')) require_once 's3.php';

// AWS access info

$awsAccessKey=$_POST['KEY'];
$awsSecretKey=$_POST['SECRET'];
$Domain=$_POST['DOMAIN'];
$PLUGIN_DIR=$_POST['PLUGIN_DIR'];

print("[".$CurrentTimeStamp."] testing....");

	$s3 = new S3($awsAccessKey, $awsSecretKey);
 
 #   if (($contents = $s3->listBuckets()) !== false) {
 #	public static function getBucket($bucket, $prefix = null, $marker = null, $maxKeys = null, $delimiter = null, $returnCommonPrefixes = false)
	
 if (($contents = $s3->getBucket($Domain,'','',1)) !== false) {
 			
 			print("S3 Domain Bucket Found & Working.");
 			print("<img src=\"".$PLUGIN_DIR."images/tick.png\">\n");
 			
  	#print_r($contents); 			
   #     foreach ($contents as $object) {        	print("\n");            print_r($object);        }
    }  ELSE {
    	 
    	echo 'ERROR: Either access credeitals are wrong or bucket/domain ['.$Domain.'] doesn\'t exist on S3';  // yeah need better E.H. here.	
    	
    }
