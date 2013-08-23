<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

    require_once 'Inc/Inc_Common.php';
	require_once 'Inc/Inc_Functions_Promotions.php';
	
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
<title>Home</title>
<?php require 'Inc/Inc_HeaderTag.php'; ?>

<body>

<?php require 'Inc/Inc_Header.php'; ?>
	

	
	<!-- Slider -->
    <div class="bannerbg">

        <div class="container clearfix">
            <div class="flexslider" >
                <ul class="slides">
                    <li><img src="images/fslide01.jpg" alt="" /><p class="flex-caption">It's all about adding info on top of maps!</p></li>
                    <li><a href="#"><img src="images/fslide02.jpg" alt="" /></a><p class="flex-caption">You can add map markers with individual notes.</p></li>
                    <li><img src="images/fslide03.jpg" alt="" /><p class="flex-caption">You can create a promotion at a specific location.</p></li>
                    <li><img src="images/fslide04.jpg" alt="" /><p class="flex-caption">You can share your map layers with anyone.</p></li>
                    <li><img src="images/fslide05.jpg" alt="" /><p class="flex-caption">You can earn a reward for completing a promotion's task.</p></li> 
                    <li><img src="images/fslide06.jpg" alt="" /><p class="flex-caption">You can show someone where you are right now.</p></li>
                </ul>
            </div>
        </div>
    </div>
	<!-- /Slider -->
	
		<div class="clear padding40"></div>
		<div class="line"></div>
		<div class="clear padding20"></div>
	
	<!-- Content -->
	<section class="container clearfix">


		<!-- START FEATURED COLUMNS -->
		<div class="col_1_4">
			<div class="features">
				<h3 style="border-bottom:#666 1px solid;">What?</h3>
				<p>Through us, you can create a layer of markers on a map.  These markers designate special areas for you.</p>  <p>Some markers just have a note, and some are interactive allowing you to perform a task in exchange for a reward.</p> 
			</div>
		</div>
		<div class="col_1_4">
			<div class="features">
				<h3 style="border-bottom:#666 1px solid;">Who?</h3>
				<p>Anyone can create a layers of markers, and anyone can interact with the markers of other people's layers.</p>  <p>Some, like your favorite vendor, may even create interactive markers that offer rewards, like coupons or special messages.</p> 
			</div>
		</div>
		<div class="col_1_4">
			<div class="features">
				<h3 style="border-bottom:#666 1px solid;">Where?</h3>
				<p>You can <a href="Marker.php">create a marker</a> for anywhere in the world.  You can create it through an address or just create a marker at your current location.</p> 
			</div>
		</div>
		<div class="col_1_4 last">
			<div class="features">
				<h3 style="border-bottom:#666 1px solid;">When?</h3>
				<p>You control the timing of when your individual promotions and markers appear and disappear from your layer. Also, see what's happening not in all the layers you follow through the "Recent Activity Feed" on <a href="Map.php#Map">your map</a> section.</p> 
			</div>
		</div>
		<!-- END FEATURED COLUMNS -->
		
        
    </section>
    
    
    
		<div class="clear padding20"></div>
		<div class="line"></div>
		<div class="clear padding40"></div>
	
    
    				
	<!-- Content -->
	<section class="container clearfix">
 
              <div style="position:relative; float:left; text-align:left;">
              <h3 style="border-bottom:#666 1px solid;">Most Popular Layers: &nbsp; &nbsp; <span class="SmallFont italic"><a href="Registration.php" class="Red">Sign up now</a> to create your own layer!</span></h3>
              
               <div class="col_1_2"> 
              <ul class="arrow_list">
              <?php  echo  $most_popular_layers=most_popular_layers(); ?>
              </ul> </div>
              
 
             <div class="col_1_2 last"> 
            <ul class="arrow_list">
             <?php echo  $most_popular_layers1=most_popular_layers1(); ?>
              </ul> </div>
              </div> 				
	
	</section>
	<!-- /Content -->
	
    	
		<div class="clear padding40"></div>	
	
    
	<section class="homepage_widgets_bg clearfix">

		<div class="clear padding10"></div>	
		
		<div class="container clearfix">
            <h2 class="regular white bottom_line">What's in it for me?</h2>
            
            <!-- START COL 1/2 -->
			<div class="col_1_2 ">
				<h3 class="regular white">Get rewarded for being there.</h3>

				<p>Users just need to go where the map shows there's an interactive marker and then "earn" the reward by completing the required task.</p>
				<p>The task could be as simple as just "checking-in" to a place at a specific time or more involved like answering a question. Either way, 
                it shouldn't be too complicated, and once you complete the task you'll earn the reward.</p>
                <p>There are three types of rewards:</p>
               		<ul class="iconic_list white">
                        <li>Coupons </li>
                        <li>Status</li>
                        <li>Messages</li>
                    </ul>
                 
                 <div class="clear padding10"></div>
			</div>
			<!-- END COL 1/2 -->


            
			<!-- START COL 1/2 -->
            
           
           <div class="col_1_2 last" id="why-section">
				<h3 class="regular white">Motivate people to be there.</h3>
                
                <p>People use maps all the time, including your friends, customers, and acquaintances. With Layr.es you can do some of the following things with your map:</p>
					<ul class="iconic_list white">
                        <li>Creat a map of your favorite locations.</li>
                        <li>Tell your friends where you are or have been.</li>
                        <li>Take attendance at a particular location.</li>
                        <li>Show people where something is happening, like a party. (use it as a map to your event)</li>
                        <li>Offer a reward to people in a particular place and at a particular time.</li>
                        <li>Request information from people in a specific area. (like taking a poll)</li>
                        <li>Encourage people with similar interests to come together somewhere at a particular time.</li>
                        <li>Keep track of who visits a place multiple times. (the basis of a loyalty program)</li>
                    </ul>

                 <div class="clear padding10"></div>
                 
                <!--<div><a href="#"><img class="alignleft MT0" id="why3" src="images/content-img2.png" alt="img" ></a></div>-->

                
                
			</div>
           <div class="clear padding10"></div>
			
			<!-- end col 1/2 -->
			
		</div>
	</section>
		
	
	<!-- footer -->
    <?php Require 'Inc/Inc_Footer.php'; ?>
	<!-- /footer -->
    </div>
    <!--wrapper end-->

</body>
</html>