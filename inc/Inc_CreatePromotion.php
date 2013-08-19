<?php 

	require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Skin_Me.php';
	require_once 'Inc/Inc_Functions_Database.php';
	
	$CreatingPromotion = "Yes";

// #############################################
// ## Determine action for Step 1, Step 2, Step 3 or Step 4
// #############################################

	if (isset($_POST['StepFinalSubmitted']))	// if last step submitted
		{
				$HiddenVariables = "";			
				$Message_Promotions = "<img src='gfx/breadcrumbs-Step5.png' border='0' />";
				$Message_Promotions .= "<br><h4>Promotion Completed</h4>
									   <div class='SmallFont LightGray'>Your promotion has been created. You should now see the complete promotion in the map above.</div>";		
				$PreviousStep = "Step4";

				// Post all step 3 data into cookie so that there is just one big submit at end of process
				if (isset($_POST['CongratsMessage'])) {  $_SESSION['CongratsMessage'] = $_POST['CongratsMessage']; }

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
					  $_SESSION['Message'] = Sanitize_Data($_SESSION['Message']);
					  $_SESSION['Coupon'] = Sanitize_Data($_SESSION['Coupon']);
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
					$sql_SaveTaskData_Progress = "INSERT INTO Promotions_Task_Progress(UName, CheckInNumber, MaxCheckInNumber, Information) ";
					$sql_SaveTaskData_Progress .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['CheckInNumber']}', '{$_SESSION['MaxCheckInNumber']}', '{$_SESSION['Message']}')";
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
				if ($Result_SaveTaskData) { $TaskID = $Result_SaveTaskData; } else { $TaskID = 0; }


				// -----------------
				// Submit Reward Data
				// -----------------				

				if ($_SESSION['RewardType'] == "Coupon") {							
					$sql_SaveRewardData_Coupon = "INSERT INTO Promotions_Rewards_Coupon(UName, CouponName, Coupon, ExpirationDate) ";
					$sql_SaveRewardData_Coupon .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['CouponName']}','{$_SESSION['Coupon']}', STR_TO_DATE('{$_SESSION['ExpirationDate']}','%b %d, %Y'))";
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Coupon);
				}
				
				if ($_SESSION['RewardType'] == "Message") {								
					$sql_SaveRewardData_Message = "INSERT INTO Promotions_Rewards_Information(UName, Information) ";
					$sql_SaveRewardData_Message .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['Message']}')";
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Message);
				}

				if ($_SESSION['RewardType'] == "Status") {								
					$sql_SaveRewardData_Status = "INSERT INTO Promotions_Rewards_Status(UName, StatusLevel1, StatusLevel2, StatusLevel3, StatusLevel4, StatusLevel5, StatusLevel6, StatusLevel7) ";
					$sql_SaveRewardData_Status .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['StatusLevel1']}', '{$_SESSION['StatusLevel2']}', '{$_SESSION['StatusLevel3']}', '{$_SESSION['StatusLevel4']}', '{$_SESSION['StatusLevel5']}', '{$_SESSION['StatusLevel6']}', '{$_SESSION['StatusLevel7']}')";
					$Result_SaveRewardData = Insert($sql_SaveRewardData_Status);
				}

				// Get the TaskID
				if ($Result_SaveRewardData) { $RewardID = $Result_SaveRewardData; }  else { $RewardID = 0; }


				// -----------------
				// Submit Promotion Data
				// -----------------
			     $sql_SavePromotionsData = "INSERT INTO Promotions(UName, TaskType, TaskID, RewardType, RewardID, StartDate, EndDate,
											PromoTitle, TaskDescription, RewardDescription, PromoRange, Proximity, 
											CongratsMessage, CreateDate) ";
			     $sql_SavePromotionsData .= "VALUES ('{$_SESSION['UName']}', '{$_SESSION['TaskType']}', '{$TaskID}', '{$_SESSION['RewardType']}', '{$RewardID}',
											STR_TO_DATE('{$_SESSION['StartDate']}','%b %d, %Y'), STR_TO_DATE('{$_SESSION['EndDate']}','%b %d, %Y'),
											'{$_SESSION['PromoTitle']}','{$_SESSION['TaskDescription']}','{$_SESSION['RewardDescription']}', 'Personal', '{$_SESSION['Proximity']}',
											'{$_SESSION['CongratsMessage']}','{$CreateDate}') "; 

			   $Result_SavePromotionsData = Insert($sql_SavePromotionsData);
	  
			  // Get PromotionID to create record in Location_Promotion table
			  $sql_GetPromotionID = "SELECT PromotionID FROM Promotions WHERE UName='{$_SESSION['UName']}' ORDER BY PromotionID DESC LIMIT 1";
			  $Result_GetPromotionID = Select($sql_GetPromotionID);	
			  if ($Result_GetPromotionID) {$PromotionID = $Result_GetPromotionID[0]['PromotionID']; } else { $PromotionID = FALSE; }
	  
			  // Add record to Location_Promotion database
			   if ($PromotionID) {
						// break apart LocationOptions into array, and insert each individual using a ForEach loop
						$LocationOptions = explode(",",trim($_SESSION['LocationOptions']));
						
						foreach ($LocationOptions as $LocationID) {
				   
						   $sql_SavePromoLocoData = "INSERT INTO Location_Promotions(PromotionID, LocationID, PromoQuantity, PromosEarned, StartDate, EndDate, Hits) ";
						   $sql_SavePromoLocoData .= "VALUES ('$PromotionID', '{$LocationID}', '{$_SESSION['PromoQuantity']}', 0 ,STR_TO_DATE('{$_SESSION['StartDate']}','%b %d, %Y'), STR_TO_DATE('{$_SESSION['EndDate']}','%b %d, %Y'),0) "; 	
								   
						   $Result_SavePromoLocoData = Insert($sql_SavePromoLocoData);		 
	  					}
	  
				   if ($Result_SavePromoLocoData) 
					  {$Message_Promotions = "<br><div class='ErrorMessage' align='center'>Promotion successfully created.</div>";}
					  else {$Message_Promotions = "<br><div class='ErrorMessage' align='center'>Promotion creation failed.  Please contact administrator.</div>";}
			   }


				// ----------------------
				// -- Map Information - Location / Icon	
				// ----------------------

				

				// Define how this section displays
 					$Message_Promotions = "<strong class='Black'>Congratulations!</strong>  Your promotion has been created.  Below is what your confirmation screen will look like.";		 				
				
				// Add "page" formatting
					$Message_Promotions .= "<br><div align='center'>";
					$Message_Promotions .= ShowPromo_ThankYou($PromotionID);
					$Message_Promotions .= "</div><br>";

					$_SESSION['CreatePromo'] = "";

		} 
	else if (isset($_POST['Step3Submitted']) || isset($_POST['Step4']))	// if step 3 submitted
	{

				// Post all step 3 data into cookie so that there is just one big submit at end of process
				if (isset($_POST['Step3Submitted'])) {
					$_SESSION['RewardDescription'] = $_POST['RewardDescription'];
					
					if ($_SESSION['RewardType'] == "Coupon") {
							$_SESSION['CouponTitle'] = $_POST['CouponTitle'];
							$_SESSION['CouponBody'] = str_replace("\r",'<br>',$_POST['CouponBody']);
							$_SESSION['CouponFinePrint'] = str_replace("\r",'<br>',$_POST['CouponFinePrint']); 		
							$_SESSION['ExpirationDate'] = $_POST['ExpirationDate'];
							
							// Format Promotion												
							$_SESSION['Coupon'] = "L**div class='Coupon_Title'**J{$_SESSION['CouponTitle']}L**/div**J
									   L**div class='Coupon_Body'**J{$_SESSION['CouponBody']}L**/div**J
									   L**div class='Coupon_FinePrint'**J{$_SESSION['CouponFinePrint']}L**/div**J";								
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

	 
					if ($_SESSION['RewardType'] == "Message") {
							$_SESSION['Message'] = $_POST['Message'];
						}
				}

				$HiddenVariables = "<input type='hidden' name='StepFinalSubmitted' value='XXXXXX' />";			
				$Message_Promotions = "<img src='gfx/breadcrumbs-Step4.png' border='0' />";
				$Message_Promotions .= "<h4>Final Review</h4>
									   <div class='SM_LightGray'>Please double check the information below for accuracy. If all looks ok, 
									   then please <strong>press the blue 'Approve the Promotion Below' button</strong>. Otherwise, go back and make any necessary updates before submitting.</div>";
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step4();", $ButtonValue_Promotions);	
				$CreatePromotionsJS = CreatePromotionsJS("Step4");
				$PreviousStep = "Step3";

				// ----------------------
				// -- Get Map Information - Location / Icon
				// ----------------------
				
				
				
				// ----------------------
				// -- Develop Data for display
				// ----------------------
					$LocationIDs = explode(",",trim($_SESSION['LocationOptions']));
					
					foreach ($LocationIDs as $LocationID) {
						
						$LocationInfoArray = LocationDetails($LocationID);
//						$_SESSION['LocationName'] = $LocationInfoArray[0]['LocationName'];
//						$_SESSION['StreetAddress'] = $LocationInfoArray[0]['StreetAddress'];
						
						$LocationValue .= "<br> &nbsp; &nbsp; &nbsp; &nbsp; <strong>{$LocationInfoArray[0]['LocationName']}</strong> ({$LocationInfoArray[0]['StreetAddress']}) "; 
						}
					
				
					$Message_Promotions .= "<div align='center' style='padding:10px''>
											<input type='button' value='Approve the Promotion Below' onclick=\"CreatePromo_Step4();\" class='submit'>
											</div>";
					
					$Message_Promotions .= "<div style='border: solid 1px #333; width:550px; padding:0 15px 3px 15px; background-color:#eee;'>
												<strong class='Black'>Your Promotion Preview</strong> <span class='SmallFont'><i>(Press \"Approve\" to create.)</i></span><br>
												<span class='SmallFont'> Promo Duration: <b>{$_SESSION['StartDate']} - {$_SESSION['EndDate']}</b></span><span class='SmallFont AlignRight'>Total Quantity: <b>{$_SESSION['PromoQuantity']}</b></span>
												<div class='SmallFont'> Valid Location(s): {$LocationValue}</div>
											</div>
								<div align='center' style='padding: 20px 0;'>";


					//  Create Array of Promotion values
					$PromotionID = "";	
					$EndDate = $_SESSION['EndDate'];
					$PromoTitle = $_SESSION['PromoTitle'];	
					$TaskType = $_SESSION['TaskType'];	
					$TaskDescription = $_SESSION['TaskDescription'];
					$TaskID = "0";
					$RewardType = $_SESSION['RewardType'];	
					$RewardDescription = $_POST['RewardDescription'];	
					$RewardID = "0";	
					$LocationName = $_SESSION['LocationName'];	
					$Proximity = $_SESSION['Proximity'];	
					$LocationLatitude = $_SESSION['LocationLatitude'];	
					$LocationLongitude = $_SESSION['LocationLongitude'];	
					$StreetAddress = $_SESSION['StreetAddress'];	
					$Zip = ""; //$_SESSION['Zip'];	
					$Phone = $_SESSION['Phone'];	
					$Description = $_SESSION['Description'];	
					
					$PromotionsDetailsArray = array('PromotionID' => $PromotionID, 'EndDate' => $EndDate, 'PromoTitle' => $PromoTitle, 
													'TaskType' => $TaskType, 'TaskDescription' => $TaskDescription, 'TaskID' => $TaskID, 
											'RewardType' => $RewardType, 'RewardDescription' => $RewardDescription, 'RewardID' => $RewardID, 
											'LocationName' => $LocationName, 'LocationLatitude' => $LocationLatitude,
											'LocationLongitude' => $LocationLongitude, 'StreetAddress' => $StreetAddress,
											'Zip' => $Zip, 'Phone' => $Phone, 'Description' => $Description,  'Proximity' => $Proximity, 'UName' => $_SESSION['UName']);			
				
					$Message_Promotions .= PromoUI($PromotionsDetailsArray,FALSE);

						
					// Confirmation Page Message
					$Message_Promotions .= "</div>";

// XXX to add the confirmation Message
/*
							<div><strong class='Black'>Confirmation Page Message:</strong> <span class='SmallFont'>(Optional)</span></div>
							
							<div class='SmallFont LightGray'>This is the message people will see once they complete a check-in.  
								This is your opportunity to thank them for checking in or promote something else. </div>
								
								<textarea name='CongratsMessage' style='width:580px; height:50px;' maxlength='140'></textarea>";
*/

					if ($_SESSION['RewardType'] == "Coupon") {												
						$Message_Promotions .= "<div><strong class='Black'>Earned Coupon</strong></div> 
									
												<div class='SmallFont LightGray'>This is the coupon customers will present to your business.</div>
												<br>";
							
						$Message_Promotions .= ShowCoupon($_SESSION['Coupon'],$RewardID="#");					
					}
					
					if ($_SESSION['RewardType'] == "Message") {												
						$Message_Promotions .= "
							<div><strong class='Black'>Information Reward:</strong></div>
							<div class='LightGray'>This is the information people will get once they complete the task:</div>
							<div align='center' class='Black' style='text-align: left;'>{$_SESSION['Message']}</div>
								";							
					}					
					
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

					
					if (!empty($_SESSION['CouponBody'])) { $_SESSION['CouponBody'] = str_replace("<br>",' ',$_SESSION['CouponBody']); }
					if (!empty($_SESSION['CouponFinePrint'])) { $_SESSION['CouponFinePrint'] = str_replace("<br>",' ',$_SESSION['CouponFinePrint']); }
		
					$HiddenVariables = "<input type='hidden' name='Step3Submitted' value='XXXXXX' />";			
				$Message_Promotions = "<img src='gfx/breadcrumbs-Step3.png' border='0' />";
				$Message_Promotions .= "<h4>Define Reward Details</h4>
										   <div class='SM_LightGray'>Please enter the reward details below.  This is where you tell them what's in it for them.</div>";
					$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step3('{$RewardType}');", $ButtonValue_Promotions);
					$CreatePromotionsJS = CreatePromotionsJS("Step3", $RewardType);
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
								$CouponBody = str_replace('<br>',"\r",$_SESSION['CouponBody']);  // this is if they come back...reformat HTML into textarea content
								$CouponFinePrint = str_replace('<br>',"\r",$_SESSION['CouponFinePrint']);  // this is if they come back...reformat HTML into textarea content
							
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Reward Type</strong>: Coupon</div>";
								$RewardHTML = "
									<li>
										<strong class='Black'>What's in it for me?</strong> &nbsp; <span class='SmallFont LightGray'>(Edit field below)</span>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<input type='hidden' name='CouponName' />
									<br>	
									<li><strong class='Black'>Coupon Expiration Date:</strong>  <div class='SmallFont LightGray'>This is the last day that a reward can be redeemed. This should be after the promo end date: <strong class='Black'>{$_SESSION['EndDate']}</strong>.</div><input id='date1' type='text' name='ExpirationDate' readonly='readonly' class='calendar' value='{$_SESSION['ExpirationDate']}'></li>						
									<br>
									<li><strong class='Black'>Coupon Content:</strong>
									<div class='SmallFont LightGray'>This is what appears on the actual coupon that they earn. (NOTE: 40 character limit on the title.)</div> <br>
									
									{$StartBorder_brown}
									<h2 class='headerimage'>Coupon</h2><br />
									<table class='SM_LightGray' align='center'>
									<tr><td>Title:</td><td align='right'><input name='CouponTitle' maxlength='35' value='{$_SESSION['CouponTitle']}' style='width:225px; background-color:#ECE9CF;'></td></tr>
									<tr><td colspan='2'>Coupon Details:*</td></tr>
									<tr><td colspan='2'><textarea name='CouponBody' style='width:265px; height:75px; background-color:#ECE9CF;'>{$_SESSION['CouponBody']}</textarea></td></tr>
									<tr><td colspan='2'>Fine Print: </td></tr>
									<tr><td colspan='2'><textarea name='CouponFinePrint' style='width:265px; height:75px; background-color:#ECE9CF;'>{$_SESSION['CouponFinePrint']}</textarea></td></tr>
									</table>
									{$EndBorder_brown}
	
									</li> 									
									";			
							}
							
						// Referral    
						if ($RewardType == "Referral") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Reward Type</strong>: Referral</div>";
								$RewardHTML = "
									<li>
										<strong class='Black'>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<br>	
									<li><strong class='Black'>Expiration Date:</strong>  (This is the last date that an earned reward can be used.)<br><input id='date1' type='text' name='ExpirationDate' readonly='readonly' class='calendar' value='{$_SESSION['ExpirationDate']}'></li>																	
									";
							}
	
						// Status
						if ($RewardType == "Status") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Reward Type</strong>: Status</div>";
								$RewardHTML = "
									<li>
										<strong class='Black'>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>											
									<br>
									<li>
										<strong class='Black'>Enter Up To 7 Levels:</strong> <div class='SmallFont LightGray'>Once the individual completes the task, they will advance one more level, starting with level 1.</div>
										<div class='SmallFont LightGray'>
											<ol>
											<li> <input name='StatusLevel1' style='width:200px;' value='{$_SESSION['StatusLevel1']}'> </li>
											<li> <input name='StatusLevel2' style='width:200px;' value='{$_SESSION['StatusLevel2']}'> </li>			
											<li> <input name='StatusLevel3' style='width:200px;' value='{$_SESSION['StatusLevel3']}'> </li>			
											<li> <input name='StatusLevel4' style='width:200px;' value='{$_SESSION['StatusLevel4']}'> </li>		
											<li> <input name='StatusLevel5' style='width:200px;' value='{$_SESSION['StatusLevel5']}'> </li>			
											<li> <input name='StatusLevel6' style='width:200px;' value='{$_SESSION['StatusLevel6']}'> </li>			
											<li> <input name='StatusLevel7' style='width:200px;' value='{$_SESSION['StatusLevel7']}'> </li>									
											</ol>
										</div>	
									</li>		
									";
							}
							
						
						// Info
						if ($RewardType == "Message") {
								$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Reward Type</strong>: Message</div>";
								$RewardHTML = "
									<li>
										<strong class='Black'>What's in it for me?</strong>
										<div><textarea name='RewardDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['RewardDescription']}</textarea></div>	
									</li>
									<li>
										<strong class='Black'>Enter Message to be Revealed After Task Completed:</strong>
										<div><textarea name='Message' style='width:420px; height:30px;' maxlength='175'>{$_SESSION['Message']}</textarea></div>	
									</li>								
									";
							}


				// Define how this section displays
 				$MyPromotions = "<div><ol>$RewardHTML</ol></div>";

		
		} 
	else if (isset($_POST['Step1Submitted']) || isset($_POST['Step2'])) {	// if step 1 Submitted
	
				// Post all step 1 data into session so that there is just one big submit at end of process
				if (isset($_POST['Step1Submitted'])) {
					$_SESSION['PromoTitle'] = $_SESSION['CouponTitle'] = $_POST['PromoTitle'];	// default coupon title (if there is a coupon) to same as promo title				
					$_SESSION['StartDate'] = $_POST['StartDate'];
					$_SESSION['EndDate'] = $_POST['EndDate'];
					$_SESSION['TaskType'] = $_POST['TaskType'];
					$_SESSION['RewardType'] = $_POST['RewardType'];
					$_SESSION['PromoQuantity'] = $_POST['PromoQuantity'];
					$_SESSION['LocationOptions'] = implode(",", $_POST['LocationOptions']);
					if (isset($_POST['Proximity'])) { $_SESSION['Proximity'] = $_POST['Proximity']; } else { $_SESSION['Proximity'] = ''; }
									
					// Just show first Location in sample promo
					$LocationID = explode(",",trim($_SESSION['LocationOptions']));

					$LocationInfoArray = LocationDetails($LocationID[0]);
					$_SESSION['LocationName'] = $LocationInfoArray[0]['LocationName'];
					$_SESSION['StreetAddress'] = $LocationInfoArray[0]['StreetAddress'];
					$_SESSION['Phone'] = $LocationInfoArray[0]['Phone'];
					$_SESSION['Description'] = $LocationInfoArray[0]['Description'];

					// Enter default values for Task and Reward Descriptions
 					$TaskType = $_POST['TaskType'];
					
					if ($_SESSION['TaskType'] == "Check-In") { 
							if ($_SESSION['Proximity'] == "Yes") { $_SESSION['TaskDescription'] = "Please click the check-in button"; }	
							else { $_SESSION['TaskDescription'] = "Please click the check-in button"; }	
						}		
					if ($_SESSION['TaskType'] == "Appointment") { 
								$_SESSION['TaskDescription'] = "Please click the check-in button during the valid time period"; }
					if ($_SESSION['TaskType'] == "Question") { 
								$_SESSION['TaskDescription'] = "Just submit a response to the question below"; }
								
					if ($_SESSION['Proximity'] == "Yes") { $_SESSION['TaskDescription'] .= " while at this promotion's location."; }
					else { $_SESSION['TaskDescription'] .= "."; }
									
		 
					

					if ($_SESSION['RewardDescription'] == "") {		
						if ($_SESSION['RewardType'] == "Coupon") { 
									$_SESSION['RewardDescription'] = "Receive a coupon from us when you complete the task."; }					
						if ($_SESSION['RewardType'] == "Status") { 
									$_SESSION['RewardDescription'] = "Improve your status."; }					
						if ($_SESSION['RewardType'] == "Message") { 
									$_SESSION['RewardDescription'] = "Get an exclusive message from us."; }
					}


//					if ($LocationDetails = GetLocationDetails($_POST['LocationOptions'])) {
//						$_SESSION['Phone'] = $LocationDetails['Phone'];
//						$_SESSION['Address'] = $LocationDetails['StreetAddress'] . "<br>" . $LocationDetails['Zip'];
//						$_SESSION['Description'] = $LocationDetails['Description'];
//							
//					} else {
//						$_SESSION['Phone'] = "";
//						$_SESSION['Address'] = "Not Found <br> There was an error pulling up address details.";				
//					}
					
				} else {
					$TaskType = $_SESSION['TaskType'];
				}


				

				$HiddenVariables = "<input type='hidden' name='Step2Submitted' value='XXXXXX' />";			
				$Message_Promotions = "<img src='gfx/breadcrumbs-Step2.png' border='0' />";
				$Message_Promotions .= "<h4>Define Task Details</h4> 
									   <div class='SM_LightGray'>Please enter the task details below.  There is where you tell the public what they need to do.</div>";
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step2('{$TaskType}');", $ButtonValue_Promotions);
				$CreatePromotionsJS = CreatePromotionsJS("Step2",$TaskType);
				$PreviousStep = "Step1";


				// ----------------------
				// -- Show Task info for display
				// ----------------------				
					// Check-In
					if ($TaskType == "Check-In") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Task Type</strong>: Check-In</div>";
							$TaskHTML = "
                                <li>
									<strong class='Black'>What do I need to do?</strong> &nbsp; <span class='SmallFont LightGray'>(Edit field below)</span>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>							
								";		
						}
			
					// Appointment
					if ($TaskType == "Appointment") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Task Type</strong>: Appointment</div>";
							
							$ValidDays = " " . $_SESSION['ValidDays'];   // to prevent day at position 0 failing to match
							
							if (strpos($ValidDays,"Monday")) { $MondayChecked = "checked='checked'"; }
							if (strpos($ValidDays,"Tuesday")) { $TuesdayChecked = "checked='checked'"; }
							if (strpos($ValidDays,"Wednesday")) { $WednesdayChecked = "checked='checked'"; } 
							if (strpos($ValidDays,"Thursday")) { $ThursdayChecked = "checked='checked'"; } 
							if (strpos($ValidDays,"Friday")) { $FridayChecked = "checked='checked'"; }
							if (strpos($ValidDays,"Saturday")) { $SaturdayChecked  = "checked='checked'"; } 
							if (strpos($ValidDays,"Sunday")) { $SundayChecked  = "checked='checked'"; }
							
							$TaskHTML = "
                                <li>
									<strong class='Black'>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>	
								
<!--								<br>
								
                                <li>
									<strong class='Black'>Choose Active Time of Day:</strong>
                                    <div class='SM_LightGray'>
										Start Time: <select name='DayStartTime'>$DayStartTimeOptions</select> 
										&nbsp; &nbsp; &nbsp;
										End Time: <select name='DayEndTime'>$DayEndTimeOptions</select>										
									</div>	
                                </li>-->
								<input type='hidden' name='DayStartTime' value=''>
								<input type='hidden' name='DayEndTime' value=''>	

								<br>

								<li>
									<strong class='Black'>Choose Active Days:</strong> &nbsp; <span class='SmallFont LightGray'><i>(select all that apply)</i></span> 
										<table><tr>
											<td>
											<INPUT type='checkbox' name='ValidDays[]' value='Monday' {$MondayChecked}> Monday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Tuesday' {$TuesdayChecked}> Tuesday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Wednesday' {$WednesdayChecked}> Wednesday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Thursday' {$ThursdayChecked}> Thursday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Friday' {$FridayChecked}> Friday </option>
											</td>
											<td width='50'>&nbsp;</td>
											<td valign='top'>
											<INPUT type='checkbox' name='ValidDays[]' value='Saturday' {$SaturdayChecked}> Saturday </option> <br>
											<INPUT type='checkbox' name='ValidDays[]' value='Sunday' {$SundayChecked}> Sunday </option>
											</td>
										</tr></table>

										
                                </li>	
								";	
						}
						
					// Progress
					if ($TaskType == "Progress") {
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Task Type</strong>: Progress</div>";
							$TaskHTML = "
                                <li>
									<strong class='Black'>What do I need to do?</strong>
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>	
								
								<br>

                                <li>
									<strong class='Black'>Number of Check-Ins to Complete Task:</strong>
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
							$Message_Promotions .= "<br><div style='border: solid 1px #333; width:550px; padding:5px; background-color:#eee;'><strong class='Black'>Selected Task Type</strong>: Question</div>";
							$TaskHTML = "
                                <li>
									<strong class='Black'>What do I need to do?</strong> 
                                    <div><textarea name='TaskDescription' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['TaskDescription']}</textarea></div>	
                                </li>
								
								<br>

                                <li>
									<strong class='Black'>Enter Question:</strong>
                                    <div><textarea name='Question' style='width:420px; height:30px;' maxlength='140'>{$_SESSION['Question']}</textarea></div>	
                                </li>

								<br>

                                <li>
									<strong class='Black'>Enter Multiple Choice Options:</strong>  <span class='SmallFont Red'>(optional)</span>
                                    <div class='SmallFont LightGray'>
									<i><u>If no options are entered</u></i>, then an open text field will be used for an unrestricted answer.
										<table width='420' cellpadding='2' class='SmallFont LightGray'>
											<tr><td>1. <input name='MCOptions1' style='width:150px;' value='{$_SESSION['MCOptions1']}' maxlength='20' tabindex=1></td><td>3. <input name='MCOptions3' style='width:150px;' value='{$_SESSION['MCOptions3']}' maxlength='20' tabindex=3></td></tr>
											<tr><td>2. <input name='MCOptions2' style='width:150px;' value='{$_SESSION['MCOptions2']}' maxlength='20' tabindex=2></td><td>4. <input name='MCOptions4' style='width:150px;' value='{$_SESSION['MCOptions4']}' maxlength='20' tabindex=4></td></tr>
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
				$Message_Promotions = "<img src='gfx/breadcrumbs-Step1.png' border='0' />";
				$Message_Promotions .= "<h4>Define Promotion Criteria</h4>
									   <div class='SM_LightGray'>Please enter the information below to determine the basic parameters of your 
									   promotion.</div>";	
				$ButtonValue_Promotions = str_replace("XXXX", "CreatePromo_Step1('');", $ButtonValue_Promotions);
				$CreatePromotionsJS = CreatePromotionsJS("Step1");


				// Clear all data to prevent mixed data in case they start over promo process before finishing previous attempt
				if (!isset($_POST['Step1'])) { ClearPromotionData(); }		//XXX  Need to prevent loss of all data if they simply come back from step 2
				
				// ----------------------
				// -- Develop Data for display
				// ----------------------
					// Location Dropdown information
					$sql_LocationData = "SELECT * FROM Location WHERE UName='{$_SESSION['UName']}' ORDER BY LocationName";
					$Result_LocationData = Select($sql_LocationData);

					// Show results of location query
					if (count($Result_LocationData)) {
				
							foreach ($Result_LocationData as $results) {
									// Make dropdown pre-select already chosen value    XXXX
									$SessionLocationID = " " . $_SESSION['LocationOptions'];  // to accommodate to space zero containing an option
									
									if (strrpos($SessionLocationID,$results['LocationID'])) { $Selected = "Selected"; } else { $Selected = ""; }
									
									$LocationOptions .= "<option value='{$results['LocationID']}' {$Selected}> {$results['LocationName']} ({$results['StreetAddress']})</option>";
								}   

					} else { $LocationOptions = "<option value=''>-- Create a location first. --</option>"; }

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
								case "Message":
									$Message = "checked='checked'";
									break;
								default:
									$Coupon = "checked='checked'";
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
								default:
									$CheckIn = "checked='checked'";									
								}
					} else { $CheckIn = "checked='checked'";	}
 
 					if (!empty($_SESSION['Proximity'])) { 
							if ($_SESSION['Proximity'] == "Yes") { $Yes_ProximityChecked = "checked='checked'"; }
							else { $No_ProximityChecked = "checked='checked'"; }
					} else { $No_ProximityChecked = "checked='checked'";  }				
 
					// Define how this section displays
					$MyPromotions = "
								<div> 
									<ol>
										<li>
											<strong class='Black'>Choose Promotion Title:</strong> <span class='SmallFont LightGray'>(Usually a short phrase describing the offer)</span>
											<div><input name='PromoTitle' style='width:420px' value='{$_SESSION['PromoTitle']}' maxlength='35'></div>
										</li>
										
										<br>
										
										<li>
											<strong class='Black'>Choose Location(s):</strong> <span class='SmallFont LightGray'>(Choose from among the locations you've created.)</span>
											<div><select name='LocationOptions[]' style='width:420px;' size='3' multiple> {$LocationOptions} </select></div>
											<div style='padding-left:25px' class='SmallFont LightGray'>

												 <input type='radio' value='Yes' name='Proximity' {$Yes_ProximityChecked}> Require Physical Presence  &nbsp; &nbsp; &nbsp; &nbsp;
												 <input type='radio' value='No' name='Proximity' {$No_ProximityChecked}> Allow Check-in From Anywhere
												
												</div>
										</li>
		
										<br />
											
										<li>
											<strong class='Black'>Choose Promotion Duration:</strong>
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
											<strong class='Black'>Choose Task Type:</strong>
											<div class='SM_LightGray'>
											<input type='radio' name='TaskType' value='CheckIn' {$CheckIn} /> Check-In &nbsp;
											<!--<input type='radio' name='TaskType' value='Progress' {$Progress} /> Progress &nbsp;-->
											<input type='radio' name='TaskType' value='Appointment' {$Appointment} /> Appointment &nbsp;
											<input type='radio' name='TaskType' value='Question' {$Question} /> Question
											</div>
										</li>
										
										<br />
										
										<li>
											<strong class='Black'>Choose Reward Type:</strong>
											<div class='SM_LightGray'>
											<input type='radio' name='RewardType' value='Coupon' {$Coupon} /> Coupon &nbsp;
											<!--<input type='radio' name='RewardType' value='Referral {$Referral}' /> Referral Bonus &nbsp;-->
											<input type='radio' name='RewardType' value='Status' {$Status} /> Status &nbsp;
											<input type='radio' name='RewardType' value='Message' {$Message} /> Message	                                    
											</div>	
										</li>
		
										<br />
																		
										<li>
											<strong class='Black'>Choose Promotion Quantity:</strong> <input type='text' name='PromoQuantity' size='3' value='{$_SESSION['PromoQuantity']}'>
										</li>
										
									</ol>
								</div>
								";
	
		}  // End of section selector

	
		

?>                       