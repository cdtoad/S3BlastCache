<?php

#error_reporting(0);

print_r($_POST);
if (!class_exists('S3')) require_once 's3.php';

// AWS access info

$awsAccessKey=$_POST['KEY'];
$awsSecretKey=$_POST['SECRET'];
$Domain=$_POST['DOMAIN'];


	$s3= new S3;

    if (($contents = $s3->getBucket($Domain)) !== false) {
        foreach ($contents as $object) {
            print_r($object);
        }
    }  ELSE {
    
    	echo 'ERROR: Either access or bucket';  // yeah need better E.H. here.	
    	
    }
