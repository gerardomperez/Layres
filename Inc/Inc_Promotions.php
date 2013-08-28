<?php 

	require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Skin_Me.php';
	require_once 'Inc/Inc_Functions_Database.php';

// #############################################
// ## Determine action for Step 1, Step 2, Step 3 or Step 4
// #############################################

	if (isset($_POST['StepFinalSubmitted']))	// if last step submitted
		{
				$HiddenVariables = "";			
				$Message_Promotions = "<h3>Promotion Completed</h3>
									   <div>Your promotion has been created. You should now see the complete promotion in the map above.</div>";		
				$PreviousStep = "Step4";

				// Post all step 3 data into cookie so that there is just one big submit at end of process
				if (isset($_POST['StepFinalSubmitted'])) {  $_SESSION['CongratsMessage'] = $_POST['CongratsMessage']; }

				// ----------------------
				// -- Submit Data
				// ----------------------			
				 // Sanitize Data
				  OpenDatabase();

					  $_SESSION['PromoTitle'] = Sanitize_Data($_SESSION['PromoTitle']);
					  $_SESSION['TaskDescription'] = Sanitize_Data($_SESSION['TaskDescription']);
					  $_SESSION['Question'] = Sanitize_Data($_SESSION['Question']);
					  $_SESSION['MCOptions1'] = Sanitize_Data($_SESSION['MCOptions1']);
					  $_SESSION['MCOptions2'] = Sanitize_Data($_SESSION['MCOptions2']);
					  $_SESSION['MCOptions3'] = Sanitize_Data($_SESSION['MCOptions3']);
					  $_SESSION['MCOptions4'] = Sanitize_Data($_SESSION['MCOptions4']);
					  $_SESSION['RewardDescription'] = Sanitize_Data($_SESSION['RewardDescription']);
					  $_SESSION['ExpirationDate'] = Sanitize_Data($_SESSION['ExpirationDate']);
					  $_SESSION['SecretInfo'] = Sanitize_Data($_SESSION['SecretInfo']);
					  $_SESSION['StatusLevel1'] = Sanitize_Data($_SESSION['StatusLevel1']);
					  $_SESSION['StatusLevel2'] = Sanitize_Data($_SESSION['StatusLevel2']);
					  $_SESSION['StatusLevel3'] = Sanitize_Data($_SESSION['StatusLevel3']);
					  $_SESSION['StatusLevel4'] = Sanitize_Data($_SESSION['StatusLevel4']);
					  $_SESSION['StatusLevel5'] = Sanitize_Data($_SESSION['StatusLevel5']);
					  $_SESSION['StatusLevel6'] = Sanitize_Data($_SESSION['StatusLevel6']);
					  $_SESSION['StatusLevel7'] = Sanitize_Data($_SESSION['StatusLevel7']);
					  $_SESSION['CongratsMessage'] = Sanitize_Data($_SESSION['CongratsMessage']);

				  CloseDatabase();


				// -----------------
				// Submit Task Data
				// -----------------
				if ($_SESSION['TaskType'] == "Progress") {
					$sql_SaveTaskData_Progress = "INSERT INTO Promotions_Task_Progress(UName, CheckInNumber, MaxCheckInNumber, SecretInfo) ";
					$sql_SaveTaskData_Progress .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['CheckInNumber']}', '{$_SESSION['MaxCheckInNumber']}', '{$_SESSION['SecretInfo']}')";
					$Result_SaveTaskData = Insert($sql_SaveTaskData_Progress);
				}

				if ($_SESSION['TaskType'] == "Appointment") {
					$sql_SaveTaskData_Appointment = "INSERT INTO Promotions_Task_Appointment(UName, DayStartTime, DayEndTime, ValidDays) ";
					$sql_SaveTaskData_Appointment .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['DayStartTime']}', '{$_SESSION['DayEndTime']}', '{$_SESSION['ValidDays']}')";
					$Result_SaveTaskData = Insert($sql_SaveTaskData_Appointment);
				}

				if ($_SESSION['TaskType'] == "Question") {				
					$sql_SaveTaskData_Question = "INSERT INTO Promotions_Task_Question(UName, Question, MCOptions1, MCOptions2, MCOptions3, MCOptions4) ";
					$sql_SaveTaskData_Question .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['Question']}', '{$_SESSION['MCOptions1']}', '{$_SESSION['MCOptions2']}', '{$_SESSION['MCOptions3']}', '{$_SESSION['MCOptions4']}')";
					$Result_SaveTaskData = Insert($sql_SaveTaskData_Question);
				}

					// Get the TaskID
					if ($Result_SaveTaskData) { 
							$sql_GetTaskID = "SELECT TaskID FROM Promotions_Task_{$_SESSION['TaskType']} WHERE UName='{$_SESSION['UName']}' ORDER BY CreateDate Desc";
							$Result_GetTaskID = Select($sql_GetTaskID);
							$TaskID = $Result_GetTaskID[0]['TaskID'];
						} else { $TaskID = 0; }


				// -----------------
				// Submit Reward Data
				// -----------------				

				if ($_SESSION['RewardType'] == "Coupon") {				
					$sql_SaveRewardData_Coupon = "INSERT INTO Promotions_Rewards_Coupon(UName, CouponName, Coupon, ExpirationDate) ";
					$sql_SaveRewardData_Coupon .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['CouponName']}','{$_SESSION['Coupon']}', STR_TO_DATE('{$_SESSION['ExpirationDate']}','%b %d, %Y'))";
//					$ErrorMessage_Promotions = $sql_SaveRewardData_Coupon; 
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Coupon);
				}
				
				if ($_SESSION['RewardType'] == "Information") {								
					$sql_SaveRewardData_Information = "INSERT INTO Promotions_Rewards_Information(UName, SecretInfo) ";
					$sql_SaveRewardData_Information .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['SecretInfo']}')";
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Information);
				}

				if ($_SESSION['RewardType'] == "Status") {								
					$sql_SaveRewardData_Status = "INSERT INTO Promotions_Rewards_Status(UName, StatusLevel1, StatusLevel2, StatusLevel3, StatusLevel4, StatusLevel5, StatusLevel6, StatusLevel7) ";
					$sql_SaveRewardData_Status .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['StatusLevel1']}', '{$_SESSION['StatusLevel2']}', '{$_SESSION['StatusLevel3']}', '{$_SESSION['StatusLevel4']}', '{$_SESSION['StatusLevel5']}', '{$_SESSION['StatusLevel6']}', '{$_SESSION['StatusLevel7']}')";
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Status);
				}

					// Get the TaskID
					if ($Result_SaveRewardData) { 
							$sql_GetRewardID = "SELECT RewardID FROM Promotions_Rewards_{$_SESSION['RewardType']} WHERE UName='{$_SESSION['UName']}' ORDER BY CreateDate Desc";
							$Result_GetRewardID = Select($sql_GetRewardID);
							$RewardID = $Result_GetRewardID[0]['RewardID'];
						} else { $RewardID = 0; }


				// -----------------
				// Submit Promotion Data
				// -----------------
			     $sql_SavePromotionsData = "INSERT INTO Promotions(UName, TaskType, TaskID, RewardType, RewardID, StartDate, EndDate,
											PromoTitle, TaskDescription, RewardDescription, 
											CongratsMessage, CreateDate) ";
			     $sql_SavePromotionsData .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['TaskType']}', '{$TaskID}', '{$_SESSION['RewardType']}', '{$RewardID}',
											STR_TO_DATE('{$_SESSION['StartDate']}','%b %d, %Y'), STR_TO_DATE('{$_SESSION['EndDate']}','%b %d, %Y'),
											'{$_SESSION['PromoTitle']}','{$_SESSION['TaskDescription']}','{$_SESSION['RewardDescription']}', 
											'{$_SESSION['CongratsMessage']}','{$CreateDate}') "; 
			   
			   //	echo $sql_SavePromotionsData;
			   $Result_SavePromotionsData = Insert($sql_SavePromotionsData);
	  
			  // Get PromotionID to create record in Location_Promotion table
			  $sql_GetPromotionID = "SELECT PromotionID FROM Promotions WHERE UName='{$_SESSION['UName']}' ORDER BY PromotionID DESC LIMIT 1";
			  $Result_GetPromotionID = Select($sql_GetPromotionID);	
			  $PromotionID = $Result_GetPromotionID[0]['PromotionID'];
	  
			  // Add record to Location_Promotion database
			   if ($PromotionID) {
				   $sql_SavePromoLocoData = "INSERT INTO Location_Promotion(PromotionID, LocationID, PromoQuantity) ";
				   $sql_SavePromoLocoData .= "VALUES ('$PromotionID', '{$_SESSION['LocationOptions']}', '{$_SESSION['PromoQuantity']}') "; 	
				   
				   $Result_SavePromoLocoData = Insert($sql_SavePromoLocoData);		 
	  
				   if ($Result_SavePromoLocoData) 
					  {$Message_Promotions = "<br><div class='ErrorMessage' align='center'>Promotion successfully created.</div>";}
					  else {$Message_Promotions = "<br><div class='ErrorMessage' align='center'>Promotion creation failed.  Please contact administrator.</div>";}
			   }


				// ----------------------
				// -- Map Information - Location / Icon	
				// ----------------------

				

				// Define how this section displays
 					$Message_Promotions = "Congratulations!  Your promotion has been created.  Below is what your confirmation screen will look like.";		 				
				
				// Add "page" formatting
					$Message_Promotions .= "<br><div align='center'>";
					$Message_Promotions .= ShowPromo_ThankYou($PromotionID);
					$Message_Promotions .= "</div><br>";


		} 
	else if (isset($_POST['Step3Submitted']) || isset($_POST['Step4']))	// if step 3 submitted
	{

				// Post all step 3 data into cookie so that there is just one big submit at end of process
				if (isset($_POST['Step3Submitted'])) {
					$_SESSION['RewardDescription'] = $_POST['RewardDescription'];
					
					if ($_SESSION['RewardType'] == "Coupon") {
							$_SESSION['Coupon'] = $_POST['Coupon'];
							$_SESSION['CouponName'] = $_POST['CouponName'];
							$_SESSION['ExpirationDate'] = $_POST['ExpirationDate'];
						}

					if ($_SESSION['RewardType'] == "Status") {
							if (NotEmpty($_POST['StatusLevel1'])) { $_SESSION['StatusLevel1'] = $_POST['StatusLevel1']; $StatusLevels = 1;}
							if (NotEmpty($_POST['StatusLevel2'])) { $_SESSION['StatusLevel2'] = $_POST['StatusLevel2']; $StatusLevels++;}
							if (NotEmpty($_POST['StatusLevel3'])) { $_SESSION['StatusLevel3'] = $_POST['StatusLevel3']; $StatusLevels++;}
							if (NotEmpty($_POST['StatusLevel4'])) { $_SESSION['StatusLevel4'] = $_POST['StatusLevel4']; $StatusLevels++;}
							if (NotEmpty($_POST['StatusLevel5'])) { $_SESSION['StatusLevel5'] = $_POST['StatusLevel5']; $StatusLevels++;}
							if (NotEmpty($_POST['StatusLevel6'])) { $_SESSION['StatusLevel6'] = $_POST['StatusLevel6']; $StatusLevels++;}
							if (NotEmpty($_POST['StatusLevel7'])) { $_SESSION['StatusLevel7'] = $_POST['StatusLevel7']; $StatusLevels++;}
							
							$_SESSION['MaxCheckInNumber'] = $StatusLevels * $_SESSION['CheckInNumber'];
						} else { $_SESSION['MaxCheckInNumber'] = $_SESSION['CheckInNumber']; }

	 
					if ($_SESSION['RewardType'] == "Information") {
							$_SESSION['SecretInfo'] = $_POST['SecretInfo'];
						}
				}

				$HiddenVariables = "<input type='hidden' name='StepFinalSubmitted' value='XXXXXX' />";			
				$Message_Promotions = "<h3>Step 4 of 5: Confirmation</h3>
									   Please double check the information below for accuracy. If all looks ok, 
									   then please <strong>press the Next button to create the promotion</strong>.";
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step4('');", $ButtonValue_Promotions);	
				$PreviousStep = "Step3";

				// ----------------------
				// -- Get Map Information - Location / Icon
				// ----------------------
				
				
				
				// ----------------------
				// -- Develop Data for display
				// ----------------------
					// Format Date
					$EndDate = FormatDate($_SESSION['EndDate']);
					$ExpirationDate = FormatDate($_SESSION['ExpirationDate']);
				
					$Message_Promotions .= "<br><br>
								<div style='border: solid 1px #333; width:550px; padding:0 15px 0 15px; background-color:#eee;'>
									<strong>Your Complete Promotion</strong> <span class='AlignRight'><i>Quantity: {$_SESSION['PromoQuantity']}</i></span>
								</div>";

					// Add "page" formatting
					$Message_Promotions .= "<br><div align='center'>
								<div class='PromoDetails'>
									<img src='gfx/icons/{$_SESSION['TaskType']}.png' align='left' hspace='5' vspace='8'>
									<h3 class='Red'>{$_SESSION['PromoTitle']}</h3>	
									<div class='SmallFont'><span class='AlignLeft'>Valid Thru: <i>{$EndDate}</i></span><span class='AlignRight'>Reward Expires: <i>{$ExpirationDate}</i></span></div>
									<div class='clear'></div>
									<hr><a href='#'><img src='gfx/CheckIn_Button.gif' hspace='5' align='right'></a>";

					// Format Reward
					$Message_Promotions .= "<h4>What's in it for me?</h4>
											<p>{$_POST['RewardDescription']}</p>";

					// Format Task
					$Message_Promotions .= "<h4>What do I need to do?</h4>
											<p>{$_SESSION['TaskDescription']}</p>";

					
					if ($_SESSION['RewardType'] == "Status") {
							$Message_Promotions .= "<h4>Promo Details</h4><div align='center' class='PromoDetailsBox'><i>Status Levels:</i>";
							
										if (isset($_SESSION['StatusLevel1']) AND $StatusLevels >= 1) { $Message_Promotions .= "<br> &nbsp; 1. {$_SESSION['StatusLevel1']}";}
										if (isset($_SESSION['StatusLevel2']) AND $StatusLevels >= 2) { $Message_Promotions .= "<br> &nbsp; 2. {$_SESSION['StatusLevel2']}";}
										if (isset($_SESSION['StatusLevel3']) AND $StatusLevels >= 3) { $Message_Promotions .= "<br> &nbsp; 3. {$_SESSION['StatusLevel3']}";}
										if (isset($_SESSION['StatusLevel4']) AND $StatusLevels >= 4) { $Message_Promotions .= "<br> &nbsp; 4. {$_SESSION['StatusLevel4']}";}
										if (isset($_SESSION['StatusLevel5']) AND $StatusLevels >= 5) { $Message_Promotions .= "<br> &nbsp; 5. {$_SESSION['StatusLevel5']}";}
										if (isset($_SESSION['StatusLevel6']) AND $StatusLevels >= 6) { $Message_Promotions .= "<br> &nbsp; 6. {$_SESSION['StatusLevel6']}";}
										if (isset($_SESSION['StatusLevel7']) AND $StatusLevels >= 7) { $Message_Promotions .= "<br> &nbsp; 7. {$_SESSION['StatusLevel7']}";}

							$Message_Promotions .= "</div><br>";
						}

					if ($_SESSION['TaskType'] == "Appointment") {
							$Message_Promotions .= "<h4>Promo Details</h4><div class='SmallFont' style='padding-left:10px'><i>Valid Promotion Times:</i> {$_SESSION['DayStartTime']} - {$_SESSION['DayEndTime']} (<i>{$_SESSION['ValidDays']}</i>)</div>";					
						
						}

					if ($_SESSION['TaskType'] == "Progress") {
							$Message_Promotions .= "<h4>Promo Details</h4><div class='SmallFont' style='padding-left:10px'><i>Number of Check-Ins to Earn Reward:</i> {$_SESSION['CheckInNumber']}</p></div>";					
						
						}

					if ($_SESSION['TaskType'] == "Question") {
							$Message_Promotions .= "<div style='padding-left:10px'>{$_SESSION['Question']}";
									
							  if (NotEmpty($_SESSION['MCOptions1'])) {
								  
								if (NotEmpty($_SESSION['MCOptions1'])) { $Message_Promotions .= "<br> &nbsp; <input type='radio' name='MCOption'> {$_SESSION['MCOptions1']}";}
								if (NotEmpty($_SESSION['MCOptions2'])) { $Message_Promotions .= "<br> &nbsp; <input type='radio' name='MCOption'> {$_SESSION['MCOptions2']}";}
								if (NotEmpty($_SESSION['MCOptions3'])) { $Message_Promotions .= "<br> &nbsp; <input type='radio' name='MCOption'> {$_SESSION['MCOptions3']}";}
								if (NotEmpty($_SESSION['MCOptions4'])) { $Message_Promotions .= "<br> &nbsp; <input type='radio' name='MCOption'> {$_SESSION['MCOptions4']}";}
								
							  } else {
									 $Message_Promotions .= "<textarea style='width:290px; height:30px;'></textarea><br>";
							  }		

							  $Message_Promotions .= "<div align='center'><a href='#'><img src='gfx/CheckIn_Button.gif' hspace='5'></a></div>";

							$Message_Promotions .= "</div>";				

						}


					// Promo author formatting
					$Message_Promotions .= "<hr>
											<h4>{$_SESSION['LocationName']}</h4>
											<span class='AlignLeft'><a href='#' class='BabyBlue'>{$_SESSION['StreetAddress']}</a></span>
													<span class='AlignRight'><a href='#' class='BabyBlue'>{$_SESSION['Phone']}</a></span><br><br>";

						
					// Confirmation Page Message
					$Message_Promotions .= "</div></div>
											<br>
											
											<div><strong>Confirmation Page Message:</strong></div>
											
											<div>This is the message people will see once they complete a check-in.  
												This is your opportunity to thank them for checking in.</div>
												
												<textarea name='CongratsMessage' style='width:580px; height:50px;' maxlength='140'></textarea>";

		}
	else if (isset($_POST['Step2Submitted']) || isset($_POST['Step3']))	// if step 2 submitted
	{
					// Post all step 2 data into cookie so that there is just one big submit at end of process
					if (isset($_POST['Step2Submitted'])) {
						$RewardType = $_SESSION['RewardType'];									 
									 
						$_SESSION['TaskDescription'] = $_POST['TaskDescription'];
						
						if ($_SESSION['TaskType'] == "Appointment") {
								$_SESSION['DayStartTime'] = $_POST['DayStartTime'];
								$_SESSION['DayEndTime'] = $_POST['DayEndTime'];
								$_SESSION['ValidDays'] = implode(",", $_POST['ValidDays']);    // Convert checkbox values into a string
							}
						
						if ($_SESSION['TaskType'] == "Progress") { $_SESSION['CheckInNumber'] = $_POST['CheckInNumber']; } 
							else { $_SESSION['CheckInNumber'] = 1; }
		
						if ($_SESSION['TaskType'] == "Question") {
								$_SESSION['Question'] = $_POST['Question'];
								$_SESSION['MCOptions1'] = $_POST['MCOptions1'];
								$_SESSION['MCOptions2'] = $_POST['MCOptions2'];
								$_SESSION['MCOptions3'] = $_POST['MCOptions3'];
								$_SESSION['MCOptions4'] = $_POST['MCOptions4'];
							}
					} else {
						$RewardType = $_SESSION['RewardType'];	
					}

		
					$HiddenVariables = "<input type='hidden' name='Step3Submitted' value='XXXXXX' />";			
					$Message_Promotions = "<h3>Step 3 of 5: Define Reward Details</h3>
										   <div>Please enter the reward details below.  This is where you tell them what's in it for them.</div>";
					$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step3('{$RewardType}');", $ButtonValue_Promotions);
					$PreviousStep = "Step2";
	
	
					// ----------------------
					// -- Get Map Information - Location / Icon
					// ----------------------
					
					
					
					// ----------------------
					// -- Develop Data for display
					// ----------------------	
					// Reward information
	
						// Coupon    -->  Need to convert this to an upload image of a certain dimension
						if ($RewardType == "Coupon") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Reward Type</strong>: Coupon</div>";
								$RewardHTML = "
									<li>
										<strong>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<input type='hidden' name='CouponName' />
									<br>
									<li><strong>Coupon:</strong> <input type='file' name='Coupon' />   <br> (*image should be no larger than 300x400 pixels)</li>
									<br>	
									<li><strong>Expiration Date:</strong>  (This is the last date that an earned reward can be redeemed.)<br><input id='date1' type='text' name='ExpirationDate' readonly='readonly' class='calendar' value='{$_SESSION['ExpirationDate']}'></li>						
									";			
							}
							
						// Referral    
						if ($RewardType == "Referral") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Reward Type</strong>: Referral</div>";
								$RewardHTML = "
									<li>
										<strong>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<br>	
									<li><strong>Expiration Date:</strong>  (This is the last date that an earned reward can be used.)<br><input id='date1' type='text' name='ExpirationDate' readonly='readonly' class='calendar' value='{$_SESSION['ExpirationDate']}'></li>																	
									";
							}
	
						// Status
						if ($RewardType == "Status") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Reward Type</strong>: Status</div>";
								$RewardHTML = "
									<li>
										<strong>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>											
									<br>
									<li>
										<strong>Enter Up To 7 Levels:</strong> (Once the individual completes the task, they will advance one more level.)
										<div class='SM_LightGray'>
											1. <input name='StatusLevel1' style='width:200px;' value='{$_SESSION['StatusLevel1']}'> <br>
											2. <input name='StatusLevel2' style='width:200px;' value='{$_SESSION['StatusLevel2']}'> <br>			
											3. <input name='StatusLevel3' style='width:200px;' value='{$_SESSION['StatusLevel3']}'> <br>			
											4. <input name='StatusLevel4' style='width:200px;' value='{$_SESSION['StatusLevel4']}'> <br>			
											5. <input name='StatusLevel5' style='width:200px;' value='{$_SESSION['StatusLevel5']}'> <br>			
											6. <input name='StatusLevel6' style='width:200px;' value='{$_SESSION['StatusLevel6']}'> <br>			
											7. <input name='StatusLevel7' style='width:200px;' value='{$_SESSION['StatusLevel7']}'>									
										</div>	
									</li>		
									";
							}
							
						
						// Info
						if ($RewardType == "Information") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Reward Type</strong>: Information</div>";
								$RewardHTML = "
									<li>
										<strong>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<li>
										<strong>Enter Information to be Revealed After Task Completed:</strong>
										<div><textarea name='SecretInfo' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['SecretInfo']}</textarea></div>	
									</li>								
									";
							}


				// Define how this section displays
 				$MyPromotions = "<div><ol>$RewardHTML</ol></div>";

		
		} 
	else if (isset($_POST['Step1Submitted']) || isset($_POST['Step2'])) {	// if step 1 Submitted
	
				// Post all step 1 data into session so that there is just one big submit at end of process
				if (isset($_POST['Step1Submitted'])) {
					$_SESSION['PromoTitle'] = $_POST['PromoTitle'];
					$_SESSION['LocationOptions'] = $_POST['LocationOptions'];
					$_SESSION['StartDate'] = $_POST['StartDate'];
					$_SESSION['EndDate'] = $_POST['EndDate'];
					$_SESSION['TaskType'] = $_POST['TaskType'];
					$_SESSION['RewardType'] = $_POST['RewardType'];
					$_SESSION['PromoQuantity'] = $_POST['PromoQuantity'];

					$TaskType = $_POST['TaskType'];

					if ($LocationDetails = GetLocationDetails($_POST['LocationOptions'])) {
						$_SESSION['Phone'] = $LocationDetails['Phone'];
						$_SESSION['Address'] = $LocationDetails['StreetAddress'] . "<br>" . $LocationDetails['Zip'];
							
					} else {
						$_SESSION['Phone'] = "";
						$_SESSION['Address'] = "Not Found <br> There was an error pulling up address details.";				
					}
					
				} else {
					$TaskType = $_SESSION['TaskType'];
				}


				

				$HiddenVariables = "<input type='hidden' name='Step2Submitted' value='XXXXXX' />";			
				$Message_Promotions = "<h3>Step 2 of 5: Define Task Details</h3>
									   <div>Please enter the task details below.  There is where you tell the public what they need to do.</div>";
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step2('{$TaskType}');", $ButtonValue_Promotions);
				$PreviousStep = "Step1";

				// ----------------------
				// For Map 
				// ----------------------

/*				// Choose icon
				$PromoIcon = "gfx/icons/OrangeFlag.png"; 
				
				// Determine Lat, Long points
				$sql_LocationData = "SELECT * FROM Location WHERE LocationID='{$_SESSION['LocationOptions']}'";
				$Result_LocationDataFull = Select($sql_LocationData);
				$Result_LocationData = $Result_LocationDataFull[0];

				$LocationLatitude = $Result_LocationData['LocationLatitude'];
				$LocationLongitude = $Result_LocationData['LocationLongitude'];
				$Address  = $Result_LocationData['StreetAddress'] . " " . $Result_LocationData['Address2'] . " " . $Result_LocationData['Zip'];
				$Phone = $Result_LocationData['Phone'];

				$LatLong = "$LocationLatitude, $LocationLongitude";
				
				// Add Marker
				if (isset($_SESSION['CompanyName'])) { $CompanyName = $_SESSION['CompanyName']; }
				
				$MapMarkers .= "
					marker = new mxn.Marker(Home);
					marker.setLabel('$CompanyName');
					marker.setIcon('gfx/icons/$PromoIcon',[25,25]);
					mapstraction.addMarker(marker);			
				";	

				//Pass $LatLong and Icon to next page
				$_SESSION['LatLong'] = $LatLong;
				$_SESSION['PromoIcon'] = $PromoIcon;
				$_SESSION['Phone'] = $Phone;
				$_SESSION['Address'] = $Address;
*/


				// ----------------------
				// -- Show Task info for display
				// ----------------------				
					// Check-In
					if ($TaskType == "Check-In") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Task Type</strong>: Check-In</div>";
							$TaskHTML = "
                                <li>
									<strong>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>							
								";		
						}
			
					// Appointment
					if ($TaskType == "Appointment") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Task Type</strong>: Appointment</div>";
							$TaskHTML = "
                                <li>
									<strong>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>	
								
								<br>
								
                                <li>
									<strong>Choose Active Time of Day:</strong>
                                    <div class='SM_LightGray'>
										Start Time: <select name='DayStartTime'>$DayStartTimeOptions</select> 
										&nbsp; &nbsp; &nbsp;
										End Time: <select name='DayEndTime'>$DayEndTimeOptions</select>										
									</div>	
                                </li>	

								<br>

								<li>
									<strong>Choose Active Days:</strong> 
                                    <div class='SM_LightGray'><i>(select all that apply)</i> <br>
										<table cellpadding='10'><tr>
											<td>
											<INPUT type='checkbox' name='ValidDays[]' value='Monday'> Monday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Tuesday'> Tuesday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Wednesday'> Wednesday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Thursday'> Thursday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Friday'> Friday </option>
											</td><td valign='top'>
											<INPUT type='checkbox' name='ValidDays[]' value='Saturday'> Saturday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Sunday'> Sunday </option>
											</td>
										</tr></table>

									</div>	
                                </li>	
								";	
						}
						
					// Progress
					if ($TaskType == "Progress") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Task Type</strong>: Progress</div>";
							$TaskHTML = "
                                <li>
									<strong>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>	
								
								<br>

                                <li>
									<strong>Number of Check-Ins to Complete Task:</strong>
                                    <select name='CheckInNumber'>
												<option value='1'>1</option>
												<option value='2'>2</option>
												<option value='3'>3</option>
												<option value='4'>4</option>
												<option value='5'>5</option>
												<option value='6'>6</option>
												<option value='7'>7</option>
												<option value='8'>8</option>
												<option value='9'>9</option>
											</select>	
                                </li>		
							";
						}
						
						
					// Questions
					if ($TaskType == "Question") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong>Selected Task Type</strong>: Question</div>";
							$TaskHTML = "
                                <li>
									<strong>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>
								
								<br>

                                <li>
									<strong>Enter Question:</strong>
                                    <div><textarea name='Question' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['Question']}</textarea></div>	
                                </li>

								<br>

                                <li>
									<strong>Enter Multiple Choice Options:</strong>  <span class='SmallFont'>(optional)</span>
                                    <div class='SM_LightGray'>
									If no options are entered, then the an open text field will be provided for an unrestricted answer.
										<table width='420' cellpadding='2' class='SM_LightGray'>
											<tr><td>1. <input name='MCOptions1' style='width:150px;' value='{$_SESSION['MCOptions1']}' maxlength='20'></td><td>3. <input name='MCOptions3' style='width:150px;' value='{$_SESSION['MCOptions3']}' maxlength='20'></td></tr>
											<tr><td>2. <input name='MCOptions2' style='width:150px;' value='{$_SESSION['MCOptions2']}' maxlength='20'></td><td>4. <input name='MCOptions4' style='width:150px;' value='{$_SESSION['MCOptions4']}' maxlength='20'></td></tr>
										</table>									
									</div>	
                                </li>
							";
						}
			

				// Define how this section displays
 				$MyPromotions = "<div><ol>$TaskHTML</ol></div>";

			} 
	else  // if on first page
			{	

				$HiddenVariables = "<input type='hidden' name='Step1Submitted' value='XXXXXX' />";			
				$Message_Promotions = "<h3>Step 1 of 5: Define Promotion Criteria</h3>
									   <div >Please enter the information below to determine the basic parameters of the 
									   promotion you'd like to create. In the next two steps, you will further specify Task and Reward parameters, 
									   and then the final step will be approving the entire promotion.</div>";
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step1('');", $ButtonValue_Promotions);


				// Clear all data to prevent mixed data in case they start over promo process before finishing previous attempt
				if (!isset($_POST['Step1'])) { ClearPromotionData(); }
				
				// ----------------------
				// -- Develop Data for display
				// ----------------------
					// Location Dropdown information
					$sql_LocationData = "SELECT * FROM Location WHERE UName='{$_SESSION['UName']}'";
					$Result_LocationData = Select($sql_LocationData);

					// Show results of location query
					if (count($Result_LocationData)) {
				
							foreach ($Result_LocationData as $results) {
									// Make dropdown pre-select already chosen value    XXXX
									if ($results['LocationID'] == $_SESSION['LocationOptions']) { $Selected = "Selected"; } else { $Selected = ""; }
							
									$LocationOptions .= "<option value='{$results['LocationID']}' {$Selected}>{$results['LocationName']}</option>";
								}   

					} else { $LocationOptions = "<option value=''>-- No Locations Found. --</option>"; }

					// Populate radio buttons if a Session value exists
					if (isset($_SESSION['RewardType'])) {
								switch ($_SESSION['RewardType']){
								case "Coupon":
									$Coupon = "checked='checked'";
									break;
								case "Referral":
									$Referral = "checked='checked'";
									break;
								case "Status":
									$Status = "checked='checked'";
									break;
								case "Information":
									$Information = "checked='checked'";
									break;
								}
					} else { $Coupon = "checked='checked'";	}
 
 					if (isset($_SESSION['TaskType'])) {
								switch ($_SESSION['TaskType']){
								case "Check-In":
									$CheckIn = "checked='checked'";
									break;
								case "Progress":
									$Progress = "checked='checked'";
									break;
								case "Appointment":
									$Appointment = "checked='checked'";
									break;
								case "Question":
									$Question = "checked='checked'";
									break;
								}
					} else { $CheckIn = "checked='checked'";	}
 
					// Define how this section displays
					$MyPromotions = "
								<div> 
									<ol>
										<li>
											<strong>Choose Promotion Title:</strong>
											<div><input name='PromoTitle' style='width:420px' value='{$_SESSION['PromoTitle']}' maxlength='40'></div>
										</li>
										
										<br>
										
										<li>
											<strong>Choose Location:</strong>
											<div><select name='LocationOptions' style='width:420px;'> {$LocationOptions} </select></div>
										</li>
		
										<br />
											
										<li>
											<strong>Choose Promotion Duration:</strong>
											<div class='SM_LightGray'>
													<span>
													Start Date: <input id='date1' type='text' name='StartDate' readonly='readonly' class='calendar' value='{$_SESSION['StartDate']}'>
													</span>
														&nbsp; &nbsp; &nbsp;  
													<span>
													End Date:  <input id='date2' type='text' name='EndDate' readonly='readonly' class='calendar' value='{$_SESSION['EndDate']}'>
													</span>
											</div>
										</li>
																		
										<br />
										
										<li>
											<strong>Choose Task Type:</strong>
											<div class='SM_LightGray'>
											<input type='radio' name='TaskType' value='CheckIn' {$CheckIn} /> Check-In &nbsp;
											<!--<input type='radio' name='TaskType' value='Progress' {$Progress} /> Progress &nbsp;-->
											<input type='radio' name='TaskType' value='Appointment' {$Appointment} /> Appointment &nbsp;
											<input type='radio' name='TaskType' value='Question' {$Question} /> Question
											</div>
										</li>
										
										<br />
										
										<li>
											<strong>Choose Reward Type:</strong>
											<div class='SM_LightGray'>
											<input type='radio' name='RewardType' value='Coupon' {$Coupon} /> Coupon &nbsp;
											<!--<input type='radio' name='RewardType' value='Referral {$Referral}' /> Referral Bonus &nbsp;-->
											<input type='radio' name='RewardType' value='Status' {$Status} /> Status &nbsp;
											<input type='radio' name='RewardType' value='Information' {$Information} /> Information	                                    
											</div>	
										</li>
		
										<br />
																		
										<li>
											<strong>Choose Promotion Quantity:</strong> <input type='text' name='PromoQuantity' size='3' value='{$_SESSION['PromoQuantity']}'>
										</li>
										
									</ol>
								</div>
								";
	
		}  // End of section selector

	
		

?>                       