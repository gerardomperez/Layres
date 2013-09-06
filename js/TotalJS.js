//########################################################
//### From Functions.js
//########################################################
//var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}

//########################################################
//### From TotalJS.js
//########################################################

<!--  Cookie Functions  -->
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)   {   c_start = c_value.indexOf(c_name + "=");   }
	if (c_start == -1)  {   c_value = null;   }
	else
	  {
	  c_start = c_value.indexOf("=", c_start) + 1;
	  var c_end = c_value.indexOf(";", c_start);
	  if (c_end == -1)    { c_end = c_value.length; }
	  c_value = unescape(c_value.substring(c_start,c_end));
	}

	return c_value;
}

function checkCookie()
{
  	var username=getCookie("username");
	
	if (username!=null && username!="")    {    alert("Welcome again " + username);   }  
	else   { 
	
	  username=prompt("Please enter your name:","");
	  if (username!=null && username!="")     {     setCookie("username",username,365);     }
	
	}

}



function toRad(Value) {
    /** Converts numeric degrees to radians */
    return Value * Math.PI / 180;
}



<!-- ############################ -->
<!-- ####  Form Validation  ####  -->
<!-- ############################ -->


function isValidEmailAddress(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

		   function LogInCheck() {
			  var UName1 = $("#UName1");  
			  var PWord1 = $("#PWord1"); 
			  var ErrorMessage = $("#ErrorMessage"); 	
		
			alert("Length:" + UName1.val().length);
		
			 if(UName1.val().length < 4){  
					ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessageShow");  
					ErrorMessage.text("We want names with more than 3 letters!");  
					return false; 
			}
			
			 if(PWord1.val().length < 4){  


				return;	
			}
			
			document.forms.LoginForm.submit();
		}			

		   function vLogInCheck() {
		
			if (document.forms.vLoginForm.vUName1.value == "") {
				alert("Please enter your username.");
				document.forms.vLoginForm.vUName1.focus();	
				return;
			}
			
			if (document.forms.vLoginForm.vPWord1.value == "") {
				alert("Please enter your password.");
				document.forms.vLoginForm.vPWord1.focus();
				return;	
			}

				document.forms.vLoginForm.submit();
		}			

// -------------------------------

		   function SignUpCheck() {
		
			if (document.forms.SignUpForm.UName.value == "") {
				alert("Please enter your username.");
				document.forms.SignUpForm.UName.focus();	
				return;
			}
			
			if (document.forms.SignUpForm.PWord.value == "") {
				alert("Please enter your password.");
				document.forms.SignUpForm.PWord.focus();
				return;	
			}
			
			if (document.forms.SignUpForm.EmailAddress.value == "") {
				alert("Please enter your email address.");
				document.forms.SignUpForm.EmailAddress.focus();
				return;	
			}		
			
			if (document.forms.SignUpForm.Zip.value == "") {
				alert("Please enter your zip code.");
				document.forms.SignUpForm.Zip.focus();
				return;	
			}					
			
			document.forms.SignUpForm.submit();
		}			

// -----------------------------------------

		   function MyAccountEdit() {

			if (document.forms.EditMyAccountForm.PWord.value == "") {
				alert("Please enter your password.");
				document.forms.EditMyAccountForm.PWord.focus();
				return;	
			}
			
			if (document.forms.EditMyAccountForm.EmailAddress.value == "") {
				alert("Please enter your email address.");
				document.forms.EditMyAccountForm.EmailAddress.focus();
				return;	
			}		
			
			if (document.forms.EditMyAccountForm.Zip.value == "") {
				alert("Please enter your zip code.");
				document.forms.EditMyAccountForm.Zip.focus();
				return;	
			}					
			
			document.forms.EditMyAccountForm.submit();
		}			


// 	-----------------------------------------------		

		   function LocationEdit() {
			
			if (document.forms.EditLocationForm.LocationName.value == "") {
				alert("Please enter your location's name.");
				document.forms.EditLocationForm.LocationName.focus();
				return;	
			}

			if (document.forms.EditLocationForm.StreetAddress.value == "") {
				alert("Please enter your street address.");
				document.forms.EditLocationForm.StreetAddress.focus();
				return;	
			}		
			
			if (document.forms.EditLocationForm.Zip.value == "") {
				alert("Please enter your zip code.");
				document.forms.EditLocationForm.Zip.focus();
				return;	
			}		
				document.forms.EditLocationForm.submit();
		}	

// 	---------------- Create Promo Form Validation -------------------------------		

		   function BackButton() {	
				document.forms.BackButtonForm.submit();
		   }

		   function PromoDetails(TaskType,Proximity) {
			   
			   var ErrorMessage = $("#ErrorMessage");
			   
			   	if (TaskType == "Question" && document.forms.PromoDetailsForm.Response.value == "") {

					ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessagePromo");  
					ErrorMessage.text("Please answer the question." + document.forms.PromoDetailsForm.Response.value);  
					return;	
					
				}	
				
				if (Proximity) {
						//Can we validate proximity here?  
						navigator.geolocation.getCurrentPosition(foundLoc, noLoc);
				
					} else {
						document.forms.PromoDetailsForm.submit();
						}
				
		   }		   
		   
		   function foundLoc(pos) {
			    var lat1 = pos.coords.latitude;
			    var lon1 = pos.coords.longitude;

				var lat2=getCookie("PromoLatitude");
				var lon2=getCookie("PromoLongitude");
				
				//var distance = "?";
						   
			   // find distance
				var radius = 6371 * 0.621371; // miles
				var dLat = toRad(lat2-lat1);  //* Math.PI / 180
				var dLon = toRad(lon2-lon1);
				var lat1 = toRad(lat1);
				var lat2 = toRad(lat2);
				
				var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
				var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
				var distance = radius * c;

				//alert('Your location: ' + lat1 + ', ' + lon1 + '\nPromo location: ' + lat2 + ', ' + lon2 + '\n\nDistance:' + distance + 'miles');
				
			   // if too far, don't submit and create error message....otherwise submit
			      if (distance > 0.12) { 
				  
				  	var ErrorMessage = $("#ErrorMessage");

					ErrorMessage.removeClass("ErrorMessageHide").addClass("ErrorMessagePromo");  
					ErrorMessage.text("Too far away. Proximity required. (" + Math.floor(distance*10)/10 + " miles)");  
					return false; 					
				   
				  } else { document.forms.PromoDetailsForm.submit(); }
			   
			   }
		   
		   function noLoc() {
			   alert('No location found.');
			   }
		   
		   function CreatePromo_Step1() {	

						if (document.forms.EditPromotionForm.PromoTitle.value == "") {
							alert("Please enter a title for this promotion.");
							document.forms.EditPromotionForm.PromoTitle.focus();
							return;	
						}	
						
						if (document.forms.EditPromotionForm.StartDate.value == "") {
							alert("Please enter your promotion's start date.");
							document.forms.EditPromotionForm.StartDate.focus();
							return;	
						}	
						
						if (document.forms.EditPromotionForm.EndDate.value == "") {
							alert("Please enter your promotion's end date.");
							document.forms.EditPromotionForm.EndDate.focus();
							return;	
						}	
						
						if (document.forms.EditPromotionForm.PromoQuantity.value == "") {
							alert("Please enter the quantity of promotions you'd like to offer.");
							document.forms.EditPromotionForm.PromoQuantity.focus();
							return;	
						}					

						if (isNaN(document.forms.EditPromotionForm.PromoQuantity.value)) {
							alert("Please enter a number for the quantity of promotions you are offering.");
							document.forms.EditPromotionForm.PromoQuantity.focus();
							return;								
						}

							document.forms.EditPromotionForm.submit();
			}	

		   function CreatePromo_Step2(TaskType) {	

						if (document.forms.EditPromotionForm.TaskDescription.value == "") {
							alert("Please enter the task description.");
							document.forms.EditPromotionForm.TaskDescription.focus();
							return;	
						}
	
				// Check-In
				if (TaskType == "Check-In") {
								  
					}
				
				// Appointment
				if (TaskType == "Appointment") {
	
/*						if (document.forms.EditPromotionForm.DayStartTime.value == "") {
							alert("Please enter a start time.");
							document.forms.EditPromotionForm.DayStartTime.focus();
							return;	
						}
						
						if (document.forms.EditPromotionForm.DayEndTime.value == "") {
							alert("Please enter an end time.");
							document.forms.EditPromotionForm.DayEndTime.focus();
							return;	
						}
						
						if (document.forms.EditPromotionForm.ValidDays.checked == false) {
							alert("Please enter the days this promotion is valid.");
							document.forms.EditPromotionForm.ValidDays.focus();
							return;	
						}				
*/		
					}
							
				// Progress
				if (TaskType == "Progress") {			
	
					}
							
							
				// Questions
				if (TaskType == "Question") {
	 
						if (document.forms.EditPromotionForm.Question.value == "") {
							alert("Please enter the question.");
							document.forms.EditPromotionForm.Question.focus();
							return;	
						}	
						
					}
			
				document.forms.EditPromotionForm.submit();
			}	


		   function CreatePromo_Step3(RewardType) {	
			
						if (document.forms.EditPromotionForm.RewardDescription.value == "") {
							alert("Please enter the reward description.");
							document.forms.EditPromotionForm.RewardDescription.focus();
							return;	
						}
	
				// Check-In
				if (RewardType == "Coupon") {
								  
					}
				
				// Appointment
				if (RewardType == "Referral") {		
	
					}
							
				// Questions
				if (RewardType == "Status") {
	 
						if (document.forms.EditPromotionForm.StatusLevel1.value == "") {
							alert("Please enter the first status level.");
							document.forms.EditPromotionForm.StatusLevel1.focus();
							return;	
						}	
						
					}

				// Questions
				if (RewardType == "Information") {
	 
						if (document.forms.EditPromotionForm.Information.value == "") {
							alert("Please enter the information to reveal upon completion of the task.");
							document.forms.EditPromotionForm.Information.focus();
							return;	
						}	
						
					}

				document.forms.EditPromotionForm.submit();
				
			}	

		   function CreatePromo_Step4() {	
			
//				if (document.forms.EditPromotionForm.CongratsMessage.value == "") {
//					alert("Please enter a closing message for people who complete a task.");
//					document.forms.EditPromotionForm.CongratsMessage.focus();
//					return;	
//				}		
					document.forms.EditPromotionForm.submit();
			}	

