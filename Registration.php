<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';
 
 	// Hide error messages until explicitly called out.
 	$Hide_SignIn = $Hide_SignUp = "Hide";
	$ErrorMessage_SignIn = $ErrorMessage_SignUp = "";	
	
	//--------------
	//  Facebook Error Messages
	//--------------		
	if (isset($_GET['FB-error'])) {
		// Sign in errors
		if ($_GET['FB-error'] == "AccountNotLinked") { $SignIn_Message = "Your account is not linked to Facebook.  You can link your account once logged in.  Then you can log in through Facebook.";   $Hide1 = ""; }
		if ($_GET['FB-error'] == "NoAccount") { $SignIn_Message = "No Layr.es account was found.<br>Please first create an account.";   $Hide1 = ""; }
		
		// Sign up errors
		if ($_GET['FB-error'] == "NotUniqueUN") { $SignUp_Message = "Username already taken. Please choose another.";   $Hide2 = ""; }
		if ($_GET['FB-error'] == "NotOriginalFBID") { $SignUp_Message = "You already have an account linked to FB.";   $Hide2 = ""; }
		if ($_GET['FB-error'] == "FailedAccountCreation") { $SignUp_Message = "Something happened. Failed account creation.";   $Hide2 = ""; }				   
		if ($_GET['FB-error'] == "NotOriginalEmail") { $SignUp_Message = "Account already exists with this email address. <br>Log in to link your account to Facebook.";   $Hide2 = ""; }
		
		  //set values for form re-submissin from cookie
			if ($_GET['FB-error'] != "AccountNotLinked") {
			   $FB_UserID = $_SESSION['FB_UserID'];
			   $UName2 = $_SESSION['UName'];
			   $PWord2 = $_SESSION['PWord'];
			   $FullName = $_SESSION['FullName'];
			   $Location = $_SESSION['Location'];
			   $EmailAddress = $_SESSION['EmailAddress'];
			   $Gender = $_SESSION['Gender'];
			   $BDay = $_SESSION['BDay'];	
			}
		
		// delete cookie values so as to not succeed to MyAccount.php
   			 unset($_SESSION['FB_UserID']);
			 unset($_SESSION['UName']);
 			 unset($_SESSION['PWord']);
			 unset($_SESSION['FullName']);
			 unset($_SESSION['Location']);
			 unset($_SESSION['EmailAddress']);
			 unset($_SESSION['Gender']);
			 unset($_SESSION['BDay']);	 
		}

		
	//--------------
	//  Redirect if person is already logged in
	//--------------
	
	Redirect("MyAccount.php", "LoggedIn");  // Must be down here so as to not interfere with cookie setting of FB errors

	//--------------
	//  Submit form data if user is signing up
	//--------------		
	if (isset($_POST['SignUp_FormStatus'])) {

		  //  Put form data into User Details array
		  if (isset($_POST['SignUp_UName'])) { $SignUp_UName = $_POST['SignUp_UName']; }
		  if (isset($_POST['SignUp_PWord'])) { $SignUp_PWord = $_POST['SignUp_PWord']; }	
		  if (isset($_POST['SignUp_EmailAddress'])) { $SignUp_EmailAddress = $_POST['SignUp_EmailAddress']; }
		  if (isset($_POST['SignUp_FullName'])) { $SignUp_FullName = $_POST['SignUp_FullName']; }
		  if (isset($_POST['SignUp_Zip'])) { $SignUp_Zip = $_POST['SignUp_Zip']; }
		  if (isset($_POST['FB_UserID'])) { $FB_UserID = $_POST['FB_UserID']; }
		  if (isset($_POST['Gender'])) { $Gender = $_POST['Gender']; }
		  if (isset($_POST['BDay'])) { $BDay = $_POST['BDay']; }
		  
		  // in case a space is included in Username
		  $UName = str_replace(' ','-',$SignUp_UName);
		  
		  // Get Lat & Long from Address
		  $LocationCoordinates = ConvertAddress($SignUp_Zip);
		  
		  // Package all user info into array
		  $UserDetailsArray = array('UName' => $SignUp_UName, 'PWord' => $SignUp_PWord, 'FullName' => $SignUp_FullName, 'EmailAddress' => $SignUp_EmailAddress, 
									'LocationLatitude' => $LocationCoordinates['LocationLatitude'], 'LocationLongitude' => $LocationCoordinates['LocationLongitude'], 
									'Zip' => $SignUp_Zip, 'JoinDate' => $JoinDate, 'LastLogin' => $LastLogin, 'IPAddress' => $IPAddress,
									'FB_UserID' => $FB_UserID, 'Gender' => $Gender, 'BDay' => $BDay);
		  
		  // if they are asking for an original username, create an account for them	
		  if (OriginalUsername($SignUp_UName)) {
				if  (UniqueEmail($SignUp_EmailAddress)) {
			   
					  if (DB_CreateAccount($UserDetailsArray)) { Redirect('MyAccount.php'); } 
						  else { $ErrorMessage_SignUp = "There was something wrong. Your account has not been created.";  $Hide_SignUp = ""; }
			  
				} else {  
					  $ErrorMessage_SignUp = "This email address already exists.<br><a href='Help.php#Password'>Click here</a> to recover your password.";	
					  $Hide_SignUp = "";
				}
			
		  } else {		

			  $ErrorMessage_SignUp = "Username taken. Please select another.";
			  $Hide_SignUp = "";

		  }
		}   
			
	//---------------------------------
	//-- Log In
	//---------------------------------
	if (isset($_POST['SignIn_FormStatus']) ) {

		  if (isset($_POST['SignIn_UName'])) { $SignIn_UName = $_POST['SignIn_UName']; }
		  if (isset($_POST['SignIn_PWord'])) { $SignIn_PWord = $_POST['SignIn_PWord']; }  

		 
			if (DB_Authenticate($SignIn_UName, $SignIn_PWord)) { Redirect('MyAccount.php'); } 
			 else { $ErrorMessage_SignIn = "Login Failed.  Check Credentials."; $Hide_SignIn = "";}

	}	

	if( (isset($_GET['P']) && isset($_GET['U']) ))
	{
		if (isset($_GET['U'])) { $SignIn_UName = $_GET['U']; }
		if (isset($_GET['P'])) { $SignIn_PWord = $_GET['P']; }
		if (DB_Authenticate($SignIn_UName, $SignIn_PWord)) { Redirect('MyAccount.php?action=Newpassword'); }
		else { $ErrorMessage_SignIn = "Login Failed.  Check Credentials."; $Hide_SignIn = "";}
		
	}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Registration</title>
<?php Require 'Inc/Inc_HeaderTag.php'; ?>
	<script type="text/javascript" src="js/TotalJS.js"></script>
	<script language="javascript">
 		$(document).ready(function(){
								   
			   $("#SignUp_Submit").click(function(event) {

					var SignInMessage = $("#Message_SignIn"); 
					SignInMessage.removeClass("ErrorMessage").addClass("ErrorMessageHide");		
									   
					  var SignUpMessage = $("#Message_SignUp");
					  var UName = $("#SignUp_UName"); 
					  var PWord = $("#SignUp_PWord"); 
					  var EmailAddress = $("#SignUp_EmailAddress"); 
					  var Zip = $("#SignUp_Zip"); 					  
					  
					 if(UName.val().length < 4){ 
							SignUpMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignUpMessage.html("Please include a username. <br><i>(Must be greater than 4 characters)</i>"); 
							UName.focus(); 
							return false; 
					}
		
					 if(PWord.val().length < 4){ 
							SignUpMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignUpMessage.html("Please include a password. <br><i>(Must be greater than 4 characters)</i>");
							PWord.focus();  
							return false; 
					}  

					 if(EmailAddress.val().length < 4 || !isValidEmailAddress(EmailAddress.val())){ 
							SignUpMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignUpMessage.text("Please include a valid email address.");  
							EmailAddress.focus();
							return false; 
					}

					 if(Zip.val().length != 5){ 
							SignUpMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignUpMessage.text("Please include a valid zip.");  
							Zip.focus();
							return false; 
					}  

					$("#SignUp_FormID").submit();
				});



			   $("#SignIn_Submit").click(function(event) {

					var SignUpMessage = $("#Message_SignUp"); 
					SignUpMessage.removeClass("ErrorMessage").addClass("ErrorMessageHide");
   								
									   
					  var SignInMessage = $("#Message_SignIn");
					  var UName = $("#SignIn_UName"); 
					  var PWord = $("#SignIn_PWord"); 

					 if(UName.val().length < 4){ 
							SignInMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignInMessage.html("Please include a username. <br><i>(Must be greater than 4 characters)</i>");  
							UName.focus();
							return false; 
					}
		
					 if(PWord.val().length < 4){ 
							SignInMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							SignInMessage.html("Please include a password. <br><i>(Must be greater than 4 characters)</i>");  
							PWord.focus();
							return false; 
					}  

					$("#SignIn_FormID").submit();
				});
			   
 		});


    </script>


<body id="Registration" onLoad="document.forms.SignIn_FormName.SignIn_UName.focus();">

<?php Require 'Inc/Inc_Header.php'; ?>


    <!-- START CONTENT -->
    <section class="container clearfix">
		<!-- Page Title -->
			<header class="container page_info clearfix">
				
					<h1 class="regular Red bottom_line">Your Account</h1>
				
				<div class="clear"></div>
			</header>
			
		<!-- /Page Title -->

		<div class="col_1_2">
				<a href="#SignIn"></a>
				<h3>Sign In</h3>
				<!-- Start Sign Up -->
				<div align="center"> 	
		<a href='Facebook.php?f=Login' id='SignUp_Submitm'><img src="gfx/btn_login_facebook.png"/></a>
				</div>
                    <form method="post" name="SignIn_FormName" id="SignIn_FormID" action="Registration.php#SignIn" class="comments_form"  style="padding:10px 0;">
                    <input type='hidden' name="SignIn_FormStatus" value="submitted" />            
                        <div class="comment_wrap">
                            <label for="name">Username <span>*</span></label>
                            <input type="text" name="SignIn_UName" id="SignIn_UName" value="<?php echo $SignIn_UName ?>" />
                        </div>
    
                        <div class="comment_wrap">
                            <label for="email">Password <span>*</span></label>
                            <input type="text" name="SignIn_PWord" id="SignIn_PWord" value="<?php echo $SignIn_PWord ?>" />
                        </div>
					</form>
    
                    <div class="clear"></div>                
                    <div class="ErrorMessage<?php echo $Hide_SignIn; ?>" id="Message_SignIn"><?php echo $ErrorMessage_SignIn; ?></div>                                                        
					<div align='center'><a href='javascript:void(0)' class='button orange' id='SignIn_Submit'>Sign In</a> </div>
                    <div class="clear"></div>
                        
                    <div class="clear padding10"></div>  


                
				<div class="clear"></div>
				<!-- End Sign Up -->   

				<div class="padding20"></div>
        		<div class="bottom_line"></div>
                <div class="padding20"></div>
                
				<a href="#SignUp"></a>
				
				<h3>Create an Account</h3>
				<!-- Start Sign Up -->
				<div align="center">
			<a href='Facebook.php?f=CreateAccount' id='SignUp_Submitm'><img src="gfx/btn_signup_facebook.png"/></a>
				</div>
                    <form method="post" name="SignUp_FormName" id="SignUp_FormID" action="Registration.php#SignUp" class="comments_form"  style="padding:10px 0;">
                    <input type='hidden' name="SignUp_FormStatus" value="submitted" />                                       
                    <input type='hidden' name="FB_UserID" value="<?php echo $FB_UserID ?>" />
                    <input type='hidden' name="Gender" value="<?php echo $Gender ?>" />
                    <input type='hidden' name="BDay" value="<?php echo $BDay ?>" />      
                              
					<div class="comment_wrap">
						<label for="name">Username <span>*</span></label>
						<input type="text" name="SignUp_UName" id="SignUp_UName" value="<?php echo $SignUp_UName ?>" />
					</div>

					<div class="comment_wrap">
						<label for="email">Password <span>*</span></label>
						<input type="text" name="SignUp_PWord" id="SignUp_PWord" value="<?php echo $SignUp_PWord ?>" />
					</div>

					<div class="clear"></div>

					<div class="comment_wrap">
						<label for="name">Name </label>
						<input type="text" name="SignUp_FullName" id="SignUp_FullName" value="<?php echo $SignUp_FullName ?>" />
					</div>

					<div class="comment_wrap">
						<label for="email">Email <span>*</span></label>
						<input type="text" name="SignUp_EmailAddress" id="SignUp_EmailAddress" value="<?php echo $SignUp_EmailAddress ?>" />
					</div>

					<div class="clear"></div>

					<div class="comment_wrap">
						<label for="email">Zip <span>*</span></label>
						<input type="text" name="SignUp_Zip" id="SignUp_Zip" value="<?php echo $SignUp_Zip ?>" />
					</div>
					</form> 

                    <div class="clear"></div>                  
                    <div class="ErrorMessage<?php echo $Hide_SignUp; ?>" id="Message_SignUp"><?php echo $ErrorMessage_SignUp; ?></div>                                                         
					<div align='center'><a href='javascript:void(0)' class='button orange' id='SignUp_Submit'>Sign Up</a> </div>
					
                    <div class="clear"></div>

                       <div class="clear padding10"></div>  
    
                        <ul class="iconic_list"><li>I accept the <a href="Terms.php" style="padding:0px; font-weight:normal;">Terms and Conditions</a>.</li></ul>                    
                    
                    <div class="padding40"></div>
                
				<div class="clear"></div>
				<!-- End Sign Up -->
             
		</div>
		
		<div class="col_1_2 last">
			<h3>What can I do with an account?</h3>
			<p>There is so much flexibility with your account! You can either create your own promotions, or you can complete the promotions of others.</p>
      		<ul class="arrow_list">
            	<li><strong>Motivate People to Be Somewhere</strong> <br>
                There are so many reasons why someone would want to motivate others to be somewhere or perhaps simply to keep track of who is there.<br><br> 

                        <ul class="iconic_list">
                            <li>Take attendance of everyone at a particular location. </li>
                            <li>Show people where something is happening, like a party. (use it as a map to your event)</li>
                            <li>Offer something to those in a particular place and at a particular time. (just like Happy Hour)</li>
                            <li>Request information from people in a specific area. (like taking a poll)</li>
                            <li>Encourage people with similar interests to come together somewhere at a particular time.</li>
                            <li>Keep track of who visits a place multiple times. (the basis of a loyalty program)</li>
                        </ul>
                        
                </li>
                <li><strong>Get Rewarded for Being There</strong> <br>
                Our platform also enables you to engage in promotions with a variety of rewards.<br><br>
                
                        <ul class="iconic_list">
                            <li>A coupon that offers a discount upon a return trip.</li>
                            <li>Custom badges or different status levels after they complete certain actions.</li>
                            <li>Special VIP information, such as a download link to a document, info about something in the area, a special message, a tip about a local vendor.</li>
                        </ul>   
                                        
                </li>
                <li><strong>Create a Note on your Map</strong> <br>
                If you want to keep it simple, you can simply just create a marker at a specific location. Here are a few things you could do with them: <br><br>
                
                         <ul class="iconic_list">
                            <li>Create a map of all your favorite restaurants</li>
                            <li>Let your friends know exactly where you are at a particular time.</li>
                            <li>Keep a record of where you saw or did something special.</li>
                        </ul>                
                
                </li>
            </ul>
			<h3>What will it cost me?</h3>
			<p>
				<strong>Absolutely nothing!</strong> We are supported by vendors who pay us to place their promotions in the same maps that your free promotions appear.
			</p>                        
		</div>
        		
		<div class="clear padding20"></div>
	
		
		<div class="clear"></div>
	
	</section>
    <!-- END CONTENT -->


	<!-- footer -->
    <?php Require 'Inc/Inc_Footer.php'; ?>
	<!-- /footer -->
    </div>
    <!--wrapper end-->

</body>
</html>
