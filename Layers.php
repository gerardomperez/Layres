<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Profile.php';
	require_once 'Inc/Inc_Functions_Promotions.php';
	
	if (!isset($_GET['ID'])) { header("Location: Home.php"); exit();}
	$MapOnPage = "Yes";

	$ErrorMessage_LayersIFollow = "";
	$Hide_LayersIFollow = "Hide";


//#########################################################################################
//### Layer Data
//#########################################################################################

	$sql_LocationData = "SELECT * FROM Layers, Users WHERE Layers.UName = Users.UName AND LayerID='{$_GET['ID']}'";
	$Result_LocationData = Select($sql_LocationData);
	
	if (!$Result_LocationData) { header("Location: Home.php"); exit();}
	
	$LayerID = $_GET['ID'];
	$LayerName = $Result_LocationData[0]["LayerName"];
	$Creator = $Result_LocationData[0]["FullName"];
	$CreatorUname = $Result_LocationData[0]["UName"];
	$Description = $Result_LocationData[0]["Description"];

	$Followers = GetFollowers($LayerID);

	/////////////////////////
	// If person selects "Follow Layer" button
	////////////////////////
	if (isset($_POST['FollowLayer'])) {
				
		if (!isset($_SESSION['UName'])) { 
				$ErrorMessage_LayersIFollow = "Sorry, you must login first."; 
				$Hide_LayersIFollow = "";			
		} else {		

			if (!empty($_POST['FollowLayer'])) { $LinkageStatus = LinkLayers($_SESSION['UName'],$_POST['FollowLayer']); }
	
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
			$Followers = GetFollowers($LayerID);
		}

	  }

	//--------------
	//  Remove / Block Layer
	//--------------
		if (isset($_POST['UnLink'])) {	

			$ErrorMessage_LayersIFollow = RemoveFollowing($_SESSION['UName'],$_POST['UnLink']);
			$Followers = GetFollowers($LayerID);
			$Hide_LayersIFollow = "Success";

			}


	/////////////////////////
	// Button status
	////////////////////////
	if (!isset($_SESSION['UName'])) { $LayerLinkButton = "<a href='Registration.php' class='button white'>Login to follow this layer</a>"; }
	else {
			$LinkageStatus = ExistingLayerLink($_SESSION['UName'],$LayerID);  
			
			if ($LinkageStatus == "Block") { 
						$LayerLinkButton = "<a href='#FollowButton' class='button white'>Currently Blocked</a>"; 
						$WhatToDo = "";
			} elseif ($LinkageStatus == "ok") { 
						$LayerLinkButton = "<a href='#FollowButton' class='button navy' id='FollowButton_Submit'>Un-link</a>"; 
						$WhatToDo = "UnLink";
			} elseif ($LinkageStatus == "NotYourself") { 
						$LayerLinkButton = ""; //<a href='#FollowButton' class='button navy' id='FollowButton_Submit'>Edit Your Layer</a>"; 
						$WhatToDo = "EditLayer"; 
			} elseif ($LinkageStatus == "NoLayer") { 
						$LayerLinkButton = "<a href='Map.php' class='button white'>No Layer with this Name</a>";	
						$WhatToDo = "";
			} else {
						$LayerLinkButton = "<a href='#FollowButton' class='button green' id='FollowButton_Submit'>Link to this Layer</a>";	
						$WhatToDo = "FollowLayer";				
			}

		}


//#########################################################################################
//### Location Data
//#########################################################################################

	$sql_LocationData = "SELECT * FROM Location, Location_Promotions WHERE Location.LocationID = Location_Promotions.LocationID AND Location_Promotions.LayerID='{$LayerID}' ORDER BY LocationName";
	$Result_LocationData = Select($sql_LocationData);
	
			if (count($Result_LocationData)) {

				  global $varLocationPoint, $varLocation;
				  
				  $varLocationPoint = "var Location0";

				  foreach ($Result_LocationData as $results) {	

					$varLocationPoint .= ", Location{$results['LocationID']}";
					$varLocation .= "Location{$results['LocationID']} = new mxn.LatLonPoint({$results['LocationLatitude']},{$results['LocationLongitude']});";

					if ($results['Description']) {	
							$DescriptionContent = 	"<div onClick=\"expandcontent('Location{$results['LocationID']}')\" class='Black'>Description &raquo;</div>
														<div id='Location{$results['LocationID']}' class='switchcontent' style='margin:5px;'>
														<div>{$results['Description']}</div>
													</div>	";  }
					else { $DescriptionContent = ""; }							
	
				    $LocationInfo .= "	<div style='padding:2px;'>		
										  <h4 style='margin:3px 0 0 0;'>{$results['LocationName']}</h4>";

					if ($results['Zip']) {
						$LocationInfo .= "	<p class='SmallFont'><a href='#map' onclick=\"mapstraction.setCenter(Location{$results['LocationID']}, {pan:true}); mapstraction.setZoom(12)\" style='padding:8px 3px;' class='BabyBlue'>{$results['StreetAddress']} &nbsp; {$results['Zip']}</a></p>";
					} else $LocationInfo .= "	<p class='SmallFont'><a href='#map' onclick=\"mapstraction.setCenter(Location{$results['LocationID']}, {pan:true}); mapstraction.setZoom(12)\" style='padding:8px 3px;' class='BabyBlue'>(Created at location)</a></p>";
				
					$LocationInfo .= "	</div>";	   				  

					  }   // End For each record
					  
			  		$varLocationPoint .= ";";	
			 
		   } else { $LocationInfo = "<div align='center' style='padding:30px;'>No locations added to this layer yet.</div>"; }

//#########################################################################################
//### Promotion Data
//#########################################################################################

	$PromoList = ShowLayerPromos($LayerID); 	
	

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title><?php echo $LayerName ?></title>
<?php Require 'Inc/Inc_HeaderTag.php'; ?>
<script language="javascript">
	
 		$(document).ready(function(){				   

				$("#FollowButton_Submit").click(function(event) {	
					$('#FollowButton_FormID').submit();
 				});
				
		});	
		
</script>

<script type="text/javascript" src="js/TotalJS.js"></script>
<body id="Home" onLoad="create_map();">

<?php Require 'Inc/Inc_Header.php'; ?>
	

	


    <!-- START CONTENT -->
    <section class="container clearfix">
		<!-- Page Title -->
			<header class="container page_info clearfix">
 
					<h1 class="regular Red bottom_line"><?php echo $LayerName; ?> </h1>
					<div class='LayerDescription'><?php echo $Description; ?></div>
				<div class="clear padding20"></div>
			</header>
			
		<!-- /Page Title -->
	

		<div class="content clearfix">
        	<a name="Map"></a>
			<div id="mymap" style="width:100%; height:400px; background-color:#eee;""></div>
            
             <div class='padding20'></div><h4 class='bottom_line regular'>Markers</h4>
                
            	<?php echo $PromoList; ?>
            
             <div class='clear padding10'></div> 
            
		</div>

		
		<!-- START SIDEBAR -->
		<div class="sidebar">
        
			<h4 class="bottom_line regular">Locations</h4>
			<div>

					<?php echo $LocationInfo; ?>          
            
			</div>
            
			<div class="clear padding10"></div>
			

			<h4 class="bottom_line regular">Creator</h4>
			<div style="padding:0 0 15px 15px;"><?php echo $Creator;?> <span class='AlignRight'><span class='Red strong'><?php echo $Followers['TotalCount']; ?></span> Followers</span></div>
			
			<div class="clear padding10"></div>
			<div class='ErrorMessage<?php echo $Hide_LayersIFollow; ?>' id='LayersIFollow_ErrorMessage'><?php echo $ErrorMessage_LayersIFollow; ?></div>            
            
                <form method='post' name='FollowButton_FormName' id='FollowButton_FormID' action='Layers.php?ID=<?php echo $_GET['ID']; ?>#Button'>
                    <input type='hidden' name='<?php echo $WhatToDo; ?>' value='<?php echo $LayerID; ?>'>
                    <div align="center"><?php echo $LayerLinkButton; ?></div>
                </form>            

			<div class="clear padding10"></div>
            
			
			<!-- START SOCIALS -->
			<h4 class="bottom_line regular">Sharing is caring</h4>
			<div class="portfolio_social">
            
				<ul style="padding:0 0 15px 15px;">
					<?php 
						$Title = urlencode($LayerName);
						$Description = urlencode('Interact with me using this map!  I am using Layr.es to describe location-based information with my friends.');
						$TwitterDescription = $Title . urlencode(' - Interact with me using this map of interesting places.');
						$Logo = urlencode('http://www.layr.es/images/logo_background.png');
					
						if (isset($_GET['ID'])) { $PageURL = urlencode("http://www.layr.es/Layers.php?ID={$_GET['ID']}"); } 
							else { $PageURL = urlencode('http://www.layr.es/Map.php'); } 
							
						$FacebookURL = "http://www.facebook.com/sharer.php?s=100&p[title]={$Title}&p[summary]={$Description}&p[url]={$PageURL}&p[images][0]={$Logo}";
						$TwitterURL = "http://twitter.com/share?url={$PageURL}&text={$TwitterDescription}";
						$LinkedInURL = "http://www.linkedin.com/shareArticle?mini=true&url={$PageURL}&title={$TwitterDescription}";
						
					?>                
                
					<li><a href="<?php echo $FacebookURL ?>" target="_blank"><img src="images/facebook_32.png" width="32" height="32" alt="Facebook"></a></li>
					<li><a href="<?php echo $TwitterURL ?>" target="_blank"><img src="images/twitter_32.png" width="32" height="32" alt="Twitter"></a></li>
                    <li><a href="<?php echo $LinkedInURL ?>" target="_blank"><img src="images/linkedin_32.png" width="32" height="32" alt="Linkedin"></a></li>
				</ul>
			</div>
			<!-- END SOCIALS -->

			<?php if ($LinkageStatus == "NotYourself") { echo $Followers['List']; } ?>
				
				<div class="clear"></div>

		</div>
		<div class="clear padding40"></div>
		<!-- END SIDEBAR -->
		
	</section>
    <!-- END CONTENT -->
 
 
 	<!-- footer -->
    <?php Require 'Inc/Inc_Footer.php'; ?>
	<!-- /footer -->
    </div>
    <!--wrapper end-->

</body>
</html>
