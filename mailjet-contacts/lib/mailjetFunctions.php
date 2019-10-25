<?php

require '../lib/mailjet-apiv3/vendor/autoload.php';
use \Mailjet\Resources;
/*


you can see 3 type of ID:
ContactID : This the ID of you recipient in mailjet. You can search the email using the resource /contact/{ContactID}
ListID: this is the ID of the list 
ID: this is the Lisrecipient ID, it is the one you need to use to delete a user from a list.

*/


//////////////////////////////////////////////// not in use //////////////////////

function getallContacts($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE)
{


	$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);

	// first get count of records for cureent APIKEY to calculate loop count
	$filters = [
	  'countOnly' => 1
	];

	$response = $mj->get(Resources::$Contact, ['filters' => $filters]);
	$response->success();
	$overallCount = $response->getCount();
	
	if(!$overallCount)
	{

		$overallCount = 0;
	}else
	{
		echo "\n... fetching ". $overallCount . " records ...";	
	}
	
	// now get all data and save it in array data
	$tmpArray = false;
	$data = array();
	$loops = $overallCount/1000;
	for($callno=0; $callno < $loops; $callno++)
	{
	// declare filter with 1000 records (whichb ist maximum) and offset for next loop run
		$filters = [
	  				'Limit' => 1000,
	  				'Offset' => $callno*1000
					];


		$response = $mj->get(Resources::$Contact, ['filters' => $filters]);
		$response->success() && $tmpArray = $response->getData();
		if($tmpArray)
			$data = array_merge($data, $tmpArray);
		
		
	}
	
	return $data;
}


function getallContactstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,$importtime,$accountName)
{


	$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);

	// first get count of records for cureent APIKEY to calculate loop count
	$filters = [
	  'countOnly' => 1
	];

	$response = $mj->get(Resources::$Contactstatistics, ['filters' => $filters]);
	$response->success();
	$overallCount = $response->getCount();
	
	if(!$overallCount)
	{

		$overallCount = 0;
	}else
	{
		echo "\n... fetching ". $overallCount . " record details ...";	
	}

	// now get all data and save it in array data
	$tmpArray = false;
	$data = array();
	$loops = $overallCount/1000;

	for($callno=0; $callno < $loops; $callno++)
	{
	// declare filter with 1000 records (whichb ist maximum) and offset for next loop run
		$filters = [
	  				'Limit' => 1000,
	  				'Offset' => $callno*1000
					];


		$response = $mj->get(Resources::$Contactstatistics, ['filters' => $filters]);
		$response->success() && $tmpArray = $response->getData();
		if($tmpArray)
		{
			$data = array_merge($data, $tmpArray);
		}
		
	}
	// fork a process and get relation data for each entry 
	$a = 0;
	$b = 0;
	echo "\n".date('Y-m-d h:m', $importtime)." working on contact to list relation: ". $accountName;
	$pid = pcntl_fork();
	if (!$pid) 
    {
		
    	// spilt into chunks for more perfromance
		$datachunk = array_chunk($data, 100);
    	
    	foreach($datachunk as $chunk)
    	{
    		// fork a process for each chunk
			$pid = pcntl_fork();
			if (!$pid) 
			{


		    	foreach($chunk as $record)
		    	{
		    		//	find adresslist to contact relations
					//echo "\n".$record['ContactID']."\n";
					
					$resultstat = getallContactListstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE, $record['ContactID']);
					if(count($resultstat) > 0)
					{
						//var_dump($resultstat);
						$staus = storeContacts2Listsrelation($resultstat,$importtime,$accountName,$record['ContactID']);
					}
					

		    	}
		    	//unset($chunk);
		    	exit($b);

		    }
		    $b++;

			while (pcntl_waitpid(0, $status) != -1) 
		    {
		        $status = pcntl_wexitstatus($status);
		//				echo "Child $status completed\n";
		    }  	
		
	    }
    	exit($a);
	}	
	$a++;
	while (pcntl_waitpid(0, $status) != -1) 
    {
        $status = pcntl_wexitstatus($status);
//				echo "Child $status completed\n";
    } 
    echo "\n". $accountName . " finished";
	return $data;
}



function getallContactListstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,$ContactID)
{


	$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);

	// now get contact to list relations and store them
	$data = array();
	
		$filters = [
	  				'Contact' => $ContactID,
	  				'ShowExtraData' => true
					];


		$response = $mj->get(Resources::$Listrecipientstatistics, ['filters' => $filters]);
		$response->success() && $data = $response->getData();
		
		
	
	return $data;
}




?>