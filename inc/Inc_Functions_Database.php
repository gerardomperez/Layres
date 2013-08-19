<?php

// -------------------------------
// -- Database Constants
// -------------------------------


//print_r($_SERVER['HTTP_HOST']);
/*
if (basename($_SERVER['REMOTE_ADDR']) == "127.0.0.1") {

	define("DB_HOSTNAME","localhost");
	define("DB_DATABASE","Sprinkles");
	define("DB_USERNAME","root");
	define("DB_PASSWORD",""); 

}
else */

if($_SERVER['HTTP_HOST'] == 'dev.layr.es' || $_SERVER['HTTP_HOST'] == 'stg.layr.es'){	

	define("DB_HOSTNAME","mysql300.ixwebhosting.com");
	define("DB_DATABASE","gerardo_Layres_dev");
	define("DB_USERNAME","gerardo_website");
	define("DB_PASSWORD","FireSnake5");	   
}
else if($_SERVER['HTTP_HOST'] == 'www.layr.es')
{
	define("DB_HOSTNAME","mysql300.ixwebhosting.com");
	define("DB_DATABASE","gerardo_Layres");
	define("DB_USERNAME","gerardo_website");
	define("DB_PASSWORD","FireSnake5");	
}	

// -------------------------------
// -- Database functions
// -------------------------------

	function OpenDatabase() {

		 $db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
			 If (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
			 
		 mysql_select_db(DB_DATABASE) or die("Unable to select database: " . mysql_error());
		 
		 return true;

	}

	function CloseDatabase() {

		$db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
		mysql_close($db_server);
		
		return true;
		
	}

	function Insert($Sql) {
			
		  //Connect to MySQL
		  $db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
			 If (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		  
		  //Select the databse
		  mysql_select_db(DB_DATABASE) or die("Unable to select database: " . mysql_error());
		  
		  //Run the query    
		  $result = mysql_query($Sql);    
			  If (!$result) die("Unable to insert into	 database: " . mysql_error());
			  
  		  $RecordID = mysql_insert_id();
	  
		  mysql_close($db_server);
		  
		  return $RecordID;   
		
		}


	function Update($Sql) {
		
		  //Connect to MySQL
		  $db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
			 If (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		  
		  //Select the databse
		  mysql_select_db(DB_DATABASE) or die("Unable to select database: " . mysql_error());
		  
		  //Run the query    
		  $result = mysql_query($Sql);    
			  If (!$result) die("Unable to update database: " . mysql_error());
	  
		  mysql_close($db_server);
		  
		  return $result;   

		}

	function Select($Sql)  {
		  //Connect to MySQL
		  $db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
			 If (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		  
		  //Select the databse
		  mysql_select_db(DB_DATABASE) or die("Unable to select database: " . mysql_error());
		  
		  //Run the query    
		  $result = mysql_query($Sql);    
			  If (!$result) die("Unable to select from database: " . mysql_error());

		  $ResultsArray = mysql_fetch_full_result_array($result);
		
		  mysql_free_result($result);
		  mysql_close($db_server);

			//print_r($ResultsArray);

		  return $ResultsArray;

		}


	function Delete($Sql) {
		
		  //Connect to MySQL
		  $db_server = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD);
			 If (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		  
		  //Select the databse
		  mysql_select_db(DB_DATABASE) or die("Unable to select database: " . mysql_error());
		  
		  //Run the query    
		  $result = mysql_query($Sql);    
			  If (!$result) die("Unable to delete from database: " . mysql_error());
	  
		  mysql_close($db_server);
		  
		  return $result;   

		}


	//parse out HTML from form data
	function Sanitize_Data($string) {

		  return strip_tags(mysql_fix_string($string));

		}
	
	
	//preface quotes with a backslash to prevent malicious hacking
	function mysql_fix_string($string) {
		
		  if (get_magic_quotes_gpc()) $string = stripslashes($string);
		  
		  return mysql_real_escape_string($string);
		
		}

	function mysql_fetch_full_result_array($result) {
		$table_result=array();
		$r=0;
		
		while($row = mysql_fetch_assoc($result)){
			$arr_row=array();
			$c=0;
	
			while ($c < mysql_num_fields($result)) {        
				$col = mysql_fetch_field($result, $c);    
				$arr_row[$col -> name] = $row[$col -> name];            
				$c++;
			}    
			
			$table_result[$r] = $arr_row;
			$r++;
		}    
		
		return $table_result;
	}

?>
