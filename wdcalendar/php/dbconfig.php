<?php
class DBConnection{
	function getConnection($hostname,$database,$username,$password){
	    //change to your database server/user name/password
		$conn = mysql_connect($hostname,$username,$password) or die("Could not connect: " . mysql_error());
    	//change to your database name
		$sele = mysql_select_db($database,$conn) or die("Could not select database: " . mysql_error());
		
	}
}
?>