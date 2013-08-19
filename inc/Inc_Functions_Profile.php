<?php

    require_once 'inc/Inc_Common.php';
	require_once 'inc/Inc_Skin.php';
	require_once 'inc/Inc_Functions_Database.php';	

// -------------------------------
// -- List of Functions
// -------------------------------
/*
	function UpdateProfile($UserDetails) {}
	function UpdateLocation($LocationDetails) {}
	function GetLocationDetails($LocationID) {}
	function InsertLocation($LocationDetails) {}
	
	function OriginalUsername($Username) {}	
	function UniqueEmail($EmailAddress) {
	function ValidEmail($EmailAddress) {}
	
	function DB_CreateAccount($AccountDetails) {}
	function DB_Authenticate($Username, $Password) {}
	
	function FB_IDMatched($FB_UserID) {}
	function FB_EmailMatched($EmailAddress) {}	
	function FB_LinkAccount($FB_UserID,$EmailAddress,$Gender,$BDay) {}	
	
	function GetLocations($UName) {}
	function MyLayers($UName) {}
	function DeleteMyLayer($DeleteMyLayer, $Uname) {}	
	function DeleteMyMarker($PromotionID, $Uname) {}	
	function UpdateLayerDetails($Description, $LayerName, $LayerID, $UName) {}
	function LayersIFollow($UName) {}	
	function GetFollowers($UName) {}
	function FollowerCount($LayerID) {}
	function RemoveFollowing($FriendID,$UName) {}
	function BlockFollower($FriendID,$UName) {}		
	function GetFacebookFriends() {}
	function LinkLayers($UName,$LayerName) {}	
	function GetUserInfo($UName) {}
	function GetUserLayers($UName) {}
	function GetLayerDetails($LayerID) {}
	function GetPassword(EmailAddress) {}	

*/

// -------------------------------
// -- MyAccount page functions
// -------------------------------

	function UpdateProfile($UserDetails) {
		
		// Sanitize data before database entry
		 OpenDatabase();

			 $PWord = Sanitize_Data($UserDetails['PWord']);
			 $EmailAddress = Sanitize_Data($UserDetails['EmailAddress']);
			 $FullName = Sanitize_Data($UserDetails['FullName']);
			 $Zip = Sanitize_Data($UserDetails['Zip']);
		  
		 CloseDatabase();


		 $LatLong = ConvertAddress($Zip);
		 $LocationLatitude = $LatLong["LocationLatitude"];
		 $LocationLongitude = $LatLong["LocationLongitude"];

		 // Update SQL
		 $sql_SaveProfileData = "UPDATE Users SET PWord='{$PWord}', 
												  EmailAddress='{$EmailAddress}', 
												  FullName='{$FullName}',
												  Zip='{$Zip}',
												  LocationLatitude='{$LocationLatitude}',
												  LocationLongitude='{$LocationLongitude}'
		 						  WHERE UName='{$_SESSION['UName']}' Limit 1";		 
		 
		 
		 // Update Cookie
		 $_SESSION['FullName'] = $UserDetails['FullName'];
		 $_SESSION['EmailAddress'] = $UserDetails['EmailAddress'];
		 $_SESSION['PWord'] = $UserDetails['PWord'];
		 $_SESSION['Zip'] = $UserDetails['Zip'];
		 $_SESSION['LocationLatitude'] = $LatLong["LocationLatitude"];
		 $_SESSION['LocationLongitude'] = $LatLong["LocationLongitude"];

	  	$Result_SaveProfileData = Update($sql_SaveProfileData);

		if ($Result_SaveProfileData) { return "Updates successful.";  }
			else { return "Updates failed. Please contact administrator."; }
		
	}


	function UpdateLocation($LocationDetails) {

		// Sanitize data before database entry
		OpenDatabase();
		
			$LocationID = Sanitize_Data($LocationDetails['EditLocation']);	 
			$LocationName = Sanitize_Data($LocationDetails['LocationName']);
			$Description = Sanitize_Data($LocationDetails['Description']);
			$StreetAddress = Sanitize_Data($LocationDetails['StreetAddress']);		   
			$Zip = Sanitize_Data($LocationDetails['Zip']);
			$Phone = Sanitize_Data($LocationDetails['Phone']);

		CloseDatabase();	
	  
		// Get Lat & Long from Address
		$CompleteAddress = $StreetAddress . " " . $Zip;
		$LocationCoordinates = ConvertAddress($CompleteAddress);
	  
	  	if (($LocationCoordinates['LocationLatitude'] == 0) OR ($LocationCoordinates['LocationLongitude'] == 0)) { return ErrorMessage("Update failed. Address not found.");}
	  
		 // Submit Form Data
		$sql_UpdateLocationData = "UPDATE Location SET LocationLatitude='{$LocationCoordinates['LocationLatitude']}', 
														LocationLongitude='{$LocationCoordinates['LocationLongitude']}', 
														LocationName='{$LocationName}', 
														Description='{$Description}', 
														StreetAddress='{$StreetAddress}', 
														Zip='{$Zip}', 
														Phone='{$Phone}' 
									WHERE LocationID='{$LocationID}'";
									
		$Result_UpdateLocationData = Update($sql_UpdateLocationData);

		if ($Result_UpdateLocationData) { return ErrorMessage("Updates successful.","Success");  }
			else { return ErrorMessage("Updates failed.  Please contact administrator."); }
					
	}


	function GetLocationDetails($LocationID) {
		
		// 	make sure to check a person is expiring a promo created by their UName
		$sql_GetLocationData = "SELECT * FROM Location WHERE LocationID='{$LocationID}' LIMIT 1";
		$Result_GetLocationData = Select($sql_GetLocationData);	
		
		if ($Result_GetLocationData) { return $Result_GetLocationData[0]; }
		else { return FALSE;}		
		
	}
	

	function InsertLocation($LocationDetails) {

		// Sanitize data before database entry
		OpenDatabase();

	   		//$Uname   
		   $LocationName = Sanitize_Data($LocationDetails['LocationName']);
		   $Description = Sanitize_Data($LocationDetails['Description']);
		   $StreetAddress = Sanitize_Data($LocationDetails['StreetAddress']);		   
		   $Zip = Sanitize_Data($LocationDetails['Zip']);
		   $Phone = Sanitize_Data($LocationDetails['Phone']);

		CloseDatabase();		   
	   
	   // Get Lat & Long from Address
	   $CompleteAddress = $StreetAddress . " " . $Zip;
	   $LocationCoordinates = ConvertAddress($CompleteAddress);		   
	
	   // Submit Form Data
	   $sql_InsertLocationData = "INSERT INTO Location(UName, 
													   LocationLatitude, LocationLongitude, 
													   LocationName, Description, StreetAddress, Zip, Phone) ";
	   $sql_InsertLocationData .= "VALUES ('{$_SESSION['UName']}', 
										   '{$LocationCoordinates['LocationLatitude']}', '{$LocationCoordinates['LocationLongitude']}', 
										   '{$LocationName}', '{$Description}', '{$StreetAddress}', '{$Zip}', '{$Phone}') "; 
	 
	   $Result_InsertLocationData = Insert($sql_InsertLocationData);

	   if ($Result_InsertLocationData) 
		{ $Message_Location = ErrorMessage("Addition successful.","Success"); }
		else { $Message_Location = ErrorMessage("Addition failed.  Please contact administrator."); }		
	
		return $Message_Location;
	
	}



// -------------------------------
// -- User functions
// -------------------------------

	function OriginalUsername($Username) {
		
			$sql_CheckUsername = "SELECT * FROM Users WHERE UName='{$Username}'";

			if (count(Select($sql_CheckUsername))==0) { return TRUE; } else { return FALSE; }
				
		}

	function UniqueEmail($EmailAddress) {
		
			$sql_CheckEmailAddress = "SELECT * FROM Users WHERE EmailAddress='{$EmailAddress}'";

			if (count(Select($sql_CheckEmailAddress))==0) { return TRUE; } else { return FALSE; }
				
		}		

	function ValidEmail($EmailAddress) {
		
			$sql_CheckEmailAddress = "SELECT * FROM Users WHERE EmailAddress='{$EmailAddress}'";
			$Result_CheckEmailAddress = Select($sql_CheckEmailAddress); 			  
			
			if ($Result_CheckEmailAddress) { return $Result_CheckEmailAddress; } else { return FALSE; }
				
		}		


	function DB_CreateAccount($AccountDetails) {
			// Sanitize data before database entry
			 OpenDatabase();
	
				 $UName = Sanitize_Data($AccountDetails['UName']);
				 $PWord = Sanitize_Data($AccountDetails['PWord']);
				 $EmailAddress = Sanitize_Data($AccountDetails['EmailAddress']);
				 $FullName = Sanitize_Data($AccountDetails['FullName']);
				 $Zip = Sanitize_Data($AccountDetails['Zip']);
			  
			 CloseDatabase();	

			// Need to convert BDay to MySql friendly version
			//$BDay = STR_TO_DATE($BDay, '%Y/%m/%d')
			

			 // Insert Statement for Creating Users
			 $sql_CreateUser = "INSERT INTO Users(UName, PWord, 
												 FullName, EmailAddress, 
												 LocationLatitude, LocationLongitude, Zip, 
												 CreateDate, LastLogin, IPAddress, Visits, 
												 FB_UserID, Gender, BDay, AccessToken) ";
			 $sql_CreateUser .= "VALUES ('{$UName}', '{$PWord}', 
										'{$FullName}', '{$EmailAddress}', 
										'{$AccountDetails['LocationLatitude']}', '{$AccountDetails['LocationLongitude']}', '{$Zip}', 
										'{$AccountDetails['JoinDate']}', '{$AccountDetails['LastLogin']}', '{$AccountDetails['IPAddress']}', 1,
										'{$AccountDetails['FB_UserID']}', '{$AccountDetails['Gender']}', '{$AccountDetails['BDay']}','{$AccountDetails['AccessToken']}')";		

			$_SESSION['FB_UserID'] = $AccountDetails['FB_UserID'];

			  
			 $Result_CreateSomeone = Insert($sql_CreateUser); 			  

			//  Set User Session
			 $_SESSION['FB_UserID'] = $AccountDetails['FB_UserID'];
			 $_SESSION['AccessToken'] = $AccountDetails['AccessToken'];
			 $_SESSION['UName'] = $AccountDetails['UName'];
			 $_SESSION['LocationLatitude'] = $AccountDetails['LocationLatitude'];
			 $_SESSION['LocationLongitude'] = $AccountDetails['LocationLongitude'];
		
			return $Result_CreateSomeone;
		}

	function DB_Authenticate($Username, $Password) {
			  // Need to sanitize data	

		    
			// Database Auth
			$sql_VerifyUser = "SELECT * FROM Users WHERE UName='{$Username}' AND PWord='{$Password}'";
			$Result_VerifyUser = Select($sql_VerifyUser); 

			if ($Result_VerifyUser) {
	
				 // Update number of Visits
				 $Visits = $Result_VerifyUser[0]['Visits'] + 1;
				 $sql_UpdateVisits = "UPDATE Users SET Visits={$Visits}, LastLogin=Now() WHERE UName='{$Username}'";
				 $Result_UpdateVisits = Update($sql_UpdateVisits);

				//  Set User Session
				 $_SESSION['FB_UserID'] = $Result_VerifyUser[0]['FB_UserID'];
				 $_SESSION['AccessToken'] = $Result_VerifyUser[0]['AccessToken'];
				 $_SESSION['UName'] = $Username;
				 $_SESSION['LocationLatitude'] = $Result_VerifyUser[0]['LocationLatitude'];
				 $_SESSION['LocationLongitude'] = $Result_VerifyUser[0]['LocationLongitude'];
				 // expires in 30 days: time()+60*60*24*30;
					   
				return TRUE;
				
			} else {
				
				return FALSE;
			}
			
		}


	function FB_IDMatched($FB_UserID) {
		    
			// Database Auth
			$sql_VerifyUser = "SELECT * FROM Users WHERE FB_UserID='{$FB_UserID}'";
			$Result_VerifyUser = Select($sql_VerifyUser); 

			if ($Result_VerifyUser) { return $Result_VerifyUser;}
			 else { return FALSE; }
			
		}

	function FB_EmailMatched($EmailAddress) {
		    
			// see if an FB email matches existing SW account
			$sql_LocateUser = "SELECT * FROM Users WHERE EmailAddress='{$EmailAddress}'";
			$Result_LocateUser = Select($sql_LocateUser); 

			if ($Result_LocateUser) { return $Result_LocateUser; }
			else { return FALSE; }
			
		}


	function FB_LinkAccount($FB_UserID,$EmailAddress,$Gender,$BDay,$AccessToken) {
		// added yes 
		// Update record to include FB_UserID, Gender, DOB
		 $sql_UpdateProfileData = "UPDATE Users SET FB_UserID='{$FB_UserID}', Gender='{$Gender}', BDay='{$BDay}', FBVerified='Yes' ,
														AccessToken='{$AccessToken}'
									WHERE EmailAddress='{$EmailAddress}'";
									
		 $Result_UpdateProfileData = Update($sql_UpdateProfileData);

		if ($Result_UpdateProfileData) { $_SESSION['FB_UserID'] = $FB_UserID; $_SESSION['AccessToken'] = $AccessToken; return TRUE; }
		  else { return FALSE;}
		  
		
		}

	function GetLocations($UName) {
		
			$sql_LocationData = "SELECT * FROM Location WHERE UName='{$UName}'";
			$Result_LocationData = Select($sql_LocationData);

			if (count($Result_LocationData)) {

				  foreach ($Result_LocationData as $results) {	
				   
				   if (empty($results['City']) AND empty($results['State'])) { $Comma = ", "; } else {$Comma = " ";}
															  
				   $LocationInfo .= "<li><h3 class='DarkGray'>{$results['LocationName']}  
									  <span class='SmallFont AlignRight'><a href='MyAccount.php?EditLocation={$results['LocationID']}'>(Edit)</a></span>			</h3>
										  <div class='BabyBlue'>		
											  {$results['StreetAddress']} &nbsp; <span class='SmallFont'> <a href='MyAccount.php?Lat={$results['LocationLatitude']}&Long={$results['LocationLongitude']}'>Map</a></span><br />
											  {$results['City']}{$Comma}{$results['State']} {$results['Zip']}<br />
											  {$results['Phone']}
										  </div>							 
								   </li><br>";
								  
					  }   // End For each record
			 
		   } else { $LocationInfo = "<div align='center' style='padding:30px;'>No locations yet.</div>"; }		
		
		}



	function DeleteMyLayer($DeleteMyLayer, $UName) {
				
				
				//
				$sql_UpdateLinkages = "DELETE FROM Layers WHERE UName='{$UName}' AND LayerName='{$DeleteMyLayer}' LIMIT 1"; 
				
				
		    	if (Delete($sql_UpdateLinkages)) { return "Successfully Deleted"; } else { return FALSE; }
				
				
		
		}
		
	function DeleteMyMarker($PromotionID, $UName) {
					
				// remove from Promotions table
				$sql_RemoveMarker = "DELETE FROM Promotions WHERE UName='{$UName}' AND PromotionID='{$PromotionID}' LIMIT 1"; 
		    	if (Delete($sql_RemoveMarker)) { 

						// Remove from Locations_promotion table
						$sql_UpdateLinkages = "DELETE FROM Location_Promotions WHERE PromotionID='{$PromotionID}' LIMIT 1"; 
						$Result = Update($sql_UpdateLinkages);
				
						// remove from lcoation table too? ...no, may want to provide control of locations separately

				
						return "Successfully Deleted"; 
						
				} else { return FALSE; }
		
		}

	function MyLayers($UName) {
			$ThisPage = basename($_SERVER['SCRIPT_NAME']);

			// ------------------------------
			// --------  Get Data  ----------
			// ------------------------------
			$sql_LayerData = "SELECT * FROM Layers WHERE UName='{$UName}' and Active='Yes'";     
			$Result_LayerData = Select($sql_LayerData);

			if (count($Result_LayerData)) {
				// ------------------------------
				// --------  Format Data  -------
				// ------------------------------								

				$LayersInfo = "<ul style='padding:10px 0; margin:5px;'>";				

				foreach($Result_LayerData as $LayerRecord) {
					$LayerName_encoded = urlencode($LayerRecord['LayerName']);

					$LayersInfo .= "
						<div class='ToggleList'>
								<span class='AlignRight'><a href='{$ThisPage}?DeleteMyLayer={$LayerName_encoded}' class='CancelX Red'>X</a></span>
								<strong>{$LayerRecord['LayerName']}</strong>
						</div>";					
				}

				$LayersInfo .= "</ul>";

			} else { $LayersInfo = "<div align='center' style='padding:30px 0;'>You have not created any layers yet.</div>"; 	 }
			
								
			return $LayersInfo;
		
		}

	function UpdateLayerDetails($Description, $LayerName, $LayerID, $UName) {

					//Sanitize data
				   OpenDatabase();

					   $LayerName = Sanitize_Data($LayerName);
					   $Description = Sanitize_Data($Description);
					
				   CloseDatabase();	

		    	$sql_UpdateDescription = "UPDATE Layers SET Description='{$Description}', LayerName='{$LayerName}'  WHERE UName='{$UName}' AND LayerID='{$LayerID}'"; 
		    	if (Update($sql_UpdateDescription)) { return "Successfully Updated"; } else { return FALSE; }
		
		}

	function LayersIFollow($UName) {
			$ThisPage = basename($_SERVER['SCRIPT_NAME']);

			// ------------------------------
			// --------  Get Data  ----------
			// ------------------------------
			$sql_FriendData = "SELECT Layers.LayerID, Layers.LayerName, Layers.UName  FROM LinkageList, Layers, Users 
								WHERE LinkageList.LayerID = Layers.LayerID 
									AND LinkageList.UName = Users.UName 
									AND LinkageList.UName='{$UName}' 
									AND Accepted = 'ok'";     
			$Result_FriendData = Select($sql_FriendData);

			if (count($Result_FriendData)) {
				// ------------------------------
				// --------  Format Data  -------
				// ------------------------------								

				$Background = "Black";	

				$FriendsInfo = "<ul style='padding:10px 0; margin:5px;'>";				

				foreach($Result_FriendData as $FriendRecord) {
			
					if ($Background == "Black") { $Background = "DarkGray"; } else { $Background = "Black"; }

					$LayerName_encoded = urlencode($FriendRecord['LayerName']);

					$FriendsInfo .= "
						<div class='ToggleList'>
							<span class='AlignRight'><a href='{$ThisPage}?RemoveLayer={$FriendRecord['LayerName']}#LayersIFollow' class='CancelX Red'>X</a></span>
							<strong>{$FriendRecord['LayerName']}</strong> 
							<br><span>By: {$FriendRecord['UName']} &nbsp; &nbsp; <a href='Layers.php?ID={$FriendRecord['LayerID']}'>See Details &raquo;</a></span>
						</div>";					
				}

				$FriendsInfo .= "</ul>";

			} else { $FriendsInfo = "<div align='center' style='padding:30px 0;'>No connections yet.</div>"; 	 }
			
								
			return $FriendsInfo;
		
		}


	function GetFollowers($LayerID) {

			// ------------------------------
			// --------  Get Data  ----------
			// ------------------------------
 $sql_FriendData = "SELECT * FROM LinkageList, Users WHERE LinkageList.UName = Users.UName AND LayerID='{$LayerID}' AND Accepted = 'ok'";   
			
$Result_FriendData = Select($sql_FriendData);

			$TotalCount = count($Result_FriendData);

			$FriendsInfo = "<div class='toogle_box'>
								<div class='toggle closed_toggle'><div class='icon'></div>Your Followers</div>
							<div class='toggle_container'>";				

	
			if (count($Result_FriendData)) {
				// ------------------------------
				// --------  Format Data  -------
				// ------------------------------								
				foreach($Result_FriendData as $FriendRecord) {
						
					if (!empty($FriendRecord['FB_UserID'])) { $ProfilePicture = "<img src='https://graph.facebook.com/{$FriendRecord['FB_UserID']}/picture?type=square' width='45'>";  }
					  elseif (!empty($FriendRecord['ProfilePic'])) { $ProfilePicture = "<img src='gfx/ProfilePics/{$FriendRecord['ProfilePic']}' border='1' width='45'>"; }
					  else { $ProfilePicture = ""; }

					$FriendsInfo .= "
						<div class='ToggleList'>
							<span class='AlignLeft' style='padding-right:15px;'>$ProfilePicture</span>
							<span class='AlignRight'><a href='Layers.php?BlockLayer={$FriendRecord['UName']}#Followers' class='CancelX Red'>X</a></span>
							<strong>{$FriendRecord['FullName']}</strong> &nbsp; <span>({$FriendRecord['UName']})</span>
						</div>";
				}
				

			} else { $FriendsInfo .= "<div class='ToggleList'><div align='center' style='padding:10px;'>Nobody is following this layer.</div></div>";  }

			$FriendsInfo .= "</div></div>";

		  	$FollowersArray = array('TotalCount' => $TotalCount, 'List' => $FriendsInfo);

			return $FollowersArray;
		
		}		

	function TranslateEmail($EmailAddress) {
		
			$sql_ExistingEmail = "Select UName FROM Users WHERE EmailAddress='{$EmailAddress}' LIMIT 1";
			$Result_ExistingEmail = Select($sql_ExistingEmail);	
			
			if ($Result_ExistingEmail) { return $Result_ExistingEmail[0]['UName']; }
			else { return FALSE;}			
		
		}


	function CreateLayer($LayerName,$Description,$UName) {
				// Check for existing Layer by that name
				$sql_ExistingLayer = "Select * FROM Layers WHERE LayerName='{$LayerName}' LIMIT 1";
				$Result_ExistingLayer = Select($sql_ExistingLayer);					
			
				if ($Result_ExistingLayer) { 
				
					if ($Result_ExistingLayer[0]['UName'] == $_SESSION['UName'] && $Result_ExistingLayer[0]['Active'] == "No") { return "You currently have an inactive layer with this name."; } 
					elseif ($Result_ExistingLayer[0]['UName'] == $_SESSION['UName']) { 											 return "You already have an active layer with this name."; } 
					else { 																										 return "Layer name already in use."; }
						
				} else {
					// Sanitize data before database entry
					 OpenDatabase();
			
						 $LayerName = Sanitize_Data($LayerName);
						 $Description = Sanitize_Data($Description);
					  
					 CloseDatabase();						
					
					$sql_InsertLayer = "INSERT INTO Layers(UName, LayerName, Description, Active) VALUES('{$UName}','{$LayerName}','{$Description}','Yes')"; 
					if (Insert($sql_InsertLayer)) { return "Successfully Created"; } else { return FALSE; }
				}
		}


	function ExistingLayerLink($UName,$LayerID) {

			//Check that Layer Exists
			$sql_ExistingLayer = "SELECT * FROM Layers WHERE LayerID='{$LayerID}' LIMIT 1";
			$Result_ExistingLayer = Select($sql_ExistingLayer);	

			if ($Result_ExistingLayer) {
				//Check that it's not their own layer
				if ($Result_ExistingLayer[0]['UName'] == $UName) { return "NotYourself"; }
				else {							

					//Otherwise, get linkage status
					$sql_ExistingLink = "Select Accepted FROM LinkageList WHERE UName='{$UName}' AND LayerID='{$LayerID}' LIMIT 1";
					$Result_ExistingLink = Select($sql_ExistingLink);					
					
					//if a relationship exists, sent status...otherwise, FALSE
					if ($Result_ExistingLink) { return $Result_ExistingLink[0]['Accepted']; } else { return FALSE; }
				}
		
			} else { return "NoLayer"; }		
			
		}
	
	function LinkLayers($UName,$LayerID) {
	
			// Check if there's already a relationship...contains either error message or LayerID for insertion
			$LinkageStatus = ExistingLayerLink($UName,$LayerID);  
			
			if ($LinkageStatus == "NoLayer") { return "NoLayer"; } 
			elseif ($LinkageStatus == "Block") { return "Blocked"; } 
			elseif ($LinkageStatus == "ok") { return "AlreadyExists"; } 
			elseif ($LinkageStatus == "NotYourself") { return "NotYourself"; } 
			elseif ($LinkageStatus == "Remove") {
				
		    	$sql_UpdateLinkages = "UPDATE LinkageList SET Accepted='ok' WHERE UName='{$UName}' AND LayerID='{$LayerID}'"; 
		    	if (Update($sql_UpdateLinkages)) { return "Updated"; } else { return FALSE; }
				
			} else {
				
		    	$sql_InsertLinkages = "INSERT INTO LinkageList(UName, LayerID, Accepted) VALUES('{$UName}','{$LayerID}','ok')"; 
		    	if (Insert($sql_InsertLinkages)) { return "Linked"; } else { return "Error-NewbieLink"; }
			}
		}


	function RemoveFollowing($UName,$LayerID) {
		
			// 	make sure to check a person is expiring a promo created by their UName
			$sql_RemoveFriend = "UPDATE LinkageList SET Accepted='Remove' WHERE UName='{$UName}' AND LayerID='{$LayerID}'";
			$Result_RemoveFriend = Update($sql_RemoveFriend);	
			
			if ($Result_RemoveFriend) { return "Successfully Removed"; }
			else { return FALSE;}		
		}
		
		
	function BlockFollower($UName,$LayerID) {
			// 	make sure to check a person is expiring a promo created by their UName
			$sql_BlockFriend = "UPDATE LinkageList SET Accepted='Block' WHERE UName='{$UName}' AND LayerID='{$LayerID}' LIMIT 1";
			$Result_BlockFriend = Update($sql_BlockFriend);	
			
			if ($Result_BlockFriend) { return "Successfully Blocked"; }
			else { return FALSE;}		
		}	
	

	function GetFacebookFriends() {
			
			$FriendsInfo = "<ul style='padding:10px 0; margin:5px;'>";
			
			//---------------------------------------------
			//------  Get Data if cookies are there -------
			//---------------------------------------------
			if (empty($_SESSION['FB_UserID']) or empty($_SESSION['AccessToken'])) { 
				$FriendsInfo = "<br><div align='center'>To use this feature, please <br><a href='Facebook.php?f=LinkAccount'>link your account to Facebook</a>.</div><br><br>"; 
			} else {	

				
				$test = "Yes";
				
				// to test 
				if ($test == "Yes") { 	
						$FriendData_JSON = '{
						   "data": [
							  {
								 "name": "Ali Wadsworth Henriques",
								 "id": "402593"
							  },
							  {
								 "name": "Timothy Chi",
								 "id": "417163"
							  },
							  {
								 "name": "Teresita Alvarez-Bjelland",
								 "id": "100000849351484"
							  },
							  {
								 "name": "Ralph Suarez",
								 "id": "100001079077472"
							  },
							  {
								 "name": "Jose Ramirez",
								 "id": "1002007"
							  },
							  {
								 "name": "Yara Lorenzo",
								 "id": "1010859"
							  },
							  {
								 "name": "Peter Perez",
								 "id": "1308610"
							  }			  
							  ]	
						}';
						
						$FriendData = json_decode($FriendData_JSON, true);
				} else {

						//Sample https://graph.facebook.com/553177974/friends?access_token=AAAFVbjXnFn0BAJOIGslIJsGJQGt7MmRWRXopZCDYvyE7wvyId2mINrrVBRGZAYpY9oZCdf1HQs424SA7IrHtNYS3b4dSSLyTgvvC5rZA0gZDZD

						$FriendDataUrl = "https://graph.facebook.com/{$_SESSION['FB_UserID']}/friends?access_token={$_SESSION['AccessToken']}";  

//					    $ch = curl_init();
//					    curl_setopt($ch, CURLOPT_URL, $FriendDataUrl);
//					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//					    $FriendData = json_decode(curl_exec($ch), true);
//					  
//					    curl_close ($ch);					

				}

				$Background = "Black";	

				foreach($FriendData['data'] as $FriendDetail) {
			
					if ($Background == "DarkGray") { $Background = "Black"; } else { $Background = "DarkGray"; } 
					
					$FriendsInfo .= "<li class='GrayMouseOver {$Background}' style='padding:0; height:45px;'>
 										
											<span style='float:left; padding:2px 3px'><img src='https://graph.facebook.com/{$FriendDetail['id']}/picture?type=square' border='0'> </span>
											<strong>{$FriendDetail['name']}</strong> <br>
											<input type='checkbox' name='FriendsList' value='{$FriendDetail['id']}'> Select &nbsp; | &nbsp; Remove
								 
									</li>";
				}
			} 

		
			$FriendsInfo .= "</ul>";
			
			return $FriendsInfo;		
		
		}

		
	function GetUserInfo($UName) {
		
		//Get Session Info, return an array of all details
		
		}

	function GetUserLayers($UName) {
		
		$sql_Layers = "SELECT * FROM Layers WHERE UName='{$UName}'";
		$Result_Layers = Select($sql_Layers);
		
		Return $Result_Layers;
		
		}

	function GetLayerDetails($LayerID) {

		$sql_Layers = "SELECT * FROM Layers WHERE LayerID='{$LayerID}'";
		$Result_Layers = Select($sql_Layers);
		
		Return $Result_Layers;
		
		}
		
	function GetPassword($EmailAddress) {
		$sql_Password = "SELECT PWord FROM Users WHERE EmailAddress='{$EmailAddress}'";
		$Result_Password = Select($sql_Password);
		
		Return $Result_Password[0]['PWord'];
		Return "not found.  Please contact support at messages@layr.es.";
		}			


?>
