<?php 
	
	// ===============================
	// = Do NOT Edit Below This Line =
	// ===============================
	
	# Params
	
	
	
	
	function storeContacts($result,$importtime,$accountName)
    {
    	include_once('../conf/postgres.conf'); 
		$POSTGRE_TABLE = $DB_TABLE_CONTACTS;
		$dbconn = pg_connect("host=$DB_HOST dbname=$DB_NAME user=$DB_USER password=$DB_PASS")
		    or die('Could not connect: ' . pg_last_error());

		# Iterate our records
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
		return 1;
	}
	
	function storeContactsDetails($resultstat,$importtime,$accountName)
	{
    	include_once('../conf/postgres.conf'); 
		$POSTGRE_TABLE = $DB_TABLE_CONTACTSDETAILS;
		$dbconn = pg_connect("host=$DB_HOST dbname=$DB_NAME user=$DB_USER password=$DB_PASS")
	    or die('Could not connect: ' . pg_last_error());

		# Iterate our records details
		foreach($resultstat as $ps)
		{
			
			# Sanatize response from endpoint contactstatistics
			/*
			    CONSTRAINT accountname_contactid UNIQUE (contactid, accountname)
			    importtime BIGINT,
			    accountname CHARACTER VARYING(250),
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
			    ["DeliveredCount"]=> '".$ps['DeliveredCount']."',
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
			 */
			# Insert or update DB
			pg_query($dbconn, "INSERT INTO $POSTGRE_TABLE (importtime,accountname,blockedcount,bouncedcount,clickedcount,contactid,deferredcount,deliveredcount,hardbouncedcount,lastactivityat,marketingcontacts,openedcount,prequeuedcount,processedcount,queuedcount,softbouncedcount,spamcomplaintcount,unsubscribedcount,usermarketingcontacts,workflowexitedcount) VALUES ('".$importtime."', '".$accountName."', '".$ps['BlockedCount']."', '".$ps['BouncedCount']."', '".$ps['ClickedCount']."', '".$ps['ContactID']."', '".$ps['DeferredCount']."', '".$ps['DeliveredCount']."', '".$ps['HardBouncedCount']."',  '".$ps['LastActivityAt']."',  '".$ps['MarketingContacts']."',  '".$ps['OpenedCount']."',  '".$ps['PreQueuedCount']."',  '".$ps['ProcessedCount']."',  '".$ps['QueuedCount']."',  '".$ps['SoftBouncedCount']."',  '".$ps['SpamComplaintCount']."', '".$ps['UnsubscribedCount']."',  '".$ps['UserMarketingContacts']."', '".$ps['WorkFlowExitedCount']."') ON CONFLICT ON CONSTRAINT accountname_email DO UPDATE SET importtime = '".$importtime."', accountname = '".$accountName."', blockedcount = '".$ps['BlockedCount']."', bouncedcount = '".$ps['BouncedCount']."'',clickedcount = '".$ps['ClickedCount']."', contactid = '".$ps['ContactID']."', deferredcount = '".$ps['DeferredCount']."', deliveredcount = '".$ps['DeliveredCount']."',hardbouncedcount = '".$ps['HardBouncedCount']."',  lastactivityat = '".$ps['LastActivityAt']."',  marketingcontacts =  '".$ps['MarketingContacts']."', openedcount =  '".$ps['OpenedCount']."', prequeuedcount = '".$ps['PreQueuedCount']."', processedcount = '".$ps['ProcessedCount']."', queuedcount = '".$ps['QueuedCount']."', softbouncedcount = '".$ps['SoftBouncedCoun']."' spamcomplaintcount = '".$ps['SpamComplaintCount']."', unsubscribedcount = '".$ps['UnsubscribedCount']."', usermarketingcontacts = '".$ps['UserMarketingContacts']."', workflowexitedcount = '".$ps['WorkFlowExitedCount']."' ;") or die("Could not execute this insert statement: ".pg_last_error());

			# Delete all records from account which where not present in json file by importtime

			//pg_query($dbconn, "DELETE FROM $POSTGRE_TABLE WHERE accountname = '$accountName' AND importtime < '$importtime'") or die("Could not execute this delete statement: ".pg_last_error());

	  

		}
	}
/*
	
		
	








	ListrecipientStatiustics:
	 array(17) {
    ["BlockedCount"]=>
    int(0)
    ["BouncedCount"]=>
    int(0)
    ["ClickedCount"]=>
    int(12)
    ["Data"]=>
    array(2) {
      [0]=>
      array(2) {
        ["Name"]=>
        string(6) "source"
        ["Value"]=>
        string(6) "widget"
      }
      [1]=>
      array(2) {
        ["Name"]=>
        string(5) "ssoid"
        ["Value"]=>
        string(7) "2940809"
      }
    }
    ["DeferredCount"]=>
    int(170)
    ["DeliveredCount"]=>
    int(170)
    ["HardBouncedCount"]=>
    int(0)
    ["LastActivityAt"]=>
    string(20) "2019-10-18T06:33:29Z"
    ["ListRecipientID"]=>
    int(1825505128)
    ["OpenedCount"]=>
    int(163)
    ["PreQueuedCount"]=>
    int(0)
    ["ProcessedCount"]=>
    int(170)
    ["QueuedCount"]=>
    int(170)
    ["SoftBouncedCount"]=>
    int(0)
    ["SpamComplaintCount"]=>
    int(0)
    ["UnsubscribedCount"]=>
    int(0)
    ["WorkFlowExitedCount"]=>
    int(0)
  }




		

	
*/
?>