<?php
/*
Retrieve all contacts:
*/
require '../lib/mailjet-apiv3/vendor/autoload.php';
use \Mailjet\Resources;

include_once('../conf/mailjet.conf'); 
include_once('mailjetFunctions.php'); 



//$mailToSeachfor = 'milan.bargiel@zeit.de';
$mailToSeachfor = $sEmail;
$mailjetHtml = '';
// iterate through all API-Keys set in mailjet.conf
for($i=0; $i<count($MJ_KEYS); $i++)
{
	$MJ_APIKEY_PUBLIC = $MJ_KEYS[$i]['APIKey'];
	$MJ_APIKEY_PRIVATE= $MJ_KEYS[$i]['SecretKey'];	
//	find email address in address lists

	$result = findContactinLists($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE,$mailToSeachfor);

// iterate through result set and query details


	for($hit=0; $hit<count($result);$hit++)
	{

		$details = findContactDetails($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE, $result[$hit]['ContactID']);
		$result[$hit]['Details'] = $details[0];

	}
/*	
echo count($result)." entries ";
var_dump($result);

echo "<br>";
*/

	if (count($result) > 0 && $result[0]['Details'] != NULL)
	{
		

		$mailjetHtml .= "<tr class='$modulo'>
			<td class='hand' border='1' title='MailJet Mandant: ". $MJ_KEYS[$i]['Name']."'><b>".$MJ_KEYS[$i]['Name']."</b></td>
			<td width='850'><table cellspacing='0' border='0'>";

 	 	foreach ($result as $row)
 	 	{
			//array_map('htmlentities', $row);
			$row['MJAPIKEYName'] = urlencode($MJ_KEYS[$i]['Name']);

			if($row['IsUnsubscribed'] === TRUE){
				$row['subscribed'] = 'Ja <br> WIEDERBELEBEN';
			}elseif($row['IsUnsubscribed'] === FALSE){
				$row['subscribed'] = 'Nein';
			}

			if($row['IsActive'] === TRUE){
				$row['IsActive'] = 'Ja <br> LÖSCHEN';
			}elseif($row['IsActive'] === FALSE){
				$row['IsActive'] = 'Nein';
			}


			if($row['Details']['IsExcludedFromCampaigns'] === FALSE){
				$row['Details']['IsExcludedFromCampaigns'] = '--';
			}elseif(['Details']['IsExcludedFromCampaigns'] === TRUE){
				$row['Details']['IsExcludedFromCampaigns'] = 'WIEDERBELEBEN';
			}

			

			$mailjetHtml .="<tr onmouseover=\"this.className='hl'\"' onmouseout=\"this.className='$modulo'\"'>
				<td width='400' class='hand' title='AdresslistenName'>".$row['ListName']." </td>";
			
			$mailjetHtml .= "<td width='100' class='hand' align='center' title='Accountdaten löschen'><a href='index.php?main=$main&kategorie=$kategorie&todo=$todo&aktion=delete&mailjet=".$row['ID']."&ListName=".$row['ListName']."&mandant=".$row['MJAPIKEYName']."' class='link' target='_new' onclick='return confirm(\"Der Maileintrag wird vollständig entfernt\");'>".$row['IsActive']."</a><br></td>";
			
			$mailjetHtml .= "<td width='100' class='hand' align='center' title='Versand wieder beleben'><a href='index.php?main=$main&kategorie=$kategorie&todo=block&mailjet=".$row['ID']."&mandant=".$row['MJAPIKEYName']."&ListName=".$row['ListName']."&aktion=unblock' class='link' target='_new' onclick='return confirm(\"Wirklich durchführen?\");'>".$row['Details']['IsExcludedFromCampaigns']."</a></td>";

			$mailjetHtml .= "<td width='150' class='hand' align='center'>
				<a href='index.php?main=$main&kategorie=$kategorie&todo=$todo&aktion=subscriptionchange&mailjet=".$row['ID']."&ListName=".$row['ListName']."&mandant=".$row['MJAPIKEYName']."&IsUnsubscribed=".$row['IsUnsubscribed']."' class='link' target='_new' onclick='return confirm(\"Wirklich Subscriptionstatus (ändern)?\");'>".$row['subscribed']."</a></td>";

			$mailjetHtml .= "</td><td width='100' class='hand' title='Bouncelimit ist nicht erricht, die Zustellung wird nicht durch das Bouncelimit verhindert'>--</td></tr>";
		    		

	 	} 
	 	$mailjetHtml .= "</table></td></tr>";	
	  		
		
	}
	unset($result);
	 
}



?>

