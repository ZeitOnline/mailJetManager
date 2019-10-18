<?php 
	
	// ===============================
	// = Do NOT Edit Below This Line =
	// ===============================
	
	# Params
	
	$CURRENT_SCRIPT = $argv[0];
	//$CATEGORY = isset($argv[2]) ? $argv[2]:$CATEGORY;
	$POSTGRE_TABLE = isset($argv[3]) ? $argv[3]:$DB_TABLE;
	
	

	# Open our file
	//$f = file_get_contents($JSON_FILE);
	//$f = json_encode($result);
	# Make sure file is good
	if( json_encode($result) === FALSE )
	{
		die('Could not open json.');
	}
	# Open Postgre Connections
	else
	{
		$dbconn = pg_connect("host=$DB_HOST dbname=$DB_NAME user=$DB_USER password=$DB_PASS")
		    or die('Could not connect: ' . pg_last_error());
	}
	
	# Decode JSON into array
	//$points = json_decode( $f, true );
	
	# Iterate our points
	foreach($result as $p)
	{
		# Sanatize response from endpoint contact
			$p['accountName']						= str_replace("'"," ",$accountName);	//				-> string
//			$p['CreatedAt'] 						-> date with timestamp
//			$p['DeliveredCount'] 					-> int
//			$p['Email'] 							-> string
//			$p['ExclusionFromCampaignsUpdatedAt']	-> date with timestamp
//			$p['ID']								-> bigint
//			$p['IsExcludedFromCampaigns']			-> bool
//			$p['IsOptInPending']					-> bool
//			$p['IsSpamComplaining']					-> bool
//			$p['LastActivityAt']					-> date with timestamp
//			$p['LastUpdateAt']						-> date with timestamp
			$p['Name']								= str_replace("'"," ",$p['Name']); 						//-> string
//			$p['UnsubscribedAt']					-> string
			$p['UnsubscribedBy']					= str_replace("'"," ",$p['UnsubscribedBy']); 				//-> string
			


		# Insert or update DB
		pg_query($dbconn, "INSERT INTO $POSTGRE_TABLE (importtime,accountname,createdat,deliveredcount,email,exclusionfromcampaignsupdatedat,id,isexcludedfromcampaigns,isoptinpending,isspamcomplaining,lastactivityat,lastupdateat,name,unsubscribedat,unsubscribedby) VALUES ('".$importtime."', '".$accountName."', '".$p['CreatedAt']."', '".$p['DeliveredCount']."', '".$p['Email']."', '".$p['ExclusionFromCampaignsUpdatedAt']."','".$p['ID']."', '".$p['IsExcludedFromCampaigns']."', '".$p['IsOptInPending']."', '".$p['IsSpamComplaining']."', '".$p['LastActivityAt']."', '".$p['LastUpdateAt']."', '".$p['Name']."', '".$p['UnsubscribedAt']."', '".$p['UnsubscribedBy']."') ON CONFLICT ON CONSTRAINT accountname_email DO UPDATE SET importtime = '".$importtime."', accountname = '".$accountName."', createdat = '".$p['CreatedAt']."', deliveredcount = '".$p['DeliveredCount']."', email = '".$p['Email']."', exclusionfromcampaignsupdatedat = '".$p['ExclusionFromCampaignsUpdatedAt']."', id = '".$p['ID']."', isexcludedfromcampaigns = '".$p['IsExcludedFromCampaigns']."', isoptinpending = '".$p['IsOptInPending']."', isspamcomplaining = '".$p['IsSpamComplaining']."', lastactivityat = '".$p['LastActivityAt']."', lastupdateat = '".$p['LastUpdateAt']."', name = '".$p['Name']."', unsubscribedat = '".$p['UnsubscribedAt']."', unsubscribedby = '".$p['UnsubscribedBy']."' ;") or die("Could not execute this insert statement: ".pg_last_error());

		# Delete all records from account which where not present in json file by importtime

		pg_query($dbconn, "DELETE FROM $POSTGRE_TABLE WHERE accountname = '$accountName' AND importtime < '$importtime'") or die("Could not execute this delete statement: ".pg_last_error());

	}

/*
	# Iterate our points
	foreach($result as $p)
	{
		/* # Sanatize response from endpoint contactstatistics
		array(18) {
    ["BlockedCount"]=>
    int(0)
    ["BouncedCount"]=>
    int(0)
    ["ClickedCount"]=>
    int(0)
    ["ContactID"]=>
    int(1863415158)
    ["DeferredCount"]=>
    int(0)
    ["DeliveredCount"]=>
    int(0)
    ["HardBouncedCount"]=>
    int(0)
    ["LastActivityAt"]=>
    string(0) ""
    ["MarketingContacts"]=>
    int(0)
    ["OpenedCount"]=>
    int(0)
    ["PreQueuedCount"]=>
    int(0)
    ["ProcessedCount"]=>
    int(0)
    ["QueuedCount"]=>
    int(0)
    ["SoftBouncedCount"]=>
    int(0)
    ["SpamComplaintCount"]=>
    int(0)
    ["UnsubscribedCount"]=>
    int(0)
    ["UserMarketingContacts"]=>
    int(0)
    ["WorkFlowExitedCount"]=>
    int(0)
  }

		

	}
*/
?>