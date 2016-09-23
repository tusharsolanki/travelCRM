<?php
session_start();

define('LOGO_PATH', $_SERVER['HTTP_HOST']."/energyupgrade/wdcalendar/logo.png");
define('FILE_PATH', $_SERVER['HTTP_HOST']."/energyupgrade/wdcalendar/");
define('REDIRECT_PATH', "http://".$_SERVER['HTTP_HOST']."/energyupgrade/index.php/admin/login/temp_page/");

class DBConnection
{
	function getConnection()
	{
		mysql_connect("localhost","docmod11","docmodule11*") or die("Could not connect: " . mysql_error());
		mysql_select_db("docmod11_healthassist") or die("Could not select database: " . mysql_error());
	}
}
?>