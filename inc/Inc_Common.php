<?php      

// -------------------------------
// -- Variable Definitions
// -------------------------------
	$VendorID = $SignUp_Message = $SignIn_Message = $OriginalUsername = $Visits = "";
	$UName1 = $PWord1 = $UName2 = $PWord2 = $FullName = $EmailAddress = $Address1 = $Zip = $Zip2 = "";
	$sql_GetProfileData = $SignUp_Message = $MyAccountInfo = $PersonalInfo = $ProfileInfo = $AccountMenu = "";
	$SignIn_Message = $Success_Message = $ErrorMessage  = $Password_Message = $Support_Message = $Message = $UName = $PWord = $EmailAddress = $Visits = "";
	$ProfilePicture = $FirstName = $LastName = $StreetAddress = $City = $State = $Zip = $Website = $Description = "";
	$Home_Current = $Map_Current = $PromotionTypes_Current = $Playing_Current = $FAQ_Current = $Login_Current = $LoginURLs = "";
	$MyAccount_Current = $WelcomeMessage = $FirstName = $JavascriptAlert = $MyAccountLink = "";	
	$MyPromotions = $CompanyName = $Who = $WhatToDo = "";
	$sql_ProfileData = $MyAccountInfo = $PersonalInfo = $LocationInfo = $LocationID = "";
	$Message_Account = $Message_Location = $Message_Promotions = $MyPromotions = $DisplayOptions = "";
	$WhatToDoNext_MyAccount = $ButtonValue_MyAccount = $WhatToDoNext_Location = $ButtonValue_Location = "";
	$WhatToDoNext_Promotions = $ButtonValue_Promotions = $HiddenVariables = "";
	$Locations = $PromoCounter = $Show = $LocationName= "";
	$sql_GetProfileData = $Result_CreateSomeone = $Result_CreateUser = $Result_CreateVendor = $MyRewards = "";
	$LocationOptions = $TaskType = $RewardType = $vVendorID = $PromoCreation_Message = $HiddenVariables = $StartDate = $EndDate = "";
	$RewardType = $vNumAvailable = $Task = $Reward = $Answer = "";
	$TaskHTML = $RewardType = $RewardHTML = $InfoBubble = $CongratsMessage = $Phone = "";
	$WhatToDoNext_Layers =  $LayersID = $Message_Layers = $LayersInfo = $ButtonValue_Layers = "";
	$CompletedPromotions = $PromotionsInProgress = $SponsoredPromotions_DisplayOptions = $ErrorMessage_Promotions = "";
	$Mode = $AntiMode = $MCOptions1 = $TaskID = "";
	$Message_Promotions = $SecretInfo = $AllDays = "";
	$LatLong = $Locations = $MapMarkers = $InfoBubble = $LatLongArray = "";
	$ArrayDebug = $Replace = $PreviousStep = $Selected = $Checked = $acesstocken = "";
	$Restrict = $Show = $OrderBy = $LayerNameClass = "";
	$EarnedPromotions = $Coupon = $Progress = $Appointment = $Question = "";
	$CheckIn = $Referral = $Status = $Information = "";	
	$Result_SaveTaskData = $Result_SaveRewardData = "";
	$Message1 = $Information = $ExpirationDateHTML = $ShowCoupon = 	$ShowEditLocation = $CreatingPromotion = "";
	$FB_Action = $FB_UserID = $Gender = $BDay = $LinkageStatus = $ProfilePicture = $FollowingLink = $FollowersLink ="";
	$CreatePromotionsJS = $RequiresProximity = $Proximity = $Yes_ProximityChecked = $No_ProximityChecked =""; 
	$MondayChecked = $TuesdayChecked = $WednesdayChecked = $ThursdayChecked = $FridayChecked = $SaturdayChecked = $SundayChecked = "";
	$AccountMenuConsumer = $AccountMenuSponsor = $AccountMenuCreate = "";
	$Hide1 = $ProximityClass = $VendorClass = $ExpirationClass = $HiddenClass = $ConsumedClass = $LocationClass = $DateClass = $ActiveClass = $ExpiredClass = ""; 
	$LocationValue = $EarnedRewards = $SponsoredPromotions = $ErrorMessage_SignUp = $ErrorMessage_SignIn = $LayersIFollow_LayerName = $MyLayers_LayerName = "";
	$SignUp_FullName = $SignUp_EmailAddress = $SignUp_Zip = $SignUp_UName = $SignUp_PWord = $SignIn_UName = $SignIn_PWord = "";
	$Marker_StreetAddress = $Marker_Zip = $Marker_LayerID = $Marker_Message = $Marker_Title = $LayerOptions = $Marker_Type = $Marker_EndDate = $Marker_StartDate = "";
	$LayerLinkButton = $MyLayers_Description = $Address_Search = $AllClass = $PromotionsClass = $MarkersClass = "";
	$varLocationPoint = $varLocation = $FeedResults = $MyMarkers_DisplayOptions = $DeleteMe = $Result = $SearchQuery = $Marker_Quantity =  $Marker_Confirmation = "";
	$Marker_LocationName = $Marker_LocationName1 = $Marker_Location = $LocationOptions = $Marker_LocationID = $Marker_Question = "";
	$AddressChecked = $LayerChecked = $PersonChecked = "";

// -------------------------------
// -- Misc Variables
// -------------------------------
	$counter = 0;			// initialize the counter once
	$DayStartTimeOptions = $DayEndTimeOptions = $StartChecked = $EndChecked = "";
	$Define_Markers = "var marker";
	$Define_Locations = "var Home";

	$JoinDate = $CreateDate = $LastLogin = $CurrentTime = date('Y/m/d G:i:s');
	$IPAddress = $_SERVER['REMOTE_ADDR'];

	//Page name
	$ThisPage = basename($_SERVER['SCRIPT_NAME']);


	$StartBorder_blue = "<div class='block1' style='width:100%'><div class='t'><div class='r'><div class='b'><div class='l'><div class='l_t'><div class='r_t'><div class='r_b'><div class='l_b'><div class='ind'>";
	$EndBorder_blue = "<div class='clear'></div></div></div></div></div></div></div></div></div></div></div>";
	$StartBorder_brown = "<div align='center'><div class='block' style='width:310px; text-align:left;'><div class='t'><div class='r'><div class='b'><div class='l'><div class='l_t'><div class='r_t'><div class='r_b'><div class='l_b'><div class='ind'>";
	$EndBorder_brown = "<div class='clear'></div></div></div></div></div></div></div></div></div></div></div></div>";
	
	$RegField = " onFocus='clearText(this)' onBlur='clearText(this)'  class='SmallField' style='width:150px' ";
	$FormTextField = " class='SmallField' style='width:170px' ";


	// create time options dropdown
	$SelectTimeOptions = "";
	$TimeOptions = array('12:00', '12:30', '1:00', '1:30', '2:00', '2:30', '3:00', '3:30', '4:00', '4:30', '5:00', '5:30', '6:00', '6:30', '7:00', '7:30', '8:00', '8:30', '9:00', '9:30', '10:00', '10:30', '11:00', '11:30');
	$AMPM = array('AM', 'PM');
	
	
	foreach ($AMPM as $ampm) {
		foreach ($TimeOptions as $Hour) {
			
			$CurrentOption = "{$Hour} {$ampm}";
			
			if (isset($_SESSION['DayStartTime'])){ 
				
				if ($_SESSION['DayStartTime'] == $CurrentOption) { $StartChecked = "selected='selected'"; }
				else { $StartChecked = ""; }
				
			   } 
			elseif ($CurrentOption == "10:00 AM") { $StartChecked = "selected='selected'"; } 
			else { $StartChecked = ""; }
			
			if (isset($_SESSION['DayEndTime'])){ 
				
				if ($_SESSION['DayEndTime'] == $CurrentOption) { $EndChecked = "selected='selected'"; }
				else { $EndChecked = ""; }

			} 
			elseif ($CurrentOption == "6:00 PM") { $EndChecked = "selected='selected'"; } 
			else { $EndChecked = ""; }
		
			$DayStartTimeOptions .= "<option value='{$Hour} {$ampm}' {$StartChecked}>{$Hour} {$ampm}</option>";
			$DayEndTimeOptions .= "<option value='{$Hour} {$ampm}' {$EndChecked}>{$Hour} {$ampm}</option>";
		
			}
	}


// -------------------------------
// -- Map functions
// -------------------------------

	// Convert Address into Lat, Long coordinates
	function ConvertAddress($CompleteAddress) {

	    // Put the address into the URL call
		$Address = "http://maps.googleapis.com/maps/api/geocode/xml?address=";
		$Address .= urlencode($CompleteAddress);
		$Address .= "&sensor=false";

		// Store XML content of address into variable and parse it
		$XMLcontent = Get_AddressData($Address);	
			
		$xmlObj = simplexml_load_string($XMLcontent);
		$arrXml = objectsIntoArray($xmlObj);

		//print_r($arrXml['result']['geometry']['location']);
	  
		// If address is not found, protect against an ugly error message
		if (isset($arrXml['result']['geometry'])) {
		
			$Latitude = $arrXml['result']['geometry']['location']['lat'];
			$Longitude = $arrXml['result']['geometry']['location']['lng'];
					  
			$LatLong = array("LocationLatitude" => $Latitude, 
							 "LocationLongitude" => $Longitude);
  
		  } else {
  
			  $LatLong = array("LocationLatitude" => 0, 
							   "LocationLongitude" => 0);
  
		  }
		  
		  return $LatLong;
		
		}


			// Return contents of the http:// request in a variable.....presumably XML
			function Get_AddressData($url) {
				
				  $c = curl_init();
				  curl_setopt($c, CURLOPT_URL, $url);
				  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				  $content = trim(curl_exec($c));
				  curl_close($c);
				  
				  return $content;		
				}
		
		
			// Parse XML to get latitude and longitude
			function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
				
				$arrData = array();
				
				// if input is object, convert into array
				if (is_object($arrObjData)) {
					$arrObjData = get_object_vars($arrObjData);
				}
				
				if (is_array($arrObjData)) {
					foreach ($arrObjData as $index => $value) {
						if (is_object($value) || is_array($value)) {
							$value = objectsIntoArray($value, $arrSkipIndices); // recursive call
						}
						if (in_array($index, $arrSkipIndices)) {
							continue;
						}
						$arrData[$index] = $value;
					}
				}
				return $arrData;
				
			}
	
	
	function GetPlaces($LatLong) {
		
		return $LatLong;
		}


// -------------------------------
// -- Misc parameter detections
// -------------------------------

	session_start();

	// ## log out
	if (isset($_GET['LogOut'])) {
			 $_SESSION['UName'] = "";
			 $_SESSION['FullName'] = "";
			 $_SESSION['PWord'] = "";
			 $_SESSION['LocationLatitude'] = "";
			 $_SESSION['LocationLongitude'] = "";
			 $_SESSION['EmailAddress'] = "";
			 $_SESSION['LoginType'] = "";
			 $_SESSION['Visits'] = "";
			 $_SESSION['AccessToken'] = "";
	
		session_destroy();
		
		header("Location:Registration.php");
		exit();	
		}


	// ## If user clicks on link with Lat / Long values for map 
	if (isset($_GET['Lat'])) { 
		$LatLong = $_GET['Lat'] . "," . $_GET['Long']; 
		
		// Add Marker
		$MapMarkers .= "
				marker = new mxn.Marker(Home);
				//marker.setIcon('gfx/icons/RedBorder.png',[34,34]);
				//mapstraction.addMarker(marker);			
				";	
		}

	// ## If user enters address in form
	if (isset($_POST["CompleteAddressForm"])) {
		$CompleteAddress = $_POST["CompleteAddress"];
		$LatLongArray = ConvertAddress($CompleteAddress);

		$LatLong = "{$LatLongArray['LocationLatitude']},{$LatLongArray['LocationLongitude']}";

		$MapMarkers .= "
				marker = new mxn.Marker(Home);
				marker.setIcon('gfx/icons/OrangeFlag.png',[25,25]);
 
				";
				
		if ($LatLong == "0,0") { 
				$InfoBubble = "<h3 class='DarkGray'>Welcome to Layr.es!</h3> <strong>This location was not found:</strong><br>$CompleteAddress"; 
				$MapMarkers .= " marker.setInfoBubble(\"$InfoBubble\"); ";
//				$MapMarkers .= " marker.openBubble(); ";
				}

		$MapMarkers .= "			
				mapstraction.addMarker(marker);		
				";
	}

// -------------------------------
// -- Common functions
// -------------------------------

	//create absolute URL from relative
	function absolute_url($page) {
		  
		  $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		  $url = rtrim($url, '/\\');
		  $url .= '/' . $page;
		  
		  return $url;
		  
		}


	function NotEmpty($input) {		 
	
		  $strTemp = $input;
		  $strTemp = trim($strTemp);
	  
		  if($strTemp != '') { return true; }
			else { return false; } 

		}


    function validateEmail($email){  
        return ereg("^[a-zA-Z0-9]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$", $email);  
    	} 


	function Redirect($URL, $Flag="None") {

			if ($Flag == "LoggedIn") {
				
				if (isset($_SESSION['UName'])) { 
				   $url = absolute_url($URL);	
				   header("Location: $url");
				   exit();		 
				 }
			} elseif ($Flag == "NotLoggedIn") {
	
				 if (!isset($_SESSION['UName'])) {
					   $url = absolute_url($URL);
					   header("Location: $url");
					   exit();		 
				 } 
			} else {
				 $url = absolute_url($URL);	
				 header("Location: $url");
				 exit();	
				 
				}
			
		}

	function FormatDate($RawDate) {

			$date = new DateTime($RawDate);
			return $date->format('M j Y');
			//return $date->format('M d, Y');
				
		}

	function Pagination() { }

	


	function get_latlon(){
		// return "25.7889689, -80.2264393";
		
	$SQL=Select("SELECT Promotions.PromotionID, Promotions.UName, Promotions.EndDate,
		DATE_FORMAT(Promotions.CreateDate, '%l:%i (%M %e)') AS CreateDate,
		DATE_FORMAT(Promotions.CreateDate, '%M %e') AS PromoDay,
		DATE_FORMAT(Now(), '%M %e') AS TodaysDay,
		PromoTitle, TaskType, RewardDescription, TaskDescription, RewardType,
		LocationName, Location.LocationLatitude, Location.LocationLongitude,
		Location.StreetAddress, Location.Zip, Location.Phone FROM Promotions, Location_Promotions, Location, Layers WHERE Location_Promotions.PromotionID = Promotions.promotionID
		AND Location_Promotions.LocationID = Location.LocationID
		AND Location_Promotions.LayerID = Layers.LayerID AND Promotions.StartDate < Now()
		AND Promotions.EndDate > Now()  AND Location_Promotions.PromoQuantity > Location_Promotions.PromosEarned AND ( Layers.LayerID IN (Select LinkageList.LayerID FROM LinkageList, Location_Promotions
		WHERE LinkageList.LayerID = Location_Promotions.LayerID
		
		AND Accepted='ok')
		OR Promotions.PromoRange IN ('All','Personal')
		)  AND Layers.LayerID = '$_GET[ID]' ");
		
	//print_r($SQL);
	
	return $SQL[0]['LocationLatitude'].','.$SQL[0]['LocationLongitude'];
		
	}
	
?>        
