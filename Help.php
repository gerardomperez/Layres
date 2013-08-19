<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';
	
	$Hide_Password = $Hide_Question = "Hide";
	$ErrorMessage_Password = $ErrorMessage_Question = "";

	//--------------
	//  Submit password form
	//--------------		
	if (isset($_POST['Password_FormStatus'])) {

		// Check that Email matches UName
			$sql_VerifyUser = "SELECT * FROM Users WHERE UName='{$_POST['Password_UName']}' AND EmailAddress='{$_POST['Password_EmailAddress']}'";
			$Result_VerifyUser = Select($sql_VerifyUser); 
		
			if ($Result_VerifyUser) {				

				// Code for Email
				$FullName = $Result_VerifyUser[0]['FullName'];
				$Password = $Result_VerifyUser[0]['PWord'];
				$username = $Result_VerifyUser[0]['UName'];
				$HTMLMessage = "The password for your account is: {$Password}.
				
				http://dev.layr.es/Registration.php?P={$Password}&U={$username}";
	
		
				require_once 'Inc/Inc_PostMark.php';
		
				define('POSTMARKAPP_MAIL_FROM_NAME', 'Layr.es Website');
	
				$postmark = new Postmark("e218eaac-d71b-4962-aef6-00a3c081dde2","message@Layr.es","message@Layr.es");
			 	$postmark->to($_POST['Password_EmailAddress'])->subject("Layr.es Password Request")->html_message($HTMLMessage)->send();


				$ErrorMessage_Password = "Password sent to your email address."; $Hide_Password = "Success";
				
			} else {
				$ErrorMessage_Password = "Credentials did not match any account."; $Hide_Password = "";
			}
		
	}
	
	
	//--------------
	//  Submit contact us form
	//--------------		
	if (isset($_POST['Question_FormStatus'])) {

			 OpenDatabase();
						
				//  Put form data into User Details array
				if (isset($_POST['Question_FullName'])) { $Question_FullName = Sanitize_Data($_POST['Question_FullName']); }
				if (isset($_POST['Question_EmailAddress'])) { $Question_EmailAddress = Sanitize_Data($_POST['Question_EmailAddress']); }	
				if (isset($_POST['Question_Message'])) { $Question_Message = Sanitize_Data($_POST['Question_Message']); }			
	
			 CloseDatabase();	

			 // Insert Statement for Creating Users
			 $sql_CreateSupport = "INSERT INTO Support(FullName, EmailAddress, Question) ";
			 $sql_CreateSupport .= "VALUES ('{$Question_FullName}', '{$Question_EmailAddress}', '{$Question_Message}')";		

			 $Result_CreateSupport = Insert($sql_CreateSupport); 		
			
			 if ($Result_CreateSupport) { $ErrorMessage_Question = "Message sent to our support department."; $Hide_Question = "Success"; } 
			  else { $ErrorMessage_Question = "Error with message submission.  Please email HelpDesk@layr.es."; $Hide_Question = ""; }
		
	}	
	
	
	
	
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Help</title>
<?php Require 'Inc/Inc_HeaderTag.php'; ?>
<script type="text/javascript" src="js/TotalJS.js"></script>
	<script language="javascript">
 		$(document).ready(function(){
								   
			   $("#Question_Submit").click(function(event) {
									   
					 var ErrorMessage = $("#Question_ErrorMessage");
					 
					 var FullName = $("#Question_FullName"); 
					 var EmailAddress = $("#Question_EmailAddress"); 
					 var Question = $("#Question_Message"); 					  
					  
					 if(FullName.val().length < 3){ 
							ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							ErrorMessage.html("Please include your name. <br><i>(Must be greater than 3 characters)</i>"); 
							FullName.focus(); 
							return false; 
					}

					 if(EmailAddress.val().length < 4 || !isValidEmailAddress(EmailAddress.val())){ 
							ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							ErrorMessage.text("Please include a valid email address.");  
							EmailAddress.focus();
							return false; 
					}

					 if(Question.val().length < 8){ 
							ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							ErrorMessage.text("Please include a full question."); 
							Question.focus(); 
							return false; 
					}  

					$("#Question_FormID").submit();
				});



			   $("#Password_Submit").click(function(event) {

					   
					 var ErrorMessage = $("#Password_ErrorMessage");
					 
					 var UName = $("#Password_UName"); 
					 var EmailAddress = $("#Password_EmailAddress"); 				  
					  
					 if(UName.val().length < 4){ 
							ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							ErrorMessage.html("Please include your username.");
							UName.focus();  
							return false; 
					}

					 if(EmailAddress.val().length < 4 || !isValidEmailAddress(EmailAddress.val())){ 
							ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							ErrorMessage.text("Please include a valid email address.");  
							EmailAddress.focus();
							return false; 
					}

					$("#Password_FormID").submit();
				});
			   
 		});


    </script>
<body>

<?php Require 'Inc/Inc_Header.php'; ?>


    <!-- START CONTENT -->
    <section class="container clearfix">
		<!-- Page Title -->
			<header class="container page_info clearfix">
				
					<h1 class="regular Red bottom_line">Help</h1>
				
				<div class="clear"></div>
			</header>
			
		<!-- /Page Title -->

		
		<div class="col_1_2">
			<h3 class="red">About Us</h3>
                <div onClick="expandcontent('sc1a')" class="Questions"><strong>Q.</strong> What is Layr.es all about? </div>
                <div id="sc1a" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Layr.es is a service that enables anyone to motivate others to be somewhere...and to reward them when they get there.  Layr.es differs from other services like FourSquare and Gowalla by making it easy for <i>anyone</i> to create a promotion. You don't have to be a business, though businesses use us.  You just have to be interested in either motivating people to be somewhere or in getting credit for being there.</div>
                </div>
        
                <div onClick="expandcontent('sc1b')" class="Questions"><strong>Q.</strong> How do you make money? </div>
                <div id="sc1b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> For the average person, the promotions they create are only visible on their friends' maps.  If more general visibility is desired, by a vendor for example, then they pay Layr.es for more general visibility for to their promotion.</div>
                </div>
        
                <div onClick="expandcontent('sc1c')" class="Questions"><strong>Q.</strong> Do you sell my information? </div>
                <div id="sc1c" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> <strong>We do not sell your information</strong> to anyone.  However, we do provide your name and email address (and nothing else) to the vendors whose promotions you take action on.</div>
                </div>        
        
                <div onClick="expandcontent('sc1d')" class="Questions"><strong>Q.</strong> How long have you been around? </div>
                <div id="sc1d" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Layr.es was first conceived in early 2012.  Our first promotions went live by the end of that year.</div>
                </div>        
        
                <div onClick="expandcontent('sc1e')" class="Questions"><strong>Q.</strong> Do you have an app? </div>
                <div id="sc1e" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Layr.es is accessible through your smartphone via a mobile website.  We have not created an app due to the high costs of producing apps for various platforms.  You can reach the mobile website at http://mobile.layr.es. </div>
                </div> 
                
                <div onClick="expandcontent('sc1f')" class="Questions"><strong>Q.</strong> How do I get my business on Layr.es? </div>
                <div id="sc1f" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> There is no cost for anyone to use Layr.es, including businesses.  All you need to do is sign up and begin using the system.  To 
                get your promotions seen, you can either encourage people to connect to your layer, or you can contact our support department, "Support" at layr.es, regarding how to subscribe 
                for universal exposure.</div>
                </div>  
        
                <div onClick="expandcontent('sc1g')" class="Questions"><strong>Q.</strong> Can I build a promotion anywhere? </div>
                <div id="sc1g" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> A promotion can be created for any location on Earth.</div>
                </div> 
        
        	<div class="clear padding20"></div>
			<h3 class="red">My Account</h3>
                <div onClick="expandcontent('sc5a')" class="Questions"><strong>Q.</strong> How do I delete my account?</div>
                <div id="sc5a" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> To have your account deleted from our system, please email to our support department, "Support" at layr.es, with your username. </div>
                </div>
        
                <div onClick="expandcontent('sc5b')" class="Questions"><strong>Q.</strong> Do you sell my information to anyone?</div>
                <div id="sc5b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> <strong>We do not sell your information</strong> to anyone.  However, we do provide your name and email address (and nothing else) to the vendors whose promotions you take action on.</div>
                </div>
        
                <div onClick="expandcontent('sc5c')" class="Questions"><strong>Q.</strong> How do I change my password?</div>
                <div id="sc5c" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> You may change your password from the profile section of your account.  Just log in and press the edit button in the profile section.  If you have forgotten your password, please go to the FAQs page and submit your account's email address in the "Forgot Password" section.  Your account email address will then be sent to you via email.</div>
                </div>
                
                <div onClick="expandcontent('sc5d')" class="Questions"><strong>Q.</strong> Can I check in using a friend's phone?</div>
                <div id="sc5d" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Yes.  Just sign in to your account using your credentials and you should be good regardless of whatever device you use. </div>
                </div>
                
                <div onClick="expandcontent('sc5d')" class="Questions"><strong>Q.</strong> How do I change my profile picture?</div>
                <div id="sc5d" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Currently, the only way to get your profile picture to show is by connecting your account to Facebook.  Because of security reasons, we do no
                yet allow users of our system to upload their own profile photos. </div>
                </div> 
        
        	<div class="clear padding20"></div>
			<h3 class="red">Requirements</h3>
                <div onClick="expandcontent('sc2a')" class="Questions"><strong>Q.</strong> What are the technical requirements to participate?</div>
                <div id="sc2a" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Anyone with a computer can use our system to create promotions.  Anyone with a GPS-enabled device (such as most smartphones, tablets, 
                and laptops) can check-in to a promotion. </div>
                </div>
        
                <div onClick="expandcontent('sc2b')" class="Questions"><strong>Q.</strong> How can I share this with a friend? </div>
                <div id="sc2b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Soon, you will be able to log into your account and use the "Invite a Friend" button to send invitations to any of your facebook 
                friends. Until then, please just tell your friend about us.  They can connect to your layer by knowning either your account's username or email address.</div>
                </div>
                
                <div onClick="expandcontent('sc2c')" class="Questions"><strong>Q.</strong> Do I have to qualify somehow to participate?</div>
                <div id="sc2c" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> You must be older than 13, but other than that, you can participate in any promotion according to the actions required for that promotion.</div>
                </div>
        
                <div onClick="expandcontent('sc2d')" class="Questions"><strong>Q.</strong> Is a Facebook account required in order to register?</div>
                <div id="sc2d" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> No. A facebook account is not require to participate in promotions.  It is, however, much easier 
                to associate your account with other people if you have a facebook account.</div>
                </div> 
                <div class="clear padding20"></div> 
                              
			<h3 class="red">Playing</h3>
                <div onClick="expandcontent('sc3a')" class="Questions"><strong>Q.</strong> How do I find your promotions?</div>
                <div id="sc3a" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Just go to the homepage of this website or the mobile site, and look in the map.  You should see 
                some icons on the map that represent promotions in your area.  <!--There is also an address bar that you can use to search the location around a 
                specific area. --></div>
                </div>
                 
                <div onClick="expandcontent('sc3b')" class="Questions"><strong>Q.</strong> How do I complete a promotion?</div>
                <div id="sc3b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> You must complete the task described for that particular promootion.  This could be as simple as checking into a location or more involved, such as answering a specific question.  Whatever you need to do to earn a promotion will be descrined when you see the promotion details.  </div>
                </div>
                             
                <div onClick="expandcontent('sc3c')" class="Questions"><strong>Q.</strong> Can I activate a promotion from home?</div>
                <div id="sc3c" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Yes and No.  Some promotions require you to be close enough to the promotion's designated address before you can complete the task.  Other's allow check-in from anywhere.  It varies from promotion to promotion.</div>
                </div>
            
                <div onClick="expandcontent('sc3d')" class="Questions"><strong>Q.</strong> How soon before I can use my reward?</div>
                <div id="sc3d" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Details are provided in the promotion's reward description.  Typically, the reward is available immediately, but on some occasions, it will require you to wait.</div>
                </div>
                
                <div onClick="expandcontent('sc3e')" class="Questions"><strong>Q.</strong> How frequently do promotions change?</div>
                <div id="sc3e" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Once a promotion goes live, it cannot be changed other than immediately expired.  However, other promotions are naturally expiring and being added all the time.</div>
                </div>
                
                <div onClick="expandcontent('sc3f')" class="Questions"><strong>Q.</strong> I don't have a smartphone.  Can I still play? </div>
                <div id="sc3f" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> You will need a mobile device with a location-sensitive browser.  Many tablets and laptops also meet this requirement.  Hypothetically, for example, you could bring your computer to the location to check-in.  </div>
                </div>
                
                <div onClick="expandcontent('sc3g')" class="Questions"><strong>Q.</strong> I lost my password, how do I retreive it?</div>
                <div id="sc3g" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Submit your email address in the "Forgot Password" section of this site's FAQ page.</div>         
                </div>        
        
                <div onClick="expandcontent('sc3h')" class="Questions"><strong>Q.</strong> Why do I see promotions from vendors I'm not following?</div>
                <div id="sc3h" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Layr.es is supported by vendors who pay to have their promotions appear universally on everyone's maps.  The promotions
                you are seeing are from vendors who have paid to have you see them.</div>         
                </div> 
        
        	<div class="clear padding20"></div>
			<h3 class="red">Rewards</h3>
                <div onClick="expandcontent('sc4b')" class="Questions"><strong>Q.</strong> The vendor tells me that they didn't offer the promotion, but their location is clearly where the promo took place. What's up?</div>
                <div id="sc4b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> On the promotion details page, check the username of the account who created the promotion.  It's located at the very bottom.  This is the 
                account who should honor the promotion, not necessarily the vendor at the location where the promotion is located.  Remember, since promotions can be created anywhere, a promotion that is 
                located somewhere may not necessarily be associated with that vendor.  There will be a "verified" icon next to vendors that have paid us to verify their authenticity.  </div>             
                </div>
                
                <div onClick="expandcontent('sc4b')" class="Questions"><strong>Q.</strong> How long does my reward last?</div>
                <div id="sc4b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> Each reward has its own expiration date.  Check your "My Account" section to see the expiration dates 
                of your promotions.  </div>             
                </div>
               
                <div onClick="expandcontent('sc4c')" class="Questions"><strong>Q.</strong> Do I need to provide my credit card information?</div>
                <div id="sc4c" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong>You will only need to provide payment information if you want to create a promotion that is 
                shown on everyone's map.  Promotions that are only available to your connections are free to create. </div>             
                </div>   
        
        	<div class="clear padding20"></div>
			<h3 class="red">Sponsors</h3>
                <div onClick="expandcontent('sc6b')" class="Questions"><strong>Q.</strong> Can I block anyone from a specific promo?</div>
                <div id="sc6b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> We do not have this functionality yet.  Once you create a promotion, it's available on the maps of everyone you're connected
                to.  </div>             
               </div>
        
                <div onClick="expandcontent('sc6b')" class="Questions"><strong>Q.</strong> How do I edit a promotion?</div>
                <div id="sc6b" class="switchcontent" style="margin:5px 25px 5px 15px;">
                <div class="Answers"><strong>A.</strong> There are only a few characteristics of a promotion that can be editted once the promotion is live.  To do so, click on the "Edit" link for that promotion in the "Sponsored Promotions" section of the My Account page.  </div>             
               </div>

			<div class="padding40"></div>

		</div>

		<div class="col_1_2 last">
				<!-- START LEAVE A COMMENT -->
                <a name="Question" class="AnchorTag">&nbsp;</a>                
				<h3>Ask a Question</h3>
                <p>Ask any question or make any comments in the form below.</p>
				<div class="clear"></div>
				
				<form name="Question_FormName" id="Question_FormID" method="post" action="#Question" class="comments_form"  style="padding:10px 0;">
                <input type='hidden' name="Question_FormStatus" value="submitted" />
					<div class="comment_wrap">
						<label for="name">Name <span>*</span></label>
						<input type="text" name="Question_FullName" id="Question_FullName" value="" />
					</div>

					<div class="comment_wrap">
						<label for="email">Email <span>*</span></label>
						<input type="text" name="Question_EmailAddress" id="Question_EmailAddress" value="" />
					</div>

					<div class="textarea_wrap">
						<label for="message">Your Question<span>*</span></label>
						<textarea name="Question_Message" id="Question_Message" cols="30" rows="3"></textarea>
					</div>
				</form>				

					<div class="clear"></div>
                    <div class='ErrorMessage<?php echo $Hide_Question; ?>' id='Question_ErrorMessage'><?php echo $ErrorMessage_Question; ?></div>
                    <div align="center"><a href="#Question" class="button orange" id="Question_Submit">Submit</a> </div>   


				<div class="clear"></div>
				<!-- END LEAVE A COMMENT --> 
                
				<div class="padding20"></div>
        		<div class="bottom_line"></div>
                <div class="padding20"></div>
                

				<!-- START Forgot Password -->                                              
                <a name="Password" class="AnchorTag">&nbsp;</a>
				<h3>Forgot Password?</h3>
                <p>Enter your username and password below to have a password reset link sent to you.</p>                
 				<div class="clear"></div>
				
				<form method="post" name="Password_FormName" id="Password_FormID" action="#Password" class="comments_form"  style="padding:10px 0;">
                <input type='hidden' name="Password_FormStatus" value="submitted" />
					<div class="comment_wrap">
						<label for="name">User Name <span>*</span></label>
						<input type="text" name="Password_UName" id="Password_UName" value="" />
					</div>

					<div class="comment_wrap">
						<label for="email">Email <span>*</span></label>
						<input type="text" name="Password_EmailAddress" id="Password_EmailAddress" value="" />
					</div>
				</form>				

					<div class="clear"></div>
                    <div class='ErrorMessage<?php echo $Hide_Password; ?>' id='Password_ErrorMessage'><?php echo $ErrorMessage_Password; ?></div>
                    <div align="center"><a href="#Password" class="button orange" id="Password_Submit">Submit</a> </div>   


				<div class="clear"></div>
				<!-- END Forgot Password -->                                              
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