CREATE TABLE contact (unsubscribedby CHARACTER VARYING(250), unsubscribedat CHARACTER VARYING(20), name CHARACTER VARYING(250), lastupdateat CHARACTER VARYING(20), lastactivityat CHARACTER VARYING(20), isspamcomplaining CHARACTER VARYING(5), isoptinpending CHARACTER VARYING(5), isexcludedfromcampaigns CHARACTER VARYING(5), id BIGINT, exclusionfromcampaignsupdatedat CHARACTER VARYING(20), email CHARACTER VARYING(250), deliveredcount BIGINT, createdat CHARACTER VARYING(20), accountname CHARACTER VARYING(250));

ALTER TABLE contact ADD CONSTRAINT accountname_email UNIQUE (email, accountname);






pg_query($dbconn, "INSERT INTO $POSTGRE_TABLE (accountname,createdat,deliveredcount,email,exclusionfromcampaignsupdatedat,id,isexcludedfromcampaigns,isoptinpending,isspamcomplaining,lastactivityat,lastupdateat,name,unsubscribedat,unsubscribedby) VALUES ( '".$accountName."', '".$p['CreatedAt']."', '".$p['DeliveredCount']."', '".$p['Email']."', '".$p['ExclusionFromCampaignsUpdatedAt']."','".$p['ID']."', '".$p['IsExcludedFromCampaigns']."', '".$p['IsOptInPending']."', '".$p['IsSpamComplaining']."', '".$p['LastActivityAt']."', '".$p['LastUpdateAt']."', '".$p['Name']."', '".$p['UnsubscribedAt']."', '".$p['UnsubscribedBy']."') ON CONFLICT ON CONSTRAINT accountname_email DO UPDATE SET accountname = '".$accountName."', createdat = '".$p['CreatedAt']."', deliveredcount = '".$p['DeliveredCount']."', email = '".$p['Email']."', exclusionfromcampaignsupdatedat = '".$p['ExclusionFromCampaignsUpdatedAt']."', id = '".$p['ID']."', isexcludedfromcampaigns = '".$p['IsExcludedFromCampaigns']."', isoptinpending = '".$p['IsOptInPending']."', isspamcomplaining = '".$p['IsSpamComplaining']."', lastactivityat = '".$p['LastActivityAt']."', lastupdateat = '".$p['LastUpdateAt']."', name = '".$p['Name']."', unsubscribedat = '".$p['UnsubscribedAt']."', unsubscribedby = '".$p['UnsubscribedBy']."' ;") or die("Could not execute this insert statement: ".pg_last_error());






