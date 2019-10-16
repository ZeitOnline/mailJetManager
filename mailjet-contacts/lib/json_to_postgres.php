<?php 
	# Configure
	$DB_USER = 'mailjet';
	$DB_PASS = 'mailjet2019!';
	$DB_HOST = 'mailjet.zeit.de';
	$DB_NAME = 'mailjet';
	// Param 3 when ran in shell will override this value
	$DB_TABLE = 'contacts.contact'; 
	// Param 2 when ran in shell will override this value
	$CATEGORY = 'misc';
	
	// ===============================
	// = Do NOT Edit Below This Line =
	// ===============================
	
	# Params
	$CURRENT_SCRIPT = $argv[0];
	$JSON_FILE = $argv[1];
	$CATEGORY = isset($argv[2]) ? $argv[2]:$CATEGORY;
	$POSTGRE_TABLE = isset($argv[3]) ? $argv[3]:$DB_TABLE;

	

	# Open our file
	//$f = file_get_contents($JSON_FILE);
	$f = json_encode($result);
	# Make sure file is good
	if( $f === FALSE )
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
	$points = json_decode( $f, true );
	
	# Iterate our points
	foreach($points as $p)
	{
		//var_dump($p);
		# Sanatize
			$p['accountName']						= str_replace("'","\'",$accountName);	//				-> string
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
			$p['Name']								= str_replace("'","\'",$p['Name']); 						//-> string
//			$p['UnsubscribedAt']					-> string
			$p['UnsubscribedBy']					= str_replace("'","\'",$p['UnsubscribedBy']); 				//-> string
			


		
		/* Insert into DB
		pg_query($dbconn,"INSERT INTO $POSTGRE_TABLE (accountname,createdat,deliveredcount,email,exclusionfromcampaignsupdatedat,id,isexcludedfromcampaigns,isoptinpending,isspamcomplaining,lastactivityat,lastupdateat,name,unsubscribedat,unsubscribedby) VALUES ( '".$accountName."', '".$p['CreatedAt']."', '".$p['DeliveredCount']."', '".$p['Email']."', '".$p['ExclusionFromCampaignsUpdatedAt']."','".$p['ID']."', '".$p['IsExcludedFromCampaigns']."', '".$p['IsOptInPending']."', '".$p['IsSpamComplaining']."', '".$p['LastActivityAt']."', '".$p['LastUpdateAt']."', '".$p['Name']."', '".$p['UnsubscribedAt']."', '".$p['UnsubscribedBy']."')") or die("Could not execute this insert statement: ".pg_last_error());
		*/


		# Insert or update DB
		pg_query($dbconn, "INSERT INTO $POSTGRE_TABLE (accountname,createdat,deliveredcount,email,exclusionfromcampaignsupdatedat,id,isexcludedfromcampaigns,isoptinpending,isspamcomplaining,lastactivityat,lastupdateat,name,unsubscribedat,unsubscribedby) VALUES ( '".$accountName."', '".$p['CreatedAt']."', '".$p['DeliveredCount']."', '".$p['Email']."', '".$p['ExclusionFromCampaignsUpdatedAt']."','".$p['ID']."', '".$p['IsExcludedFromCampaigns']."', '".$p['IsOptInPending']."', '".$p['IsSpamComplaining']."', '".$p['LastActivityAt']."', '".$p['LastUpdateAt']."', '".$p['Name']."', '".$p['UnsubscribedAt']."', '".$p['UnsubscribedBy']."') ON CONFLICT ON CONSTRAINT accountname_email DO UPDATE SET accountname = '".$accountName."', createdat = '".$p['CreatedAt']."', deliveredcount = '".$p['DeliveredCount']."', email = '".$p['Email']."', exclusionfromcampaignsupdatedat = '".$p['ExclusionFromCampaignsUpdatedAt']."', id = '".$p['ID']."', isexcludedfromcampaigns = '".$p['IsExcludedFromCampaigns']."', isoptinpending = '".$p['IsOptInPending']."', isspamcomplaining = '".$p['IsSpamComplaining']."', lastactivityat = '".$p['LastActivityAt']."', lastupdateat = '".$p['LastUpdateAt']."', name = '".$p['Name']."', unsubscribedat = '".$p['UnsubscribedAt']."', unsubscribedby = '".$p['UnsubscribedBy']."' ;") or die("Could not execute this insert statement: ".pg_last_error());
	}
?>