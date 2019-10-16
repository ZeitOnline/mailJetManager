<?php
/*
Retrieve all contacts:
*/
require '../lib/mailjet-apiv3/vendor/autoload.php';
use \Mailjet\Resources;

include_once('../conf/mailjet.conf'); 
include_once('../lib/mailjetFunctions.php'); 




// iterate through all API-Keys set in mailjet.conf
for($i=0; $i<count($MJ_KEYS); $i++)
{
	$accountName 		= $MJ_KEYS[$i]['Name'];

	$MJ_APIKEY_PUBLIC 	= $MJ_KEYS[$i]['APIKey'];
	$MJ_APIKEY_PRIVATE	= $MJ_KEYS[$i]['SecretKey'];	
//	find email address in address lists
echo "working on: ". $accountName;
	$result = getallContacts($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);

echo "\n".count($result)." entries ";
// iterate through result set and query details
/*

	for($hit=0; $hit<count($result);$hit++)
	{

		$details = findContactDetails($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE, $result[$hit]['Email']);
		$result[$hit]['Details'] = $details[0];
		echo "get details for:".$hit."\n";
		var_dump($result[$hit]);
	}
*/

//var_dump($result);
	if(count($result) > 0)
	{
		include_once('../lib/json_to_postgres.php'); 
	}


echo "\n";
}

?>