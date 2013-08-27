<?php 

    Require_once 'Inc/Inc_Common.php';
    Require_once 'Inc/Inc_Functions_Promotions.php';	
	
	if (isset($_POST['PID'])) { $PromoID = $_POST['PID']; }
		else { $PromoID = $_GET['PID']; }
		
	if (isset($_GET['Sponsored'])) { $BackLink = "<a href='Map.php' class='White NoUnderline'>&laquo; Back to Map</a> &nbsp; &nbsp; | &nbsp; &nbsp; <a href='MyAccount.php#P' class='White NoUnderline'> Back to Promotions  &raquo;</a> "; }
	else { $BackLink = " <a href='Map.php' class='White NoUnderline'>&laquo; Back to Map</a>"; }
			
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Promotion</title>
    <?php Require 'Inc/Inc_HeaderTag.php'; ?>
</head>

<body id="Promotion" bgcolor="#666">
	<div id="site_center">
                	
        <!-- start header -->
        <?php Require 'Inc/Inc_Header.php'; ?>
        <!-- end header -->             

 
	  <div id="main"><div id="content">      
      <!-- start content -->
                   
              <h1 id="PageTitle" class="White">Promotion Details</h1>
              <div align="center">
              <?php echo $BackLink ?>
              <br><br>
              
             
				<?php 
				
					if (isset($_POST['CheckIn'])) { 
					
						$Reason = Validate_CheckIn($PromoID);
					
						if (strpos($Reason,"VALID")) {
							echo ShowPromo_ThankYou($PromoID,$Reason); 
						} else {
							echo ShowPromo_ThankYouFailed($Reason); 
						}
					
					} 
						else { echo ShowPromo($PromoID,"IncludeForm"); 
					}
				?>

              <br>
              <?php echo $BackLink ?>
              </div>
              
                            
              <div class="clear"></div>
                    
      <!-- end content -->          
	  </div></div>

    
        <!-- start footer -->
        <?php Require 'Inc/Inc_Footer.php'; ?> 
        <!-- end footer -->                   
                
    </div>
</body>
</html>
