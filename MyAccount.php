<?php 



    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';
	require_once 'Inc/Inc_Functions_Promotions.php';

 	// Hide error messages until explicitly called out.
 	$Hide_Profile = $Hide_MyLocations = $Hide_Promotions = $Hide_LayersIFollow = $Hide_MyLayers = $Hide_RemoveMarker = $Hide_DeleteLayer = "Hide";
	$ErrorMessage_Profile = $ErrorMessage_MyLocations = $ErrorMessage_Promotions = $ErrorMessage_MyLayers = $ErrorMessage_RemoveMarker = $ErrorMessage_DeleteLayer = "";
	$MyLayers_ToggleState = $LayersIFollow_ToggleState = $RemoveMarker_ToggleState = $RemoveLayer_ToggleState = "closed_toggle"; //"opened_toggle";
	
	
	//--------------
	//  Redirect if person is not logged in
	//--------------
	Redirect("Registration.php", "NotLoggedIn");

	
	
	//$MapOnPage = "Yes";
	
	//--------------
	//  Facebook Error Messages
	//--------------		
	if (isset($_GET['FB-error'])) {
		// Linking error
		if ($_GET['FB-error'] == "LinkFailed") { $ErrorMessage_MyAccount = "The attempt to link to layer failed for some undetermined reason.";   $Hide_MyAccount = ""; }
		if ($_GET['FB-error'] == "NoEmailMatched") { $ErrorMessage_MyAccount = "Facebook account email did not match <br>this account email.  Could not link to layer.";   $Hide_MyAccount = ""; }
	}

//#########################################################################################
//### Profile Data
//#########################################################################################

	//--------------
	//  Save Updated "My Account" Data
	//--------------
	  if (isset($_POST['Profile_ToggleVariable'])) {
		 //Needed to center map on this page
		 $LatLong = ConvertAddress($_POST['Zip']);
		 
		// print_r($LatLong);
		 
		 $LocationLatitude = $LatLong["LocationLatitude"];
		 $LocationLongitude = $LatLong["LocationLongitude"];
		
		 //echo "Lat: {$LocationLatitude}, Long: {$LocationLongitude}";		    
		  
		  
		 $UserDetails = array("PWord" => $_POST['PWord'], 
								"EmailAddress" => $_POST['EmailAddress'], 
								"FullName" => $_POST['FullName'], 
								"Zip" => $_POST['Zip']);

		// print_r($UserDetails);
		 
		//Make sure zip is recognized
		if ($LocationLatitude == "0" && $LocationLongitude == "0") {
			
			$ErrorMessage_Profile = "This zip was not found. No update made.";  $Hide_Profile = "";
			
		} else {		
			//Make sure that email address is not change to a duplicate of another user
			if(isset($_SESSION['EmailAddress']))
			{
			if ($_SESSION['EmailAddress'] != $_POST['EmailAddress']) {
				 if (UniqueEmail($_POST['EmailAddress'])) { $ErrorMessage_Profile = UpdateProfile($UserDetails); $Hide_Profile = "Success"; 
				 }
				   else { 
				   	$ErrorMessage_Profile = "This email address is already used by another account. No update made.";  $Hide_Profile = "";
				    }
			}
			else {
			
				
				$ErrorMessage_Profile = UpdateProfile($UserDetails); $Hide_Profile = "Success";
			}
			}
			 else { 
			
				$ErrorMessage_Profile = UpdateProfile($UserDetails); $Hide_Profile = "Success"; 
			}
		}
	  }

	//--------------
	//  Show Profile Data 
	//--------------

	  // Get Data
	  $sql_GetProfileData = "SELECT * FROM Users WHERE UName='{$_SESSION['UName']}'";
	  $Result_GetProfileDataFull = Select($sql_GetProfileData);
	  $Result_GetProfileData = $Result_GetProfileDataFull[0];
  
  	  // Facebook stuff added here 
	  $FB_LinkAccount = "<a href='Facebook.php?f=LinkAccount' class='button blue'>Link Account to Facebook</a>";
  
  	  if (!empty($_SESSION['FB_UserID'])) { 
	  			$ProfilePicture = "<img src='https://graph.facebook.com/{$_SESSION['FB_UserID']}/picture?type=square' style='float:left; padding:0 10px 0px 0;'>";  $FB_LinkAccount = "";}
	  elseif (!empty($Result_GetProfileData['ProfilePic'])) {
		   	  	$ProfilePicture = "<img src='gfx/ProfilePics/{$Result_GetProfileData['ProfilePic']}' style='float: left; padding:0 10px 20px 0;'>"; }
	  else { 	$ProfilePicture = "";  }
  

		  $FullName = str_replace('\'','&rsquo;',$Result_GetProfileData['FullName']);

		  if($_GET['action']=='Newpassword')
		  	$Result_GetProfileData['PWord']="";
		  $EditProfileInfo = "<p><label for='Username'>Username<span>*</span></label>
								  {$Result_GetProfileData['UName']}</p>
							  <p><label for='LayersIFollow_LayerName'>Name<span>*</span></label>
								  <input class='inputText' type='text' name='FullName' id='FullName_Profile' placeholder='Name*' value=\"{$Result_GetProfileData['FullName']}\" /></p>  
							  <p><label for='LayersIFollow_LayerName'>Password<span>*</span></label>
								  <input class='inputText' type='text' name='PWord' id='PWord_Profile' placeholder='Password*' value=\"{$Result_GetProfileData['PWord']}\" /></p>  
								  
							  <p><label for='LayersIFollow_LayerName'>Zip<span>*</span></label>
								  <input class='inputText' type='text' name='Zip' id='Zip_Profile' placeholder='Zip*' value=\"{$Result_GetProfileData['Zip']}\" /></p>  
							  <p><label for='LayersIFollow_LayerName'>Email<span>*</span></label>
								  <input class='inputText' type='text' name='EmailAddress' id='EmailAddress_Profile' placeholder='Email*' value=\"{$Result_GetProfileData['EmailAddress']}\" /></p>";	

			

			if ($Result_GetProfileData['PWord']) { $Password = "*********" . substr($Result_GetProfileData['PWord'], -3); } 
			  else { $Password = "<span><i>(Please create a password.)</i></span>"; }
					
			$ProfileInfo = "  <div style='padding:0 0 15px 15px;'>{$ProfilePicture}
								  <h3>{$Result_GetProfileData['FullName']}</h3></div>
								  
							  <div style='padding:0 0 15px 15px;'>
								  <strong>Username:</strong> {$Result_GetProfileData['UName']} <br>
								  <strong>Password:</strong> {$Password} 
							  </div>                
		  
							  <div style='padding:0 0 15px 15px;'>
								  <strong>Email:</strong> {$Result_GetProfileData['EmailAddress']}<br />
								  <strong>Zip:</strong> {$Result_GetProfileData['Zip']}
							  </div>
				  
							<div align='center'>{$FB_LinkAccount}</div>";
	

//#########################################################################################
//### Location Data
//#########################################################################################

	$LocationName = $_SESSION['UName'];

	//--------------
	//  Save Updated Locations Data
	//--------------
	
		if (isset($_POST['SubmitLocationForm'])) {
			
			$LocationDetails = array("EditLocation" => $_POST['EditLocation'], 
									"LocationName" => $_POST['LocationName'],
									"Description" => $_POST['Description'], 
									"StreetAddress" => $_POST['StreetAddress'], 
									"Zip" => $_POST['Zip'], 
									"Phone" => $_POST['Phone']);
			
			if (UpdateLocation($LocationDetails)) { $Message_Location = UpdateLocation($LocationDetails); }
			else { $Message_Location = ErrorMessage("Sorry, this address was not recognized. <br>Please adjust and try again.");  $ShowEditLocation = "Yes"; }
			
	  }	

	//--------------
	//  Insert new Locations record
	//--------------
	
	  if (isset($_POST['InsertLocationForm'])) {

			$LocationDetails = array("LocationName" => $_POST['LocationName'], 
									"Description" => $_POST['Description'],
									"StreetAddress" => $_POST['StreetAddress'], 
									"Zip" => $_POST['Zip'], 
									"Phone" => $_POST['Phone']);
			
			$Message_Location = InsertLocation($LocationDetails);
			
			if ($Message_Location) {  }
			else { $Message_Location = ErrorMessage("Sorry, this address was not recognized.  <br>Please adjust and try again.");  $ShowEditLocation = "Yes"; }

	  }

	//--------------
	//  Show Location Data 
	//--------------
	  if (isset($_GET['EditLocation']) OR isset($_GET['CreateLocation'])) {
		  
		  // depending if they are updating or inserting a new location
		  if (isset($_GET['EditLocation'])) {  
		  		$WhatToDoNext_Location = "SubmitLocationForm"; 
				$ButtonValue_Location = "<input type='button' value='Update' class='submit' id='Button_MyLocations'  />";
				//$ButtonValue_Location = "<div class='submit' id='Button_MyLocations'><span>Update</span></div>";   //--new button style
				  $sql_LocationData = "SELECT * FROM Location WHERE LocationID='{$_GET['EditLocation']}' AND UName='{$_SESSION['UName']}'";
				  $Result_LocationDataFull = Select($sql_LocationData);				
				  
				  if ($Result_LocationDataFull) {
					  $Result_LocationData = $Result_LocationDataFull[0];
		
						$LocationID = $_GET['EditLocation'];
						$LocationName = $Result_LocationData['LocationName'];
						$Description = $Result_LocationData['Description'];
						$StreetAddress = $Result_LocationData['StreetAddress'];
						$Zip = $Result_LocationData['Zip'];
						$Phone = $Result_LocationData['Phone'];
					} else {  $ErrorMessage_MyLocations = "Error:  Location not found."; $Hide_MyLocations = ""; }

		  } else { 
				$WhatToDoNext_Location = "InsertLocationForm"; 
				$ButtonValue_Location = "<input type='button' value='Create' id='Button_MyLocations' class='submit' />";

				if (isset($_POST['LocationName'])) { $LocationName = $_POST['LocationName']; }
				if (isset($_POST['Description'])) { $Description = $_POST['Description']; }
				if (isset($_POST['StreetAddress'])) { $StreetAddress = $_POST['StreetAddress']; }
				if (isset($_POST['Zip'])) { $Zip = $_POST['Zip']; }
				if (isset($_POST['Phone'])) { $Phone = $_POST['Phone']; }

		  }

		  $LocationInfo = "<br>
				  <table align='center' border='0' width='100%' class='LoginForm'>
					<tr><td align='left'>Name:<span>*</span></td><td><input value=\"{$LocationName}\" name='LocationName' id='LocationName_MyLocations' /></td></tr>
					<tr><td align='left'>Address:<span>*</span></td><td><input value=\"{$StreetAddress}\" name='StreetAddress' id='StreetAddress_MyLocations' /></td></tr>
					<tr><td align='left'>Zip:<span>*</span></td><td><input value=\"{$Zip}\" name='Zip' id='Zip_MyLocations' /></td></tr>      
					<tr><td align='left'>Phone:</td><td><input value=\"{$Phone}\" name='Phone' /></td></tr>
					<tr><td align='left' colspan='2'>Description: <span class='SmallFont LightGray'>(250 character limit)</span></td></tr>
  					<tr><td align='left' colspan='2'><textarea name='Description' style='width:240px; height:80px;' maxlength='251'>{$Description}</textarea></td></tr>    
				  </table>";	

	  } else {	// show all locations for this account

			$WhatToDoNext_Location = "EditLocationForm";
			$ButtonValue_Location = "<input type='submit' value='Add Location' class='submit' />";		

			$sql_LocationData = "SELECT * FROM Location WHERE UName='{$_SESSION['UName']}' ORDER BY LocationName";
			$Result_LocationData = Select($sql_LocationData);

			if (count($Result_LocationData)) {

				  foreach ($Result_LocationData as $results) {	
				   
				   if ($results['City'] AND $results['State']) { $Comma = ", "; }
					  else {$Comma = " ";}
															  

					if ($results['Description']) {	$DescriptionContent = 	"<div onClick=\"expandcontent('Location{$results['LocationID']}')\" class='Black'>Description &raquo;</div>
																			<div id='Location{$results['LocationID']}' class='switchcontent' style='margin:5px;'>
																			<div>{$results['Description']}</div>
																			</div>	";  }
					else { $DescriptionContent = ""; }							

				   $LocationInfo .= "<div class='padding10'></div>
                                        <div class='ToggleList'>
                                        	<a href='MyAccount.php?Mode=Sponsor&Lat={$results['LocationLatitude']}&Long={$results['LocationLongitude']}&Map=Yes'>{$results['LocationName']}</a>
											<span class='h4-submenu'><a href='MyAccount.php?EditLocation={$results['LocationID']}#Location' class='Red'>Edit</a></span>
											<br>
                                            {$results['StreetAddress']} &nbsp;   {$results['Zip']}<br>
                                            {$results['Phone']}
                                            {$DescriptionContent}
										</div>";					  
							  						  
					  }   // End For each record
			 
		   } else { $LocationInfo = "<div align='center' style='padding:30px;'>No locations yet.  Please use the button below to enter your first locations.</div>"; }
		   
	  }	


//#########################################################################################
//### My Layers Data
//#########################################################################################


	//--------------
	//  Delete Layer
	//--------------
		if (isset($_GET['DeleteLayer'])) {	

			$DeleteLayer_ToggleState = "opened_toggle";			
			$ErrorMessage_DeleteLayer = DeleteMyLayer($_GET['DeleteLayer'],$_SESSION['UName']); 
			$Hide_DeleteLayer = "Success";

			}
			
	//--------------
	//  Remove Marker
	//--------------
		if (isset($_GET['RemoveMarker'])) {	

			$RemoveMarker_ToggleState = "opened_toggle";			
			$ErrorMessage_RemoveMarker = DeleteMyMarker($_GET['RemoveMarker'],$_SESSION['UName']); 
			$Hide_RemoveMarker = "Success";

			}

	//--------------
	//  Update Layer Description
	//--------------
		if (isset($_POST['DescriptionID'])) {	
		
			$ErrorMessage_RemoveMarker = UpdateLayerDetails($_POST['Description'],$_POST['LayerName'],$_POST['DescriptionID'],$_SESSION['UName']); 
			$Hide_RemoveMarker = "Success";

			}

	//--------------
	//  Show or Invite Layers 
	//--------------
		
		if (isset($_POST['MyLayers_ToggleVariable'])) { 
			$MyLayers_ToggleState = "opened_toggle";			
			$ErrorMessage_MyLayers = CreateLayer($_POST['MyLayers_LayerName'],$_POST['MyLayers_Description'],$_SESSION['UName']); 

			if ( $ErrorMessage_MyLayers == "Successfully Created") { $Hide_MyLayers = "Success"; } 
			else { $Hide_MyLayers = ""; }
			
		}

		if (isset($_GET['DeleteMyLayer'])) { 
			$MyLayers_ToggleState = "opened_toggle";			
			$ErrorMessage_MyLayers = DeleteMyLayer($_GET['DeleteMyLayer'],$_SESSION['UName']); 
			$Hide_MyLayers = "Success";
		}

		if (isset($_GET['DeleteMyMarker'])) { 
			$MyMarker_ToggleState = "opened_toggle";			
			$ErrorMessage_MyLayers = DeleteMyLayer($_GET['PromotionID'],$_SESSION['UName']); 
			$Hide_MyLayers = "Success";
		}

		$MyLayers = MyLayers($_SESSION['UName']);	


//#########################################################################################
//### Layers I Follow Data
//#########################################################################################

	//--------------
	//  Remove / Block Layer
	//--------------
		if (isset($_GET['RemoveLayer'])) {	

			
			
			$ErrorMessage_LayersIFollow = RemoveFollowing($_GET['RemoveLayer'],$_SESSION['UName']); 
			$Hide_LayersIFollow = "Success";

			}
			
		if (isset($_GET['BlockLayer'])) { 
			
			$LayersIFollow_ToggleState = "opened_toggle";			
			$ErrorMessage_LayersIFollow = BlockFollower($_GET['BlockLayer'],$_SESSION['UName']); 
			$Hide_LayersIFollow = "Success";
			
			}

	//--------------
	//  Insert new Layers record
	//--------------

	  if (isset($_POST['LayersIFollow_ToggleVariable'])) {
		
			$LayersIFollow_ToggleState = "opened_toggle";
			
			if (!empty($_POST['LayersIFollow_LayerName'])) { $LinkageStatus = LinkLayers($_SESSION['UName'],$_POST['LayersIFollow_LayerName']); }
	
			if ($LinkageStatus == "Linked") {  
				$ErrorMessage_LayersIFollow = "Successly Linked"; 
				$Hide_LayersIFollow = "Success";
				
	  		} elseif ($LinkageStatus == "NoLayer") { 
				$ErrorMessage_LayersIFollow = "This layer was not found."; 
				$Hide_LayersIFollow = "";
				
	  		} elseif ($LinkageStatus == "NotYourself") { 
				$ErrorMessage_LayersIFollow = "Cannot link to your own layer!"; 
				$Hide_LayersIFollow = "";
				
	  		} elseif ($LinkageStatus == "ok") { 
				$ErrorMessage_LayersIFollow = "You are already following this layer!"; 
				$Hide_LayersIFollow = "Success";	

	  		} elseif ($LinkageStatus == "Updated") { 
				$ErrorMessage_LayersIFollow = "You are now following this layer!"; 
				$Hide_LayersIFollow = "Success";	
				
	  		} elseif ($LinkageStatus == "Blocked") { 
				$ErrorMessage_LayersIFollow = "The creator has blocked you from this layer!"; 
				$Hide_LayersIFollow = "";					
				
			} else { 
				$ErrorMessage_LayersIFollow = "Sorry, there has been an unexpected failure."; 
				$Hide_LayersIFollow = ""; 
				
			}
	  }

	//--------------
	//  Show or Invite Layers 
	//--------------
		$LayersFollowing = LayersIFollow($_SESSION['UName']);	
			


//#########################################################################################
//### Promotion data
//#########################################################################################


	if (isset($_GET['Show'])) { $Show = $_GET['Show']; } else { $Show = "";}

	//--------------
	//  Consume Promotion
	//--------------
		if (isset($_GET['Consumed'])) { if (ConsumeReward($_GET['Consumed'])) { $ErrorMessage_Promotions = "Promotion Consumed."; $Hide_Promotions = "Success"; }
										else { $ErrorMessage_Promotions = "Error: Not Consumed."; $Hide_Promotions = ""; }
									}

	//--------------
	//  Expire Promotion
	//--------------
		if (isset($_GET['Expire'])) { if (ExpirePromotion($_GET['Expire'])) { $ErrorMessage_Promotions = "Promotion Expired."; $Hide_Promotions = "Success";} }

	//--------------
	//  Hide Reward
	//--------------
		if (isset($_GET['HidePromo'])) { if (HideReward($_GET['HidePromo'])) { $ErrorMessage_Promotions = "Promotion Hidden."; $Hide_Promotions = "Success";} }	

	//--------------
	//  UnHide Reward
	//--------------
		if (isset($_GET['UnHidePromo'])) { if (UnHideReward($_GET['UnHidePromo'])) { $ErrorMessage_Promotions = "Promotion Un-hidden."; $Hide_Promotions = "Success";} }	

	//--------------
	//  Submit Edit Promotion
	//--------------
	if (isset($_POST['EditPromo'])) {  
	
				// List fields that could be editted and send data to SubmitEditPromo function
				$EditPromoData = "";
	
				$ErrorMessage_Promotions = SubmitEditPromo($EditPromoData);
				$Hide_Promotions = "Success";
	}

	//--------------
	//  Create Promotion
	//--------------


	
	//--------------
	//  Show Earned Rewards
	//--------------
		if (isset($_GET['Show'])) {
			  if ($_GET['Show'] == "Vendor") { $VendorClass = "current"; }
			  if ($_GET['Show'] == "Expiration") { $ExpirationClass = "current"; }			
			  if ($_GET['Show'] == "Hidden") { $HiddenClass = "current"; }
			  if ($_GET['Show'] == "Consumed") { $ConsumedClass = "current"; }
		} else { $VendorClass = "current"; }
			
		$EarnedRewards_DisplayOptions = "
				<div id='MyAccount_SubMenu' class='SmallFont'>
					<div id='MyAccount_SubMenu' class='SmallFont'>&nbsp;
						 <span class='AlignLeft'><strong>Order By</strong>: &nbsp;  <a href='$ThisPage?Show=Vendor#Rewards' class='$VendorClass'>Vendor</a> &nbsp; | &nbsp; <a href='$ThisPage?Show=Expiration#Rewards' class='$ExpirationClass'>Expiration</a></span>
						 <span class='AlignRight Disappear_Small'><strong>Show</strong>: &nbsp;  <a href='$ThisPage?Show=Hidden#Rewards' class='$HiddenClass'>Hidden</a> &nbsp; | &nbsp; <a href='$ThisPage?Show=Consumed#Rewards' class='$ConsumedClass'>Consumed</a></span>
					</div>											
			    </div>";

		if (isset($_GET['Show'])) { $Show = $_GET['Show']; }
		if (isset($_GET['OrderBy'])) { $OrderBy = $_GET['OrderBy']; }
		
		$EarnedRewards = ShowProgressPromos($OrderBy); 

	//--------------
	//  Show Sponsored Promotions
	//--------------  
		  // Nesting ensures that only one items is hightlighted at any given time.
		  	if (isset($_GET['Show'])) {
				  if ($_GET['Show'] == "Location") { $LocationClass = "current"; }
				  if ($_GET['Show'] == "StartDate") { $DateClass = "current"; }
				  if ($_GET['Show'] == "LayerName") { $LayerNameClass = "current"; }
				  if ($_GET['Show'] == "active") { $ActiveClass = "current"; }
				  if ($_GET['Show'] == "expired") { $ExpiredClass = "current"; }
			} else { $LocationClass = "current"; }			


			$SponsoredPromotions_DisplayOptions = "
					<div id='MyAccount_SubMenu' class='SmallFont'>&nbsp;
						 <span class='AlignLeft Disappear_Small'><strong>Order By</strong>: &nbsp;  <a href='$ThisPage?OrderBy=Location#Markers' class='$LocationClass'>Location</a> &nbsp; | &nbsp; <a href='$ThisPage?OrderBy=StartDate#Markers' class='$DateClass'>Start Date</a></span>
						 <span class='AlignRight'><strong>Show</strong>: &nbsp;  <a href='$ThisPage?Show=active#Markers' class='$ActiveClass'>Active</a> &nbsp; | &nbsp; <a href='$ThisPage?Show=expired#Markers' class='$ExpiredClass'>Expired</a></span>
					</div>				";		 
			
			//--------------
			//  Edit Promotion...else show all sponsored promotions
			//--------------
			if (isset($_GET['EditPromo'])) {  $MySponsoredPromotions = EditPromoForm($_GET['EditPromo']); } 
			else { $SponsoredPromotions = ShowSponsoredPromos($Show); }

	//--------------
	//  Show My Markers
	//-------------- 
			
	
			$MyMarkers_DisplayOptions2 = "
					<div id='MyAccount_SubMenu' class='SmallFont'>&nbsp;
						 <span class='AlignLeft'><strong>Order By</strong>: &nbsp;  <a href='$ThisPage?Show=Location' class='$LocationClass'>Location</a> &nbsp; | &nbsp; <a href='$ThisPage?Show=StartDate' class='$DateClass'>Create Date</a>&nbsp; | &nbsp; <a href='$ThisPage?Show=LayerName' class='$LayerNameClass'>Layer Name</a></span>
						 <span class='AlignRight Disappear_Small'><strong>Show</strong>: &nbsp;  <a href='$ThisPage?Show=Active' class='$ActiveClass'>Active</a> &nbsp; | &nbsp; <a href='$ThisPage?Show=Expired' class='$ExpiredClass'>Expired</a></span>
					</div>
				";		  

			$MyMarkers = ShowMyMarkers($Show,"Toggle");
			$MyEmptyLayers = ShowMyEmptyLayers();

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Home</title>
<?php Require 'Inc/Inc_HeaderTag.php'; ?>
<style>
.BabyBlue_for_layer
{
display:none;
}
</style>
<script type="text/javascript" src="js/TotalJS.js"></script>
<script language="javascript">

	
 		$(document).ready(function(){				   

		   $("#ShowMap").click(function(event) {

				 var mymap = $("#mymap"); 

				 mymap.removeClass("Hide").addClass("Show");    

			});	


			// Show "Profile" edit section
			$("#Profile_ToggleButton").click(function(event) {
					
					 $("#Profile_Default").addClass("Hide");
					 $("#Profile_Hidden").removeClass("Hide");

 			});

			// "Profile" form validation
			$("#Profile_Submit").click(function(event) {
					
					  var Profile_ErrorMessage = $("#Profile_ErrorMessage"); 
					  var FullName = $("#FullName_Profile"); 
					  var PWord = $("#PWord_Profile"); 
					  var Zip = $("#Zip_Profile"); 
					  var EmailAddress = $("#EmailAddress_Profile"); 
				

					 if(FullName.val().length < 2){ 
							Profile_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							Profile_ErrorMessage.html("Name must be at least 2 characters");  
							return false; 
					}  
					
					 if(PWord.val().length < 4){ 
							Profile_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							Profile_ErrorMessage.html("Password name must be at least 4 characters");  
							return false; 
					}  

					 if(Zip.val().length != 5){ 
							Profile_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							Profile_ErrorMessage.text("Please include a valid zip.");  
							return false; 
					}  

					 if(EmailAddress.val().length < 4){ 
							Profile_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							Profile_ErrorMessage.text("Please include a valid email address.");  
							return false; 
					} 
					
					$('#Profile_FormID').submit();

 			});
			
  
 			// "MyLayers" form validation
			$("#MyLayers_Submit").click(function(event) {

					  var MyLayers_ErrorMessage = $("#MyLayers_ErrorMessage"); 
					  var LayerName = $("#MyLayers_LayerName"); 
				

					 if(LayerName.val().length < 2){ 
							MyLayers_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							MyLayers_ErrorMessage.html("Layer name must be at least 2 characters.</i>");  
							return false; 
					}  

					$('#MyLayers_FormID').submit();

 			});
			
			
 			// Show "LayersIFollow" edit section
			$("#LayersIFollow_ToggleButton").click(function(event) {
					
					 $("#LayersIFollow_Default").addClass("Hide");
					 $("#LayersIFollow_Hidden").removeClass("Hide");

 			});
 
 			// "LayersIFollow" form validation
			$("#LayersIFollow_Submit").click(function(event) {
					
					  var LayersIFollow_ErrorMessage = $("#LayersIFollow_ErrorMessage"); 
					  var LayerName = $("#LayersIFollow_LayerName"); 
				
				
					 if(LayerName.val().length < 2){ 
							LayersIFollow_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							LayersIFollow_ErrorMessage.html("Layer name must be at least 2 characters.</i>");  
							return false; 
					}  

					$('#LayersIFollow_FormID').submit();

 			});

 
 		});


/*
#XXXX
XXXX_ToggleButton
XXXX
XXXX_Hidden
XXXX_FormName
XXXX_FormID
XXXX_ToggleVariable
XXXX_Submit
XXXX_ErrorMessage
$ErrorMessage_XXXX
$XXXX_ToggleState

Profile
MyLayers
LayersIFollow
*/


</script> 
<body id="MyAccount">

<?php Require 'Inc/Inc_Header.php'; ?>

    <!-- START CONTENT -->
 
    <section class="container clearfix">
		<!-- Page Title -->
			<header class="container page_info clearfix">
				
					<h1 class="regular Red bottom_line">My Account <span class="h1-submenu"><a href='MyAccount.php?LogOut'>Log Out</a></span></h1>
				
				<div class="clear"></div>
			</header>
			
		<!-- Page Title -->
	   <?php if($_GET['action']!='Newpassword') { ?>
		<!-- Start Primary Tab section -->
		<div class="content clearfix">
            
				<div class="tabs">
					<ul>
						<li><a href="#Follow_Layers">Layers | Follow</a></li>
						<li><a href="#Markers">My Layers</a></li>
                        <li><a href="#Rewards">My Check-Ins</a></li>
					</ul>
					<div class="clear"></div>
					<div class="bordered_box">
                    <div id="Follow_Layers">
                    <div class="content_text">
                    <?php echo $LayersFollowing; ?>
                         <div class='ErrorMessage<?php  echo $Hide_LayersIFollow; ?>' id='LayersIFollow_ErrorMessage'><?php  echo $ErrorMessage_LayersIFollow; ?></div>
                         
                    </div>
                    </div>
						<div id="Markers">	<!--  Sponsored Promotions  -->
							<div class="content_text">
                                    
                                    <div class='ErrorMessage<?php echo $Hide_RemoveMarker; ?>'><?php echo $ErrorMessage_RemoveMarker; ?></div>
                                    <?php echo $MyMarkers_DisplayOptions; ?>
                                    <?php echo $MyMarkers; ?>
                                    <?php echo $MyEmptyLayers; ?>
									                                            
                            </div>
						</div>
						<div id="Rewards" >		<!--  Earned Rewards  -->
							<div class="content_text">
                                    
									<?php //echo $EarnedRewards_DisplayOptions; ?>
                                    <?php echo $EarnedRewards; ?>                                                                                                          

							</div>
						</div>

					</div>
				</div>
            
		</div>
        <!-- End Primary Tab section -->
				      
        
        	<?php } ?>
		<!-- END MAIN COLUMN -->
		
		<!-- START PORTFOLIO DESCRIPTION -->
		<div class="sidebar">
			<div class="padding10"></div>
          
            
<!--  Start "Profile" Section -->            
            <a name="Profile" id="Profile"></a>
			<h4 class="bottom_line regular">Profile <span class="h4-submenu"><a href="Javascript:void(0);" id="Profile_ToggleButton">Edit</a></span></h4>
    <?php if($_GET['action']!='Newpassword') { ?>
                <div id="Profile_Default">
						<div class='ErrorMessage<?php echo $Hide_Profile; ?>'><?php echo $ErrorMessage_Profile; ?></div>
                        <?php echo $ProfileInfo; ?>
						
                </div>
            
                <div id="Profile_Hidden" class="Hide">
<?php } ?>
              <?php if($_GET['action']=='Newpassword') { ?>
               <div id="Profile_Default">
						<div class='ErrorMessage<?php echo $Hide_Profile; ?>'><?php echo $ErrorMessage_Profile; ?></div>
                        
						
                </div>
                <div id="Profile_Hidden" class="">
<?php } ?>
				<div style="padding:0 10px;">
                    <form method="post" name="Profile_FormName" id="Profile_FormID" action="MyAccount.php#Profile" style="padding:10px;">
                    <input type="hidden" name="Profile_ToggleVariable" value="Open">
                    
                    	<?php echo $EditProfileInfo; ?>
                        
                    </form>
                </div>
                          <div class='ErrorMessageHide' id='Profile_ErrorMessage'></div>
                          <div align="center"><a href="#" class="button orange" id="Profile_Submit">Submit</a> </div>                
                </div>
                <div class="padding20"></div>     
<!--  End "Profile" Section --> 
            

<!--  Start "LayersIFollow" Section 
                
			<a name="LayersIFollow" id="LayersIFollow"></a>
			<div class="toogle_box">
				<div class="toggle <?php // echo $LayersIFollow_ToggleState; ?>"><div class="icon"></div>
					Layers I Follow
				</div>
				<div class="toggle_container">

                    <div id="LayersIFollow_Default">
                    
							<?php // echo $LayersFollowing; ?>
                            <div class='ErrorMessage<?php // echo $Hide_LayersIFollow; ?>' id='LayersIFollow_ErrorMessage'><?php // echo $ErrorMessage_LayersIFollow; ?></div>
                            <!--<div align="center"><a href="Javascript:void(0);" class="button orange" id="LayersIFollow_ToggleButton">Link to a Layer</a> </div>
                    
                    </div>
                    <div id="LayersIFollow_Hidden" class="Hide">

                            <form method="post" name="LayersIFollow_FormName" id="LayersIFollow_FormID" action="MyAccount.php#LayersIFollow" style="padding:10px;">
                            <input type="hidden" name="LayersIFollow_ToggleVariable" value="Open">

                                <p><label for="LayersIFollow_LayerName">Enter the layer to follow<span>*</span></label>
                                    <input class="inputText" type="text" name="LayersIFollow_LayerName" id="LayersIFollow_LayerName" placeholder="Layer Name*" value="<?php // echo $LayersIFollow_LayerName; ?>" /></p>    

								
                            </form>
                    
 		                   <div class='ErrorMessageHide' id='LayersIFollow_ErrorMessage'></div>
                           <div align="center"><a href="#" class="button orange" id="LayersIFollow_Submit">Submit</a> </div>
                    
                    </div>
                    
				</div>
            </div>
            <div class="padding20"></div>
 End "LayersIFollow" Section -->


<!--  Start "MyLayers" Section -->
			<a name="MyLayers" id="MyLayers"></a>
			<div class="toogle_box">
				<div class="toggle <?php echo $MyLayers_ToggleState; ?>"><div class="icon"></div>
					Create a Layer
				</div>
				<div class="toggle_container">

                    <div id="MyLayers">
                            
                            <form method="post" name="MyLayers_FormName" id="MyLayers_FormID" action="MyAccount.php#MyLayers" style="padding:10px;">
                            <input type="hidden" name="MyLayers_ToggleVariable" value="Open">

                                <p><label for="MyLayers_LayerName">Layer Name<span>*</span></label>
                                    <input class="inputText" type="text" name="MyLayers_LayerName" id="MyLayers_LayerName" placeholder="Layer Name*" value="<?php echo $MyLayers_LayerName; ?>" /></p>    
                                            
                                <p><label for="MyLayers_Description">Description<span>*</span></label>
                                    <textarea name="MyLayers_Description" id="MyLayers_Description" placeholder="Enter Your Message Here*"><?php echo $MyLayers_Description; ?></textarea></p>		
                            </form>
                    
  	                  		<div class='ErrorMessageHide' id='MyLayers_ErrorMessage'></div>
 		                    <div align="center"><a href="#" class="button orange" id="MyLayers_Submit">Submit</a> </div>
                    
                    </div>                    
                    
				</div>
            </div>
            <div class="padding20"></div>
<!--  End "MyLayers" Section -->

			<div class="clear padding10"></div>			
            
			<!-- START Locations Toggle -- >
            <a name="Location"></a>
			<div class="toogle_box">
				<div class="toggle closed_toggle">
					<div class="icon"></div>
					My Locations
				</div>
				<div class="toggle_container">

					<?php // echo $LocationInfo; ?>
			
                    <div class="padding10"></div>	
                    <div align="center"><a href="MyAccount.php?CreateLocation#Location" class="button orange">Create Location</a> </div>
           			<div class="clear padding20"></div>

				</div>
			</div>
			<!-- END Locations Toggle -->

            
            <div class="clear"></div>

		</div>
		<div class="clear padding40"></div>
		<!-- END PORTFOLIO DESCRIOPTION -->
		
	</section>

    <!-- END CONTENT -->
 
 
 	<!-- footer -->
    <?php Require 'Inc/Inc_Footer.php'; ?>
	<!-- /footer -->
    </div>
    <!--wrapper end-->

</body>
</html>
