<?php 
 
    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';
	require_once 'Inc/Inc_Functions_Promotions.php';

	$MapOnPage = "Yes";

	//--------------
	//  Redirect if person is not logged in, otherwise set $UName value
	//--------------
	//Redirect("Login.php", "NotLoggedIn");

	$Hide_Marker = "Hide";
	$ErrorMessage_Marker = "";

	if (isset($_POST['Marker_FormStatus'])) { 
		if(!$_POST['LocationType'])
			$_POST['LocationType']="Here";
		if ($_POST['LocationType'] == "Here") { $LocationName = $_POST['Marker_LocationName1']; }
		elseif ($_POST['LocationType'] == "ByAddress") { $LocationName = $_POST['Marker_LocationName']; }
		elseif ($_POST['LocationType'] == "Previous") { $LocationName = $_POST['Marker_LocationID']; }
		else { $LocationName = "Error: Not Captured"; }

		$MarkerDetails = array("Title" => $_POST['Marker_Title'], 
								"Message" => $_POST['Marker_Message'], 
								"LocationName" => $LocationName, 
								"LayerID" => $_POST['Marker_LayerID'],
								"StreetAddress" => $_POST['Marker_StreetAddress'],
								"Zip" => $_POST['Marker_Zip'],
								"StartDate" => $_POST['Marker_StartDate'],
								"EndDate" => $_POST['Marker_EndDate'],
								"Quantity" => $_POST['Marker_Quantity'],
								"Question" => $_POST['Marker_Question'],
								"CongratsMessage" => $_POST['Marker_Confirmation'],
								"LocationLatitude" => $_POST['CurrentLatitude'],
								"LocationLongitude" => $_POST['CurrentLongitude'],
								"LocationType" => $_POST['LocationType'], 
								"Type" => $_POST['Type'] ,
								"Reward" => $_POST['Reward'],
								"coupon_date"=>$_POST['coupon_date'],
								"coupon_msg"=>$_POST['coupon_msg'],
								"fine_print"=>$_POST['fine_print'],
								"status_1"=>$_POST['status_1'],
								"status_2"=>$_POST['status_2'],
								"status_3"=>$_POST['status_3'],
								"reward_Confirmation"=>$_POST['reward_Confirmation']
				
								);
		
	
		
		setcookie('LayerID',$_POST['Marker_LayerID'],time() + (86400* 15));	  //valid for 15 days
		
		$ErrorMessage = CreateMarker($MarkerDetails);

		//var_dump($ErrorMessage);
		
		if ($ErrorMessage == "Success") {				$Hide_Marker = "Success";
		
														$LayerDetailsArray = GetLayerDetails($_POST['Marker_LayerID']);
														$ErrorMessage_Marker = "Marker creation was successful. <a href='Layers.php?ID={$_POST['Marker_LayerID']}' class='BabyBlue'>See Layer</a>."; 
														//header("Location:Layers.php?ID={$_POST['Marker_LayerID']}"); 
														//exit(); 
														}
		elseif ($ErrorMessage == "Invalid-Location") { 	$Hide_Marker = ""; 
														$ErrorMessage_Marker = "This address was not found."; }
		elseif ($ErrorMessage == "Promotion_Location-Failure") { $Hide_Marker = ""; 
														$ErrorMessage_Marker = "Unexpected promo/location creation failure."; }
		elseif ($ErrorMessage == "No-Location") { 		$Hide_Marker = ""; 
														$ErrorMessage_Marker = "No location associated with this marker."; }
		else { 											$Hide_Marker = ""; 
														$ErrorMessage_Marker = "Marker creation has failed."; }
		
	}  

  if (isset($_SESSION['UName'])) {
 
 
			// -----------------------------------------		
			// --------  Layer Dropdown Options  -------
			// -----------------------------------------		
			$MyLayersArray = GetUserLayers($_SESSION['UName']);
			
			if (count($MyLayersArray)) {
											
				$LayerOptions = "<option value='0'>Choose One</option>";

				foreach($MyLayersArray as $LayerRecord) {

					$Selected = "";
					if (isset($_POST['Marker_LayerID'])) { if ($_POST['Marker_LayerID'] == $LayerRecord['LayerID']) { $Selected = "selected"; } } 
					elseif (isset($_COOKIE['LayerID'])) { if ($_COOKIE['LayerID'] == $LayerRecord['LayerID']) { $Selected = "selected"; } }  

					$LayerOptions .= "<option value='{$LayerRecord['LayerID']}' {$Selected}>{$LayerRecord['LayerName']}</option>";
				}


			} else { $LayerOptions = "<option value=''>Please create a Layer first in your Account.</option>";  }

			// -----------------------------------------		
			// --------  Location Dropdown Options  -------
			// -----------------------------------------		
			$sql_LocationData = "SELECT * FROM Location WHERE UName='{$_SESSION['UName']}' ORDER BY LocationName";
			$Result_LocationData = Select($sql_LocationData);
			
			if (count($Result_LocationData)) {
											
				$LocationOptions = "<option value='0'>Choose One</option>";

				foreach($Result_LocationData as $LocationRecord) { $LocationOptions .= "<option value='{$LocationRecord['LocationID']}' >{$LocationRecord['LocationName']}</option>"; }

			} else { $LocationOptions = "<option value=''>No Locations Yet.</option>";  }
			

	} else { $LayerOptions = $LocationOptions = "<option value=''>Please login first</option>";	}
				
?>	
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Create a Marker</title>

		<?php Require 'Inc/Inc_HeaderTag.php'; ?>
		<script type="text/javascript" src="http://j.maxmind.com/app/geoip.js"></script>
        <script type="text/javascript" src="js/TotalJS.js" ></script>


        
        <script type="text/javascript">


        
		     

        var lat = geoip_latitude();
        var lng = geoip_longitude();
        var city= geoip_city();
       // document.write("Latitude: "+lat+"</br>Longitude: "+lng);     
        
        

<!-- #############  Start Location Options  ############# -->
		   function Here() {

			  // Hide sections
			  var HereDetails = $("#HereDetails"); 
			  var ByAddressDetails = $("#ByAddressDetails"); 
			  var PreviousDetails = $("#PreviousDetails"); 

			  HereDetails.removeClass("Hide"); 
			  ByAddressDetails.addClass("Hide"); 
			  PreviousDetails.addClass("Hide"); 
			  
			  // Adjust link formating
			  var HereLink = $("#HereLink");
			  var ByAddressLink = $("#ByAddressLink");
			  var PreviousLink = $("#PreviousLink");
			  
			  HereLink.addClass("strong").removeClass("Underline").removeClass("BabyBlue");	
			    ByAddressLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");
			    PreviousLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");	
			  
			  $("#LocationType").val('Here');	

			  $("#StreetAddress").val('Not Applicable');			  
			  $("#Zip").val('Not Applicable');

		   }
		   
		   function ByAddress() { 
		   
			  // Hide sections
			  var HereDetails = $("#HereDetails");
			  var ByAddressDetails = $("#ByAddressDetails"); 
			  var PreviousDetails = $("#PreviousDetails"); 

			  HereDetails.addClass("Hide"); 
			  ByAddressDetails.removeClass("Hide"); 
			  PreviousDetails.addClass("Hide"); 
			  
			  // Adjust link formating
			  var HereLink = $("#HereLink");
			  var ByAddressLink = $("#ByAddressLink");
			  var PreviousLink = $("#PreviousLink");
			  
			  ByAddressLink.addClass("strong").removeClass("Underline").removeClass("BabyBlue");	
			    HereLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");
			    PreviousLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");	
			  
			  $("#LocationType").val('ByAddress');		  

			  $("#StreetAddress").val('');			  
			  $("#Zip").val('');
			  
			  }	

		   function Previous() { 
		   
			  // Hide sections
			  var HereDetails = $("#HereDetails");
			  var ByAddressDetails = $("#ByAddressDetails"); 
			  var PreviousDetails = $("#PreviousDetails"); 

			  HereDetails.addClass("Hide"); 
			  ByAddressDetails.addClass("Hide"); 
			  PreviousDetails.removeClass("Hide"); 
			  
			  // Adjust link formating
			  var HereLink = $("#HereLink");
			  var ByAddressLink = $("#ByAddressLink");
			  var PreviousLink = $("#PreviousLink");
			  
			  PreviousLink.addClass("strong").removeClass("Underline").removeClass("BabyBlue");	
			    ByAddressLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");
			    HereLink.removeClass("strong").addClass("Underline").addClass("BabyBlue");	
			  
			  $("#LocationType").val('Previous');
			  
			  $("#StreetAddress").val('');			  
			  $("#Zip").val('');
			  
			  }					  	

<!-- #############  Start Option Options  ############# -->
		   function CollapseOptions() {

			  var OptionDetails = $("#OptionDetails"); 
			  OptionDetails.addClass("Hide"); 	

			  var Advanced = $("#Advanced");
			  Advanced.removeClass("Hide");

  			  var HideAdvanced = $("#HideAdvanced");
			  HideAdvanced.addClass("Hide");	

			  $("#RewardDetails").addClass("Hide");			  

		   }
		   
		   function ExpandOptions() { 
		   
			  var OptionDetails = $("#OptionDetails"); 		  
			  OptionDetails.removeClass("Hide");  

			  var Advanced = $("#Advanced");
			  Advanced.addClass("Hide");	
			  
  			  var HideAdvanced = $("#HideAdvanced");
			  HideAdvanced.removeClass("Hide");		  

			}	

		
<!-- #############  Start Type Options  ############# -->
		   function CollapseType() {

			  var CheckInDetails = $("#CheckInDetails"); 
			  var QuestionDetails = $("#QuestionDetails"); 
			  CheckInDetails.addClass("Hide");			  
			  QuestionDetails.addClass("Hide"); 	
			  $("#RewardDetails").addClass("Hide");
			  var Simple = $("#Simple");
			  Simple.addClass("strong").removeClass("Underline").removeClass("BabyBlue");	
			  
			  var CheckIn = $("#CheckIn");
			  CheckIn.removeClass("strong").addClass("Underline").addClass("BabyBlue");	
			  
			  var Question = $("#Question");
			  Question.removeClass("strong").addClass("Underline").addClass("BabyBlue");	
	
			  $("#Type").val('Marker');
			  
		   }
		   
		   function CheckInType() { 
		   
			  var CheckInDetails = $("#CheckInDetails"); 		  
			  var QuestionDetails = $("#QuestionDetails"); 		  
			  CheckInDetails.removeClass("Hide");  			  
			  QuestionDetails.addClass("Hide");  
			$("#RewardDetails").removeClass("Hide");
			  var CheckIn = $("#CheckIn");
			  CheckIn.addClass("strong").removeClass("Underline").removeClass("BabyBlue");

			  var Simple = $("#Simple");
			  Simple.removeClass("strong").addClass("Underline").addClass("BabyBlue");		

			  var Question = $("#Question");
			  Question.removeClass("strong").addClass("Underline").addClass("BabyBlue");

			  
			  $("#Type").val('CheckIn');
			  $("#Reward").val('Coupon');
		    }	

		   function QuestionType() { 
		   
			  var CheckInDetails = $("#CheckInDetails"); 		  
			  var QuestionDetails = $("#QuestionDetails"); 		  
			  CheckInDetails.addClass("Hide");  
			  QuestionDetails.removeClass("Hide");  
			  $("#RewardDetails").removeClass("Hide");
			  var Question = $("#Question");
			  Question.addClass("strong").removeClass("Underline").removeClass("BabyBlue");

			  var Simple = $("#Simple");
			  Simple.removeClass("strong").addClass("Underline").addClass("BabyBlue");	

			  var CheckIn = $("#CheckIn");
			  CheckIn.removeClass("strong").addClass("Underline").addClass("BabyBlue");	

			  $("#Type").val('Question');
			  $("#Reward").val('Coupon');
		    }							  


		   function Statustype() { 
			   
			   var StatusDetails = $("#StatusDetails"); 		  
				 var MessageDetails=$("#MessageDetails");
				 $("#CouponDetails").addClass("Hide");
				 StatusDetails.removeClass("Hide");
				 MessageDetails.addClass("Hide");
				 
				  var message = $("#Message");
				  message.removeClass("strong").addClass("Underline").addClass("BabyBlue");

				  var status = $("#Status");
				  status.addClass("strong").removeClass("Underline").removeClass("BabyBlue");		

				  var coupon = $("#Coupon");
				  coupon.removeClass("strong").addClass("Underline").addClass("BabyBlue");
				  $("#Reward").val('Status');

			    }	


		   function Messagetype() { 
			   //  Coupon Status Message
			   //    	RewardDetails StatusDetails MessageDetails 
				  var StatusDetails = $("#StatusDetails"); 		  
				 var MessageDetails=$("#MessageDetails");
				 $("#CouponDetails").addClass("Hide");
				 StatusDetails.addClass("Hide");
				 
				 MessageDetails.removeClass("Hide");
				  var message = $("#Message");
				  message.addClass("strong").removeClass("Underline").removeClass("BabyBlue");

				  var status = $("#Status");
				  status.removeClass("strong").addClass("Underline").addClass("BabyBlue");		

				  var coupon = $("#Coupon");
				  coupon.removeClass("strong").addClass("Underline").addClass("BabyBlue");
				  
				  $("#Reward").val('Message');
			    }	


		   function Coupontype() { 
			   //  Coupon Status Message 
			   //    	RewardDetails StatusDetails MessageDetails 
				  var StatusDetails = $("#StatusDetails"); 		  
				 var MessageDetails=$("#MessageDetails");
				 StatusDetails.addClass("Hide");
				 MessageDetails.addClass("Hide");
				 $("#CouponDetails").removeClass("Hide");
				  var message = $("#Message");
				  message.removeClass("strong").addClass("Underline").addClass("BabyBlue");

				  var status = $("#Status");
				  status.removeClass("strong").addClass("Underline").addClass("BabyBlue");		

				  var coupon = $("#Coupon");
				  coupon.addClass("strong").removeClass("Underline").removeClass("BabyBlue");

				  $("#Reward").val('Coupon');
			    }	
		   
		    
			  
        </script>

	<script language="javascript">
 		$(document).ready(function(){

 	         $("#CurrentLatitude").val(lat);
 			 $("#CurrentLongitude").val(lng);
 			  $('#Marker_LocationName1').val(city);
			
			   $("#Marker_Submit").click(function(event) {


				   
					  var LayerID = $("#Marker_LayerID"); 
					  var Title = $("#Marker_Title"); 
					  var StreetAddress = $("#Marker_StreetAddress");  
					  var Zip = $("#Marker_Zip"); 			  
					  var ErrorMessage = $("#Message_Marker"); 						  


					  var HereDetails = $("#HereDetails");
					  var ByAddressDetails = $("#ByAddressDetails"); 
					  var PreviousDetails = $("#PreviousDetails"); 

					 
					  
					  if(LayerID.val() == '0'){  
							  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							  ErrorMessage.text("Please select a layer name.");  
							  return false; 
					  }		
					  
					  if(Title.val().length < 4){  
							  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							  ErrorMessage.text("Please provide a title for your marker.");  
							  return false; 
					  }				

					  if(ByAddressDetails.attr('class')=='Hide' &&  PreviousDetails.attr('class')=='Hide'){
						  
					  if($('#Marker_LocationName1').val()=='')
					  {  
						  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
						  ErrorMessage.text("Please provide location for your marker.");  
						  return false; 
					  }	
					  			
					  }
					  if(ByAddressDetails.attr('class')=='' )
					  {
						  if($('#Marker_LocationName').val()=='')
						  {  
							  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							  ErrorMessage.text("Please provide location for your marker.");  
							  return false; 
					  }			
						  
						  if(StreetAddress.val().length < 4){  
								  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
								  ErrorMessage.text("Please include a street address.");  
								  return false; 
						  }		
						  
						   if(Zip.val().length < 4){  
								  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
								  ErrorMessage.text("Please include a Zip.");  
								  return false; 
						  }
					  }
					  if(PreviousDetails.attr('class')=='' ){
						  
						  if($('#Marker_LocationID').val()=='')
						  {  
							  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							  ErrorMessage.text("Please provide location for your marker.");  
							  return false; 
					  }	
						  			
						  }

					  if($("#Marker_StartDate").val() > $("#Marker_EndDate").val())
					  {
						  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
						  ErrorMessage.text("Date Should not exceed start date.");  
						  return false; 
					  }
					  
					if($("#OptionDetails").attr("class")=='')
					{
						
						if($("#QuestionDetails").attr("class")=='')
						{

							if($("#Marker_Question").val()=='')
							{
								  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
								  ErrorMessage.text("Enter the Question for a location");  
								  return false;
							}

					}
						
						
					}
if($("#RewardDetails").attr("class")=='')
{

	if($("#CouponDetails").attr("class")=='')
	{

		if($("#coupon_date").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the coupon date");  
			  return false;
		}
		if($("#coupon_msg").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the coupon message");  
			  return false;
		}
	
}

	if($("#StatusDetails").attr("class")=='')
	{

		if($("#status_1").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the status 1");  
			  return false;
		}

		if($("#status_2").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the status 2");  
			  return false;
		}

		if($("#status_3").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the status 3");  
			  return false;
		}

		
}

	if($("#MessageDetails").attr("class")=='')
	{

		if($("#reward_Confirmation").val()=='')
		{
			  ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage"); 
			  ErrorMessage.text("Enter the confirmation message");  
			  return false;
		}

		
}
	
}


					 $("#Marker_FormID").submit();
				});
		
 		});


    </script>				        
  

        <link href="gfx/homescreen.gif" rel="apple-touch-icon" />
        <link href="gfx/startup.png" rel="apple-touch-startup-image" />
    
    </head>
<body>

<?php Require 'Inc/Inc_Header.php'; ?>


    <!-- START CONTENT -->
    <section class="container clearfix">
		<!-- Page Title -->
        			<a name="Create"></a>
			<header class="container page_info clearfix">
				
					<h1 class="regular Red bottom_line">Create a Marker</h1>
				
				<div class="clear"></div>
			</header>
		<!-- /Page Title -->

        <!-- START CONTACT FORM -->	
        <form method="post" name="Marker_FormName" id="Marker_FormID" action="Marker.php#Create" class="order-guide"  style="padding:10px 0;">
        <input type='hidden' name="Marker_FormStatus" value="submitted" />            
        <input type='hidden' name="CurrentLatitude" id="CurrentLatitude" value="" />
        <input type='hidden' name="CurrentLongitude" id="CurrentLongitude" value="" />	
        <input type="hidden" name="LocationType" value="" id="LocationType" />
         <input type="hidden" name="Type" value="Marker" id="Type" />
        <input type="hidden" name="Reward" value="" id="Reward" />
         

		<div class="col_1_3">
            	
	            <div class="ErrorMessage<?php echo $Hide_Marker; ?>"><?php echo $ErrorMessage_Marker; ?></div>                       
                <div class="clear"></div>                
    
                <p>
                    <label for="Marker_LayerID">Layer Name <span>*</span></label>
                    <select class="selectText" name="Marker_LayerID" id="Marker_LayerID">
                    	<?php echo $LayerOptions; ?>
                    </select>
                </p>               
    
                <p>
                    <label for="Marker_Title">Marker Title <span>*</span></label>
                    <input class="inputText" type="text" name="Marker_Title" id="Marker_Title" placeholder="Marker Title*" value="<?php echo $Marker_Title; ?>" />
                </p>    
                            
                <p>
                    <label for="Marker_Message">Message </label>
                    <textarea name="Marker_Message" id="Marker_Message" class="inputTextarea" placeholder="Enter Your Message Here*"><?php echo $Marker_Message; ?></textarea>
                </p>

             
                
				 <div class="clear padding20"></div>		            

                <p>
                    <span class="label">Where:</span> &nbsp; &nbsp; 
                             <span onClick="Here()" class='strong NoUnderline' id="HereLink">Here</span>  
                             &nbsp; &nbsp; | &nbsp; &nbsp; 
                             <span onClick="ByAddress()"  class='BabyBlue Underline' id="ByAddressLink">By Address</span>
                             &nbsp; &nbsp; | &nbsp; &nbsp; 
                             <span onClick="Previous()"  class='BabyBlue Underline' id="PreviousLink">Previous</span>         				
                </p>
                <div class="clear"></div>

                <div id='HereDetails'>
                            <p>
                                <label class="SmallLabel" for="Marker_LocationName">Location Name <span>*</span></label>
                                <input type="text" name="Marker_LocationName1" id="Marker_LocationName1" placeholder="Enter name for future reference" value="<?php echo $Marker_LocationName1; ?>" class="inputText" />
                            </p>               
                            <div class="clear"></div>                
                </div>    
    
                <div id='ByAddressDetails' class="Hide">
                            <p>
                                <label class="SmallLabel" for="Marker_LocationName">Location Name <span>*</span></label>
                                <input type="text" name="Marker_LocationName" id="Marker_LocationName" placeholder="Enter name for future reference" value="<?php echo $Marker_LocationName; ?>" class="inputText" />
                            </p>               
                            <div class="clear"></div>
                            <p>
                                <label class="SmallLabel" for="Marker_StreetAddress">Street Address <span>*</span></label>
                                <input type="text" name="Marker_StreetAddress" id="Marker_StreetAddress" placeholder="Street Address*" value="<?php echo $Marker_StreetAddress; ?>" class="inputText" />
                            </p>               
                            <div class="clear"></div>
                            <p>
                                <label class="SmallLabel" for="Marker_Zip">Zip <span>*</span></label>
                                <input type="text" name="Marker_Zip" id="Marker_Zip" placeholder="Zip*" value="<?php echo $Marker_Zip; ?>" class="inputText"  />
                            </p>               
                            <div class="clear"></div>            
                </div>
                <div id='PreviousDetails' class="Hide">
                            <p>
                                <label class="SmallLabel" for="Marker_LocationID">Location <span>*</span> &nbsp; <span class="LightGray SmallFont">(Choose from previous locations)</span></label>
                                <select class="selectText" name="Marker_LocationID" id="Marker_LocationID">
                                    <?php echo $LocationOptions; ?>
                                </select>
                            </p>                        
                </div>
                
          &nbsp; &nbsp; <span onClick="ExpandOptions()"  class='BabyBlue Underline' id="Advanced">Advanced Options &raquo;</span>
          <span onClick="CollapseOptions()" class='Hide BabyBlue Underline' id="HideAdvanced"> &laquo; Hide Options</span> 
                                
                <div class="clear"></div>
    
                <!-- END CONTACT FORM -->		
            
            <div class="clear padding20"></div>		            
            <div class="clear"></div>

		</div>
		
		<div class="col_1_3 last">
        
        	<div id='OptionDetails' class="Hide">

                <p>
                    <span class="label">Type:</span> &nbsp; &nbsp; &nbsp;
                             <span onClick="CollapseType()" class='strong NoUnderline' id="Simple">Simple</span>  
                              &nbsp; &nbsp; | &nbsp; &nbsp;  
                             <span onClick="CheckInType()"  class='BabyBlue Underline' id="CheckIn">Check-In</span>
							  &nbsp; &nbsp; | &nbsp; &nbsp;  
                             <span onClick="QuestionType()"  class='BabyBlue Underline' id="Question">Question</span>                                      				
                </p>
                <div class="clear"></div>

                <p>
                    <label for="Marker_StartDate">Start Date &nbsp; <span class="LightGray SmallFont">(Date marker starts appearing on map)</span></label>
                    <input class="inputText" type="text" name="Marker_StartDate" id="Marker_StartDate" placeholder="Leave blank to start immediately" value="<?php echo $Marker_StartDate; ?>" />
                </p>    

                <p>
                    <label for="Marker_EndDate">End Date &nbsp; <span class="LightGray SmallFont">(Last day marker appears on a map)</span></label>
                    <input class="inputText" type="text" name="Marker_EndDate" id="Marker_EndDate" placeholder="Leave blank for no expiration" value="<?php echo $Marker_EndDate; ?>" />
                </p>                            	

                <div id='QuestionDetails' class="Hide">
          
          
                                            
      						<p>
                                <label for="Marker_Question">Question <span>*</span> &nbsp; <span class="LightGray SmallFont">(Question to be answered at location)</span></label>
                                <textarea name="Marker_Question" id="Marker_Question" class="inputTextarea" placeholder="Question to be asked*"><?php echo $Marker_Question; ?></textarea>
                            </p>               
                            
                            <div class="clear"></div>
                            	<p>
                                <label for="Marker_Confirmation">What's in it for me </label>
                                <textarea name="Marker_Confirmation" id="Marker_Confirmation" class="inputTextarea" placeholder="Message shown following check-in*"><?php echo $Marker_Confirmation; ?></textarea>
                            </p>               
                            <div class="clear"></div>
                                      
                            <p>
                              <label for="Marker_Quantity">Quantity  &nbsp; <span class="LightGray SmallFont">(# of allowed check-ins before marker disappears)</span></label>
                              <input type="text" name="Marker_Quantity" id="Marker_Quantity" placeholder="Leave blank for unlimited" value="<?php echo $Marker_Quantity; ?>" class="inputText"  />
                            </p>               
                            <div class="clear"></div>    
    
            </div>

                <div id='CheckInDetails' class="Hide">

      						<p>
                                <label for="Marker_Confirmation">What's in it for me </label>
                                <textarea name="Marker_Confirmation" id="Marker_Confirmation" class="inputTextarea" placeholder="Message shown following check-in*"><?php echo $Marker_Confirmation; ?></textarea>
                            </p>               
                            <div class="clear"></div>
                                      
                            <p>
                              <label for="Marker_Quantity">Quantity  &nbsp; <span class="LightGray SmallFont">(# of allowed check-ins before marker disappears)</span></label>
                              <input type="text" name="Marker_Quantity" id="Marker_Quantity" placeholder="Leave blank for unlimited" value="<?php echo $Marker_Quantity; ?>" class="inputText"  />
                            </p>               
                            <div class="clear"></div>        
    
            </div>	
                 
            </div>
        
        </div>

        		<div class="reward_col">
        
        	<div id='RewardDetails' class="Hide">

                <p>
                    <span class="label">Reward:</span> &nbsp; &nbsp; &nbsp;
                             <span onClick="Coupontype()" class='strong NoUnderline' id="Coupon">Coupon</span>  
                              &nbsp; &nbsp; | &nbsp; &nbsp;  
                             <span onClick="Statustype()"  class='BabyBlue Underline' id="Status">Status</span>
							  &nbsp; &nbsp; | &nbsp; &nbsp;  
                             <span onClick="Messagetype()"  class='BabyBlue Underline' id="Message">Message</span>                                      				
                </p>
                <div class="clear"></div>
      <div id='CouponDetails' class="">
                		<p>
      					<label for="coupon_date"> Expiration Date  <span> * </span></label>
      					<input type="text" class="inputText" placeholder="Enter Expire date " name="coupon_date" id="coupon_date" value="<?php echo $coupon_date; ?>">
      					</p>
                       <p>
                       <label for="coupon_msg"> Coupon message <span> *</span></label>
                       <textarea name="coupon_msg" class="inputTextarea" id="coupon_msg" placeholder="Enter a coupon message"><?php echo $coupon_msg;?></textarea>                
                       </p>                  
                       <p>
                       
                       <label for="fine_print">Fine Print</label>
                       <textarea name="fine_print" class="inputTextarea" id="fine_print" placeholder="enter optional values"><?php echo $coupon_fine;?></textarea>                
                     
                       </p>
                       
      <div class="clear"></div>
</div>
                <div id='StatusDetails' class="Hide">
          
                                            
      					<p>
      					<label for="status_1"> Status Level 1  <span> * </span></label>
      					<input type="text" class="inputText" placeholder="Enter name for future reference " name="status_1" id="status_1" value="<?php echo $status1; ?>">
      					</p> 
                        <p>
      					<label for="status_2"> Status Level 2 <span> * </span></label> 
      					<input type="text" class="inputText" placeholder="Enter name for future reference " name="status_2" id="status_2" value="<?php echo $status2; ?>">
      					</p>
      					<p>
      					<label for="status_3"> Status Level 3 <span> * </span></label>
      					<input type="text" class="inputText" placeholder="Enter name for future reference " name="status_3" id="status_3" value="<?php echo $status3; ?>">
      					</p>             
                            <div class="clear"></div>
    
            </div>

                <div id='MessageDetails' class="Hide">
   						<p>
                       <label for="reward_Confirmation">Confirmation Message <span>*</span></label>
                      <textarea name="reward_Confirmation" id="reward_Confirmation" class="inputTextarea" placeholder="Message shown following check-in*"><?php echo $Marker_Confirmation; ?></textarea>
                      </p>               
                  <div class="clear"></div>
                                      
                                
    
            </div>	
                 
            </div>
        
        </div>
        
        <div class="clear padding10"></div>		            
        <div class="clear"></div>        
        
        <div class="ErrorMessageHide" id="Message_Marker"></div>

<?php 
	if (!isset($_SESSION['UName'])) { echo "<div align='center'><a href='Registration.php#SignIn' class='button white'>You must login first</a> </div>"; 		 }
	else { echo "<div align='center'><a href='#Create' class='button orange' id='Marker_Submit'>Submit</a> </div>";	}
?>	
		</form>
        
      <div class="clear padding20"></div>	
	
</section>
    <!-- END CONTENT -->


	<!-- footer -->
    <?php Require 'Inc/Inc_Footer.php'; ?>
	<!-- /footer -->
    </div>
    <!--wrapper end-->

</body>
</html>
 