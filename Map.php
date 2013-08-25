<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Promotions.php';
	
	$MapOnPage = "Yes";
	$ErrorMessage_Search = "";
	$Hide_Search = "Hide";  


	if (isset($_GET['Show'])) { $Show = $_GET['Show']; } else { $Show = "";}

	if (isset($_GET['Show'])) {
		  if ($_GET['Show'] == "All") { $AllClass = "current"; }
		  if ($_GET['Show'] == "Promotions") { $PromotionsClass = "current"; }			
		  if ($_GET['Show'] == "Markers") { $MarkersClass = "current"; }
	} else { $AllClass = "current"; }

	$ActivityFeed_Options = "
				<div id='Filter' class='SmallFont' style='padding:0 0 30px 9px'>
						 <span class='AlignLeft'><strong>Show</strong>: &nbsp;  
						 	<a href='$ThisPage?Show=All' class='$AllClass'>All</a> &nbsp; &nbsp; | &nbsp;  &nbsp;
							<a href='$ThisPage?Show=Promotions' class='$PromotionsClass'>Promotions</a>  &nbsp; &nbsp; | &nbsp; &nbsp; 
							<a href='$ThisPage?Show=Markers' class='$MarkersClass'>Markers</a>
						  </span>
				</div>";	


//#########################################################################################
//### Search Address Functionality
//#########################################################################################	
if (isset($_POST["SearchQuery"])) { 
	
	$SearchQuery = $_POST['SearchQuery'];
	$SearchType = $_POST["SearchType"];
	
	$ResultsTitle = "Search Results for '{$SearchQuery}'";
	$ActivityFeed = ActivityFeed($Show, $SearchType, $SearchQuery);
//	$LatLong = GetPlaces($Show, $SearchType, $SearchQuery);
//var_dump($ActivityFeed);

	if ($SearchType == "Address") { $AddressChecked = "checked"; }
	if ($SearchType == "Layer") { $LayerChecked = "checked"; }
	if ($SearchType == "Person") { $PersonChecked = "checked"; }
	
} else { 
	 $AddressChecked = "checked";
	$ResultsTitle = "Recent Activity Feed"; 
	$ActivityFeed = ActivityFeed($Show, "Default","");
	
}
	
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Map</title>
<?php Require 'Inc/Inc_HeaderTag.php'; ?>

	<script language="javascript">

 		$(document).ready(function(){				   

 			if($("#hidden_add").val() == 'Not')
 			{
 				$("#Search_ErrorMessage").removeClass("ErrorMessageHide").addClass("ErrorMessage");  
 				$("#Search_ErrorMessage").html("Address Not found");  
 				$(".jp-next").css('display','none');
 			}
 			 
			// "Profile" form validation 
			$("#Search_Submit").click(function(event) {
										
					  var Search_ErrorMessage = $("#Search_ErrorMessage"); 
					  var SearchQuery = $("#SearchQuery"); 				

					 
					  
					 if(SearchQuery.val().length < 3){ 
							Search_ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessage");  
							Search_ErrorMessage.html("Search query must be at least 3 characters");  
							return false; 
					} 
					
					$('#Search_FormID').submit();					

 			});
		});

 
		function setHeight() {
			var MapHeight = window.innerHeight - 100;
			
			document.getElementById('mymap').style.height = MapHeight + 'px';
		}
		
	</script>  

<body id="Home" onLoad="create_map(); setHeight();">

	<?php Require 'Inc/Inc_Header.php'; ?>

    <!-- START CONTENT -->
    <section class="container clearfix">
		
       		<div class="content clearfix">
 		<span  style="  float: left;     left: 227px;     position: absolute;">
  
      <a class="maptocenter" onclick="mapstraction.setCenterAndZoom(Home,15);">
      <img src="gfx/nearby.gif" /></a>   <a class="googlecontrol" href="#Search">
        <img src="gfx/search.gif" />
      </a>
      </span>  
      
               <div id="mymap" style="width:100%; background-color:#eee; z-index:1;"></div>  
			   <div class="clear"></div>
               
			</div>
 		
		<!-- START SIDEBAR -->
		<div class="sidebar">

			<div class="clear padding20"></div>
        
        
                 <div align="center">
                 <a name="Search"></a>
                 <form method="post" name="Search_FormName" id="Search_FormID" action="Map.php" style="padding:0 0 10px;">
                    <input type="text" name="SearchQuery" value="<?php echo $SearchQuery; ?>" id="SearchQuery" placeholder="Select search type below" />
                    <div align="center">
                    				<input type="radio" name="SearchType" value="Address" style="width:12px;" <?php echo $AddressChecked; ?>>Address &nbsp; 
                    				<input type="radio" name="SearchType" value="Layer" style="width:12px;" <?php echo $LayerChecked; ?>>Layer &nbsp;  
                                    <input type="radio" name="SearchType" value="Person" style="width:12px;" <?php echo $PersonChecked; ?>>Person 
                    </div>
                </form> 
                   
                   <div class="clear padding10"></div>
                            	  
				   <div class='ErrorMessage<?php echo $Hide_Search; ?>'><?php echo $ErrorMessage_Search; ?></div>
                   <div class='ErrorMessageHide' id='Search_ErrorMessage'></div>
                   <div align="center"><a href="Javascript:void(0);" class="button orange" id="Search_Submit">Submit</a> </div>                
                
                </div>
                
                <div class="clear padding40"></div>
                
			<h4 class="bottom_line regular"><?php echo $ResultsTitle; ?></h4>
	
		 	<div id="dataholder">


					<?php 
					if(strpos($ActivityFeed,'Address') !==false)
					{
					$preg=preg_match_all('!\d+!', $ActivityFeed, $matches);
					if($preg==0){
echo '<input type="hidden" id="hidden_add" value="Not">';
						echo "No Results Found ";
						}
					else{

$val=explode('*/!', $ActivityFeed);
echo $val[0];						
// 						/echo rtrim("*/!",$ActivityFeed);
						}
						
					
					}
					if(strpos($ActivityFeed,'Address') === false)
					{
						echo $ActivityFeed;
					}
					 ?>

            
			</div>
	
          	 <div class="holder" ></div>
      
			<div class="clear padding40"></div>
						
			<!-- START SOCIALS -->
			<h4 class="bottom_line regular">Sharing is caring</h4>
			<div class="portfolio_social">
            
				<ul style="padding:0 0 15px 15px;">
					<?php 
						$Title = urlencode('Interact with me using this map!');
						$Description = urlencode('I am using Layr.es to describe location-based information with my friends.');
						$TwitterDescription = urlencode('Interact with me using this map of interesting places my friends and I have created.');
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
				
				<div class="clear"></div>

		</div>
		<div class="clear"></div>
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