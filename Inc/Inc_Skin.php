<?php

    require_once 'Inc/Inc_Common.php';
/*
	function ErrorMessage($Message,$Type="") {}
	function ShowCoupon($Message) {	}	
	function PromoHeaderUI($PromoDetailsArray,$PromoType) {
	function PromoFooterUI($PromoDetailsArray) {
	function PromoUI($PromoDetailsArray, $IncludeForm=TRUE) {		
	function EarnedRewardsUI($PromoDetailsArray) {
	function SponsoredPromosUI($PromoDetailsArray) {
	function EditPromoFormUI($PromoDetailsArray) {
	function PromoResultsUI($PromotionResultsArray) {
	function PromoRewardsUI($PromotionResultsArray) {
	function LayerListUI($LayerResultsArray) {	}	
	function MyMarkersUI($PromoDetailsArray, $Toggled="") { }
	function ShowMyEmptyLayersUI($PromoDetailsArray) { }	
*/

	function ErrorMessage($Message,$Type="") {	

		return "<br><div class='ErrorMessage{$Type}' align='center'>{$Message}</div>";
		}

	function ShowCoupon($Message,$RewardID="",$LocationName="Earned Coupon") {
		
		$Message = str_replace("**J",'>',$Message);
		$Message = str_replace("L**",'<',$Message);			

		if ($RewardID == "#") { $MarkAsUsed = "<a href='#'>Mark as Used</a>"; } 
		elseif ($RewardID) { $MarkAsUsed = "<a href='MyAccount.php?Consumed={$RewardID}'>Mark as Used</a>"; } 
		else { $MarkAsUsed = "<strong class='Black'>Consumed</strong>"; }

		$CouponUI = "<div align='center' style='padding: 15px 0;'><div class='block' style='width:290px; text-align:left;'><div class='t'><div class='r'><div class='b'><div class='l'><div class='l_t'><div class='r_t'><div class='r_b'><div class='l_b'><div class='ind'>";

		$CouponUI .= "<h2 class='headerimage'>{$LocationName}</h2><br />
					  {$Message}
					  <div>
						  <!--<span class='Coupon_Body AlignLeft'>Earned By: <strong>{$_SESSION['UName']}</strong></span>-->
						  <span class='AlignRight BabyBlue SmallFont'>{$MarkAsUsed}</span>
					  </div>";
					  
							
		$CouponUI .= "<div class='clear'></div></div></div></div></div></div></div></div></div></div></div></div>";

		return $CouponUI;
		}

	function PromoHeaderUI($PromoDetailsArray,$PromoType="") {
		//print_r($PromoDetailsArray);
		
		  $EndDate = FormatDate($PromoDetailsArray['EndDate']);
		
		  $Promotions_Header = "<div class='PromoDetails'>
								<span style='padding:7px 15px 25px 7px; float:left;'><img src='gfx/icons/{$PromoDetailsArray['TaskType']}.png'></span>
								<h3 class='Red' style='padding-top:7px;'>{$PromoDetailsArray['PromoTitle']}</h3> 
								<div class='SmallFont AlignLeft'>
									Promo End Date: <strong><i>{$EndDate}</i></strong>
									</div>
								
								<div class='clear'></div>";
								
		if ($PromoType <> "Question" AND $PromoType <> "ThankYou") { 
		  $Promotions_Header .= "<div align='center'><img src='gfx/Buttons_Check-in.png' hspace='5' onclick=\"PromoDetails('{$PromoDetailsArray['TaskType']}',{$PromoDetailsArray['Proximity']});\"></div>"; 
		  }
								
		  $Promotions_Header .= "<hr noshade='noshade'>
								
								<div style='padding:5px 7px;'>
								";
									
		   return $Promotions_Header;
			}

	function PromoFooterUI($PromoDetailsArray, $IncludeForm=TRUE) {

		  if ($IncludeForm) {	
				  
				  $AddressLink = "<a href='Map.php?lat={$PromoDetailsArray['LocationLatitude']}&long={$PromoDetailsArray['LocationLongitude']}&z=13' class='BabyBlue SmallFont'>
									  {$PromoDetailsArray['StreetAddress']}</a>"; 
									  
		  } else { $AddressLink = "{$PromoDetailsArray['StreetAddress']}"; }

		  $EndDate = FormatDate($PromoDetailsArray['EndDate']);
		
		  $Promotions_Footer = "</div>
		  
		  						<hr noshade='noshade'>
								  <h4 class='Red'>{$PromoDetailsArray['LocationName']}</h4>
								  <div style='padding:0 7px;'>
									  <span class='AlignLeft BabyBlue SmallFont'>
										$AddressLink
									  </span>
									  <span class='AlignRight BabyBlue SmallFont'>{$PromoDetailsArray['Phone']}</span><br>
									  <div class='SmallFont'>{$PromoDetailsArray['Description']}</div>
								  </div>
								  
								  </div>";

		   $Promotions_Footer .= "<div align='center' class='SmallFont VeryLightGray'>
		   							Promotion offered by: <strong>{$PromoDetailsArray['UName']}</strong>";

		   if (!empty($PromoDetailsArray['Verified'])) { $Promotions_Footer .= " <img src='gfx/verified.png'> "; }
									
		   $Promotions_Footer .= "</div>
								  <script>
								  	// set Lat and Long JS cookie here
									setCookie(\"PromoLatitude\",{$PromoDetailsArray['LocationLatitude']},1);
									setCookie(\"PromoLongitude\",{$PromoDetailsArray['LocationLongitude']},1);
								  </script>
								  ";
		   
		   
		   return $Promotions_Footer;
			}


	function PromoUI($PromoDetailsArray, $IncludeForm=TRUE) {

		// Title section
		if ($IncludeForm) {
			
			$Message_Promotions = "<form name='PromoDetailsForm' method='post'>
									<input type='hidden' name='PID' value='{$PromoDetailsArray['PromotionID']}'>
									<input type='hidden' name='CheckIn' value='Yes'>";

		} else { $Message_Promotions = ""; }
		  
		  $Message_Promotions .= PromoHeaderUI($PromoDetailsArray,$PromoDetailsArray['TaskType']);					


		  //---- Reward Section
		  if ($PromoDetailsArray['RewardType'] == "Coupon") { 
			  if (NotEmpty($PromoDetailsArray['PromotionID'])) { 
			  		
					$ExpirationDate = CouponExpiration($PromoDetailsArray['PromotionID'],"Simple"); 
					$CouponExpiresDate = "<span class='SmallFont Italics LightGray'>(<strong>Coupon Expires</strong>: {$ExpirationDate})</span>"; 
				} else { $CouponExpiresDate = ""; }
			  
		  } else { $CouponExpiresDate = ""; }

		  $Message_Promotions .= "<h4>What's in it for me?</h4>
								  <p>{$PromoDetailsArray['RewardDescription']} {$CouponExpiresDate}</p>";

	
		  if ($PromoDetailsArray['RewardType'] == "Status") {
			  
				$sql_StatusData = "SELECT * FROM Promotions_Rewards_Status WHERE RewardID = {$PromoDetailsArray['RewardID']}";
				$Result_StatusData = Select($sql_StatusData);		
				
			  	  if ($Result_StatusData) {
	
					// Determine individual's status
					if (isset($_SESSION['UName'])) {
										$StatusArray = DetermineStatus($PromoDetailsArray['PromotionID']);
										
										$CurrentStatus = " &nbsp; Current Status: <span class='SmallFont Green'>{$StatusArray['CurrentStatus']}</span>
																		<br> &nbsp; <span class='SmallFont'>(Next Status: {$StatusArray['NextStatus']})</span>
																						"; }
					else { $CurrentStatus = " &nbsp; <span class='SmallFont Green'>(Sign in to see current status)</span>"; }
				
					$Message_Promotions .= "<div align='center' class='PromoDetailsBox'>{$CurrentStatus}</div>";
				
				  } else {

					$StatusArray = DetermineStatus($PromoDetailsArray['PromotionID']);
	
					// Determine individual's status
					if (isset($_SESSION['UName'])) { $CurrentStatus = " &nbsp; Current Status: <span class='SmallFont Green'>No Status Yet</span>
																		<br> &nbsp; <span class='SmallFont'>(Next Status: {$_SESSION['StatusLevel1']})</span>
																						"; }
																						
					$Message_Promotions .= "<div align='center' class='PromoDetailsBox'>{$CurrentStatus}</div>";
					  
					}
										
			  }

		  if ($PromoDetailsArray['Proximity'] == "1") { 
		  	$RequiresProximity = "<div class='AlignRight LightGray SmallFont'>(*Requires Proximity)</div>
									<div id='ErrorMessage' class='ErrorMessageHide'></div>"; } 
		  else { $RequiresProximity = ""; }

		  //---- Task Section
		  $Message_Promotions .= "<h4 style='padding-top:15px;'>What do I need to do? {$RequiresProximity}</h4>
								  <p>{$PromoDetailsArray['TaskDescription']}</p>";


		  if ($PromoDetailsArray['TaskType'] == "Appointment") {
			  
				  $sql_AppointmentData = "SELECT * FROM Promotions_Task_Appointment WHERE TaskID = {$PromoDetailsArray['TaskID']}";
				  $Result_AppointmentData = Select($sql_AppointmentData);							
			  
			  	  if ($Result_AppointmentData) {
					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'><i><strong>Valid Promotion Times:</strong></i>
											  
												  <br><i>{$Result_AppointmentData[0]['ValidDays']}</i></div>";					
				  	} else { 
					
					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'><i><strong>Valid Promotion Times:</strong></i>
											  
												  <br><i>{$_SESSION['ValidDays']}</i></div>";	
					}
			  }

		  if ($PromoDetailsArray['TaskType'] == "Progress") {

				  $sql_ProgressData = "SELECT * FROM Promotions_Task_Progress WHERE TaskID = {$PromoDetailsArray['TaskID']}";
				  $Result_ProgressData = Select($sql_ProgressData);		

			  	  if ($Result_ProgressData) {
					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'><i>Number of Check-Ins to Earn Reward:</i>
											  <br>{$Result_ProgressData[0]['CheckInNumber']}</div>";
				  	} else { 
					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'><i>Number of Check-Ins to Earn Reward:</i>
											  <br>{$_SESSION['CheckInNumber']}</div>";
						}
			  }

		  if ($PromoDetailsArray['TaskType'] == "Question") {
			  
				  $sql_QuestionData = "SELECT * FROM Promotions_Task_Question WHERE TaskID = {$PromoDetailsArray['TaskID']}";
				  $Result_QuestionData = Select($sql_QuestionData);	

			  	  if ($Result_QuestionData) {
					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'>
											 <div> <b>{$Result_QuestionData[0]['Question']}</b></div>";
							  
						  if (!Empty($Result_QuestionData[0]['MCOptions1'])) {
								if ($Result_QuestionData[0]['MCOptions1']) { 
											$Message_Promotions .= " &nbsp; <input type='radio' value='{$Result_QuestionData[0]['MCOptions1']}' name='Response'> {$Result_QuestionData[0]['MCOptions1']}";}
								if ($Result_QuestionData[0]['MCOptions2']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' value='{$Result_QuestionData[0]['MCOptions2']}' name='Response'> {$Result_QuestionData[0]['MCOptions2']}";}
								if ($Result_QuestionData[0]['MCOptions3']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' value='{$Result_QuestionData[0]['MCOptions3']}' name='Response'> {$Result_QuestionData[0]['MCOptions3']}";}
								if ($Result_QuestionData[0]['MCOptions4']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' value='{$Result_QuestionData[0]['MCOptions4']}' name='Response'> {$Result_QuestionData[0]['MCOptions4']}";}
							  } 
							else {
								   $Message_Promotions .= "<textarea style='width:230px; height:40px;' name='Response'></textarea>";
							  }						

				  		} else { 

					  $Message_Promotions .= "<div align='center' class='PromoDetailsBox'>
											 <div> <b>{$_SESSION['Question']}</b></div>";
							  
						  if (!Empty($_SESSION['MCOptions1'])) {
								if ($_SESSION['MCOptions1']) { 
											$Message_Promotions .= " &nbsp; <input type='radio' name='Response'> {$_SESSION['MCOptions1']}";}
								if ($_SESSION['MCOptions2']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' name='Response'> {$_SESSION['MCOptions2']}";}
								if ($_SESSION['MCOptions3']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' name='Response'> {$_SESSION['MCOptions3']}";}
								if ($_SESSION['MCOptions4']) { 
											$Message_Promotions .= "<br> &nbsp; <input type='radio' name='Response'> {$_SESSION['MCOptions4']}";}
							  } 
							else {
								   $Message_Promotions .= "<textarea style='width:230px; height:40px;' name='Response'></textarea>";
							  }	
						
						}

					  $Message_Promotions .= "<div align='center'><img src='gfx/Buttons_Submit.png' hspace='5'onclick=\"PromoDetails('{$PromoDetailsArray['TaskType']}',{$PromoDetailsArray['Proximity']});\" ></div>";
					  
				  
				  $Message_Promotions .= "</div>";
				  
			  }
			  

		  // Final Section
		  $Message_Promotions .= PromoFooterUI($PromoDetailsArray, $IncludeForm);

		  if ($IncludeForm) { $Message_Promotions .= "</form>"; }	

		  Return $Message_Promotions;	

		}


	function EarnedRewardsUI($PromoDetailsArray) {

			$ThisPage = basename($_SERVER['SCRIPT_NAME']);
			$CompletedPromotions = "<ul style='padding:10px 0 0 0px; margin:0;' class='ShowBorders'>";
			$RewardOptions = "";

			// loop through each earned promotion
			if (count($PromoDetailsArray)) { 
			  $num = 0; 
			  $BackgroundColor = "#FBFBFB";
				
			  foreach ($PromoDetailsArray as $results) {
				$num++;

				$RewardLabel = "View Reward";

				//-- Rewards = Coupon
				$ExpirationDateHTML = $ShowCoupon = "";

				if ($results['RewardType'] == "Coupon") {

					$sql_CouponData = "SELECT * FROM Promotions_Rewards_Coupon WHERE RewardID = {$results['RewardID']}";
					$Result_CouponData = Select($sql_CouponData);			

					$ExpirationDate =  FormatDate(Date($Result_CouponData[0]['ExpirationDate']));
					
					if (strtotime($Result_CouponData[0]['ExpirationDate']) > time()) {
							$ExpirationDateHTML = "<span class='SmallFont AlignRight'>Expires: <strong><i>{$ExpirationDate}</i></strong></span>"; }
					else {	$ExpirationDateHTML = "<span class='SmallFont AlignRight Black'><i>Expired: <a href='MyAccount.php?HidePromo={$results['RewardWonID']}' class='CancelX Red NoUnderline'>X</a> </i></span> "; }
					if ($results['UsedDate'] == "0000-00-00 00:00:00") { $MarkAsUsed = $results['RewardWonID']; } else { $MarkAsUsed = ""; }

					$ShowCoupon = ShowCoupon($Result_CouponData[0]['Coupon'],$MarkAsUsed); 
					$RewardOptions = "<a href='JavaScript:Void()' onClick=\"expandcontent('Earned{$num}')\" >View Coupon</a> &nbsp; | &nbsp; Gift Coupon";

				}  


				//-- Reward = Message
				$Message = "";
				
				if ($results['RewardType'] == "Message") {

					$sql_MessageData = "SELECT * FROM Promotions_Rewards_Information WHERE RewardID = {$results['RewardID']}";
					$Result_MessageData = Select($sql_MessageData);			

					$Message = "<div style='margin:10px 0 2px 30px; padding: 6px; line-height:14px; font-size:12px; border: #ccc 1px solid;'><strong class='Black'>Message:</strong> &nbsp; <i>{$Result_MessageData[0]['Information']}</i></div>";
					$RewardOptions = "<span onClick=\"expandcontent('Earned{$num}')\" class='SmallFont BabyBlue Underline' >View Message Reward</span>";
					
				} 

				//-- Reward = Status
				$CurrentStatus = "";
				
				if ($results['RewardType'] == "Status") { 
					$CurrentStatusArray = DetermineStatus($results['PromotionID']);
					$CurrentStatus = "<div class='SmallFont Green'> &nbsp; Current Status: <strong>{$CurrentStatusArray['CurrentStatus']}</strong></div>
									  <div  class='SmallFont Black'> &nbsp;  &nbsp; (Next Status: <strong>{$CurrentStatusArray['NextStatus']}</strong>)</div>"; 
					$RewardOptions = "<a href='JavaScript:Void()' onClick=\"expandcontent('Earned{$num}')\" >View Current Status</a>";
				}
				
					// Find Lat/Long of this promotion
					$sql_LocationDetailsData = "SELECT * FROM Location, Location_Promotions
												WHERE Location.LocationID = Location_Promotions.LocationID
												AND Location_Promotions.PromotionID = {$results['PromotionID']}					
												";
					$Result_LocationDetailsData = Select($sql_LocationDetailsData);			
					
					$Promo_LocationLatitude = $Result_LocationDetailsData[0]['LocationLatitude'];
					$Promo_LocationLongitude = $Result_LocationDetailsData[0]['LocationLongitude'];

					if ($results['Hide'] == "Yes") { $HideLink = "<div align='right' style='padding-right: 20px;'><a href='{$ThisPage}?UnHidePromo={$results['RewardWonID']}' class='SmallFont Black Underline'>Un-Archive</a></div>";}
					elseif ($results['Hide'] == "No") { $HideLink = "<div align='right' style='padding-right: 20px;'><a href='{$ThisPage}?HidePromo={$results['RewardWonID']}' class='SmallFont Red Underline'>Archive</a></div>";}
					else { $HideLink = ""; }

				if ($BackgroundColor == "#FFFFFF") { $BackgroundColor == "#FBFBFB"; } else { $BackgroundColor = "#FFFFFF"; }

				$CompletedPromotions .= "  
								<div style='padding-bottom:10px; margin:3px; background:{$BackgroundColor}'>
									<span style='padding:5px 10px 5px 0px; float:left;'><img src='gfx/icons/{$results['TaskType']}.png'></span>
									<h4 style='margin:0;' class='Green'>
										{$Result_LocationDetailsData[0]['LocationName']} &nbsp; &nbsp; 
										<span class='SmallFont'> <a href='{$ThisPage}?Lat={$Promo_LocationLatitude}&Long={$Promo_LocationLongitude}&Map=Yes' class='BabyBlue Underline'>Map</a></span>  
										{$ExpirationDateHTML}
									</h4>	
									
									<span style=\"padding-left:2px;\"><i>{$results['PromoTitle']}</i> &nbsp; &nbsp; </span>
									<span class='SmallFont'>{$RewardOptions}</span>
										
									<div id='Earned{$num}' class='switchcontent' style=\"padding:0 10px 0 10px;\">
										{$CurrentStatus}
										{$Message}
										{$ShowCoupon}
										<span class='SmallFont AlignLeft' style='margin:0 0 0 40px;'><strong>Promo By:</strong> {$results['UName']}</span>
										{$HideLink}
									</div>
								</div>	
						"; 
	
				}   // End Foreach record
	
			// if no records are found
			} else { 
				if (isset($_GET['Show'])) { $Show = $_GET['Show']; } else { $Show = ""; }
				
				$CompletedPromotions = "<div align='center' style='padding:30px;'>You do not have any <i>{$Show}</i> rewards at the moment. <br>Check the <a href='Map.php'>main map</a> or add some layers to see more promotions.</div>"; 		  				
				}		
			
			$CompletedPromotions .= " </ul>";
			$CompletedPromotions2 = "<br><div align='center'><a class='submit' href='Map.php'>See Available Promotions</a></div>
									 <div align='center' class='SmallFont'><a href='MyAccount.php?Mode=Hidden'>See Hidden Promotions</a></center>";
			
			return $CompletedPromotions;

		}


	function SponsoredPromosUI($PromoDetailsArray) {

			$PromoCounter = 0;
			$MyPromotions = "<div style='padding:10px 0 0 0px; margin:0;'>";
		
			if (count($PromoDetailsArray)) {
			
			  $BackgroundColor = "#FFFFFF";
				
			  foreach ($PromoDetailsArray as $results) {
			  
				// Format Date
				$StartDate = FormatDate($results['StartDate']);
				$EndDate = FormatDate($results['EndDate']);
				$PromoDetails = ShowPromo($results['PromotionID']);
				
				if (strtotime($results['EndDate']) > strtotime(date("Y-m-d H:i:s"))) {
					$EndNow = "<span class='SmallFont AlignRight BabyBlue'>
								 	<a href='MyAccount.php?Expire={$results['PromotionID']}'>(End Now)</a>   &nbsp;   &nbsp; 
						 	   </span><br>";
					} else { $EndNow = "	"; }


 					// Customize if it's a Marker
 					if ($results['TaskType'] == "Marker") { 
							$PromoType = $results['TaskType'];
							$MyPromotionsSubMenu = ""; 
							$PromoColor = "Brown";
							$TypeDetails = "<div class='SmallFont'>{$results['TaskDescription']}</div>";
							$MyPromotionsSubMenu = "<div class='SmallFont'>  Layer: &nbsp; {$results['LayerID']}</div>";
					} else {
							$PromoType = "{$results['TaskType']} / {$results['RewardType']}";
					  		$MyPromotionsSubMenu = "<div class='SmallFont' align='center'>  &nbsp;   &nbsp; 
												<a href='PromoDetails.php?PID={$results['PromotionID']}&Sponsored=Yes' class='BabyBlue'>View Promo</a> &nbsp; &nbsp; | &nbsp; &nbsp; 
												<!--<a href='MyAccount.php?EditPromo={$results['PromotionID']}' class='BabyBlue'>Edit Promo</a> &nbsp; &nbsp; | &nbsp; &nbsp; -->
												<a href='MyAccount.php?Results={$results['PromotionID']}' class='BabyBlue'>View Check-ins</a>  
												<!-- &nbsp; &nbsp; | &nbsp; &nbsp; <a href='MyAccount.php?Rewards={$results['PromotionID']}' class='BabyBlue'>View Rewards</a>-->
																					  
										  			</div>";
							$PromoColor = "Orange";			  
							$TypeDetails = "<div class='SmallFont'><strong class='Black'>Type:</strong> <i class='Black'>{$PromoType}</i></div>";
					} 


				if ($BackgroundColor == "#FFFFFF") { $BackgroundColor = "#FBFBFB"; } else { $BackgroundColor = "#FFFFFF"; }

				$MyPromotions .= "
							<div style='padding-bottom:10px; margin:3px; background:{$BackgroundColor}'>	
									<div class='col_1_2_tab'>
										<span style='padding:5px 10px 5px 0px; float:left;'><img src='gfx/icons/{$results['TaskType']}.png'></span>
										<h4 style='margin:0;'>{$results['LocationName']} &nbsp; &nbsp; <span class='SmallFont'> <a href='MyAccount.php?Lat={$results['LocationLatitude']}&Long={$results['LocationLongitude']}&Map=Yes' class='Underline BabyBlue'>Map</a>  </span></h4>
										<span><strong  class='{$PromoColor} italic'>{$results['PromoTitle']}</strong></span> 
									</div>
									
									<div class='col_1_2_tab last'>
										<div class='SmallFont' style='margin-top:7px;'>Promo Dates: <strong>{$StartDate} - {$EndDate}</strong> &nbsp; $EndNow &nbsp; </div>
										$MyPromotionsSubMenu
										<div class='clear'></div>
										
									</div>
							</div>				
				";


				$MyPromotions2 = "<!-- This is where the Content of the Lightbox Begins 
									  <div class='black_overlay preloaded-images' id='fade'>&nbsp;</div>

								  
									  <div class='white_content' id='LB{$results['PromotionID']}' style='width:315px; background-color:#333;'>
										  <p class='close-btn' style='left:0px; top:0px; text-align:right; background-color:#333; z-index:1100001; margin-bottom:-1px;'>
											  <a href='javascript:void(0)' class='White' onclick=\"document.getElementById('LB{$results['PromotionID']}').style.display='none';document.getElementById('fade').style.display='none'\">X</a></p>
										  
									  {$PromoDetails}
											  
									  </div>  -->
								  <!-- This is where the Content of the Lightbox Ends -->
								  ";

			  }   // End Foreach record

		  // if no records are found
		  } else { $MyPromotions .= "<div align='center' style='padding:30px;'>No promotions yet. What are you waiting for? <br><a href='MyAccount.php?Mode=Create'>Create a Promotion</a></div>"; 		  }	
		   
		  $MyPromotions .= "</div>";	
		  
		  return $MyPromotions;

		}	

	function PromoListUI($PromoDetailsArray) {

			$PromoCounter = 0;
			$ThisPage = basename($_SERVER['SCRIPT_NAME']);
			
			$MyPromotions = "<div style='padding:0; margin:0;'>";
		
			if (count($PromoDetailsArray)) {
			
			  $BackgroundColor = "#FFFFFF";
				
			  foreach ($PromoDetailsArray as $results) {
			  
				// Format Date
				$StartDate = FormatDate($results['StartDate']);
				$EndDate = FormatDate($results['EndDate']);
				$PromoDetails = ShowPromo($results['PromotionID']);
				
 					// Customize if it's a Marker
 					if ($results['TaskType'] == "Marker") { 
							$PromoType = $results['TaskType'];
							$MyPromotionsSubMenu = ""; 
							$PromoColor = "";
							$TypeDetails = "<div class='SmallFont'>{$results['TaskDescription']}</div>";
							$PromoDates = "";
							$PromoTitle = "";
							
					} else {
							$PromoType = "{$results['TaskType']} / {$results['RewardType']}";
					  		$MyPromotionsSubMenu = "<div class='SmallFont' align='center'>  &nbsp;   &nbsp; 
												<a href='PromoDetails.php?PID={$results['PromotionID']}&Sponsored=Yes' class='BabyBlue'>View Promo</a> &nbsp; &nbsp; | &nbsp; &nbsp; 
												<!--<a href='MyAccount.php?EditPromo={$results['PromotionID']}' class='BabyBlue'>Edit Promo</a> &nbsp; &nbsp; | &nbsp; &nbsp; -->
												<a href='MyAccount.php?Results={$results['PromotionID']}' class='BabyBlue'>View Check-ins</a>  
												<!-- &nbsp; &nbsp; | &nbsp; &nbsp; <a href='MyAccount.php?Rewards={$results['PromotionID']}' class='BabyBlue'>View Rewards</a>-->
																					  
										  			</div>";
							$PromoColor = "Orange";			  
							$TypeDetails = "<div class='SmallFont'><strong class='Black'>Type:</strong> <i class='Black'>{$PromoType}</i></div>";
							$PromoDates = "<div class='SmallFont AlignRight' style='margin-top:7px;'>Promo Dates:<strong>{$StartDate} - {$EndDate}</strong> &nbsp;  &nbsp; </div>";
							$PromoTitle = "<span><strong class='{$PromoColor} italic'>{$results['PromoTitle']}</strong><br>";
					} 


				if ($BackgroundColor == "#FFFFFF") { $BackgroundColor = "#FBFBFB"; } else { $BackgroundColor = "#FFFFFF"; }

				$MyPromotions .= "
							<div style='padding-bottom:10px; margin:3px; background:{$BackgroundColor}'>	
									<div class='col_1_2_tab'>
										<span style='padding:5px 10px 5px 0px; float:left;'><img src='gfx/icons/{$results['TaskType']}.png'></span>
										<h4 class='{$PromoColor}' style='margin:0;'>{$results['LocationName']} &nbsp; &nbsp; <span class='SmallFont'> <a href='{$ThisPage}?Lat={$results['LocationLatitude']}&Long={$results['LocationLongitude']}' class='Underline BabyBlue'>Map</a>  </span></h4>
										{$PromoTitle}
										{$results['TaskDescription']}
										</span> 
									</div>
									
									<div class='col_1_2_tab last'>
										{$PromoDates}
										<div class='clear'></div>
										$MyPromotionsSubMenu
									</div>
							</div>		
							<div class='clear'></div>		
				";


			  }   // End Foreach record

		  // if no records are found
		  } else { $MyPromotions .= "<div align='center' style='padding:30px;'>No markers yet."; 		  }	
		   
		  $MyPromotions .= "</div>";	
		  
		  return $MyPromotions;

		}	


		function EditPromoFormUI($PromoDetailsArray) {
			
			$PromoCounter = 0;
			$MyPromotions = "<ul style='padding:10px 0 0 0px; margin:0;' class='ShowBorders'>";
		
			if (count($PromoDetailsArray)) {
				  
				// Format Date
				$EndDate = FormatDate($PromoDetailsArray[0]['EndDate']);

				$MyPromotions .= "<li style='padding-bottom:10px;'>
									<h4 class='Orange'>{$PromoDetailsArray[0]['PromoTitle']} &nbsp; &nbsp; 
									  <span class='SmallFont'> <a href='MyAccount.php?Lat={$PromoDetailsArray[0]['LocationLatitude']}&Long={$PromoDetailsArray[0]['LocationLongitude']}'>Map</a>  </span>
									  <span class='SmallFont AlignRight'>End Date: <strong>{$EndDate}</strong></span> 
									</h4>
									
									<div style=\"padding:0 10px 0 10px;\">
										<span class='AlignRight'><strong class='Black'>Location:</strong> <i>{$PromoDetailsArray[0]['LocationName']}</i></span>
										<div><strong class='Black'>Type:</strong> <i class='Black'>{$PromoDetailsArray[0]['TaskType']} / {$PromoDetailsArray[0]['RewardType']}</i></div>								
									</div>";

				$MyPromotions .= "<div style=\"padding:20px 10px;\">
									<strong class='Black'>Edit a Promotion</strong> &nbsp; <a class='SmallFont' href=\"Javascript:location.href='MyAccount.php'\">&laquo; Back</a>
									  <div class='SM_LightGray'>You may only modify the following properties of this promotion:</div> 
									  	<div class='SM_LightGray' style=\"padding:20px;\">																  
									  		Promotion Quantity: <input type='text' name='PromoQuantity' size='2' value='{$PromoDetailsArray[0]['PromoQuantity']}'> 
									  	</div>

									  <div align='center'><input type='submit' class='submit' value='Submit Updates'></div>
										  
									</div>
								  </li>";


		  // if no records are found
		  } else { $MyPromotions .= "<div align='center' style='padding:30px;'>There appears to be a problem.  No promo found for editting.</div><br><div align='center'><a class='submit' href='MyAccount.php?Mode=Sponsor'>Show Sponsored Promotions</a></div>"; 		  }	
		   
		  $MyPromotions .= "</ul>";	
		  
		  return $MyPromotions;
			
		}

		function PromoResultsUI($PromotionResultsArray) {

			$PromotionResults = "<h3>Promotion Results &nbsp; <a href=\"Javascript:location.href='MyAccount.php'\" class='SmallFont'>&laquo; Back</a> </h3> ";

			if (count($PromotionResultsArray)) {
				
				$PromotionResults .= "<div align='center' style='padding:15px 0 5px 0;'><h3 class='Red'>{$PromotionResultsArray[0]['PromoTitle']}</h3></div>";
	
		// Show results of promotion query
				$PromotionResults .= "<table width='100%' style='border:#999 solid 2px; background-color:#C0D3E2; text-align:center;'><tr class='ResultsHeader'>
										<td>&nbsp;</td>
										<td>Name</td>
										<td>Email</td>
										<td>Date</td>";

				if ($PromotionResultsArray[0]['TaskType'] == "Question") { $PromotionResults .= "<td>Response</td></tr>"; }

				$CurrentNumber = 0;
				
				foreach ($PromotionResultsArray as $results) {
				
				  // Format Date
				  $CheckInTime = FormatDate($results['CreateDate']);
				  $CurrentNumber = $CurrentNumber + 1;
				  
				  $PromotionResults .= "<tr style='background-color:#fff;'>
				  						  <td class='SmallFont'>{$CurrentNumber}.</td>
										  <td class='SmallFont'>{$results['FullName']}</td>
										  <td class='SmallFont'>{$results['EmailAddress']}</td>
										  <td class='SmallFont'>{$CheckInTime}</td>";
										  
				  if ($PromotionResultsArray[0]['TaskType'] == "Question") { $PromotionResults .= "<td class='SmallFont'>{$results['UserResponse']}</td>";   }
				  
				  $PromotionResults .= "</tr>";
  
				}   // End Foreach record
	  
	  			$PromotionResults .= "</table>";
	  
			// if no records are found
			} else { $PromotionResults .= "<div align='center' style='padding:30px;'>No results yet.</div>"; 		  }	


		  return $PromotionResults;
			
			}
		
		function PromoRewardsUI($PromotionRewardsArray) {

			$PromotionRewards = "<h3>Promotion Rewards &nbsp; <a href=\"Javascript:location.href='MyAccount.php'\" class='SmallFont'>&laquo; Back</a> </h3> ";

			if (count($PromotionRewardsArray)) {
				
				$PromotionRewards .= "<div align='center' style='padding:15px 0 5px 0;'><h3 class='Red'>{$PromotionRewardsArray[0]['PromoTitle']}</h3></div>";
	
		// Show Rewards of promotion query
				$PromotionRewards .= "<table width='100%' style='border:#999 solid 2px; background-color:#C0D3E2; text-align:center;'><tr class='ResultsHeader'>
										<td>&nbsp;</td>
										<td>Name</td>
										<td>Email</td>
										<td>Won Date</td>
										<td>Used Date</td>";

				$CurrentNumber = 0;
				
				foreach ($PromotionRewardsArray as $Rewards) {
								
				  // Format Date
				  $CurrentNumber = $CurrentNumber + 1;
				  $WonDate = FormatDate($Rewards['WonDate']);

				if ($PromotionRewardsArray[0]['UsedDate'] == "0000-00-00 00:00:00") { $UsedDate = FormatDate($Rewards['UsedDate']); $bgcolor = "#FFC";}
				else { $UsedDate = "-"; $bgcolor = "#FFF";}
				  
				  $PromotionRewards .= "<tr style='background-color:{$bgcolor};'>
				  						  <td class='SmallFont'>{$CurrentNumber}.</td>
										  <td class='SmallFont'>{$Rewards['FullName']}</td>
										  <td class='SmallFont'>{$Rewards['EmailAddress']}</td>
										  <td class='SmallFont'>{$WonDate}</td>
										  <td class='SmallFont'>{$UsedDate}</td>";
				  
				  $PromotionRewards .= "</tr>";
  
				}   // End Foreach record
	  
	  			$PromotionRewards .= "</table>";
	  
			// if no records are found
			} else { $PromotionRewards .= "<div align='center' style='padding:30px;'>No rewards earned yet.</div>"; 		  }	


		  return $PromotionRewards;
			
			}


	function MyMarkersUI($PromoDetailsArray, $Toggled="") {

			$PromoCounter = 0;
			$CloseToggle = $CurrentLayer = "";
			
			$MyPromotions = "<div style='padding:0; margin:0;'>";
		
			if (count($PromoDetailsArray)) {
			
			  $BackgroundColor = "#FFFFFF";
				
			  foreach ($PromoDetailsArray as $results) {

				

				//Set up the Toggle for each layer
				if ($Toggled) {
					
					if ($CurrentLayer != $results['LayerID']) { 
						if ($results['Description']) { $LayerDescription = $results['Description']; } else { $LayerDescription = "<i>No Description Created</i>"; }
					
						$MyPromotions .= $CloseToggle;				
						$MyPromotions .= "<div class='toogle_box'>
											<div class='toggle closed_toggle NoBottomBorder'><div class='icon'></div>{$results['LayerName']}</div>
										  <div class='toggle_container'>
										  <div style='padding:0 15px;' id='LayerDescription{$results['LayerID']}'>
										  		<span style='cursor:pointer;' OnClick=\"$('#Layer{$results['LayerID']}_FormID').removeClass('Hide');$('#LayerDescription{$results['LayerID']}').addClass('Hide');\" class='AlignRight LeftSpace SmallFont BabyBlue'>Edit</span>
										  		
												{$LayerDescription} &nbsp; &nbsp; 
												
												<span class='SmallFont BabyBlue'>
										  		<a href='Layers.php?ID={$results['LayerID']}' class='SmallFont BabyBlue'>See Details &raquo;</a></span>
										  </div>

										  <form method='post' name='Layer{$results['LayerID']}_FormName' id='Layer{$results['LayerID']}_FormID' class='Hide'>
											  <input type='hidden' name='DescriptionID' value='{$results['LayerID']}'>
											  <input style='width:96%' type='text' name='LayerName' id='LayerName' placeholder='Layer Name*' value='{$results['LayerName']}' />
											  <textarea name='Description' class='WideTextarea' placeholder='Enter Description Here*'>{$results['Description']}</textarea>
											  <div align='center'><span href='#' class='button orange' OnClick=\"$('#Layer{$results['LayerID']}_FormID').submit();\">Update</span> </div>
										  <div class='clear padding10'></div>
										  </form>
										  ";
										  
						$CloseToggle = "<div class='clear padding20'></div>
										</div></div>";
						
						$CurrentLayer = $results['LayerID'];
						$Where = "";
					}
					
					$DeleteMe = "<span class='AlignRight'><a href='MyAccount.php?RemoveMarker={$results['PromotionID']}' class='CancelX Red'>X</a></span>";
					$EditMarker = " &nbsp; <span style='cursor:pointer;' class='SmallFont Disappear_Small BabyBlue' onclick=\"\">Edit</span>";
					 
				} else {
					
					$Where = "<span class='SmallFont'>
								<a href='#Map' onclick=\"mapstraction.setCenter(Location{$results['LocationID']}, {pan:true}); mapstraction.setZoom(12)\" style='padding:8px 3px;' class='BabyBlue_for_layer'>Where?</a>
							  </span>";
					$DeleteMe = "";
					$EditMarker = "";
						
				}
				
				// Format Date
				$StartDate = FormatDate($results['StartDate']);
				$EndDate = FormatDate($results['EndDate']);
				$PromoDetails = ShowPromo($results['PromotionID']);
				$LayerName_encoded = urlencode($results['LayerName']);
				
				if (strtotime($results['EndDate']) > strtotime(date("Y-m-d H:i:s"))) {
					$EndNow = "<span class='SmallFont AlignRight BabyBlue'>
								 	<a href='MyAccount.php?Expire={$results['PromotionID']}'>(End Now)</a>   &nbsp;   &nbsp; 
						 	   </span><br>";
					} else { $EndNow = "	"; }


				$MyPromotionsSubMenu = "<div class='SmallFont'>  Layer: &nbsp; {$results['LayerID']}</div>";


				if ($BackgroundColor == "#FFFFFF") { $BackgroundColor = "#FBFBFB"; } else { $BackgroundColor = "#FFFFFF"; }

				//echo $results['PromoRange'].$results['TaskType'];
				if($results['PromoRange']=='Personal') { $img="<img src='gfx/icons/{$results['TaskType']}.png'>"; }
				else { $img="<img src='gfx/icons/{$results['TaskType']}-Red.png'>";	}

				$MyPromotions .= "
							<div style='padding:2px; background-color:{$BackgroundColor}'>	
										<span style='padding:9px; float:left;'>$img</span>
										<span style='padding:18px 0;' class='SmallFont Disappear_Small Black AlignRight'><i>{$StartDate}</i></span>
										<h4 style='margin:5px 0 0 0 ;'>{$results['PromoTitle']} &nbsp; &nbsp; 
											{$DeleteMe} {$Where}
										</h4>
										<div class='TaskDescription'>
											{$results['TaskDescription']}
											{$EditMarker} 
										</div>										
							</div>
							<div class='clear'></div>
				";

			  }   // End Foreach record

				if ($Toggled) { $MyPromotions .= "</div></div>";  }  // to close last toggle

		  // if no records are found
		  } else { $MyPromotions .= "<div align='center' style='padding:30px;'>No markers have been created for this layer yet.</div>"; 		  }	
		   
		  $MyPromotions .= "</div>";	
		  
		  return $MyPromotions;

		}	
		
		
		
	function ShowMyEmptyLayersUI($PromoDetailsArray) { 
	
			$CloseToggle = $CurrentLayer = "";
			
			$MyPromotions = "<div style='padding:0px 0 0 0px; margin:0;'>";
		
			if (count($PromoDetailsArray)) {
			
			  $BackgroundColor = "#FFFFFF";
				
			  foreach ($PromoDetailsArray as $results) {
					
					if ($results['MarkerCount'] < 2) { 
						if ($results['Description']) { $LayerDescription = $results['Description']; } else { $LayerDescription = "<i>No Description Created</i>"; }
					
						$MyPromotions .= $CloseToggle;				
						$MyPromotions .= "<div class='toogle_box'>
											<div class='toggle closed_toggle NoBottomBorder'><div class='icon'></div>{$results['LayerName']}</div>
										  <div class='toggle_container'>
										  <div style='padding:0 15px;' id='LayerDescription{$results['LayerID']}'>
										  		<span style='cursor:pointer;' OnClick=\"$('#Layer{$results['LayerID']}_FormID').removeClass('Hide');$('#LayerDescription{$results['LayerID']}').addClass('Hide');\" class='AlignRight LeftSpace SmallFont BabyBlue'>Edit</span>
										  		
												{$LayerDescription} &nbsp; &nbsp; 
												
												<span class='SmallFont BabyBlue'>
										  		<a href='Layers.php?ID={$results['LayerID']}' class='SmallFont BabyBlue'>See Details &raquo;</a></span>
										  </div>

										  <form method='post' name='Layer{$results['LayerID']}_FormName' id='Layer{$results['LayerID']}_FormID' class='Hide'>
											  <input type='hidden' name='DescriptionID' value='{$results['LayerID']}'>
											  <input style='width:96%' type='text' name='LayerName' id='LayerName' placeholder='Layer Name*' value='{$results['LayerName']}' />
											  <textarea name='Description' class='WideTextarea' placeholder='Enter Description Here*'>{$results['Description']}</textarea>
											  <div align='center'><span href='#' class='button orange' OnClick=\"$('#Layer{$results['LayerID']}_FormID').submit();\">Update</span> </div>
										  <div class='clear padding10'></div>
										  </form>
										  ";
										  
						$CloseToggle = "<div class='clear padding20'></div>
										</div></div>";
						
					}



			  }   // End Foreach record

				$MyPromotions .= "</div></div>";   // to close last toggle

		  }  
		   
		  $MyPromotions .= "</div>";	
		  
		  return $MyPromotions;	
	
	}		
?>
