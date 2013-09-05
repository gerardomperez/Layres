<?php

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Skin.php';
	require_once 'Inc/Inc_Functions_Database.php';
	
// -------------------------------
// -- List of Functions
// -------------------------------
/*

	function ExpirePromotion($PromoID) { }
	function ConsumeReward($PromoID) { }	
	function HideReward($PromoID) {
	function UnHideReward($PromoID) {
		
	function ShowSponsoredPromos($Show) {}
	function ShowMyMarkers($Show,$Toggled) {}
	function ShowMyEmptyLayers() {}
	function ShowEarnedRewards($OrderBy, $Show="") {
	function ShowNearbyPromos($OrderBy) {}
	function SearchResults($SearchQuery) {}
	
	function ShowProgressPromos($OrderBy)  {	
	function ShowPromoResults($PromoID) {	
	function ShowPromoRewards($PromoID) {		
	function EditPromoForm($PromoID) {		
	function ShowPromo($PromoID,$IncludeForm = "") {

	function Validate_CheckIn($PromoID) {	
	function ShowPromo_ThankYouFailed($Reason) {
	function ShowPromo_ThankYou($PromoID, $Reason = "") {		
	
	function DetermineStatus($PromoID) {
	function LocationDetails($LocationID)	{
	function ClearPromotionData() {
	function CheckInCount($PromotionID)  {}
	function CouponExpiration ($PromoID, $Format="Raw") {}
	function CreatePromotionsJS($Step)  {}
	function CreateMarker($MarkerDetails) {}
	
	function ActivityFeed($Show,$SearchType,$SearchQuery) {}
		
*/


// -------------------------------
// -- Promotion functions
// -------------------------------

	function ExpirePromotion($PromoID) {
		
			$CurrentTime = date('Y/m/d G:i:s');

			$sql_ExpirePromo = "UPDATE Promotions SET EndDate='{$CurrentTime}' WHERE UName='{$_SESSION['UName']}' AND PromotionID='{$PromoID}' LIMIT 1";
			$Result_ExpirePromo = Update($sql_ExpirePromo);	
			
			if ($Result_ExpirePromo) {
				$sql_ExpirePromo2 = "UPDATE Location_Promotions SET EndDate='{$CurrentTime}' WHERE PromotionID='{$PromoID}' LIMIT 1";
				$Result_ExpirePromo2 = Update($sql_ExpirePromo2);				
			}
			
			if ($Result_ExpirePromo AND $Result_ExpirePromo) { return TRUE; }
			else { return FALSE;}
						
		}


	function ConsumeReward($RewardWonID) { 
	
			$CurrentTime = date('Y/m/d G:i:s');
	
			$sql_ConsumeReward = "UPDATE RewardsWon SET Hide='Yes', UsedDate='{$CurrentTime}' WHERE UName='{$_SESSION['UName']}' AND RewardWonID={$RewardWonID} LIMIT 1";
			$Result_ConsumeReward = Update($sql_ConsumeReward);			
			
			if ($Result_ConsumeReward) { return TRUE; }
			else { return FALSE;}
	}	

	function HideReward($PromoID) {

			$sql_HideReward = "UPDATE RewardsWon SET Hide='Yes' WHERE UName='{$_SESSION['UName']}' AND RewardWonID='{$PromoID}' LIMIT 1";
			$Result_HideReward = Update($sql_HideReward);	
			
			if ($Result_HideReward) { return TRUE; }
			else { return FALSE;}
						
		}

	function UnHideReward($PromoID) {

			$sql_HideReward = "UPDATE RewardsWon SET Hide='No' WHERE UName='{$_SESSION['UName']}' AND RewardWonID='{$PromoID}' LIMIT 1";
			$Result_HideReward = Update($sql_HideReward);	
			
			if ($Result_HideReward) { return TRUE; }
			else { return FALSE;}
						
		}

	function DetermineStatus($PromoID) {
			if (!$PromoID) { return "-";}
		
			$sql_StatusData = "SELECT * FROM Promotions, Promotions_Rewards_Status,RewardsWon 
									WHERE Promotions.PromotionID = RewardsWon.PromotionID
									  AND Promotions.UName = Promotions_Rewards_Status.UName 
									  AND Promotions.PromotionID = {$PromoID}
									  AND RewardsWon.UName = '{$_SESSION['UName']}'";	
			$Result_StatusData = Select($sql_StatusData);

			$StatusLevel = count($Result_StatusData);
			
			if ($StatusLevel == 0) { 
				// Since no results, you don't know what next status level is.
				$sql_StatusLevelData = "SELECT * FROM Promotions, Promotions_Rewards_Status
											WHERE Promotions.RewardID = Promotions_Rewards_Status.RewardID
											AND Promotions.PromotionID = {$PromoID}";
				$Result_StatusLevelData = Select($sql_StatusLevelData);
				
				$FirstStatusLevel = $Result_StatusLevelData[0]['StatusLevel1'];
			
				$StatusArray = array('CurrentStatus' => "No Status Yet", 'NextStatus' => $FirstStatusLevel); }
			else if ($StatusLevel == 1) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel1'], 'NextStatus' => $Result_StatusData[0]['StatusLevel2']); }
			else if ($StatusLevel == 2) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel2'], 'NextStatus' => $Result_StatusData[0]['StatusLevel3']); }
			else if ($StatusLevel == 3) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel3'], 'NextStatus' => $Result_StatusData[0]['StatusLevel4']); }
			else if ($StatusLevel == 4) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel4'], 'NextStatus' => $Result_StatusData[0]['StatusLevel5']); }
			else if ($StatusLevel == 5) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel5'], 'NextStatus' => $Result_StatusData[0]['StatusLevel6']); }
			else if ($StatusLevel == 6) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel6'], 'NextStatus' => $Result_StatusData[0]['StatusLevel7']); }
			else if ($StatusLevel == 7) { 
				$StatusArray = array('CurrentStatus' => $Result_StatusData[0]['StatusLevel7'], 'NextStatus' => "Off The Charts!"); }
			else { 
				$StatusArray = array('CurrentStatus' => "Off The Charts!", 'NextStatus' => "Majorly Uncharted Territory"); }

			if ($StatusArray['CurrentStatus'] == "") { $StatusArray['CurrentStatus'] = "Off The Charts! <strong>({$StatusLevel} Check-ins)</strong>";}
			if ($StatusArray['NextStatus'] == "") { $StatusArray['NextStatus'] = "Off The Charts!";}

			return $StatusArray;

		}


	function ShowSponsoredPromos($Show) {
		    $CurrentTime = date('Y/m/d G:i:s');

			// Get Promotion Data
			$sql_PromotionData = "SELECT * FROM Promotions, Location, Location_Promotions 
								  WHERE Promotions.UName = '{$_SESSION['UName']}'
									  AND Promotions.PromotionID = Location_Promotions.PromotionID
									  AND Location.LocationID = Location_Promotions.LocationID";
									  
			if ($Show == "Active")  { $sql_PromotionData .= " AND Promotions.EndDate > '{$CurrentTime}' "; }
			elseif ($Show == "Expired") { $sql_PromotionData .= " AND Promotions.EndDate < '{$CurrentTime}' "; }
			else {  $sql_PromotionData .= " AND Promotions.EndDate > '{$CurrentTime}'"; }
			
			if ($Show == "Location") {  $sql_PromotionData .= " ORDER BY LocationName"; }
			elseif ($Show == "StartDate") {  $sql_PromotionData .= " ORDER BY Promotions.StartDate"; }
			else {  $sql_PromotionData .= " ORDER BY Promotions.StartDate"; }
			
		  	$Result_PromotionData = Select($sql_PromotionData);
		  
		    return SponsoredPromosUI($Result_PromotionData);
		  	
		}
	
	function ShowMyMarkers($Show,$Toggled="") {
		    $CurrentTime = date('Y/m/d G:i:s');

			// Get Promotion Data
			$sql_PromotionData = "SELECT Layers.Description, LayerName, Layers.LayerID, Promotions.PromotionID, Location.LocationID, TaskType, PromoTitle, TaskDescription, Promotions.StartDate, Promotions.EndDate, Promotions.PromoRange 
								  FROM Location, Location_Promotions, Promotions, Layers 
								  WHERE Promotions.UName = '{$_SESSION['UName']}'
									  AND Promotions.PromotionID = Location_Promotions.PromotionID
									  AND Layers.LayerID = Location_Promotions.LayerID
									  AND Location.LocationID = Location_Promotions.LocationID";									  
									  
			if ($Show == "Active")  { $sql_PromotionData .= " AND Promotions.EndDate > '{$CurrentTime}' "; }
			elseif ($Show == "Expired") { $sql_PromotionData .= " AND Promotions.EndDate < '{$CurrentTime}' "; }
			else {  $sql_PromotionData .= " AND Promotions.EndDate > '{$CurrentTime}'"; }
			
			if ($Show == "Location") {  $sql_PromotionData .= " ORDER BY LocationName"; }
			elseif ($Show == "StartDate") {  $sql_PromotionData .= " ORDER BY Promotions.StartDate"; }
			else {  $sql_PromotionData .= " ORDER BY Layers.LayerID DESC, Promotions.StartDate"; }
			
			//echo $sql_PromotionData;
			
		  	$Result_PromotionData = Select($sql_PromotionData);
			
		    return MyMarkersUI($Result_PromotionData,$Toggled);
		  	
		}


	function ShowMyEmptyLayers() {
		    $CurrentTime = date('Y/m/d G:i:s');

			// Get Promotion Data
			$sql_PromotionData = "SELECT LayerName, Layers.LayerID, Description, COUNT(*) AS 'MarkerCount' 
									FROM Layers LEFT JOIN Location_Promotions 
									ON Layers.LayerID = Location_Promotions.LayerID  
									
									WHERE Layers.UName = '{$_SESSION['UName']}'
									GROUP BY Layers.LayerID"; 
			
			//echo $sql_PromotionData;
			
		  	$Result_PromotionData = Select($sql_PromotionData);
		
		  //	print_r($Result_PromotionData);
		  	
		    return ShowMyEmptyLayersUI($Result_PromotionData);
		  	
		}


	function ShowLayerPromos($LayerID) {

			$sql_PromotionData = "SELECT Layers.Description, LayerName, Layers.LayerID, Promotions.PromotionID, PromoRange, Location.LocationID, 
										TaskType, PromoTitle, TaskDescription, Promotions.StartDate, Promotions.EndDate 
									FROM Promotions, Location_Promotions, Location, Layers 
									WHERE Promotions.PromotionID = Location_Promotions.PromotionID 
										AND Layers.LayerID = Location_Promotions.LayerID 
										AND Location.LocationID = Location_Promotions.LocationID 
										AND Location_Promotions.LayerID='{$LayerID}' ORDER BY PromoTitle";
			$Result_PromotionData = Select($sql_PromotionData);

		    return MyMarkersUI($Result_PromotionData);
		  	
		}


	function ShowEarnedRewards($OrderBy, $Show="") {

			$ThisPage = basename($_SERVER['SCRIPT_NAME']);
			
			if (isset($_GET['OrderBy'])) { $OrderBy = $_GET['OrderBy'];}
			$CompletedPromotions = "";
		
			$sql_CompletedPromotions = "SELECT * FROM RewardsWon, Promotions, Users 
												WHERE RewardsWon.UName='{$_SESSION['UName']}'
												AND RewardsWon.PromotionID = Promotions.PromotionID 
												AND Promotions.UName = Users.UName"; 

			if ($Show == "Hidden") {	 		$sql_CompletedPromotions .= " AND RewardsWon.Hide = 'Yes' AND RewardsWon.UsedDate = '0000-00-00 00:00:00'"; }
			elseif ($Show == "Consumed") {		$sql_CompletedPromotions .= " AND RewardsWon.Hide = 'Yes' AND RewardsWon.UsedDate <> '0000-00-00 00:00:00	'"; }
			else {								$sql_CompletedPromotions .= " AND RewardsWon.Hide = 'No'"; }
			
			$sql_CompletedPromotions .= "		GROUP BY RewardsWon.PromotionID";
												
			if ($OrderBy == "Vendor")	{ 		$sql_CompletedPromotions .= " ORDER BY Promotions.UName"; }				 
			elseif ($OrderBy == "Expiration")	{ $sql_CompletedPromotions .= " ORDER BY Promotions.RewardType, Promotions.EndDate"; }				//  XXX not the real Expiration Date 
			else {  							$sql_CompletedPromotions .= " ORDER BY Users.FullName"; }											
												
			// $sql_CompletedPromotions;
			
			$Result_CompletedPromotions = Select($sql_CompletedPromotions);
			
			return EarnedRewardsUI($Result_CompletedPromotions);	
		
		}

	function ShowNearbyPromos($OrderBy, $Layer="", $Zip="33145") {
			
			$PromoRange = "('All')";	// Modify this to get subset of promotions  (All | Personal | Private) ... will need to get fancier in SQL to show specialized demographic subsections
			
			// ----------------------
			// ------  Main SQL Statement
			// ----------------------		
								// Necessary Data	
			$sql_LocationData = "SELECT Promotions.PromotionID, Promotions.UName, Promotions.EndDate, 
										DATE_FORMAT(Promotions.CreateDate, '%l:%i (%M %e)') AS CreateDate, 
										DATE_FORMAT(Promotions.CreateDate, '%M %e') AS PromoDay, 
										DATE_FORMAT(Now(), '%M %e') AS TodaysDay, 
										PromoTitle, TaskType, RewardDescription, TaskDescription, RewardType, 
										 LocationName, Location.LocationLatitude, Location.LocationLongitude, 
										 Location.StreetAddress, Location.Zip, Location.Phone ";
										 
			$sql_LocationData .= "FROM Promotions, Users, Location, Location_Promotions  ";
		
								// Joing tables
			$sql_LocationData .= "WHERE Promotions.PromotionID = Location_Promotions.PromotionID 
									AND Location.LocationID = Location_Promotions.LocationID 
									AND Users.UName = Promotions.UName ";
		
								// Promo dates are valid							   
			$sql_LocationData .= "  AND Promotions.StartDate < Now()
									AND Promotions.EndDate > Now() ";
						  
								// There is still quantity left	  
			$sql_LocationData .= " 	AND Location_Promotions.PromoQuantity > Location_Promotions.PromosEarned ";
				
		
			// ----------------------
			// ------  Modifications
			// ----------------------		  
			
			if ($Layer) {  $sql_LocationData .= "  AND Promotions.UName = '{$Layer}'"; }
			elseif (isset($_SESSION['UName'])) { 											
		
				   $sql_LocationData .= "AND ( Promotions.PromoRange IN {$PromoRange} 
											   OR Promotions.UName IN (Select Invitee FROM FriendsList WHERE Invitor='{$_SESSION['UName']}')  
											   OR Promotions.UName='{$_SESSION['UName']}') ";  // show your own promotions in your map
					 			
			} else { // show only promotions that are in "All" range
					$sql_LocationData .= "AND Promotions.PromoRange IN {$PromoRange} ";
			}	
			
		  	$Result_LocationData = Select($sql_LocationData);
		  
		    return NearbyPromosUI($Result_LocationData);
		
		}


	function SearchResults($SearchQuery) {
			$PromoRange = "('All')";	// Modify this to get subset of promotions  (All | Personal | Private) ... will need to get fancier in SQL to show specialized demographic subsections
			
			// ----------------------
			// ------  Main SQL Statement
			// ----------------------		
								// Necessary Data	
			$sql_SearchData = "SELECT Promotions.PromotionID, Promotions.UName, Promotions.EndDate, 
										DATE_FORMAT(Promotions.CreateDate, '%l:%i (%M %e)') AS CreateDate, 
										DATE_FORMAT(Promotions.CreateDate, '%M %e') AS PromoDay, 
										DATE_FORMAT(Now(), '%M %e') AS TodaysDay, 
										PromoTitle, TaskType, RewardDescription, TaskDescription, RewardType, 
										 LocationName, Location.LocationLatitude, Location.LocationLongitude, 
										 Location.StreetAddress, Location.Zip, Location.Phone ";
										 
			$sql_SearchData .= "FROM Promotions, Users, Location, Location_Promotions  ";
		
								// Joing tables
			$sql_SearchData .= "WHERE Promotions.PromotionID = Location_Promotions.PromotionID 
									AND Location.LocationID = Location_Promotions.LocationID 
									AND Users.UName = Promotions.UName ";
		
								// Promo dates are valid							   
			$sql_SearchData .= "  AND Promotions.StartDate < Now()
									AND Promotions.EndDate > Now() ";
						  
								// There is still quantity left	  
			$sql_SearchData .= " 	AND Location_Promotions.PromoQuantity > Location_Promotions.PromosEarned ";
				
		
			// ----------------------
			// ------  Modifications
			// ----------------------		  
		
		   $sql_SearchData .= "AND (Promotions.UName LIKE '%{$SearchQuery}%'
									   OR Users.EmailAddress LIKE '%{$SearchQuery}%'
									   OR Location.LocationName LIKE '%{$SearchQuery}%'
									   OR Promotions.PromoTitle LIKE '%{$SearchQuery}%') ";  // show your own promotions in your map
					 			

			//return $sql_SearchData;
			
		  	$Result_SearchData = Select($sql_SearchData);
		  
		  	if (count($Result_SearchData)) { return NearbyPromosUI($Result_SearchData, "Yes"); }
			else { return "<div align='center' style='Padding:20px 0;'>Sorry, no results found.</div>"; }
		}

	function ShowProgressPromos($OrderBy)  {

			$ThisPage = basename($_SERVER['SCRIPT_NAME']);
			$ProgressPromotions = "";
	
			$sql_ProgressPromotions = "SELECT *,CheckIns.CreateDate FROM CheckIns, Promotions, Location_Promotions, Layers, Location
											WHERE 
											 CheckIns.PromotionID = Location_Promotions.PromotionID 
											AND Location_Promotions.PromotionID = Promotions.PromotionID 
											AND Layers.LayerID =Location_Promotions.LayerID
											AND Location.LocationID=Location_Promotions.LocationID 
											GROUP BY Location_Promotions.PromotionID  ORDER BY Promotions.CreateDate DESC";
											
/* 			if ($OrderBy == "Proximity")  		{  }
			else if ($OrderBy == "Expiration")	{  } */
			
			 							//  $sql_ProgressPromotions .= ""; 											
											
			//echo $sql_ProgressPromotions;
			
			$Result_ProgressPromotions = Select($sql_ProgressPromotions);
			
			// loop through each earned promotion
			if (count($Result_ProgressPromotions)) { 
			  $num = 0; 
				
			  foreach ($Result_ProgressPromotions as $results) {
					  $CheckInCount = CheckInCount($results['PromotionID']);	  	  	
					//  print_r($results);
//echo $results['MaxCheckInNumber'];
// $results['MaxCheckInNumber'] > $CheckInCount 

					  if ( $CheckInCount ) {		// if there are still pending potential check-ins
						$num++;
						//$EndDate = FormatDate($results['EndDate']);
						$ProgressPromotions .= "  
								<li style=\"padding-bottom:10px;list-style:none;\">
									<h3 class='Orange'> <a href='Layers.php?ID={$results['LayerID']}'>{$results['LayerName']} </a> ( {$results['PromoTitle']} )	</h3>	
									<i>{$results['LocationName']}</i> &nbsp; &nbsp; 
									<span class='SmallFont'> {$results['CreateDate']} </span>
								
								</li>	
								"; 
					  }		
	
				}   // End Foreach record
	
			// if no records are found
			} else { $ProgressPromotions .= "<div align='center' style='padding:30px;'>No promotions have been earned yet.</div>"; 		  }		
			
			return $ProgressPromotions;
	
		}

	function ShowPromoResults ($PromoID) {

			$sql_PromotionResultsData = "SELECT PromoTitle, TaskType, UserResponse, FullName, EmailAddress, CheckIns.CreateDate FROM Users, CheckIns, Promotions
								  	  WHERE CheckIns.UName = Users.UName
									  AND Promotions.PromotionID = CheckIns.PromotionID
									  AND CheckIns.PromotionID = {$PromoID}
									  ORDER BY CheckIns.CreateDate desc";
		
			$Result_PromotionResultsData = Select($sql_PromotionResultsData);

			return PromoResultsUI($Result_PromotionResultsData);

		}

	function ShowPromoRewards ($PromoID) {

			$sql_PromotionResultsData = "SELECT PromoTitle, FullName, EmailAddress, UsedDate, WonDate FROM Users, RewardsWon, Promotions
								  	  WHERE RewardsWon.UName = Users.UName
									  AND Promotions.PromotionID = RewardsWon.PromotionID
									  AND RewardsWon.PromotionID = {$PromoID}
									  ORDER BY RewardsWon.WonDate desc";
		
			$Result_PromotionResultsData = Select($sql_PromotionResultsData);

			return PromoRewardsUI($Result_PromotionResultsData);

		}

	function EditPromoForm($PromoID) {

			// Get Promotion Data
			$sql_PromotionData = "SELECT * FROM Promotions, Location, Location_Promotions 
								  WHERE Promotions.UName = '{$_SESSION['UName']}'
									  AND Promotions.PromotionID = {$PromoID}
									  AND Promotions.PromotionID = Location_Promotions.PromotionID
									  AND Location.LocationID = Location_Promotions.LocationID"; 
			
		  	$Result_PromotionData = Select($sql_PromotionData);
		  
		    return EditPromoFormUI($Result_PromotionData);
			
		}

	function SubmitEditPromo($UpdatedDataArray) {	
	
			return "Promotion has been updated. (Pending creation of function)";	
		}
	
	function ShowPromo($PromoID,$IncludeForm = "") {

			$ThisPage = basename($_SERVER['SCRIPT_NAME']);
			if (isset($_GET['PID'])) { $PromoID = $_GET['PID']; }
		
			$sql_PromotionData = "SELECT * FROM Promotions, Location, Location_Promotions, Users 
								  WHERE Promotions.PromotionID = Location_Promotions.PromotionID
								  	  AND Promotions.UName = Users.UName
									  AND Location.LocationID = Location_Promotions.LocationID
									  AND Promotions.PromotionID = {$PromoID}";
			$Result_PromotionData = Select($sql_PromotionData);
			
			if (!$Result_PromotionData) { return "<div class='PromoDetails'>
													  <h3 class='Red'>Houston, we have a problem!</h3>
													  <hr>
													  <h3>Promotion details were not found!</h3>
													  <p>Please go back to map and search for a different promotion.</p>
												  </div>";}
			else { return PromoUI($Result_PromotionData[0],$IncludeForm); }

		}


	function Validate_CheckIn($PromoID) {
			
		//-- 1. Check if logged in
				if (!isset($_SESSION['UName'])) { return "NOT_LOGGED_IN"; }	
	
				// Get promo details
				$sql_PromotionDetails = "SELECT * FROM Promotions, Location_Promotions 
											WHERE Promotions.PromotionID = Location_Promotions.PromotionID 
											  AND Promotions.PromotionID = {$PromoID}";	 
				$Results_PromotionDetails = select($sql_PromotionDetails);
				$PromotionDetailsArray = $Results_PromotionDetails[0];
				
				
	
		//-- 2. Any still available? Have all the promotions been claimed?  
				if ($PromotionDetailsArray['PromosEarned'] >= $PromotionDetailsArray['PromoQuantity']) { return "PROMO_CONSUMED"; }
	
		//-- 3. Confirm that check-in is during valid time
				$CurrentTime = strtotime(date("Y-m-d H:i:s"));   // convert to seconds to accurately compare
				$StartDate = strtotime($PromotionDetailsArray['StartDate']);
				$EndDate = strtotime($PromotionDetailsArray['EndDate']);
				
				if ($CurrentTime > $StartDate AND $CurrentTime < $EndDate) {
	
					// check further if it's an Appointment task
					if ($PromotionDetailsArray['TaskType'] == "Appointment") {
							
							$sql_AppointmentData = "SELECT * FROM Promotions, Promotions_Task_Appointment 
													WHERE Promotions.TaskID = Promotions_Task_Appointment.TaskID 
													  AND Promotions.PromotionID = {$PromoID}";	
							$Result_AppointmentData = Select($sql_AppointmentData);
							
							$Today = date("l");
						
							// Check that check-in is during promo hours	
							if (!is_numeric(strpos($Result_AppointmentData[0]['ValidDays'],$Today))) { return "WRONG_DAY"; }
							 else { 
									// Check time of day
								//$PromotionDetailsArray['DayStartTime']	
								//$PromotionDetailsArray['DayEndTime']
	
								//return "NOT_TIME";
							 }
						}
					
				} else {
					// should not occur since we're not going to show promos that are not current on the map....but just in case someone bookmarked promo detail page
					return "PROMO_EXPIRED";					
				}
	
		//-- 4. Check proximity to promotion
				//Handle this in JavaScript form / submission validation
			
		//-- 5. Check if person can still check into the promo
				// Count CheckIn number
				$sql_CheckinCheck = "SELECT * FROM CheckIns
									 WHERE PromotionID = {$PromoID}
										AND UName = '{$_SESSION['UName']}' 
										ORDER BY CreateDate DESC";
	
				$Results_CheckinCheck = select($sql_CheckinCheck);
				$CheckIn_Num = count($Results_CheckinCheck);
				
				//Compare with max possible check-ins for this user 
				if ($PromotionDetailsArray['RewardType'] == "Status") {
					// XXX allow them to check in multiple times.	
					//	if ($PromotionDetailsArray['MaxCheckInNumber'] <= $CheckIn_Num) { return "MAX_CHECKIN"; } 	  
				} elseif ($CheckIn_Num) { return "PROMO_EARNED"; }
				
				// Determine if they've logged in within the same day
				if ($CheckIn_Num) {				
					$DaySeconds = 12 * 60 * 60;  // only allow one log in within a 12 hour period
					if (strtotime($Results_CheckinCheck[0]['CreateDate']) + $DaySeconds > $CurrentTime) { return "TOO_RECENT"; }
				}
	
		//-- 6. If eligible, log check-in in database
			    if (!isset($_POST['Response'])) { $Response = "-";}
				else  { 
					// Sanitize data before database entry
					 OpenDatabase();
			
						 $Response = Sanitize_Data($_POST['Response']);
	
					 CloseDatabase();
					
					}

				$LocationLatitude = "1.1";
				$LocationLongitude = "1.1";
				
				$sql_LogCheckIn = "INSERT INTO CheckIns (UName, PromotionID, UserResponse, LocationLatitude, LocationLongitude)
												VALUES ('{$_SESSION['UName']}',{$PromoID},'{$Response}','{$LocationLatitude}','{$LocationLongitude}')";
				$Success = Insert($sql_LogCheckIn);
				
				// update promotions table with a new hit
				if (!$Success) { return "FAILED_CHECK_IN"; } 
				
	
			  // 7. Check to see if promo earned 
			  if ($PromotionDetailsArray['TaskType'] == "Progress") {    
			  
				  //Determine if they've made enough progress to earn the promotion   
				  $CheckIn_Num2 = $CheckIn_Num++;
				  
				  if ($CheckIn_Num2 % $PromotionDetailsArray['CheckInNumber']) { $PromoEarned = FALSE; }
					else { $PromoEarned = TRUE; }
					
			  } else { $PromoEarned = TRUE; }
			  
		//-- 8. Register the earned promotion
			  if ($PromoEarned) {
				  // Update promotions table
				  $PromosEarnedUpdate = $PromotionDetailsArray['PromosEarned'] + 1;
				  $HitsUpdate = $PromotionDetailsArray['Hits'] + 1;
				  $sql_UpdatePromotion = "UPDATE Location_Promotions SET PromosEarned={$PromosEarnedUpdate}, Hits={$HitsUpdate} 
				  							WHERE PromotionID={$PromoID}
												AND LocationID={$PromotionDetailsArray['LocationID']}  Limit 1";
				  $UpdatePromoYes = Update($sql_UpdatePromotion);
				  
				  if (!$UpdatePromoYes) { return "FAILED-EARNED-ERROR1"; }
				  
				  // Update Rewards won table
				  $sql_UpdateRewards = "INSERT INTO RewardsWon(UName, Hide, PromotionID) 
				  									VALUES ('{$_SESSION['UName']}','No',{$PromoID})";
				  $EarnPromoYes = Insert($sql_UpdateRewards);
				  
				  if ($EarnPromoYes) { return "-VALID-EARNED"; }
				   else { return "FAILED-EARNED-ERROR2"; }
				   
			  } else {  //just update the Hits column for promo
				  $HitsUpdate = $PromotionDetailsArray['Hits'] + 1;
				  $sql_UpdatePromotion = "UPDATE Location_Promotions SET Hits={$HitsUpdate} 
				  							WHERE PromotionID={$PromoID}
											AND LocationID={$PromotionDetailsArray['LocationID']}  Limit 1";
				  $UpdatePromoYes = Update($sql_UpdatePromotion);			  
				  
				  if (!$UpdatePromoYes) { return "FAILED-UPDATE-HITS"; }
				  
				  return "-VALID-NOTYET"; 
			   }
		  
		}

	function ShowPromo_ThankYouFailed($Reason) {

			$Message_Promotions = "<div class='PromoDetails'>
								  <h3 class='Red'>Unable to check you in.</h3>
								  <hr>
								  <p>";

			// User Errors
			if ($Reason == "NOT_LOGGED_IN") { $Message_Promotions .= "You must <a href='Login.php' class='BabyBlue'>log in</a> first before you can check in for any promotions."; }
			else if ($Reason == "NOT_CLOSE_ENOUGH") { $Message_Promotions .= "You are not yet close enough to the promotion location to check in."; }
			else if ($Reason == "MAX_CHECKIN") { $Message_Promotions .= "You have already achieved the maximum level of status for this promotion."; }
			else if ($Reason == "WRONG_DAY") { $Message_Promotions .= "The promotion is not available today. Please check the promotion for valid hours."; }
			else if ($Reason == "NOT_TIME") { $Message_Promotions .= "The promotion is not available now. Please check in during the official promotion hours."; }
			else if ($Reason == "TOO_RECENT") { $Message_Promotions .= "You have already signed into this promotion within the past day."; }
			 
			
			// Promo Issues
			else if ($Reason == "PROMO_EARNED") { $Message_Promotions .= "You have already earned this promotion. Check <a href='MyAccount.php?Mode=User'>your account</a> for a list of your promotions."; }		
			else if ($Reason == "PROMO_CONSUMED") { $Message_Promotions .= "All available instances of this promotion have been earned."; }
			else if ($Reason == "PROMO_EXPIRED") { $Message_Promotions .= "This promotion is not available right now."; }
			
			// System Errors
			else if ($Reason == "FAILED_CHECK_IN") { $Message_Promotions .= "There was a problem with our system recording your check-in.  We're very sorry for this inconvenience.  </p><p>Please contact support at <b>support@secondworld.me</b> and provide them error code: PromoID-TimeStamp-LMNOP."; }
			else if ($Reason == "FAILED-EARNED-ERROR1") { $Message_Promotions .= "Our records indicate that you have completed this task, but we were unable to extend you the promotion due to a system glitch. </p><p> Your checkin has been recorded, but we were unable to reduce the number of available promotions.  Please contact
			 customer support to have the problem fixed. "; }
			else if ($Reason == "FAILED-EARNED-ERROR2") { $Message_Promotions .= "Our records indicate that you have completed this task, but we were unable to extend you the promotion due to a system glitch.  </p><p>Your checkin has been recorded.  Please contact customer support to have the reward extended to you."; }
			else if ($Reason == "FAILED-UPDATE-HITS") { $Message_Promotions .= "Your individual check-in was recorded, but there was a problem elsewhere in our system.  We were unable to record a check-in for this promotion."; }
			
			else {$Message_Promotions .= "Value: {$Reason} "; }

			$Message_Promotions .= "</p></div>";
			
			Return $Message_Promotions;
		
		}


	function ShowPromo_ThankYou($PromoID, $Reason = "") {

			$sql_PromotionData = "SELECT * FROM Promotions, Location, Location_Promotions, Users 
								  WHERE Promotions.PromotionID = Location_Promotions.PromotionID
								  	  AND Promotions.UName = Users.UName
									  AND Location.LocationID = Location_Promotions.LocationID
									  AND Promotions.PromotionID = {$PromoID}";
		
			$Result_PromotionData = Select($sql_PromotionData);

			$EndDate = FormatDate($Result_PromotionData[0]['EndDate']);
			$ExpirationDate = ""; //FormatDate($Result_PromotionData[0]['ExpirationDate']);				//  XXX need to only show this for Coupon rewards

					$Message_Promotions = PromoHeaderUI($Result_PromotionData[0],"ThankYou");
					
					$Message_Promotions .= "<h4>Congratulations!</h4>
											  <p align='center'>";

			if ($Reason == "-VALID-EARNED") 	{ $Message_Promotions .= "You have checked in and earned the reward."; }
			else if ($Reason == "-VALID-NOTYET"){ $Message_Promotions .= "You have checked in.  Continue doing so until you earn the reward."; }
			else if ($Reason == "TOO_RECENT"){ $Message_Promotions .= "You can only check in for a promotion once per day.  Please try again another day."; }
			else  								{ $Message_Promotions .= "You have checked in for this promotion."; }

					$Message_Promotions .= "</p>
								  			  <p>{$Result_PromotionData[0]['CongratsMessage']}</p>";
									
					if ($Result_PromotionData[0]['RewardType'] == "Message") {
							$sql_MessageData = "SELECT * FROM Promotions_Rewards_Information WHERE RewardID = {$Result_PromotionData[0]['RewardID']}";
							$Result_MessageData = Select($sql_MessageData);							
						
							$Message_Promotions .= "<div style='padding:10px'><b class='Green'>Message for you:</b> <br> 
													<i>{$Result_MessageData[0]['Information']}</i></div>";
						}						


					// Promo author formatting
					$Message_Promotions .= PromoFooterUI($Result_PromotionData[0]);
			
			Return $Message_Promotions;
		
		}
		

	function LocationDetails($LocationID)	{

			$sql_LocationData = "SELECT * FROM Location WHERE LocationID = {$LocationID}";
			$Result_LocationData = Select($sql_LocationData);

			return $Result_LocationData;
		}

	function ClearPromotionData() {
		
			// Clear all session variables to prevent mixed data in case they start over promo process before finishing previous attempt
			$_SESSION['PromoTitle'] = '';
			$_SESSION['LocationOptions'] = '';
			$_SESSION['Proximity'] = '';
			$_SESSION['StartDate'] = date("M d, Y");
			$_SESSION['EndDate'] = 'Dec 31,2013';
			$_SESSION['ExpirationDate'] = '';
			$_SESSION['TaskType'] = '';
			$_SESSION['RewardType'] = '';
			$_SESSION['PromoQuantity'] = '99';				
			$_SESSION['TaskDescription'] = '';
			$_SESSION['DayStartTime'] = '';
			$_SESSION['DayEndTime'] = '';
			$_SESSION['ValidDays'] = '';
			$_SESSION['CheckInNumber'] = '';
			$_SESSION['MaxCheckInNumber'] = '';
			$_SESSION['Coupon'] = '';
			$_SESSION['CouponName'] = '';
			$_SESSION['CouponTitle'] = '';
			$_SESSION['CouponBody'] = '';
			$_SESSION['CouponFinePrint'] = 'Not valid with other offers.  Must present coupon at time of purchase. Other limitations may apply.';
			$_SESSION['Message'] = '';
			$_SESSION['Question'] = '';
			$_SESSION['MCOptions1'] = '';
			$_SESSION['MCOptions2'] = '';
			$_SESSION['MCOptions3'] = '';
			$_SESSION['MCOptions4'] = '';
			$_SESSION['RewardDescription'] = '';
			$_SESSION['SecretInfo'] = '';
			$_SESSION['StatusLevel1'] = '';
			$_SESSION['StatusLevel2'] = '';
			$_SESSION['StatusLevel3'] = '';
			$_SESSION['StatusLevel4'] = '';
			$_SESSION['StatusLevel5'] = '';
			$_SESSION['StatusLevel6'] = '';
			$_SESSION['StatusLevel7'] = '';	
			$_SESSION['CongratsMessage'] = '';
			$_SESSION['LocationName'] = '';
			$_SESSION['Phone'] = '';
			$_SESSION['StreetAddress'] = '';
			$_SESSION['Description'] = '';
				
		}


	function CheckInCount($PromotionID)  {
		
			$sql_CheckInCount = "SELECT * FROM CheckIns WHERE PromotionID = {$PromotionID}";
			$Result_CheckInCount = Select($sql_CheckInCount);
			
			return count($Result_CheckInCount);
		
		}

		function CheckInShow()  {
		
			$sql_CheckIn = "SELECT LayerName, StartDate FROM Layers";
			$Result_CheckInCount = Select($sql_CheckIn);
				
			return $Result_CheckInCount;
		
		}
		

	function CouponExpiration ($PromoID, $Format="Raw") {

			$sql_CouponExpirationData = "SELECT ExpirationDate FROM Promotions_Rewards_Coupon, Promotions
								  	  WHERE Promotions_Rewards_Coupon.RewardID = Promotions.RewardID
									  AND Promotions.PromotionID = {$PromoID}";
		
			$Result_CouponExpirationData = Select($sql_CouponExpirationData);

			if ($Format == "Simple") { 

				$date = new DateTime($Result_CouponExpirationData[0]['ExpirationDate']);
				return $date->format('M d');

			} else { return $Result_CouponExpirationData[0]['ExpirationDate']; }

		}

	function CreatePromotionsJS($Step,$Modifier="")  {

		if ($Step == "Step1") {}
		elseif ($Step == "Step2") {}
		elseif ($Step == "Step3") {}
		elseif ($Step == "Step4") {}

		}

	function CreateMarker($MarkerDetails) {

		$CreateDate = date('Y/m/d G:i:s');
		$TaskID = "";
		$chkId="";
	
		// Sanitize data before database entry
		OpenDatabase();
  
		   $Title = Sanitize_Data($MarkerDetails['Title']);
   		   $Message = Sanitize_Data($MarkerDetails['Message']);
		   $Question = Sanitize_Data($MarkerDetails['Question']);
		   $CongratsMessage = Sanitize_Data($MarkerDetails['CongratsMessage']);
		   $LayerID = Sanitize_Data($MarkerDetails['LayerID']);
		   $StreetAddress = Sanitize_Data($MarkerDetails['StreetAddress']);		   
		   $Zip = Sanitize_Data($MarkerDetails['Zip']);
		   $LocationName = Sanitize_Data($MarkerDetails['LocationName']);
		   
		CloseDatabase();		   
		
	    if (!$LocationName) {
	    	$LocationName = $Title;
	     }
	      
	//----------------------------------
	//-----------  Create the Location
	//----------------------------------
	     
		if ($MarkerDetails['LocationType'] == "Previous") { 
			$LocationID = $MarkerDetails['LocationName']; }
		else if ($MarkerDetails['LocationType'] == "Here") { 
			
			$LocationLatitude = $MarkerDetails['LocationLatitude'];
			$LocationLongitude = $MarkerDetails['LocationLongitude'];
			
			$sql_InsertLocationData = "INSERT INTO Location(UName,
															   LocationLatitude, LocationLongitude,
															   LocationName, StreetAddress, Zip) ";
			$sql_InsertLocationData .= "VALUES ('{$_SESSION['UName']}',
			'{$LocationLatitude}', '{$LocationLongitude}',
			'{$LocationName}', '{$StreetAddress}', '{$Zip}') ";
			
			$Result_InsertLocationData = Insert($sql_InsertLocationData);
			$LocationID = $Result_InsertLocationData;
			
			
		}
		else  { 

			//-----------  Create a Location
			   // Get Lat & Long from Address
				if ($MarkerDetails['StreetAddress']) {
		
					    $CompleteAddress = $StreetAddress . " " . $Zip;
					   $LocationCoordinates = ConvertAddress($CompleteAddress);		   
				
					   if ($LocationCoordinates['LocationLatitude'] == '0.0') { return "Invalid-Location"; }
						
						$LocationLatitude = $LocationCoordinates['LocationLatitude'];
						$LocationLongitude = $LocationCoordinates['LocationLongitude'];
						
				} else {
						if (empty($MarkerDetails['LocationLatitude'])) { return "No-Location";} 
		
						$LocationLatitude = $MarkerDetails['LocationLatitude'];
						$LocationLongitude = $MarkerDetails['LocationLongitude'];
				}


			   $sql_InsertLocationData = "INSERT INTO Location(UName, 
															   LocationLatitude, LocationLongitude, 
															   LocationName, StreetAddress, Zip) ";
			   $sql_InsertLocationData .= "VALUES ('{$_SESSION['UName']}', 
												   '{$LocationLatitude}', '{$LocationLongitude}', 
												   '{$LocationName}', '{$StreetAddress}', '{$Zip}') "; 
			 
			   $Result_InsertLocationData = Insert($sql_InsertLocationData);
			   if (!$Result_InsertLocationData) { return "Location-Creation-Failure"; }
			   else { $LocationID = $Result_InsertLocationData; }
		} 
	
		
	//----------------------------------
	//-----------  Create the Question
	//----------------------------------
		if ($MarkerDetails['Type'] == "Question") {
		   $sql_InsertQuestionData = "INSERT INTO Promotions_Task_Question(UName, Question, CreateDate) ";
		   $sql_InsertQuestionData .= "VALUES ('{$_SESSION['UName']}', '{$Question}', Now()) "; 		

		   $Result_InsertQuestionData = Insert($sql_InsertQuestionData);
		   if (!$Result_InsertQuestionData) { return "Question-Creation-Failure"; }	
		   else { $TaskID = $Result_InsertQuestionData; }

		}


		
		
// 

		if ($MarkerDetails['Reward'] == "Coupon") {
			$sql_SaveRewardData_Coupon = "INSERT INTO Promotions_Rewards_Coupon(UName, CouponName, Coupon, ExpirationDate) ";
			$sql_SaveRewardData_Coupon .= "VALUES ('{$_SESSION['UName']}', '{$MarkerDetails['fine_print']}','{$MarkerDetails['Coupon_msg']}', STR_TO_DATE('{$MarkerDetails['coupon_date']}','%b %d, %Y'))";
			$Result_SaveRewardData = Insert($sql_SaveRewardData_Coupon);
		}
		
		if ($MarkerDetails['Reward'] == "Message") {
			$sql_SaveRewardData_Message = "INSERT INTO Promotions_Rewards_Information(UName, Information) ";
			$sql_SaveRewardData_Message .= "VALUES ('{$_SESSION['UName']}', '{$MarkerDetails['reward_Confirmation']}')";
			$Result_SaveRewardData = Insert($sql_SaveRewardData_Message);
		}
		
		if ($MarkerDetails['Reward'] == "Status") {
			$sql_SaveRewardData_Status = "INSERT INTO Promotions_Rewards_Status(UName, StatusLevel1, StatusLevel2, StatusLevel3) ";
			$sql_SaveRewardData_Status .= "VALUES ('{$_SESSION['UName']}', '{$MarkerDetails['status_1']}', '{$MarkerDetails['status_2']}', '{$MarkerDetails['status_3']}' )";
			$Result_SaveRewardData = Insert($sql_SaveRewardData_Status);
		}
		
		// Get the TaskID
		if ($Result_SaveRewardData) { $RewardID = $Result_SaveRewardData; }  else { $RewardID = 0; }
		
		
		
	//----------------------------------
	//-----------  Create the Promotion
	//----------------------------------
		if (empty($MarkerDetails['EndDate'])) { $EndDate = "now() + interval 1000 day"; }
		else { $EndDate = $MarkerDetails['EndDate']; }
	
	   $sql_InsertPromotionsData = "INSERT INTO Promotions(UName, TaskType, TaskID, RewardType, RewardID, StartDate, EndDate,
								  PromoTitle, TaskDescription, RewardDescription, PromoRange, CongratsMessage, CreateDate) ";
	   $sql_InsertPromotionsData .= "VALUES ('{$_SESSION['UName']}', '{$MarkerDetails['Type']}', '{$TaskID}', '{$MarkerDetails['Reward']}', '{$RewardID}', now(), {$EndDate},
								  '{$Title}','{$Message}','','Personal', '{$CongratsMessage}', Now()) "; 
	
	   $Result_InsertPromotionsData = Insert($sql_InsertPromotionsData);
	   if (!$Result_InsertPromotionsData) { return "Promotion-Creation-Failure"; }
	   else { $PromotionID = $Result_InsertPromotionsData; }		
		
	//----------------------------------
	//-----------  Create the Location_Promotion
	//----------------------------------		

	   $sql_InsertPromoLocoData = "INSERT INTO Location_Promotions(PromotionID, LayerID, LocationID, PromoQuantity, PromosEarned, StartDate, EndDate, Hits) ";
	   $sql_InsertPromoLocoData .= "VALUES ('$PromotionID', '{$LayerID}', '{$LocationID}', '999', 0 , now(), {$EndDate}, 0) "; 	
	   
	   $Result_InsertPromoLocoData = Insert($sql_InsertPromoLocoData);		

	   
	   //----------------------------------
	   //-----------  Create the CheckIn
	   //----------------------------------
	   
	   		
	   
	   if ($MarkerDetails['Type'] == "CheckIn") {
	   	
	   		$Response = "-";
	   	if(isset($_POST['Response']))
	   		$Response = Sanitize_Data($_POST['Response']);
	   	
	   	
	   	$sql_LogCheckIn = "INSERT INTO CheckIns (UName, PromotionID, UserResponse, LocationLatitude, LocationLongitude)
	   	VALUES ('{$_SESSION['UName']}',{$PromotionID},'{$Response}','{$LocationLatitude}','{$LocationLongitude}')";
	   		
	   		

	   		
	   	$Result_InsertchkinData = Insert($sql_LogCheckIn);
	   	if (!$Result_InsertchkinData) { return "Checkin-Creation-Failure"; }
	   
	   
	   	}
	   	
	   	
	   	
		
		return "Success";
		}



	function ActivityFeed($Show,$SearchType,$SearchQuery) {
	
		global $varLocationPoint, $varLocation;
		
		$varLocationPoint = "var Location0";	
		$FeedResults = "";
		$i = 1;
		if (isset($_SESSION['UName']) ) {
		$PromoRange = "('All','Personal')";
		}
		else
		{
		$PromoRange = "('All')";
		}
		
		//echo $SearchType;
		
		// ##########################################
		// ######  Default Results
		// ##########################################
		if ($SearchType == "Default") {
			  // Get Activity Feed Data
				$sql_ActivityFeedData = "SELECT Location.LocationID, Layers.LayerID, LayerName, Promotions.CreateDate, LocationLatitude, LocationLongitude, PromoTitle, Layers.UName, Promotions.PromoRange							
										  FROM Promotions, Location_Promotions, Location, Layers ";
			
			  // Joining tables
				$sql_ActivityFeedData .= "WHERE Location_Promotions.PromotionID = Promotions.promotionID
											AND Location_Promotions.LocationID = Location.LocationID
											AND Location_Promotions.LayerID = Layers.LayerID";
			
			  // Promo dates are valid							   
				$sql_ActivityFeedData .= " AND Promotions.StartDate < Now()
										   AND Promotions.EndDate > Now() ";
							  
			  // There is still quantity left	  
				$sql_ActivityFeedData .= " AND Location_Promotions.PromoQuantity > Location_Promotions.PromosEarned ";					  
	  
			  // ----------------------
			  // ------  Modifications
			  // ----------------------		 
			  
			  // Show all relevant promos from user and friends...or just show public ones if not logged in
			  if (isset($_SESSION['UName'])) {
			  	//, Location_Promotions    LinkageList.LayerID = Location_Promotions.LayerID AND  
				  $sql_ActivityFeedData .= " AND Layers.LayerID IN (Select LinkageList.LayerID FROM LinkageList
				  
																  WHERE 
																    LinkageList.UName='{$_SESSION['UName']}' 
																  AND Accepted='ok'
																  )
				  				AND ( Promotions.PromoRange IN {$PromoRange}
												  OR Promotions.UName='{$_SESSION['UName']}')";
			  } else { 
				  $sql_ActivityFeedData .= " AND Promotions.PromoRange IN {$PromoRange}"; 
			  }
	   
			  // if on Layer Details page, show only those promos in that layer
			  if (isset($_GET['Layer'])) { 	
				  $sql_ActivityFeedData .= "  AND Layers.LayerName = '{$_GET['Layer']}'";
			  } 
	   
			  //Limit to a specific Marker type if requested	
			  if (isset($_GET['MarkerType'])) { 
			  	$sql_ActivityFeedData .= " AND Promotions.TaskType .= '{$_GET['MarkerType']}'";  }
	  
			  $sql_ActivityFeedData .= " GROUP BY Promotions.PromotionID 
										 ORDER BY Promotions.StartDate DESC LIMIT 0,6"; 	
	  
			  $Result_ActivityFeedData = Select($sql_ActivityFeedData);		
	  
			 // echo $sql_ActivityFeedData;
			  
			  
			//  echo '<pre>' print_r($Result_ActivityFeedData);   echo '</pre>';
			  //Loop through results
			  if (count($Result_ActivityFeedData)) {
				  foreach ($Result_ActivityFeedData as $results) {
					  
				
				  	
						  $CreateDate = FormatDate($results['CreateDate']);
					  
						  $varLocationPoint .= ", Location{$results['LocationID']}";
						  $varLocation .= "Location{$results['LocationID']} = new mxn.LatLonPoint({$results['LocationLatitude']},{$results['LocationLongitude']});";
					  
							$img_src="<img src='gfx/icons/Marker.png'>" ;
				 			if($results['PromoRange']=='All')
				  			$img_src="<img src='gfx/icons/Marker-Red.png'>" ;
						  
						  $FeedResults .= "	<div style='padding:2px;'>		
												<span style='padding:0px 5px 31px 5px; float:left;'>
												<a href='#map' onclick=\"mapstraction.setCenter(Location{$results['LocationID']}, {pan:true}); mapstraction.setZoom(12)\" style='padding:8px 3px;' class='BabyBlue AlignRight'>
												$img_src </a></span>
												<h4 style='margin:3px 0 0 0;'>{$results['PromoTitle']} &nbsp; &nbsp; 
												</h4>
												<p class='SmallFont'><strong class='DarkGray'>Layer: </strong>
												<a href='Layers.php?ID={$results['LayerID']}' class='BabyBlue'>{$results['LayerName']}</a>
												  <br><strong class='DarkGray'>Created By:</strong> {$results['UName']}</p>
											  </div>
											  <div class='clear'></div>";
											  
							// Determine how many to show				  
							//if ($i > 4) { break; } else {  $i++; }									  
				  }
			  } else { $FeedResults = "No activity yet."; }


		// ##########################################
		// ######  Default Results
		// ##########################################
		} elseif ($SearchType == "Address") {

			  // Put the address into the URL call
			  $Address = "http://maps.googleapis.com/maps/api/geocode/xml?address=";
			  $Address .= urlencode($_POST["SearchQuery"]);
			  $Address .= "&sensor=false";
	  
			  // Store XML content of address into variable and parse it
			  $XMLcontent = Get_AddressData($Address);	
				  
			  $xmlObj = simplexml_load_string($XMLcontent);
			  $arrXml = objectsIntoArray($xmlObj);
	  
			  //print_r($arrXml['result']['geometry']['location']);
	  
			  if (isset($arrXml['result']['geometry']['location']['lat'])) {
				  $Latitude = $arrXml['result']['geometry']['location']['lat'];
				  $Longitude = $arrXml['result']['geometry']['location']['lng'];
			  
				  $LatLong = "{$Latitude}, {$Longitude}"; 
				  
				  

				  $sql_ActivityFeedData = "SELECT Location.LocationID, Layers.LayerID, LayerName, Promotions.CreateDate, Promotions.PromoRange,
				  LocationLatitude, LocationLongitude, PromoTitle, Layers.UName, ABS( LocationLatitude - $Latitude ) AS Nearpoint
				  FROM Promotions, Location_Promotions, Location, Layers
				  WHERE Location_Promotions.PromotionID = Promotions.promotionID
				  AND Location_Promotions.LocationID = Location.LocationID
				  AND Location_Promotions.LayerID = Layers.LayerID
				  GROUP BY LocationID  ORDER BY Nearpoint ASC LIMIT 0 , 24"; //  LIMIT 0 , 6 
				  
				  	
				  	
				  $Result_ActivityFeedData = Select($sql_ActivityFeedData);
				  	
				 
				  	
				  if (count($Result_ActivityFeedData)) {
				  	foreach ($Result_ActivityFeedData as $results) {
				  
				  		$CreateDate = FormatDate($results['CreateDate']);
				  
				  		$varLocationPoint .= ", Location{$results['LocationID']}";
				  		$varLocation .= "Location{$results['LocationID']} = new mxn.LatLonPoint({$results['LocationLatitude']},{$results['LocationLongitude']});";
				  			$img_src="<img src='gfx/icons/Marker.png'>" ;
				 		if($results['PromoRange']=='All')
				  			$img_src="<img src='gfx/icons/Marker-Red.png'>" ;
				  			
				  				
				  		$FeedResults .= "	<div style='padding:2px;'>
				  		<span style='padding:0px 5px 31px 5px; float:left;'>
				  		<a href='#map' onclick=\"mapstraction.setCenter(Location{$results['LocationID']}, {pan:true}); mapstraction.setZoom(12)\" style='padding:8px 3px;' class='BabyBlue AlignRight'>{$img_src}</a>
				  		</span>
				  		<h4 style='margin:3px 0 0 0;'>{$results['PromoTitle']}</h4>
				  		<p class='SmallFont'><strong class='DarkGray'>Layer: </strong>
				  		<a href='Layers.php?ID={$results['LayerID']}' class='BabyBlue'>{$results['LayerName']}</a>
				  		<br><strong class='DarkGray'>Created By:</strong> {$results['UName']}</p>
				  		</div>
						<div class='clear'></div>";
				  
				  	
				  	}
				  	}
				  
				  	
				  	
			  } else { 
				  //Address not found
					  $ErrorMessage_Search = "Address not found";
					  $Hide_Search = "";  		
					  $FeedResults='';	
					  $LatLong='';
			  }
			  //close
	
			
			//$FeedResults = $FeedResults."Address search*/!".$LatLong;
			
			
		// ##########################################
		// ######  Default Results
		// ##########################################		
		} elseif ($SearchType == "Layer") {


			$sql_ActivityFeedData = "SELECT Location.LocationID, Layers.LayerID, LayerName, Promotions.CreateDate, LocationLatitude,
			LocationLongitude, PromoTitle, Layers.UName FROM Promotions, Location_Promotions, Location, Layers
			WHERE Location_Promotions.PromotionID = Promotions.promotionID
			AND Location_Promotions.LocationID = Location.LocationID
			AND Location_Promotions.LayerID = Layers.LayerID
			
			AND Layers.LayerName LIKE '%$SearchQuery%' GROUP BY LayerID LIMIT 0,6";
			
			
				
			//	echo $sql_ActivityFeedData;
				
				
			$Result_ActivityFeedData = Select($sql_ActivityFeedData);
				
				
			if (count($Result_ActivityFeedData)) {
				foreach ($Result_ActivityFeedData as $results) {
			
					$CreateDate = FormatDate($results['CreateDate']);
			
					$varLocationPoint .= ", Location{$results['LocationID']}";
					$varLocation .= "Location{$results['LocationID']} = new mxn.LatLonPoint({$results['LocationLatitude']},{$results['LocationLongitude']});";
			
					$FeedResults .= "
						<div style='padding:2px;'>
							<h4 style='margin:3px 0 0 0;'>{$results['LayerName']}</h4>
							<p class='SmallFont'>Created By: <strong class='DarkGray'>{$results['UName']}</strong><br>
							<a href='Layers.php?ID={$results['LayerID']}' class='BabyBlue'>See Details &raquo;</a>
							</p>
							</div>
						<div class='clear'></div>";
			
							// Determine how many to show
						//	if ($i > 11) { break; } else {  $i++; }
							}

							$FeedResults = $FeedResults." ";
							}
			
							else
							{
								$FeedResults = "No Layers Found";
							}
		
			
			//$FeedResults = $FeedResults."Address search*/!".$LatLong;
			
			
		// ##########################################
		// ######  Default Results
		// ##########################################			
		} elseif ($SearchType == "Person") {

		
			$sql_ActivityFeedData = "SELECT Location.LocationID, Layers.LayerID, LayerName, Promotions.CreateDate, LocationLatitude,
			LocationLongitude, PromoTitle, Layers.UName FROM Promotions, Location_Promotions, Location, Layers
			WHERE Location_Promotions.PromotionID = Promotions.promotionID
			AND Location_Promotions.LocationID = Location.LocationID
			AND Location_Promotions.LayerID = Layers.LayerID
				
			AND Layers.UName LIKE '%$SearchQuery%' GROUP BY LocationID LIMIT 0,10";
		
				
			
			//echo $sql_ActivityFeedData;
			
			
			$Result_ActivityFeedData = Select($sql_ActivityFeedData);
			//echo '<pre>'; 			
			//print_r($Result_ActivityFeedData);
			//echo '</pre>';
			
			if (count($Result_ActivityFeedData)) {
			foreach ($Result_ActivityFeedData as $results) {
				
			$CreateDate = FormatDate($results['CreateDate']);
				
			$varLocationPoint .= ", Location{$results['LocationID']}";
			$varLocation .= "Location{$results['LocationID']} = new mxn.LatLonPoint({$results['LocationLatitude']},{$results['LocationLongitude']});";
		
					$FeedResults .= "	
						<div style='padding:2px;'>
							<h4 style='margin:3px 0 0 0;'>Person Name &nbsp; &nbsp;</h4>
							<p class='SmallFont'>Username: <strong class='DarkGray'>username</strong><br>
							<a href='#'>List Layers &raquo;</a></p>
							</div>
						<div class='clear'></div>";
				
			// Determine how many to show
						//if ($i > 7) { break; } else {  $i++; }
			}
			$FeedResults = $FeedResults;
			}
			
			else
			{
			$FeedResults = "No Results Found";
			}
			
			
			

		// ##########################################
		// ######  Default Results
		// ##########################################			
		} else { $FeedResults = "Search error:  Please try again."; }
		
		
		
		$varLocationPoint .= ";";		
		
		return $FeedResults;
		
		}

		/* most popular layers */
		function most_popular_layers(){
			
			$SQL=Select("SELECT Layers.LayerID,LayerName 
FROM Layers, LinkageList
WHERE LinkageList.LayerID = Layers.LayerID
AND Accepted = 'ok'
GROUP BY Layers.LayerID
ORDER BY COUNT( * ) DESC
LIMIT 0 , 4");
			$layers='';
	
			foreach ($SQL as $row)
			{
				//print_r($result);
				
				$layers .= '<li><a href="Layers.php?ID='.$row['LayerID'].'">'.$row['LayerName'].'</a></li>';
			}
	
			return $layers;
		}
		
		function most_popular_layers1(){
				
			$SQL=Select("SELECT Layers.LayerID,LayerName
FROM Layers, LinkageList
WHERE LinkageList.LayerID = Layers.LayerID
AND Accepted = 'ok'
GROUP BY Layers.LayerID
ORDER BY COUNT( * ) DESC
LIMIT 4 , 4 ");
			$layers='';
		
			foreach ($SQL as $row)
			{
				//print_r($result);
		
				$layers .= '<li><a href="Layers.php?ID='.$row['LayerID'].'">'.$row['LayerName'].'</a></li>';
			}
		
			return $layers;
		}
		
?>