<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="robots" content="index,follow" />
<link rel="shortcut icon" href="images/favicon.ico" /> 
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" />
<link rel="stylesheet" href="css/style.css" type="text/css" />

<!--[if (gte IE 6)&(lte IE 8)]>
<script type="text/javascript" src="js/html5.js"></script>
<script type="text/javascript" src="js/selectivizr-min.js"></script>
<link rel="stylesheet" href="css/ie_7.css" type="text/css" />
<![endif]-->

<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>

  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="js/all-in-one-min.js"></script>
<script type="text/javascript" src="js/setup.js"></script>

  <script type="text/javascript" src="js/highlight.pack.js"></script>
  <script type="text/javascript" src="js/tabifier.js"></script>
  <script src="js/js.js"></script>
  <script src="js/jPages.js"></script>
  
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/TotalJS.js"></script>
<link rel="shortcut icon" href="gfx/favicon.ico" />
  <link rel="stylesheet" href="css/jPages.css">
 <?php  
 
	Require_once 'Inc/Inc_Common.php';
	
	// Last chance....if no set LatLong coordinates, then assign them a default value for Miami, FL.
if(isset($ActivityFeed))
	if(strpos($ActivityFeed,"Address") !== false )
	{
	//	echo "SDF";
		
  $LatLong=str_replace('*/!','',strstr($ActivityFeed,'*/!'));
 
	}
	
// var_dump($LatLong);

// /	$LatLong="";
else{
	if (!$LatLong) {
		
		if (isset($_SESSION['LocationLatitude'])) {
			$LatLong = "{$_SESSION['LocationLatitude']}, {$_SESSION['LocationLongitude']}";
		 }
		else { 
			$LatLong = "25.7889689, -80.2264393";
		 }  
		}

}
	// If on MyAccount.php, then restrict map markers to just their own
	$Restrict = "?";

	
	if (isset($_GET['ID'])) { 
		$Restrict .= "LayerID={$_GET['ID']}&";

		$LatLong=get_latlon();

	}	
	elseif (isset($_SESSION['UName'])) { $Restrict .= "UName={$_SESSION['UName']}&"; }
	
 	// Show some additional JavaScript for Maps here, if necessary
	if (isset($MapOnPage)) {
			echo "		<script>
								
						   navigator.geolocation.getCurrentPosition(AssignValues, function(){});
				
						   function AssignValues(pos) {
								var LatitudeCookie = getCookie('LatitudeCookie');
								var LongitudeCookie = getCookie('LongitudeCookie');
				
								// store value in a cookie for use elsewhere
								if (LatitudeCookie!=null && LatitudeCookie!='0')    {    
				
										//alert('Welcome back: ' + LatitudeCookie + ', ' + LongitudeCookie);   
								} else { 
										var LocationLatitude = pos.coords.latitude;
										var LocationLongitude = pos.coords.longitude;
				
										setCookie('LatitudeCookie',LocationLatitude);
										setCookie('LongitudeCookie',LocationLongitude);
						
										//alert('New values: ' + LocationLatitude + ', ' + LocationLongitude);			   		   
								}
						   }
						   
						</script>";


		// Add JS for calendar if on Create Promo section of MyAccount page
		if ($ThisPage == "MyAccount.php" and $_SESSION['Mode'] == "Create") { 
	
			echo "<script type='text/javascript' src='js/mootools.js'></script>
				  <script type='text/javascript' src='js/calendar.js'></script>    
				  <script type='text/javascript'>
					  window.addEvent('domready', function() { myCal = new Calendar({ date1: 'M d, Y', date2: 'M d, Y' }); });
				  </script>	";
				  
			echo "<link href='css/calendar.css' rel='stylesheet' type='text/css' />
				  <link href='css/lightbox.css' rel='stylesheet' type='text/css' />";

		// Otherwise show all map content		
		} else { 
	
	
			// for some reason, JSON of map is not compatible with mootools.js framework of calendar
			echo "<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>
				  <script src='js/maps/mxn.js?(googlev3)' type='text/javascript'></script>
				  <script src='js/maps/mxn.google.geocoder.js' type='text/javascript'></script>
				  <script type='text/javascript'>
					var mapstraction; 
					{$varLocation}
					
					function create_map() {
						$.getJSON('MapData.php{$Restrict}', function(jobj) {
							mapstraction = new mxn.Mapstraction('mymap','googlev3');
							      mapstraction.addControls({zoom:'small'});
							
							{$varLocationPoint}
				
							for (var i=0; i < jobj.length; i++) {						
								
								var promo = jobj[i];			
															
								var mk = new mxn.Marker(new mxn.LatLonPoint(promo.LocationLatitude, promo.LocationLongitude));			
								
								mk.setLabel(promo.LocationName);
								mk.setIconAnchor([12,12]);

								if (promo.TaskType == 'Marker') { 
									mk.setInfoBubble('<div class=\'bubble\'><div class=\'bubbleHeader\'>' + promo.PromoTitle + '</div><div class=\'bubbleContent\'><span class=\'MarkerAuthor\'>' +  promo.CreateDate + '</span> - ' + promo.TaskDescription + ' <a href=\'http://google.com/maps?q=' + promo.LocationLatitude + ',' + promo.LocationLongitude + '\' class=\'bubbleURL\' target=\'_blank\'>Directions &raquo;</a></div><div class=\'MarkerAuthor\'>By: ' + promo.UName + '</div>');
								} else {
									mk.setInfoBubble('<div class=\'bubble\'><div class=\'bubbleHeader\'>' + promo.PromoTitle + '</div><div class=\'bubbleContent\'>' + promo.RewardDescription + ' &nbsp; &nbsp; <span style=\"white-space: nowrap;\"><a href=\'PromoDetails.php?PID=' + promo.PromotionID + '\' class=\'bubbleURL\'>Details &raquo;</a></span></div><hr><div><b>' + promo.LocationName + '</b></div><span class=\'AlignLeft bubbleURL\'>' + promo.StreetAddress + '</span><span class=\'AlignRight bubbleURL\'>' + promo.Phone + '<span></div>');
								}

								//alert(promo.PromoDay)
								
				  if (promo.TodaysDay == promo.PromoDay) {
				  				if (promo.TaskType == 'Marker') { mk.setIcon('gfx/icons/YellowFlag.png',[25,25]); }
								if (promo.TaskType == 'CheckIn') {	mk.setIcon('gfx/icons/Check-In-Yellow.png',[25,25]); }
								if (promo.TaskType == 'Appointment') { mk.setIcon('gfx/icons/Appointment-Yellow.png',[25,25]); }
								if (promo.TaskType == 'Question') {	mk.setIcon('gfx/icons/Question-Yellow.png',[25,25]); }
							}
							
				   if (promo.TodaysDay != promo.PromoDay && promo.PromoRange == 'Personal') {
								if (promo.TaskType == 'CheckIn') {	mk.setIcon('gfx/icons/Check-In.png',[25,25]); }
								if (promo.TaskType == 'Progress') {	mk.setIcon('gfx/icons/Progress.png',[25,25]); }
								if (promo.TaskType == 'Appointment') { mk.setIcon('gfx/icons/Appointment.png',[25,25]); }
								if (promo.TaskType == 'Question') {	mk.setIcon('gfx/icons/Question.png',[25,25]); }
								if (promo.TaskType == 'Marker') { mk.setIcon('gfx/icons/Marker.png',[25,25]);  }	
								}
								/* if (promo.TaskType == 'Marker') {	
									if (promo.TodaysDay == promo.PromoDay) { mk.setIcon('gfx/icons/YellowFlag.png',[25,25]);  }
									else { mk.setIcon('gfx/icons/Marker.png',[25,25]);  }
									if (promo.TaskType == 'CheckIn') {	mk.setIcon('gfx/icons/Check-In.png',[25,25]); }
								if (promo.TaskType == 'Progress') {	mk.setIcon('gfx/icons/Progress.png',[25,25]); }
								if (promo.TaskType == 'Appointment') { mk.setIcon('gfx/icons/Appointment.png',[25,25]); }
								if (promo.TaskType == 'Question') {	mk.setIcon('gfx/icons/Question.png',[25,25]); }

								
								}	*/	
	  
								if(promo.PromoRange == 'All')
								{
								
								if (promo.TaskType == 'Marker') { mk.setIcon('gfx/icons/Marker-Red.png',[25,25]); }
								if (promo.TaskType == 'CheckIn') {	mk.setIcon('gfx/icons/Check-In-Red.png',[25,25]); }
								if (promo.TaskType == 'Appointment') { mk.setIcon('gfx/icons/Appointment-Red.png',[25,25]); }
								if (promo.TaskType == 'Question') {	mk.setIcon('gfx/icons/Question-Red.png',[25,25]); }						
								}
								
								mapstraction.addMarker(mk);
								mk.setAttribute('Category','promo.TaskType');
							}
							
							// Customize Map
							 
							mapstraction.setMapType(1);	   //1 - Road, 2 - Satellite, 3 - Hybrid
//mapstraction.addSmallControls();
 mapstraction.enableScrollWheelZoom();
//mapstraction.addControls({  zoom:	 true,  scale:	true,  });

							// Determine whether to pull static value or from cookie
							var LatitudeCookie = getCookie(\"LatitudeCookie\");
							var LongitudeCookie = getCookie(\"LongitudeCookie\");
			
							if (LatitudeCookie!=null && LatitudeCookie!=\"0\")    {    

									Home = new mxn.LatLonPoint(LatitudeCookie,LongitudeCookie);
									//alert('We found you!');   
			
							} else { 
							
									Home = new mxn.LatLonPoint($LatLong);							
									// alert('No geolocation enabled. Map will show default setting.');			   		   
			
							}

							mapstraction.setCenterAndZoom(Home,11);
							
							/// Add Markers
							$MapMarkers						
						}); 
					   }
					   
						function FilterMap(Criteria) {
							maptraction.removeAllFilters();
							mapstraction.addfilter('Category', 'eq', Criteria);
							mapstraction.doFilter();
							}		
							
					  </script> ";	
		}
	}  // end if (isset($MapOnPage))
  

	echo "<script type='text/javascript'>
											$(document).ready(function() {
	$('#Marker_StartDate').datepicker();
	$('#Marker_EndDate').datepicker();
											});
	</script> ";
	
 ?>