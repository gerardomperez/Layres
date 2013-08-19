<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';

	session_start();
	
   if (isset($_GET['f'])) { $FB_Action = $_GET['f']; } else {$FB_Action = "No action set.";} 

// changed the application id & urls 

   $app_id = "628345723858444";
   $app_secret = "a9f0d6a7b4a631ec5d0b51a9ce566aee";
   $FBpermissions = "email,user_birthday";
   $MyUrl = "http://dev.layr.es/Facebook.php?f={$FB_Action}";
   $ReferringUrl = $_SERVER['HTTP_REFERER'];

   if (isset($_REQUEST["code"])) { $code = $_COOKIE['test2'] = $_REQUEST["code"];  } 


	// ----------------------------------------------------------------------------
   // -------------------------- first trip to this page --------------------------
   // -----------------------------------------------------------------------------
   if(empty($code)) {
	   
	   $_SESSION['state'] = $_COOKIE['test1'] = md5(uniqid(rand(), TRUE)); //CSRF protection
	   $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&scope=" . $FBpermissions . "&redirect_uri=" . urlencode($MyUrl) . "&state=" . $_SESSION['state'];

	   echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

	// ----------------------------------------------------------------------------
   // -------------------------- second trip to this page --------------------------
   // -----------------------------------------------------------------------------

	if($_REQUEST['state'] != $_SESSION['state']) {  
echo("The state does not match. You may be a victim of CSRF."); // <br> Request: {$_REQUEST['state']}<br> Test Cookie1: {$_COOKIE['test1']} <br> Test Cookie2: {$_COOKIE['test2']}");  
	} else {  


	    //get token based on returned code
	   $token_url = "https://graph.facebook.com/oauth/access_token?" . "client_id=" . $app_id . "&redirect_uri=" . urlencode($MyUrl) . "&client_secret=" . $app_secret . "&code=" . $code;
	   $response = file_get_contents($token_url);
  
  	   //get user data
	   $params = null;
	   parse_str($response, $params);
	   $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
		
	   $user = json_decode(file_get_contents($graph_url));



		// store data in variables
		$FBUName = $user->username; 
		$PWord = "";
		$AccessToken = $params['access_token']; 
		$EmailAddress = $user->email; 
		$FullName = $user->name; 
		$LocationCoordinates = ConvertAddress($user->location->name);
		
		$FB_UserID = $user->id; 
		$Gender = $user->gender; 
		$BDay = $user->birthday; 
		$FBZip = "";
		
		
		
		// Package all user info into array
		$UserDetailsArray = array('UName' => $FBUName, 'PWord' => $PWord, 'FullName' => $FullName, 'EmailAddress' => $EmailAddress, 
								  'LocationLatitude' => $LocationCoordinates['LocationLatitude'], 'LocationLongitude' => $LocationCoordinates['LocationLongitude'], 
								  'Zip' => $FBZip, 'JoinDate' => $JoinDate, 'LastLogin' => $LastLogin, 'IPAddress' => $IPAddress,
								  'FB_UserID' => $FB_UserID, 'Gender' => $Gender, 'BDay' => $BDay, 'AccessToken' => $AccessToken);

/*
		 //set cookie values in case we return to Registration.php due to failed submission
		   $_SESSION['FB_UserID'] = $UserDetailsArray['FB_UserID'];
		   $_SESSION['UName'] = $UserDetailsArray['UName'];
		   $_SESSION['PWord'] = $UserDetailsArray['PWord'];
		   $_SESSION['FullName'] = $UserDetailsArray['FullName'];
		   $_SESSION['Location'] = $user->location->name;
		   $_SESSION['EmailAddress'] = $UserDetailsArray['EmailAddress'];
 		   $_SESSION['Gender'] = $UserDetailsArray['Gender'];
		   $_SESSION['BDay'] = $UserDetailsArray['BDay'];
		   $_SESSION['AccessToken'] = $UserDetailsArray['AccessToken'];
*/


		//---------------------------------------------------
		//-----------------  Take Actions  ------------------
		//---------------------------------------------------

		  if ($FB_Action == "CreateAccount") {

				  echo "Redirecting....";
			  
				// if they are asking for an original username / email and have no matching FacebookID, create an account for them	
				if (FB_IDMatched($FB_UserID)) {  Redirect('Registration.php?FB-error=NotOriginalFBID');  }
				 else {	
				   if (OriginalUsername($UName)) {
					  if (UniqueEmail($EmailAddress)) {
					 
							if (DB_CreateAccount($UserDetailsArray)) { Redirect('MyAccount.php?CreateAccount=Success'); } 
							else { Redirect('Registration.php?FB-error=FailedAccountCreation'); }
					
					  } else { Redirect('Registration.php?FB-error=NotUniqueUN');	}
					
				   } else {	Redirect('Registration.php?FB-error=NotOriginalEmail');  }		
				 } 		

		} elseif ($FB_Action == "Login") {
			
			//echo $FB_UserID;


			// $Result_VerifyUser = DB_CreateAccount($FB_UserID);
			
			$Result_VerifyUser = FB_IDMatched($FB_UserID);

			if ($Result_VerifyUser) { 

				 // Update number of Visits
				 $Visits = $Result_VerifyUser[0]['Visits'] + 1;
				 $sql_UpdateVisits = "UPDATE Users SET Visits={$Visits}, LastLogin=Now() WHERE FB_UserID='{$FB_UserID}'";
				 $Result_UpdateVisits = Update($sql_UpdateVisits);

				//  Set User Session					 
				 $_SESSION['FB_UserID'] = $FB_UserID;
				 $_SESSION['UName'] = $Result_VerifyUser[0]['UName']; 
				 $_SESSION['PWord'] = $Result_VerifyUser[0]['PWord']; 
				 $_SESSION['FullName'] = $Result_VerifyUser[0]['FullName']; 
 				 $_SESSION['EmailAddress'] = $Result_VerifyUser[0]['EmailAddress'];
				 $_SESSION['LocationLatitude'] = $Result_VerifyUser[0]['LocationLatitude'];
				 $_SESSION['LocationLongitude'] = $Result_VerifyUser[0]['LocationLongitude'];
				 $_SESSION['Zip'] = $Result_VerifyUser[0]['Zip'];
				 $_SESSION['LoginType'] = 'site';
				 $_SESSION['Visits'] = $Visits;

				 Redirect('MyAccount.php?Login=Success');
				
			} else {	// if not found, figure out whether there is no account...or just not linked.

				  if (FB_EmailMatched($EmailAddress)) { Redirect('Registration.php?FB-error=AccountNotLinked'); }
				  else { Redirect('Registration.php?FB-error=NoAccount'); }

			}
			

		} elseif ($FB_Action == "LinkAccount") {
		
			echo "Redirecting..";
		
			// if email matches, then update DB with FB_UserID, gender, BDay, picture 

			// commented here  Redirect('MyAccount.php?LinkedAccount=Successful');


			if (FB_EmailMatched($EmailAddress)) { 

				if (FB_LinkAccount($FB_UserID,$EmailAddress,$Gender,$BDay,$AccessToken)) { Redirect('MyAccount.php?LinkedAccount=Successful'); }
				else { Redirect('MyAccount.php?FB-error=LinkFailed'); }
			} else {


 Redirect('MyAccount.php?FB-error=NoEmailMatched'); } 

		
		} elseif ($FB_Action == "debug") {
			
		  // -------- print out data to test
		  echo("<br><br>FB Action: {$FB_Action}<br>");
		  //echo("Database Array: <br>");	print_r($UserDetailsArray);
			
		  echo("<br><strong>Direct Via FB Object:</strong>");
		  echo("<br>Access Token: " . $params['access_token']);
		  echo("<br>ID: " . $user->id);
		  echo("<br>Username: " . $user->username);
		  echo("<br>Email: " . $user->email);
		  echo("<br>Full Name: " . $user->name);		
		  echo("<br>Hometown: " . $user->location->name);
		  echo("<br>Gender: " . $user->gender);
		  echo("<br>BDay: " . $user->birthday);
		  echo("<br>First Name: " . $user->first_name);
		  echo("<br>Last Name: " . $user->last_name);
		  
		  echo("<br><br><strong>Via Session Variables:</strong>");
		  echo("<br>Access Token: " . $_SESSION['AccessToken']);
		  echo("<br>ID: " . $_SESSION['FB_UserID']);
		  echo("<br>Username: " . $_SESSION['UName']);
		  echo("<br>Email: " . $_SESSION['EmailAddress']);
		  echo("<br>Full Name: " . $_SESSION['FullName']);		
		  echo("<br>Hometown: " . $_SESSION['Location']);
		  echo("<br>BDay: " . $_SESSION['BDay']);

		  $FriendsList = "<a href='https://graph.facebook.com/" . $user->id . "/friends?access_token=" . $params['access_token'] . "' target='_blank'>Friends</a>";
		  echo("<br><br>Friends located at: " . $FriendsList);
		  $L_Picture = "<img src='https://graph.facebook.com/" . $user->id . "/picture?type=normal'>";
		  $S_Picture = "<img src='https://graph.facebook.com/" . $user->id . "/picture?type=small'>";		
  
		  echo("<br><br>".$L_Picture); 
		  
		}
	}
 ?>
