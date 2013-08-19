<?php

	// #############################################
	// ## Set VendorID value if we want to get a subset of promotions 
	// ############################################
		$VendorID = "";
		
		if (isset($_GET['VendorID'])) { $VendorID = $_GET['VendorID']; }


	// Last chance....if no set LatLong coordinates, then assign them a default value for Miami, FL.
	if (!$LatLong) { $LatLong = "25.7889689, -80.2264393";  }  
	//else { $LatLong = "pos.coords.latitude, pos.coords.longitude"; }


	// #############################################
	// ## Define JavaScript that puts together the map
	// #############################################
	$MapMarkersJS = "
	<script src='js/jquery-1.5.1.min.js'></script>
	<script type=\"text/javascript\">
		// declare these as global variables so that it can be accessed via click events
		var mapstraction; 
		
		$Define_Markers;
		$Define_Locations;
	
			function create_map() {
				Home = new mxn.LatLonPoint($LatLong);
				$Locations
				
				mapstraction = new mxn.Mapstraction('mymap','googlev3'); 
				
				// Customize Map
				mapstraction.addSmallControls();	
				mapstraction.setCenterAndZoom(Home,11);
				
				// Add Markers
				$MapMarkers				
				
				}

    </script>

	";

	// #############################################
	// ## This JavaScript includes database markers
	// #############################################
	$MapMarkersJS2 = "
		<script src='http://code.jquery.com/jquery-1.5.js'></script>
		<script type='text/javascript'>
			var mapstraction; 
		
			function create_map() {
				$.getJSON('Data.php?VendorID=$VendorID', function(jobj) {
					mapstraction = new mxn.Mapstraction('mymap','googlev3');
		
					for (i=0; i < 5; i++) {			
						var mk = new mxn.Marker(new mxn.LatLonPoint(jobj.child[i].LocationLatitude, jobj.child[i].LocationLongitude));			
						mk.setInfoBubble(jobj.child[i].PromoTitle);
						
						if (jobj.child[i].TaskType == 'CheckIn') {	mk.setIcon('gfx/icons/Check-In.png',[25,25]); }
						if (jobj.child[i].TaskType == 'Progress') {	mk.setIcon('gfx/icons/Progress.png',[25,25]); }
						if (jobj.child[i].TaskType == 'Appointment') {	mk.setIcon('gfx/icons/Appointment.png',[25,25]); }
						if (jobj.child[i].TaskType == 'Question') {	mk.setIcon('gfx/icons/Question.png',[25,25]); }
						
						mapstraction.addMarker(mk);
						}	
					
					// Customize Map
					mapstraction.addSmallControls();	
					
					Home = new mxn.LatLonPoint($LatLong);		
					mapstraction.setCenterAndZoom(Home,11);
				});
				
				// Add Markers
				$MapMarkers	
				
				}
		
		</script>

	";


	// #############################################
	// ## JS include files
	// #############################################
	$MapJS = "
	<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>
    <script src='js/maps/mxn.js?(googlev3)' type='text/javascript'></script>
    <script src='js/maps/mxn.google.geocoder.js' type='text/javascript'></script>	
	";
?>

