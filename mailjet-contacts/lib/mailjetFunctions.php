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
	}
echo "\n... fetching ". $overallCount . " records ...";
	// now get all data and save it in array data
	$tmpArray = false;
	$data = array();
	$loops = $overallCount/1000;
	for($callno=0; $callno < $loops; $callno++)
	{
	// declare filter with 1000 records (whichb ist maximum) and offset for next loop run
		$filters = [
	  				'Limit' => 1000,
	  				'offset' => $callno*1000
					];


		$response = $mj->get(Resources::$Contact, ['filters' => $filters]);
		$response->success() && $tmpArray = $response->getData();
		if($tmpArray)
			$data = array_merge($data, $tmpArray);
		
		
	}
	
	return $data;
}


function getallContactstats($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE)
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
	}
echo "\n... fetching ". $overallCount . " record details ...";
	// now get all data and save it in array data
	$tmpArray = false;
	$data = array();
	$loops = $overallCount/1000;
	for($callno=0; $callno < $loops; $callno++)
	{
	// declare filter with 1000 records (whichb ist maximum) and offset for next loop run
		$filters = [
	  				'Limit' => 1000,
	  				'offset' => $callno*1000
					];


		$response = $mj->get(Resources::$Contactstatistics, ['filters' => $filters]);
		$response->success() && $tmpArray = $response->getData();
		if($tmpArray)
			$data = array_merge($data, $tmpArray);
		
		
	}
	
	return $data;
}



?>