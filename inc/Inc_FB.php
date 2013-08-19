<?php
//---------------------------------
// Code to handle data returned from logging in
//---------------------------------


//---------------------------------
// Set cookie so that they do not have to constantly FBConnect
//---------------------------------


//---------------------------------
// Show My Account link in top nav
//---------------------------------
$MyAccountLink = "<a href='MyAccount.php'>My Account</a>"

?>

<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=124406850951832&xfbml=1"></script>

<fb:registration 
  fields="name,birthday,gender,location,email" 
  redirect-uri="http://developers.facebook.com/tools/echo/"
  width="530">
</fb:registration> 

<!-- Facebook Connect -->
		<p><fb:login-button autologoutlink="true"></fb:login-button></p>
		<p><fb:like></fb:like></p>
	
		<div id="fb-root"></div>
		
		<script>
		  window.fbAsyncInit = function() {
			FB.init({appId: '124406850951832', status: true, cookie: true,
					 xfbml: true});
		  };
		  (function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = document.location.protocol +
			  '//connect.facebook.net/en_US/all.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
		  }());
		</script>
        