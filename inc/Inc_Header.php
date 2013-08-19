<?php

// -------------------------
// --  highlight nav link of current page
// -------------------------
$Registration_Current = "class='SignUp'";

if ($ThisPage == "Home.php") { $Home_Current = "class='current'"; }
if ($ThisPage == "PromotionTypes.php") { $PromotionTypes_Current = "class='current'"; }
if ($ThisPage == "Playing.php") { $Playing_Current = "class='current'"; }
if ($ThisPage == "FAQ.php") { $FAQ_Current = "class='current'"; }
if ($ThisPage == "Map.php") { $Map_Current = "class='current'"; }
if ($ThisPage == "Login.php") { $Login_Current = "class='current'"; }
if ($ThisPage == "MyAccount.php") { $MyAccount_Current = "class='current'"; }
if ($ThisPage == "Registration.php") { $Registration_Current = "class='current Red'"; } else { $Registration_Current = "class='Red'"; }


// -------------------------
// --  show the appropriate Login info depending on whether the cookie exists
// -------------------------

if (isset($_SESSION['UName'])) { 
		//echo $_SESSION['UName'];
		$WelcomeMessage = "<li><a {$MyAccount_Current} href='MyAccount.php'>Account</a></li>"; 
								
} else { 

		$WelcomeMessage = "<li><a href='Registration.php' class='Yellow'>Login</a> </li> 	";	 	
}
?>
<header class="header_bg clearfix">
		<div class="container clearfix">
			<div style="text-align:center"><a href="Marker.php#Map" class="red-button">Create a Marker</a></div>
        
			 <!-- Logo -->
			<div class="logo">
				<a href="Map.php#Map"><img src="images/logo.png" alt="" /></a>
			</div>
			 <!-- /Logo -->
			
			<!-- Master Nav -->
			<nav class="main-menu">
				<ul>
					<li><a href="Home.php">Home</a></li>
					<li><a href="Map.php#Map">Map</a></li>
					<li><a href="Help.php">Help</a></li>
                    <?php echo $WelcomeMessage; ?>
				    <!--<li><a href="Registration.php">Sign Up</a></li>-->
				</ul>
			</nav>
			<!-- /Master Nav -->
		</div>
	</header>
    <div class="clear"></div>
    <a name="Map"></a>