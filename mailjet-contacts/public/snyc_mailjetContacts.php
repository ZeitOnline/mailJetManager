<?php
/*
Retrieve all contacts:
*/
require '../lib/mailjet-apiv3/vendor/autoload.php';
use \Mailjet\Resources;

include_once('../conf/mailjet.conf'); 
include_once('../lib/mailjetFunctions.php'); 
include('../lib/json_to_postgres.php'); 


$importtime = time();
$l = 0;
$a = 0;

echo "\n\nStarting script @".date('Y-m-d h:m', $importtime)."\n\n";
// iterate through all API-Keys set in mailjet.conf
	for($i=0; $i<count($MJ_KEYS); $i++)
	{
		$accountName 		= $MJ_KEYS[$i]['Name'];

		$MJ_APIKEY_PUBLIC 	= $MJ_KEYS[$i]['APIKey'];
		$MJ_APIKEY_PRIVATE	= $MJ_KEYS[$i]['SecretKey'];	

// fork a child for each account		
		$pid = pcntl_fork();
		if (!$pid) 
	    {
			//	find email address in address lists
			//echo "\n\n".date('Y-m-d h:m', $importtime)."working on: ". $accountName."\n";
			$result 	= getallContacts($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);			
//var_dump($result);
			if(count($result) > 0)
			{
				$staus = storeContacts($result,$importtime,$accountName);
			}
			
		exit($l);
		}
		$l++;

		$pid = pcntl_fork();
		if (!$pid) 
	    {
			//	find Details of contacts
			echo "\n\n".date('Y-m-d h:m', $importtime)." working on: ". $accountName."\n";
			$resultstat = getallContactstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,$importtime,$accountName);
			if(count($resultstat) > 0)
			{
				$staus = storeContactsDetails($resultstat,$importtime,$accountName);
			}
		exit($a);
		}
		$a++;


	echo "\n";
	}

	while (pcntl_waitpid(0, $status) != -1) 
        {
            $status = pcntl_wexitstatus($status);
//				echo "Child $status completed\n";
        }  

?>