<?php
/*
Retrieve all contacts:
*/
require '../lib/mailjet-apiv3/vendor/autoload.php';
use \Mailjet\Resources;

include_once('../conf/mailjet.conf'); 
include_once('../lib/mailjetFunctions.php'); 


$importtime = time();
$l = 0;



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
			echo date('Y-m-d h:m', $importtime)."working on: ". $accountName;
			$result = getallContacts($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);
//			$result = getallContactstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);
	

//	var_dump($result);
	
			if(count($result) > 0)
			{
				include('../lib/json_to_postgres.php'); 
			}
			exit($l);
		}
		$l++;

	echo "\n";
	}

	while (pcntl_waitpid(0, $status) != -1) 
        {
            $status = pcntl_wexitstatus($status);
//				echo "Child $status completed\n";
        }  

?>