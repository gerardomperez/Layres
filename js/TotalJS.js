//########################################################
//### From Functions.js
//########################################################
var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}

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

<!--  Menu Switching -->	
// JavaScript Document
/***********************************************
* Contractible Headers script- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use. Last updated Mar 23rd, 2004.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

var enablepersist="off" //Enable saving state of content structure using session cookies? (on/off)
var collapseprevious="yes" //Collapse previously open content when opening present? (yes/no)

if (document.getElementById){
document.write('<style type="text/css">')
document.write('.switchcontent{display:none;}')
document.write('</style>')
}

function getElementbyClass(classname){
ccollect=new Array()
var inc=0
var alltags=document.all? document.all : document.getElementsByTagName("*")
for (i=0; i<alltags.length; i++){
if (alltags[i].className==classname)
ccollect[inc++]=alltags[i]
}
}

function contractcontent(omit){
var inc=0
while (ccollect[inc]){
if (ccollect[inc].id!=omit)
ccollect[inc].style.display="none"
inc++
}
}

function expandcontent(cid){
if (typeof ccollect!="undefined"){
if (collapseprevious=="yes")
contractcontent(cid)
document.getElementById(cid).style.display=(document.getElementById(cid).style.display!="block")? "block" : "none"
}
//else {
//document.getElementById("default").style.display="blocked"
}

function revivecontent(){
contractcontent("omitnothing")
selectedItem=getselectedItem()
selectedComponents=selectedItem.split("|")
for (i=0; i<selectedComponents.length-1; i++)
document.getElementById(selectedComponents[i]).style.display="block"
}

function get_SESSION(Name) { 
var search = Name + "="
var returnvalue = "";
if (document.cookie.length > 0) {
offset = document.cookie.indexOf(search)
if (offset != -1) { 
offset += search.length
end = document.cookie.indexOf(";", offset);
if (end == -1) end = document.cookie.length;
returnvalue=unescape(document.cookie.substring(offset, end))
}
}
return returnvalue;
}

function getselectedItem(){
if (get_SESSION(window.location.pathname) != ""){
selectedItem=get_SESSION(window.location.pathname)
return selectedItem
}
else
return ""
}

function saveswitchstate(){
var inc=0, selectedItem=""
while (ccollect[inc]){
if (ccollect[inc].style.display=="block")
selectedItem+=ccollect[inc].id+"|"
inc++
}

document.cookie=window.location.pathname+"="+selectedItem
}

function do_onload(){
uniqueidn=window.location.pathname+"firsttimeload"
getElementbyClass("switchcontent")
if (enablepersist=="on" && typeof ccollect!="undefined"){
document.cookie=(get_SESSION(uniqueidn)=="")? uniqueidn+"=1" : uniqueidn+"=0" 
firsttimeload=(get_SESSION(uniqueidn)==1)? 1 : 0 //check if this is 1st page load
if (!firsttimeload)
revivecontent()
}
}


if (window.addEventListener)
window.addEventListener("load", do_onload, false)
else if (window.attachEvent)
window.attachEvent("onload", do_onload)
else if (document.getElementById)
window.onload=do_onload

if (enablepersist=="on" && document.getElementById)
window.onunload=saveswitchstate







