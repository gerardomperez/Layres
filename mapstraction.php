<?php 
	$Address = "http://maps.googleapis.com/maps/api/geocode/xml?address=6505+Allison+Road+Miami+FL+33141&sensor=false";
	$Latitude = "37.764";
	$Longitude = "-122.453"; 
		
	// Returns the source from the provided URL 
	function Get_AddressData($url) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($c));
		curl_close($c);
		
		return $content;		
		}
	
	// Parse XML to get latitude and longitude
		function objectsIntoArray($arrObjData, $arrSkipIndices = array())
		{
			$arrData = array();
			
			// if input is object, convert into array
			if (is_object($arrObjData)) {
				$arrObjData = get_object_vars($arrObjData);
			}
			
			if (is_array($arrObjData)) {
				foreach ($arrObjData as $index => $value) {
					if (is_object($value) || is_array($value)) {
						$value = objectsIntoArray($value, $arrSkipIndices); // recursive call
					}
					if (in_array($index, $arrSkipIndices)) {
						continue;
					}
					$arrData[$index] = $value;
				}
			}
			return $arrData;
		}
		

	if (isset($_POST["CompleteAddress"])) {
		// Put the address into the URL call
		$Address = "http://maps.googleapis.com/maps/api/geocode/xml?address=";
		$Address .= urlencode($_POST["CompleteAddress"]);
		$Address .= "&sensor=false";

		// Store XML content of address into variable and parse it
		$XMLcontent = Get_AddressData($Address);	
			
		$xmlObj = simplexml_load_string($XMLcontent);
		$arrXml = objectsIntoArray($xmlObj);

		//print_r($arrXml['result']['geometry']['location']);

		$Latitude = $arrXml['result']['geometry']['location']['lat'];
		$Longitude = $arrXml['result']['geometry']['location']['lng'];
	}

	// Declare value of InforBubbles
	$InfoBubble1 = $InfoBubble2 = $InfoBubble3 = $InfoBubble4 = $InfoBubble5 = "";
	
	$BubbleURL = "http://www.citrix.com";
	$Task = "This is the task. This is the task. This is the task. This is the task.";
	$Reward = "This is the reward. This is the reward. This is the reward. This is the reward.";
	$BubbleAddress = "605 Lincoln Road<br>7th floor<br>Miami Beach, FL 33139<br>305-555-1234";
	$InfoBubble1 = $InfoBubble2 = $InfoBubble3 = $InfoBubble4 = $InfoBubble5 = "<div style='font:Arial, Helvetica, sans-serif; font-size:12px;'>Vendor Name &nbsp; &nbsp; (<a href='$BubbleURL'>Check-In</a>)<hr noshade='noshade' /><div style='width:250px;'><strong>Task</strong>: $Task  </div><br><div style='width:250px;'><strong>Reward</strong>: $Reward</div><br><div style='width:250px'><strong>Address</strong>:<br>$BubbleAddress</div><div align='right'>(<a href='$BubbleURL'>Check-In</a>)</div></div>";

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Google Map with Mapstraction</title>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script src="js/maps/mxn.js?(googlev3)" type="text/javascript"></script>
    <script src="js/maps/mxn.google.geocoder.js" type="text/javascript"></script>
    <script type="text/javascript">
		// declare thess as global variables so that it can be accessed via click events
		var mapstraction; 
		var marker1, marker2, marker3, marker4, marker5;
		var SanFran, SanFran1, SanFran2, SanFran3, SanFran5;
	
		function create_map() {
			Home = new mxn.LatLonPoint(<?php echo "$Latitude, $Longitude"; ?>);
			SanFran1 = new mxn.LatLonPoint(37.764,-122.403);
			SanFran2 = new mxn.LatLonPoint(37.768,-122.411);
			SanFran3 = new mxn.LatLonPoint(37.763,-122.413);
			SanFran4 = new mxn.LatLonPoint(37.772,-122.415);
			SanFran5 = new mxn.LatLonPoint(37.760,-122.421);
			
			mapstraction = new mxn.Mapstraction('mymap','googlev3'); 
			
			// Customize Map Controls
			mapstraction.addSmallControls();
			
			// Add Markers

			marker = new mxn.Marker(Home);
			marker.setIcon('gfx/icons/GreenFlag.png',[25,25]);
			marker.setInfoBubble("<b>Infopress Office</b><br>123 Here St.<br>San Francisco, CA 29771");
			mapstraction.addMarker(marker);

			marker1 = new mxn.Marker(SanFran1);
			marker1.setAttribute('Category','CheckIn');
			marker1.setIcon('gfx/icons/CheckIn.png',[25,25]);
			marker1.setInfoBubble("<?php echo $InfoBubble1 ?>");
			mapstraction.addMarker(marker1);

			marker2 = new mxn.Marker(SanFran2);
			marker1.setAttribute('Category','Group-CheckIn');
			marker2.setIcon('gfx/icons/Group-CheckIn.png',[25,25]);
			marker2.setInfoBubble("<?php echo $InfoBubble2 ?>");
			mapstraction.addMarker(marker2);
			
			marker3 = new mxn.Marker(SanFran3);
			marker1.setAttribute('Category','Progress');
			marker3.setIcon('gfx/icons/Progress.png',[25,25]);
			marker3.setInfoBubble("<?php echo $InfoBubble3 ?>");
			mapstraction.addMarker(marker3);
			
			marker4 = new mxn.Marker(SanFran4);
			marker1.setAttribute('Category','Appointment');
			marker4.setIcon('gfx/icons/Appointment.png',[25,25]);
			marker4.setInfoBubble("<?php echo $InfoBubble4 ?>");
			mapstraction.addMarker(marker4);
			
			marker5 = new mxn.Marker(SanFran5);
			marker1.setAttribute('Category','QA');
			marker5.setIcon('gfx/icons/QA.png',[25,25]);
			marker5.setInfoBubble("<?php echo $InfoBubble5 ?>");
			mapstraction.addMarker(marker5);

			// Automatically set zoom level based on # of Markers
			// mapstraction.autoCenterAndZoom();
			
			// Create Map			
			mapstraction.setCenterAndZoom(Home,15);
			}
			
			function FilterMap(Criteria) {
				maptraction.removeAllFilters();
				mapstraction.addfilter('Category', 'eq', Criteria);
				mapstraction.doFilter();
				}
    </script>
	<style type="text/css">
		body { padding:0; margin:0;}
    	div#mymap {
			width: 100%;
			height: 400px;
		    background-color:#333;
			}
		a { text-decoration:none;}
    </style>
</head>
<body onload="create_map()">
   
    <div id="mymap"></div>

<table align="center" width="80%">
<tr><td align="center">	
    	<table align="right" cellpadding="2">
         <tr>
         	<td><a href="JavaScript:void" onclick="FilterMap('CheckIn');">**</a> </td>
            <td><a href="JavaScript:void" onclick="mapstraction.setCenter(SanFran1, {pan:true});">CheckIn</a></td>
            <td><a href="JavaScript:void" onclick="marker1.openBubble();">+</a> <a href="JavaScript:void" onclick="marker1.closeBubble();">-</a></td></tr>
         <tr>
         	<td><a href="JavaScript:void" onclick="FilterMap('Group-CheckIn');">**</a> </td>
            <td><a href="JavaScript:void" onclick="mapstraction.setCenter(SanFran2, {pan:true});">Group-CheckIn</a></td>
            <td><a href="JavaScript:void" onclick="marker2.openBubble();">+</a> <a href="JavaScript:void" onclick="marker2.closeBubble();">-</a> </td></tr>             
         <tr>
         	<td><a href="JavaScript:void" onclick="FilterMap('Progress');">**</a> </td>
            <td><a href="JavaScript:void" onclick="mapstraction.setCenter(SanFran3, {pan:true});">Progress</a></td>
            <td><a href="JavaScript:void" onclick="marker3.openBubble();">+</a> <a href="JavaScript:void" onclick="marker3.closeBubble();">-</a> </td></tr>   
         <tr>
         	<td><a href="JavaScript:void" onclick="FilterMap('Appointment');">**</a> </td>
            <td><a href="JavaScript:void" onclick="mapstraction.setCenter(SanFran4, {pan:true});">Appointment</a></td>
            <td><a href="JavaScript:void" onclick="marker4.openBubble();">+</a> <a href="JavaScript:void" onclick="marker4.closeBubble();">-</a>  </td></tr>  
         <tr>
         	<td><a href="JavaScript:void" onclick="FilterMap('QA');">**</a> </td>
            <td><a href="JavaScript:void" onclick="mapstraction.setCenter(SanFran5, {pan:true});">QA</a></td>
            <td><a href="JavaScript:void" onclick="marker5.openBubble();">+</a> <a href="JavaScript:void" onclick="marker5.closeBubble();">-</a></td></tr>    
		</table>
</td><td align="center">
 
        <form method="post">
        	<input type="hidden" name="CompleteAddressForm" value="Submitted" />
            <input type="text" name="CompleteAddress" value="" style="width:400px;" /> 
            <input type="submit" value="Go" />
        </form>

	<div style="border: #333 dashed 2px; width:250px;">
        The latitude / longitude of green flag: <br /> 
		<?php echo "$Latitude, $Longitude"; ?>
    </div>     

</td></tr>
</table>

</body>
</html>
