<?php 

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Promotions.php';
	
	$MapOnPage = "Yes";
	
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
	
		function setHeight() {
			var MapHeight = window.innerHeight - 100;
			
			document.getElementById('mymap').style.height = MapHeight + 'px';
		}
		
	</script>  

<body id="Home" onLoad="create_map(); setHeight();">

	<?php Require 'Inc/Inc_Header.php'; ?>


    <!-- START CONTENT -->
    <section class="clearfix">
		

               <!--
                 <div align="center" style="background-color:#666;">
                 <form method="post" style="padding:0; margin:0;">
                    <input type="hidden" name="CompleteAddressForm" value="Submitted" />
                    <input type="text" name="CompleteAddress" value="" style="width:850px;" placeholder="Enter location here" /> 
                    <input type="submit" value="Go" />
                </form> </div>
                
                <a class="googlecontrol" href="PromoList.php">View as List</a> 
                -->   
               <div id="mymap" style="width:100%;"></div>  

 		
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