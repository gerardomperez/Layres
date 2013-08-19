<?php 

	Require_once 'Inc/Inc_Functions_Database.php';
	session_start();


	if (isset($_GET['Show'])) { $Show = $_GET['Show']; }
	else { $Show = ""; }	
	
	
	// ----------------------
	// ------  Main SQL Statement
	// ----------------------		

						// Necessary Data	
	$sql_LayerDate = "SELECT Promotions.PromotionID, Promotions.PromoRange, Promotions.UName, Promotions.EndDate, 
								DATE_FORMAT(Promotions.CreateDate, '%l:%i (%M %e)') AS CreateDate, 
								DATE_FORMAT(Promotions.CreateDate, '%M %e') AS PromoDay, 
								DATE_FORMAT(Now(), '%M %e') AS TodaysDay, 
								PromoTitle, TaskType, RewardDescription, TaskDescription, RewardType, 
								 LocationName, Location.LocationLatitude, Location.LocationLongitude, 
								 Location.StreetAddress, Location.Zip, Location.Phone "; 

	$sql_LayerDate .= "FROM Promotions, Location_Promotions, Location, Layers ";

						// Joing tables
	$sql_LayerDate .= "WHERE Location_Promotions.PromotionID = Promotions.promotionID
					    AND Location_Promotions.LocationID = Location.LocationID
					    AND Location_Promotions.LayerID = Layers.LayerID";

						// Promo dates are valid							   
	$sql_LayerDate .= " AND Promotions.StartDate < Now()
						AND Promotions.EndDate > Now() ";
				  
						// There is still quantity left	  
	$sql_LayerDate .= " AND Location_Promotions.PromoQuantity > Location_Promotions.PromosEarned ";	  
	

		// ----------------------
		// ------  Modifications
		// ----------------------	

		// ------  
		// if logged in, show all relevant promos from user and friends
		// ------  
		if (isset($_SESSION['UName'])) {
			
			$PromoRange = "('All','Personal')";
				// Modify this to get subset of promotions  (All | Personal | Private) ... will need to get fancier in SQL to show specialized demographic subsections
					
			
				// show a peron's relevant promotions and all that are in "All" range
				$sql_LayerDate .= "AND (Layers.LayerID IN (Select LinkageList.LayerID FROM LinkageList, Location_Promotions 
																		WHERE LinkageList.LayerID = Location_Promotions.LayerID 
																		AND LinkageList.UName='{$_SESSION['UName']}' 
																		AND Accepted='ok')
										 OR Promotions.PromoRange IN {$PromoRange}
										 OR Promotions.UName='{$_SESSION['UName']}') ";  // show your own promotions in your map
		} 
		// ------  
		// Set results to just for layer
		// ------  
		if (isset($_GET['LayerID'])) {
			$PromoRange = "('All','Personal')";
				$sql_LayerDate .= " AND Promotions.PromoRange IN {$PromoRange} AND Layers.LayerID = '{$_GET['LayerID']}' ";
		} 
		
		else {
			$PromoRange = "('All')";
				
			$sql_LayerDate .= " AND Promotions.PromoRange IN {$PromoRange}";
		 }
		
		// ------  
  	    //Limit to a specific Marker type if requested	
		// ------  
		if (isset($_GET['MarkerType'])) { $sql_ActivityFeedData .= " AND Promotions.TaskType .= '{$_GET['MarkerType']}'";  }

	//	echo $sql_LayerDate;
		

  	$Result_LayerDate = Select($sql_LayerDate);
// echo $sql_LayerDate;
//	 echo "<br><br>";  
 	print json_encode($Result_LayerDate);


?>